<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBorrowRequest;
use App\Models\Book;
use App\Models\BorrowRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BorrowRequestController extends Controller
{
    public function index()
    {
        $pending = BorrowRequest::with(['book', 'user'])
            ->whereIn('status', [
                BorrowRequest::STATUS_PENDING,
                BorrowRequest::STATUS_RETURN_REQUESTED,
            ])
            ->orderBy('status')
            ->latest()
            ->paginate(15);

        $history = BorrowRequest::with(['book', 'user', 'processor', 'returnConfirmer'])
            ->whereIn('status', [
                BorrowRequest::STATUS_APPROVED,
                BorrowRequest::STATUS_REJECTED,
                BorrowRequest::STATUS_RETURNED,
            ])
            ->latest()
            ->limit(20)
            ->get();

        return view('borrows.index', compact('pending', 'history'));
    }

    public function store(StoreBorrowRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $book = Book::findOrFail($data['book_id']);

        $existingRequest = BorrowRequest::where('user_id', $request->user()->id)
            ->where('book_id', $book->id)
            ->whereIn('status', [
                BorrowRequest::STATUS_PENDING,
                BorrowRequest::STATUS_APPROVED,
                BorrowRequest::STATUS_RETURN_REQUESTED,
            ])
            ->exists();

        if ($existingRequest) {
            return back()->with('error', 'Anda masih memiliki peminjaman aktif untuk buku ini.');
        }

        BorrowRequest::create([
            'user_id' => $request->user()->id,
            'book_id' => $book->id,
            'status' => BorrowRequest::STATUS_PENDING,
            'due_date' => $data['due_date'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        return back()->with('success', 'Permintaan peminjaman buku berhasil dikirim.');
    }

    public function approve(Request $request, BorrowRequest $borrowRequest): RedirectResponse
    {
        $this->authorizeAction($borrowRequest);

        $data = $request->validate([
            'due_date' => ['required', 'date', 'after:today'],
        ]);

        if ($borrowRequest->status !== BorrowRequest::STATUS_PENDING) {
            return back()->with('error', 'Permintaan tidak dalam status pending.');
        }

        return DB::transaction(function () use ($borrowRequest, $data, $request) {
            $book = $borrowRequest->book()->lockForUpdate()->first();

            if ($book->stock <= 0) {
                return back()->with('error', 'Stok buku habis.');
            }

            $book->decrement('stock');

            $borrowRequest->update([
                'status' => BorrowRequest::STATUS_APPROVED,
                'due_date' => $data['due_date'],
                'borrow_code' => $borrowRequest->borrow_code ?? strtoupper(Str::random(8)),
                'processed_by' => $request->user()->id,
                'processed_at' => now(),
                'processed_action' => 'approved',
            ]);

            return back()->with('success', 'Permintaan peminjaman disetujui.');
        });
    }

    public function reject(Request $request, BorrowRequest $borrowRequest): RedirectResponse
    {
        $this->authorizeAction($borrowRequest);

        if ($borrowRequest->status !== BorrowRequest::STATUS_PENDING) {
            return back()->with('error', 'Permintaan tidak dalam status pending.');
        }

        $borrowRequest->update([
            'status' => BorrowRequest::STATUS_REJECTED,
            'processed_by' => $request->user()->id,
            'processed_at' => now(),
            'processed_action' => 'rejected',
        ]);

        return back()->with('success', 'Permintaan peminjaman ditolak.');
    }

    public function requestReturn(BorrowRequest $borrowRequest): RedirectResponse
    {
        if ($borrowRequest->user_id !== request()->user()->id) {
            abort(403);
        }

        if ($borrowRequest->status !== BorrowRequest::STATUS_APPROVED) {
            return back()->with('error', 'Status peminjaman tidak valid untuk permintaan pengembalian.');
        }

        $borrowRequest->update([
            'status' => BorrowRequest::STATUS_RETURN_REQUESTED,
        ]);

        return back()->with('success', 'Permintaan pengembalian buku telah dikirim.');
    }

    public function confirmReturn(Request $request, BorrowRequest $borrowRequest): RedirectResponse
    {
        $this->authorizeAction($borrowRequest);

        if (! in_array($borrowRequest->status, [BorrowRequest::STATUS_APPROVED, BorrowRequest::STATUS_RETURN_REQUESTED], true)) {
            return back()->with('error', 'Status peminjaman tidak valid untuk konfirmasi pengembalian.');
        }

        return DB::transaction(function () use ($borrowRequest, $request) {
            $book = $borrowRequest->book()->lockForUpdate()->first();

            $book->increment('stock');

            $borrowRequest->update([
                'status' => BorrowRequest::STATUS_RETURNED,
                'return_confirmed_by' => $request->user()->id,
                'return_confirmed_at' => now(),
            ]);

            return back()->with('success', 'Pengembalian buku telah dikonfirmasi.');
        });
    }

    protected function authorizeAction(BorrowRequest $borrowRequest): void
    {
        $user = request()->user();

    if (! $user || ! in_array($user->role, [User::ROLE_ADMIN, User::ROLE_PEGAWAI], true)) {
            abort(403);
        }
    }
}
