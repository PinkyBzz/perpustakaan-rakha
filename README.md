## Perpustakaan Baru

Aplikasi manajemen perpustakaan berbasis Laravel dengan tiga peran utama: **Admin**, **Pegawai**, dan **User**. Admin serta pegawai dapat mengelola buku dan proses peminjaman, sedangkan user dapat melihat katalog, mengajukan peminjaman, mengkonfirmasi pengembalian, serta memberikan rating dan komentar buku.

### Fitur Utama

- 🔐 Autentikasi siap pakai (Laravel Breeze) dengan upload foto profil saat registrasi.
- 👥 Manajemen pengguna khusus admin (tambah, edit, hapus, ubah foto profil & peran).
- 📚 CRUD buku untuk admin & pegawai, lengkap dengan upload sampul dan pelacakan stok otomatis.
- 📝 Permintaan peminjaman buku oleh user dan persetujuan / penolakan oleh admin atau pegawai.
- 🔄 Alur pengembalian terkontrol: user meminta pengembalian, petugas mengkonfirmasi, stok diperbarui.
- 🔑 Kode peminjaman acak digenerate otomatis saat permintaan disetujui sebagai bukti fisik.
- ⭐ Rating & komentar buku oleh user (satu rating per user per buku).
- 📊 Dashboard admin dengan statistik, daftar pengguna terbaru, dan grafik lingkaran aktivitas peminjaman.
- 🧑‍💼 Dashboard pegawai untuk memantau permintaan dan pengembalian terbaru.
- 👤 Dashboard user dengan riwayat peminjaman aktif dan rekomendasi buku terbaru.

### Struktur Role

| Role    | Kapabilitas Utama |
|---------|-------------------|
| Admin   | Kelola user & buku, aprov / tolak peminjaman, konfirmasi pengembalian, lihat statistik. |
| Pegawai | Kelola buku, aprov / tolak peminjaman, konfirmasi pengembalian. |
| User    | Lihat katalog, ajukan pinjam, konfirmasi pengembalian, beri rating & komentar. |

### Persiapan Lingkungan

1. **Kloning atau salin** project ini ke dalam direktori web server Anda (`c:/xampp/htdocs/perpustakaan_baru`).
2. **Install dependensi PHP**:
	```bash
	composer install
	```
3. **Install dependensi front-end**:
	```bash
	npm install && npm run build
	```
4. **Konfigurasi environment**
	- Salin `.env.example` menjadi `.env` (sudah disiapkan oleh scaffolding).
	- Pastikan variabel koneksi database menunjuk ke MySQL lokal dengan nama basis data `perpustakaan_baru`.
	- Pastikan `APP_URL` sesuai dengan host lokal Anda, misalnya `http://localhost/perpustakaan_baru/public` bila tidak menggunakan Laravel Valet/Artisan serve.
5. **Buat database MySQL** bernama `perpustakaan_baru` via phpMyAdmin atau perintah MySQL.
6. **Link storage** untuk akses file upload:
	```bash
	php artisan storage:link
	```
7. **Migrasi & seeding**:
	```bash
	php artisan migrate --seed
	```

Seeder akan membuat akun contoh:

| Role  | Email                     | Password |
|-------|---------------------------|----------|
| Admin | admin@perpustakaan.com    | password |
| Pegawai | pegawai@perpustakaan.com | password |

Serta beberapa user dan buku sample.

### Menjalankan Aplikasi

- Jalankan server pengembangan Laravel:
  ```bash
  php artisan serve
  ```
- Atau gunakan konfigurasi Apache/Nginx lokal yang mengarah ke direktori `public/`.

Untuk development front-end (Hot Module Reloading):

```bash
npm run dev
```

### Pengujian

Jalankan test PHPUnit standar:

```bash
php artisan test
```

### Catatan Tambahan

- Simpan gambar (sampul buku & foto profil) tersedia di disk `public`. Pastikan folder `storage/app/public` dapat ditulisi dan sudah di-link ke `public/storage`.
- Session disimpan di database sehingga tabel `sessions` wajib termigrasi.
- Untuk penggunaan produksi, sesuaikan konfigurasi cache, queue, dan mailer sesuai kebutuhan.

---

Dibangun dengan ❤️ menggunakan Laravel 12.
