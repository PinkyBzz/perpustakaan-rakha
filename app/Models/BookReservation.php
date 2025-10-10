<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReservation extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_WAITING = 'waiting';      // User menunggu antrian
    const STATUS_NOTIFIED = 'notified';    // User sudah dinotif, buku tersedia
    const STATUS_FULFILLED = 'fulfilled';   // User berhasil pinjam
    const STATUS_EXPIRED = 'expired';       // Lewat 48 jam, tidak pinjam
    const STATUS_CANCELLED = 'cancelled';   // User cancel sendiri

    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'notified_at',
        'expires_at',
        'notes',
    ];

    protected $casts = [
        'notified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Book
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if reservation is waiting
     */
    public function isWaiting(): bool
    {
        return $this->status === self::STATUS_WAITING;
    }

    /**
     * Check if reservation is notified
     */
    public function isNotified(): bool
    {
        return $this->status === self::STATUS_NOTIFIED;
    }

    /**
     * Check if reservation is expired
     */
    public function isExpired(): bool
    {
        return $this->status === self::STATUS_NOTIFIED 
            && $this->expires_at 
            && $this->expires_at->isPast();
    }
}
