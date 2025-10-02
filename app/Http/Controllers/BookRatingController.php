<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRatingRequest;
use App\Models\Book;
use App\Models\BookRating;
use Illuminate\Http\RedirectResponse;

class BookRatingController extends Controller
{
    public function store(StoreBookRatingRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();

        BookRating::updateOrCreate(
            [
                'book_id' => $book->id,
                'user_id' => $request->user()->id,
            ],
            [
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]
        );

        return back()->with('success', 'Terima kasih atas ulasan anda.');
    }
}
