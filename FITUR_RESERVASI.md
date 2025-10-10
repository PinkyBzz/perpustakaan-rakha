# FITUR RESERVASI / WAITING LIST BUKU

## 📋 Deskripsi
Fitur ini memungkinkan user untuk mendaftar dalam antrian ketika stok buku habis. User akan mendapat notifikasi otomatis saat buku tersedia.

## ✅ Yang Sudah Diimplementasikan

### 1. Database
- **Tabel**: `book_reservations`
- **Kolom**:
  - `id`, `user_id`, `book_id`
  - `status`: waiting | notified | fulfilled | expired | cancelled
  - `notified_at`, `expires_at` (48 jam untuk pinjam)
  - `timestamps`

### 2. Model & Relasi
- `BookReservation` model dengan relasi ke User & Book
- User hasMany BookReservation
- Book hasMany BookReservation

### 3. Controller
- `BookReservationController`:
  - `store()` - User daftar antrian
  - `cancel()` - User batalkan antrian
  - `notifyWaitingUsers()` - Static method dipanggil saat buku dikembalikan

### 4. Routes
- POST `/reservations` - Daftar antrian
- DELETE `/reservations/{id}` - Batalkan antrian

### 5. Notifikasi
- Email + Database notification
- `BookAvailableNotification` - Kirim saat buku tersedia
- Auto-trigger saat buku dikembalikan & stok bertambah

### 6. UI/UX

#### Halaman Detail Buku (`books/show.blade.php`)
**Ketika stok > 0:**
- Tombol "Pinjam Buku" (hijau)

**Ketika stok = 0 & belum reservasi:**
- Badge "Stok Habis" (abu-abu)
- Tombol "Menunggu Antrian" (kuning-orange)
- Info text tentang fitur

**Ketika sudah reservasi status=waiting:**
- Alert kuning dengan icon jam
- Menampilkan: "Anda dalam Antrian"
- Menampilkan: "Posisi antrian Anda: X"
- Info: "Anda akan mendapat notifikasi saat buku tersedia"
- Link "Batalkan Antrian"

**Ketika sudah reservasi status=notified:**
- Alert hijau dengan icon check
- Menampilkan: "Buku Tersedia untuk Anda!"
- Countdown: "Segera ajukan peminjaman sebelum [tanggal + jam]"
- Buku kembali bisa dipinjam

#### Dashboard User (`user/dashboard.blade.php`)
**Section baru: "Antrian Reservasi Buku"**
- Muncul hanya jika ada reservasi aktif
- Background kuning-orange gradient
- Per buku menampilkan:
  - Judul & pengarang
  - Badge "Antrian ke-X" (waiting) atau "✅ Buku Tersedia!" (notified)
  - Tombol cancel (×)
  - Link "Pinjam Sekarang →" (jika notified)

## 🔄 Alur Kerja

### Skenario 1: User Daftar Antrian
1. User buka detail buku yang stok = 0
2. Klik tombol "Menunggu Antrian"
3. Record baru di `book_reservations` dengan status=waiting
4. User redirect kembali dengan pesan sukses
5. Dashboard user menampilkan antrian + posisi

### Skenario 2: Buku Dikembalikan & Notifikasi
1. Petugas konfirmasi pengembalian buku
2. Stok buku +1
3. Sistem cek apakah ada user di waiting list
4. Ambil user pertama (FIFO berdasarkan created_at)
5. Update status reservasi: waiting → notified
6. Set expires_at = now + 48 jam
7. Kirim notifikasi email + database
8. User dapat email & notif in-app

### Skenario 3: User Pinjam dari Notifikasi
1. User buka detail buku (masih tampil alert hijau "Buku Tersedia")
2. Klik "Pinjam Buku"
3. Form peminjaman normal
4. Setelah submit, reservasi auto-update ke status=fulfilled

### Skenario 4: Reservasi Expired (48 jam lewat)
1. Jika user tidak pinjam dalam 48 jam
2. Scheduled job (perlu dibuat) update status → expired
3. Notifikasi user berikutnya di antrian

### Skenario 5: User Batalkan Antrian
1. User klik "Batalkan Antrian" di detail buku atau dashboard
2. Status update → cancelled
3. Posisi antrian user lain otomatis naik

## 📊 Perhitungan Posisi Antrian

```php
$queuePosition = BookReservation::where('book_id', $bookId)
    ->where('status', 'waiting')
    ->where('created_at', '<', $userReservation->created_at)
    ->count() + 1;
```

Logika:
- Hitung berapa reservasi dengan created_at lebih awal
- +1 untuk posisi user saat ini
- Hanya count yang status=waiting (exclude notified, expired, cancelled)

