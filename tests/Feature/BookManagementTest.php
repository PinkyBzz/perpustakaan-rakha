<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_pegawai_can_create_book(): void
    {
        $pegawai = User::factory()->create([
            'role' => User::ROLE_PEGAWAI,
        ]);

        $this->actingAs($pegawai)
            ->get(route('books.create'))
            ->assertOk();

        $payload = [
            'title' => 'Panduan Pegawai Kreatif',
            'author' => 'Tim Perpus',
            'publisher' => 'Perpustakaan Center',
            'publication_year' => now()->year,
            'isbn' => '978' . str_pad((string) random_int(0, 9999999999), 10, '0', STR_PAD_LEFT),
            'stock' => 7,
            'description' => 'Buku panduan internal untuk petugas perpustakaan.',
        ];

        $response = $this->actingAs($pegawai)->post(route('books.store'), $payload);

        $response->assertRedirect(route('books.index'));

        $this->assertDatabaseHas('books', [
            'title' => $payload['title'],
            'created_by' => $pegawai->id,
            'updated_by' => $pegawai->id,
        ]);
    }

    public function test_regular_member_cannot_access_book_form(): void
    {
        $member = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

    $response = $this->actingAs($member)->get(route('books.create'));

    $this->assertContains($response->status(), [403, 404]);
    }

    public function test_regular_member_cannot_store_book(): void
    {
        $member = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $payload = [
            'title' => 'Buku Rahasia',
            'author' => 'Anonim',
            'stock' => 3,
        ];

        $response = $this->actingAs($member)->post(route('books.store'), $payload);

        $response->assertForbidden();

        $this->assertDatabaseCount('books', 0);
    }
}
