<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@perpustakaan.com',
            'role' => User::ROLE_ADMIN,
            'password' => bcrypt('password'),
        ]);

        $pegawai = User::factory()->create([
            'name' => 'Petugas Perpustakaan',
            'email' => 'pegawai@perpustakaan.com',
            'role' => User::ROLE_PEGAWAI,
            'password' => bcrypt('password'),
        ]);

        $members = User::factory(10)->create();

        Book::factory(8)->create([
            'created_by' => $admin->id,
        ]);

        Book::factory(4)->create([
            'created_by' => $pegawai->id,
        ]);
    }
}
