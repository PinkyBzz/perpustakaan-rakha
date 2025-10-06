<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_RETURN_REQUESTED = 'return_requested';
    public const STATUS_RETURNED = 'returned';

    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'due_date',
        'borrow_code',
        'notes',
        'processed_by',
        'processed_at',
        'processed_action',
        'return_confirmed_by',
        'return_confirmed_at',
        'guest_name',
        'guest_contact',
        'guest_identifier',
        'is_guest',
    ];

    protected $casts = [
        'due_date' => 'date',
        'processed_at' => 'datetime',
        'return_confirmed_at' => 'datetime',
        'is_guest' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function returnConfirmer()
    {
        return $this->belongsTo(User::class, 'return_confirmed_by');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isReturnRequested(): bool
    {
        return $this->status === self::STATUS_RETURN_REQUESTED;
    }

    public function isReturned(): bool
    {
        return $this->status === self::STATUS_RETURNED;
    }
}