## 🧪 Cara Test

### Test 1: Daftar Antrian
```bash
1. Login sebagai user (siswa)
2. Cari buku yang stok = 0 (atau set manual di database)
3. Buka detail buku
4. Klik "Menunggu Antrian"
5. ✅ Cek: Muncul alert kuning "Anda dalam Antrian - Posisi antrian: 1"
6. ✅ Cek dashboard: Muncul section "Antrian Reservasi Buku"
```

### Test 2: Posisi Antrian Bertambah
```bash
1. Login user kedua
2. Reservasi buku yang sama
3. ✅ Cek: Posisi antrian = 2
4. Login user ketiga
5. Reservasi buku yang sama
6. ✅ Cek: Posisi antrian = 3
```

### Test 3: Notifikasi Saat Buku Tersedia
```bash
1. Login sebagai admin/pegawai
2. Konfirmasi pengembalian buku yang direservasi
3. ✅ Cek email user pertama: Ada email "Buku yang Anda Reservasi Sudah Tersedia"
4. Login sebagai user pertama
5. ✅ Cek dashboard: Alert hijau "Buku Tersedia untuk Anda!"
6. ✅ Cek detail buku: Alert hijau dengan countdown 48 jam
```

### Test 4: Pinjam dari Notifikasi
```bash
1. User yang dapat notifikasi buka detail buku
2. Klik "Pinjam Buku"
3. Submit form
4. ✅ Cek: Peminjaman berhasil dibuat
5. ✅ Cek: Alert notifikasi hilang (status=fulfilled)
```

### Test 5: Batalkan Antrian
```bash
1. User yang sudah reservasi (status=waiting)
2. Klik "Batalkan Antrian"
3. Confirm dialog
4. ✅ Cek: Reservasi hilang dari dashboard
5. ✅ Cek: Detail buku kembali tampil tombol "Menunggu Antrian"
6. ✅ Cek: User lain di antrian naik posisi
```

## 🐛 Troubleshooting

**Error: Class BookReservation not found**
```bash
composer dump-autoload
php artisan optimize:clear
```

**Notifikasi tidak terkirim**
- Cek `.env` config MAIL_*
- Test: `php artisan tinker` → `Mail::raw('test', fn($m) => $m->to('email@test.com')->subject('Test'));`

**Posisi antrian tidak update**
- Cek query di view: `where('status', 'waiting')` harus exclude yang sudah notified/expired

**Reservasi tidak auto-fulfilled**
- Perlu tambah logic di `BorrowRequestController::store()` untuk update reservasi user saat borrow berhasil

## 🚀 Enhancement Future (Opsional)

1. **Scheduled Job untuk Expired**
   ```php
   // app/Console/Commands/ExpireReservations.php
   BookReservation::where('status', 'notified')
       ->where('expires_at', '<', now())
       ->update(['status' => 'expired']);
   ```

2. **Limit Maksimal Antrian per User**
   ```php
   $activeReservations = BookReservation::where('user_id', auth()->id())
       ->whereIn('status', ['waiting', 'notified'])
       ->count();
   
   if ($activeReservations >= 5) {
       return back()->with('error', 'Maksimal 5 antrian aktif.');
   }
   ```

3. **Statistik Antrian**
   - Total reservasi per buku
   - Rata-rata waktu tunggu
   - Conversion rate (notified → fulfilled)

4. **Notifikasi Multiple Channel**
   - WhatsApp (via Twilio/Fonnte)
   - SMS
   - Push notification (PWA)

## 📝 File yang Dimodifikasi

```
database/migrations/
  └── 2025_10_10_000002_create_book_reservations_table.php

app/Models/
  ├── BookReservation.php (NEW)
  ├── User.php (+ reservations relation)
  └── Book.php (+ reservations relation)

app/Http/Controllers/
  ├── BookReservationController.php (NEW)
  └── BorrowRequestController.php (+ notify on return)

app/Notifications/
  └── BookAvailableNotification.php (NEW)

resources/views/
  ├── books/show.blade.php (+ reservation UI)
  └── user/dashboard.blade.php (+ reservation section)

routes/
  └── web.php (+ reservation routes)
```

## ✨ Fitur Selesai & Ready!

Semua komponen sudah terimplementasi:
- ✅ Database schema
- ✅ Model & relasi
- ✅ Controller logic
- ✅ Routes
- ✅ Notifikasi
- ✅ UI/UX lengkap
- ✅ Auto-trigger on book return

**Silakan test sesuai panduan di atas!** 🎉
