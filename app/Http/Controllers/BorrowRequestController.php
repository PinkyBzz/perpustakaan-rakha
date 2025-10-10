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
use App\Http\Controllers\BookReservationController;

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

    /**
     * Staff creates a borrow directly (can be for existing user or guest).
     */
    public function staffCreate(Request $request): RedirectResponse
    {
        $this->authorizeStaff();

        $data = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'due_date' => ['required', 'date', 'after:today'],
            'existing_user_id' => ['nullable', 'exists:users,id'],
            'guest_name' => ['required_without:existing_user_id', 'nullable', 'string', 'max:255'],
            'guest_contact' => ['nullable', 'string', 'max:255'],
            'guest_identifier' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'book_id.required' => 'Pilih buku.',
            'due_date.required' => 'Tanggal pengembalian wajib.',
            'due_date.after' => 'Tanggal harus setelah hari ini.',
            'guest_name.required_without' => 'Nama tamu wajib jika tidak memilih user terdaftar.',
        ]);

        // Convert empty string to null for existing_user_id
        if (isset($data['existing_user_id']) && $data['existing_user_id'] === '') {
            $data['existing_user_id'] = null;
        }

        $book = Book::findOrFail($data['book_id']);
        if ($book->stock <= 0) {
            return back()->with('error', 'Stok buku habis.');
        }

        // Prevent duplicate active borrow for same book & user if existing user chosen
        if (!empty($data['existing_user_id'])) {
            $duplicate = BorrowRequest::where('user_id', $data['existing_user_id'])
                ->where('book_id', $book->id)
                ->whereIn('status', [
                    BorrowRequest::STATUS_PENDING,
                    BorrowRequest::STATUS_APPROVED,
                    BorrowRequest::STATUS_RETURN_REQUESTED,
                ])->exists();
            if ($duplicate) {
                return back()->with('error', 'User tersebut masih punya peminjaman aktif untuk buku ini.');
            }
        }

        return DB::transaction(function () use ($data, $book, $request) {
            // Reduce stock immediately when staff creates (auto-approved scenario)
            $book = Book::where('id', $book->id)->lockForUpdate()->first();
            if ($book->stock <= 0) {
                return back()->with('error', 'Stok buku habis.');
            }
            $book->decrement('stock');

            $isGuest = empty($data['existing_user_id']);
            
            $borrow = BorrowRequest::create([
                'user_id' => $data['existing_user_id'] ?? null,
                'book_id' => $book->id,
                'status' => BorrowRequest::STATUS_APPROVED,
                'due_date' => $data['due_date'],
                'notes' => $data['notes'] ?? null,
                'borrow_code' => 'BRW-' . strtoupper(Str::random(6)),
                'processed_by' => $request->user()->id,
                'processed_at' => now(),
                'processed_action' => 'approved',
                'guest_name' => $isGuest ? ($data['guest_name'] ?? null) : null,
                'guest_contact' => $isGuest ? ($data['guest_contact'] ?? null) : null,
                'guest_identifier' => $isGuest ? ($data['guest_identifier'] ?? null) : null,
                'is_guest' => $isGuest,
            ]);

            return back()->with('success', 'Peminjaman berhasil dibuat ('.($borrow->is_guest ? 'Tamu' : 'User').'). Kode: '.$borrow->borrow_code);
        });
    }

    public function approve(Request $request, BorrowRequest $borrowRequest): RedirectResponse
    {
        $this->authorizeAction($borrowRequest);

        // Validasi input
        $validated = $request->validate([
            'due_date' => ['required', 'date', 'after:today'],
        ], [
            'due_date.required' => 'Tanggal pengembalian wajib diisi.',
            'due_date.date' => 'Format tanggal tidak valid.',
            'due_date.after' => 'Tanggal pengembalian harus setelah hari ini.',
        ]);

        // Cek status peminjaman
        if ($borrowRequest->status !== BorrowRequest::STATUS_PENDING) {
            return back()->with('error', 'Permintaan tidak dalam status pending. Status saat ini: ' . $borrowRequest->status);
        }

        // Proses approval dalam transaction
        return DB::transaction(function () use ($borrowRequest, $validated, $request) {
            // Lock buku untuk update stok
            $book = $borrowRequest->book()->lockForUpdate()->first();

            // Cek stok
            if ($book->stock <= 0) {
                return back()->with('error', 'Stok buku "' . $book->title . '" habis. Stok saat ini: ' . $book->stock);
            }

            // Kurangi stok
            $book->decrement('stock');

            // Generate kode peminjaman jika belum ada
            $borrowCode = $borrowRequest->borrow_code ?? 'BRW-' . strtoupper(Str::random(6));

            // Update status peminjaman
            $borrowRequest->update([
                'status' => BorrowRequest::STATUS_APPROVED,
                'due_date' => $validated['due_date'],
                'borrow_code' => $borrowCode,
                'processed_by' => $request->user()->id,
                'processed_at' => now(),
                'processed_action' => 'approved',
            ]);

            return back()->with('success', 'Peminjaman disetujui! Kode: ' . $borrowCode . ' | Tenggat: ' . date('d M Y', strtotime($validated['due_date'])));
        });
    }

    public function reject(Request $request, BorrowRequest $borrowRequest): RedirectResponse
    {
        $this->authorizeAction($borrowRequest);

        // Cek status peminjaman
        if ($borrowRequest->status !== BorrowRequest::STATUS_PENDING) {
            return back()->with('error', 'Permintaan tidak dalam status pending. Status saat ini: ' . $borrowRequest->status);
        }

        // Update status menjadi rejected
        $borrowRequest->update([
            'status' => BorrowRequest::STATUS_REJECTED,
            'processed_by' => $request->user()->id,
            'processed_at' => now(),
            'processed_action' => 'rejected',
        ]);

        $userName = $borrowRequest->user ? $borrowRequest->user->name : $borrowRequest->guest_name;
        return back()->with('success', 'Permintaan peminjaman dari ' . $userName . ' untuk buku "' . $borrowRequest->book->title . '" telah ditolak.');
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

        // Cek status peminjaman
        if (! in_array($borrowRequest->status, [BorrowRequest::STATUS_APPROVED, BorrowRequest::STATUS_RETURN_REQUESTED], true)) {
            return back()->with('error', 'Status peminjaman tidak valid untuk konfirmasi pengembalian. Status saat ini: ' . $borrowRequest->status);
        }

        // Proses pengembalian dalam transaction
        return DB::transaction(function () use ($borrowRequest, $request) {
            // Lock buku untuk update stok
            $book = $borrowRequest->book()->lockForUpdate()->first();

            // Tambah stok kembali
            $book->increment('stock');

            // Update status peminjaman
            $borrowRequest->update([
                'status' => BorrowRequest::STATUS_RETURNED,
                'return_confirmed_by' => $request->user()->id,
                'return_confirmed_at' => now(),
            ]);

            // Notify waiting users about book availability
            BookReservationController::notifyWaitingUsers($book);

            $userName = $borrowRequest->user ? $borrowRequest->user->name : $borrowRequest->guest_name;
            return back()->with('success', 'Pengembalian buku "' . $book->title . '" oleh ' . $userName . ' telah dikonfirmasi. Stok sekarang: ' . $book->stock);
        });
    }

    public function scanner()
    {
        $this->authorizeStaff();
        return view('scanner.index');
    }

    public function verifyQR(Request $request)
    {
        $this->authorizeStaff();

        $data = $request->validate([
            'qr_data' => ['required', 'string'],
        ]);

        try {
            // Parse QR code data
            $qrData = json_decode($data['qr_data'], true);

            if (!$qrData || !isset($qrData['code']) || $qrData['type'] !== 'borrow_verification') {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau format salah.'
                ], 400);
            }

            // Find borrow request by code
            $borrowRequest = BorrowRequest::with(['book', 'user'])
                ->where('borrow_code', $qrData['code'])
                ->first();

            if (!$borrowRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode peminjaman tidak ditemukan.'
                ], 404);
            }

            // Return borrow details (supports guest)
            $dueDate = $borrowRequest->due_date ? \Carbon\Carbon::parse($borrowRequest->due_date)->format('d M Y') : null;
            $processedAt = $borrowRequest->processed_at ? \Carbon\Carbon::parse($borrowRequest->processed_at)->format('d M Y H:i') : null;
            $daysRemaining = $borrowRequest->due_date ? now()->diffInDays(\Carbon\Carbon::parse($borrowRequest->due_date), false) : null;

            return response()->json([
                'success' => true,
                'data' => [
                    'borrow_code' => $borrowRequest->borrow_code,
                    'status' => $borrowRequest->status,
                    'status_label' => $this->getStatusLabel($borrowRequest->status),
                    'book' => [
                        'title' => $borrowRequest->book->title,
                        'author' => $borrowRequest->book->author,
                        'isbn' => $borrowRequest->book->isbn,
                    ],
                    'user' => $borrowRequest->user ? [
                        'name' => $borrowRequest->user->name,
                        'email' => $borrowRequest->user->email,
                        'is_guest' => false,
                    ] : [
                        'name' => $borrowRequest->guest_name,
                        'email' => $borrowRequest->guest_contact,
                        'identifier' => $borrowRequest->guest_identifier,
                        'is_guest' => true,
                    ],
                    'due_date' => $dueDate,
                    'processed_at' => $processedAt,
                    'days_remaining' => $daysRemaining,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memverifikasi QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function getStatusLabel($status)
    {
        return match($status) {
            BorrowRequest::STATUS_PENDING => 'Menunggu Persetujuan',
            BorrowRequest::STATUS_APPROVED => 'Disetujui',
            BorrowRequest::STATUS_REJECTED => 'Ditolak',
            BorrowRequest::STATUS_RETURN_REQUESTED => 'Menunggu Pengembalian',
            BorrowRequest::STATUS_RETURNED => 'Dikembalikan',
            default => 'Tidak Diketahui',
        };
    }

    protected function authorizeAction(BorrowRequest $borrowRequest): void
    {
        $user = request()->user();

    if (! $user || ! in_array($user->role, [User::ROLE_ADMIN, User::ROLE_PEGAWAI], true)) {
            abort(403);
        }
    }

    protected function authorizeStaff(): void
    {
        $user = request()->user();

        if (! $user || ! in_array($user->role, [User::ROLE_ADMIN, User::ROLE_PEGAWAI], true)) {
            abort(403);
        }
    }
}
