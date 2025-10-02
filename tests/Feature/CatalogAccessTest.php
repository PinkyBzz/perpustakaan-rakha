<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_catalog_and_see_book_card(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $book = Book::factory()->create([
            'title' => 'Belajar Laravel Untuk Pemula',
            'description' => 'Panduan lengkap untuk memahami dasar Laravel secara praktis.',
        ]);

        $response = $this->actingAs($user)->get(route('books.catalog'));

        $response->assertOk()
            ->assertSeeText('Katalog Buku')
            ->assertSeeText($book->title)
            ->assertSeeText('Pinjam Buku')
            ->assertSeeText('Detail');
    }

    public function test_user_can_view_book_detail_with_description_and_borrow_button(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $book = Book::factory()->create([
            'title' => 'Desain Sistem Informasi',
            'description' => 'Materi lengkap mengenai analisis dan perancangan sistem informasi.',
        ]);

        $response = $this->actingAs($user)->get(route('books.show', $book));

        $response->assertOk()
            ->assertSeeText($book->title)
            ->assertSeeText($book->description)
            ->assertSeeText('Pinjam Buku')
            ->assertSeeText('Ulasan');
    }

    public function test_user_can_submit_rating_for_book(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $book = Book::factory()->create();

        $payload = [
            'rating' => 4,
            'comment' => 'Sangat membantu untuk memahami konsep perpustakaan digital.',
        ];

        $response = $this->actingAs($user)->post(route('books.ratings.store', $book), $payload);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('book_ratings', [
            'book_id' => $book->id,
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => $payload['comment'],
        ]);
    }
}
