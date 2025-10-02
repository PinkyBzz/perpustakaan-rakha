<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'publication_year',
        'isbn',
        'stock',
        'description',
        'cover_image',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'publication_year' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }

    public function ratings()
    {
        return $this->hasMany(BookRating::class);
    }

    public function averageRating(): float
    {
        return round((float) $this->ratings()->avg('rating'), 1);
    }
}
