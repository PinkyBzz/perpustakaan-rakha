<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookReservation;
use App\Notifications\BookAvailableNotification;
use Illuminate\Http\Request;

class BookReservationController extends Controller
{
    /**
     * Store a new reservation (user masuk antrian)
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($request->book_id);

        // Cek apakah user sudah punya reservasi aktif untuk buku ini
        $existingReservation = BookReservation::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->whereIn('status', [BookReservation::STATUS_WAITING, BookReservation::STATUS_NOTIFIED])
            ->first();

        if ($existingReservation) {
            return back()->with('error', 'Anda sudah terdaftar dalam antrian buku ini.');
        }

        // Buat reservasi baru
        BookReservation::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'status' => BookReservation::STATUS_WAITING,
        ]);

        return back()->with('success', 'Berhasil mendaftar antrian! Anda akan mendapat notifikasi saat buku tersedia.');
    }

    /**
     * Cancel a reservation
     */
    public function cancel(BookReservation $reservation)
    {
        // Pastikan hanya user yang buat reservasi yang bisa cancel
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Hanya bisa cancel jika status waiting atau notified
        if (!in_array($reservation->status, [BookReservation::STATUS_WAITING, BookReservation::STATUS_NOTIFIED])) {
            return back()->with('error', 'Reservasi tidak dapat dibatalkan.');
        }

        $reservation->update(['status' => BookReservation::STATUS_CANCELLED]);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }

    /**
     * Notify waiting users when book becomes available
     * Dipanggil saat buku dikembalikan
     */
    public static function notifyWaitingUsers(Book $book)
    {
        // Refresh book model untuk mendapat stok terbaru
        $book->refresh();
        
        // Cek apakah ada stok tersedia
        if ($book->stock <= 0) {
            return;
        }

        // Ambil user pertama dalam antrian (FIFO berdasarkan created_at)
        $waitingReservation = BookReservation::where('book_id', $book->id)
            ->where('status', BookReservation::STATUS_WAITING)
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$waitingReservation) {
            return; // Tidak ada yang menunggu
        }

        // Update status menjadi notified
        $waitingReservation->update([
            'status' => BookReservation::STATUS_NOTIFIED,
            'notified_at' => now(),
            'expires_at' => now()->addHours(48), // 48 jam untuk meminjam
        ]);

        // Kirim notifikasi ke user
        $waitingReservation->user->notify(new BookAvailableNotification($book));
    }
}
