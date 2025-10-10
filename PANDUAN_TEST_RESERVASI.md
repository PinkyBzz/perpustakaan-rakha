# 🧪 PANDUAN TEST FITUR RESERVASI

## ✅ Persiapan
Pastikan sudah menjalankan:
```bash
php artisan migrate
php artisan optimize:clear
```

## 📋 Checklist Test

### ✅ 1. TEST DAFTAR ANTRIAN (User)

**Langkah**:
1. Login sebagai user (siswa): `user@example.com`
2. Cari buku yang stok = 0 (atau set manual di database)
3. Buka detail buku
4. **Expected**: Muncul tombol "Menunggu Antrian" (warna kuning/orange)
5. Klik tombol tersebut
6. **Expected**: 
   - Redirect ke halaman yang sama
   - Muncul alert sukses: "Berhasil mendaftar antrian! Anda akan mendapat notifikasi saat buku tersedia."
   - Tombol berubah menjadi alert kuning: "Anda dalam Antrian - Posisi antrian: 1"

**Screenshot Checklist**:
- [ ] Tombol "Menunggu Antrian" tampil
- [ ] Alert sukses muncul
- [ ] Posisi antrian "1" tampil
- [ ] Section "Antrian Reservasi Buku" muncul di dashboard

---

### ✅ 2. TEST POSISI ANTRIAN BERTAMBAH

**Langkah**:
1. Logout
2. Login sebagai user lain: `user2@example.com` (buat dulu jika belum ada)
3. Buka detail buku yang sama
4. Klik "Menunggu Antrian"
5. **Expected**: 
   - Posisi antrian: **2**
6. Login user ketiga
7. Daftar antrian lagi
8. **Expected**:
   - Posisi antrian: **3**

**Screenshot Checklist**:
- [ ] User 1: Posisi 1
- [ ] User 2: Posisi 2
- [ ] User 3: Posisi 3

---

### ✅ 3. TEST DASHBOARD RESERVASI

**Langkah**:
1. Login sebagai salah satu user yang sudah daftar antrian
2. Buka Dashboard
3. **Expected**:
   - Muncul section "Antrian Reservasi Buku"
   - Background kuning/orange
   - Tampil judul buku
   - Badge "Antrian ke-X"
   - Tombol Cancel (×)

**Screenshot Checklist**:
- [ ] Section reservasi muncul
- [ ] Badge "Antrian ke-1" (atau sesuai posisi)
- [ ] Tombol cancel ada

---

### ✅ 4. TEST CANCEL ANTRIAN

**Langkah**:
1. Klik tombol Cancel (×) di dashboard
2. **Expected**:
   - Confirm dialog muncul
3. Klik "Ya, Batalkan"
4. **Expected**:
   - Redirect ke dashboard
   - Alert sukses: "Reservasi berhasil dibatalkan."
   - Section reservasi hilang (jika tidak ada antrian lain)
5. Buka detail buku lagi
6. **Expected**:
   - Kembali tampil tombol "Menunggu Antrian"

**Screenshot Checklist**:
- [ ] Confirm dialog tampil
- [ ] Alert sukses muncul
- [ ] Reservasi hilang dari dashboard
- [ ] Detail buku kembali normal

---

### ✅ 5. TEST NOTIFIKASI BUKU TERSEDIA (Staff Return)

**Langkah**:
1. Pastikan ada minimal 1 user di antrian
2. Login sebagai pegawai: `pegawai@example.com`
3. Buka menu "Peminjaman"
4. Pilih peminjaman buku yang direservasi
5. Klik "Konfirmasi Pengembalian"
6. **Expected**:
   - Stok buku +1
   - Sistem otomatis kirim notifikasi ke **user pertama** di antrian
7. Logout dan login sebagai user pertama
8. **Expected**:
   - Badge notifikasi muncul (🔔 dengan angka)
   - Klik badge → muncul notifikasi: "Buku yang Anda Reservasi Sudah Tersedia!"
   - Buka Dashboard → Alert hijau "✅ Buku Tersedia untuk Anda!"
   - Countdown: "Segera ajukan peminjaman sebelum [tanggal + jam]"
9. Buka detail buku
10. **Expected**:
    - Alert hijau "Buku Tersedia untuk Anda!"
    - Tombol "Pinjam Buku" kembali muncul

**Screenshot Checklist**:
- [ ] Badge notifikasi (🔔 1)
- [ ] Isi notifikasi benar
- [ ] Alert hijau di dashboard
- [ ] Countdown 48 jam tampil
- [ ] Alert hijau di detail buku

---

### ✅ 6. TEST EMAIL NOTIFIKASI

**Persiapan - Config .env**:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Langkah**:
1. Ulangi Test #5 (staff return buku)
2. **Expected**:
   - Email diterima di inbox user pertama
   - Subject: "Buku yang Anda Reservasi Sudah Tersedia!"
   - Isi: Info buku + tombol "Pinjam Sekarang" + warning 48 jam

**Screenshot Checklist**:
- [ ] Email diterima
- [ ] Subject benar
- [ ] Tombol CTA ada
- [ ] Warning 48 jam ada

---

### ✅ 7. TEST PINJAM DARI NOTIFIKASI

**Langkah**:
1. User yang dapat notifikasi (status=notified)
2. Buka detail buku (masih ada alert hijau)
3. Klik "Pinjam Buku"
4. Isi form peminjaman
5. Submit
6. **Expected**:
   - Peminjaman berhasil dibuat
   - Alert notifikasi hilang
   - Section reservasi hilang dari dashboard
   - Status reservasi di database: **fulfilled**

**Screenshot Checklist**:
- [ ] Form pinjam berfungsi
- [ ] Peminjaman berhasil
- [ ] Alert hilang
- [ ] Reservasi fulfilled

---

### ✅ 8. TEST NOTIFIKASI USER BERIKUTNYA

**Langkah**:
1. Pastikan ada minimal 2 user di antrian
2. Staff return buku
3. **Expected**: User pertama dapat notifikasi
4. User pertama **TIDAK PINJAM** (biarkan)
5. Staff return buku lagi (ada user lain yang pinjam & return)
6. **Expected**:
   - User kedua dapat notifikasi (otomatis naik jadi pertama)
   - Posisi antrian: **1**

**Screenshot Checklist**:
- [ ] User 2 dapat notifikasi
- [ ] Posisi berubah jadi 1

---

### ✅ 9. TEST EXPIRED (48 JAM)

**Langkah Manual** (simulasi):
1. Update database manual:
   ```sql
   UPDATE book_reservations 
   SET notified_at = NOW() - INTERVAL 49 HOUR,
       expires_at = NOW() - INTERVAL 1 HOUR
   WHERE status = 'notified';
   ```
2. Buat scheduled command untuk auto-expire:
   ```bash
   php artisan make:command ExpireReservations
   ```
3. Jalankan command:
   ```bash
   php artisan reservations:expire
   ```
4. **Expected**:
   - Status reservasi: **expired**
   - User berikutnya dapat notifikasi

**Screenshot Checklist**:
- [ ] Status expired di database
- [ ] User berikutnya naik

---

### ✅ 10. TEST VALIDASI

**Test 10.1 - User sudah punya antrian**:
1. User daftar antrian untuk buku A
2. User coba daftar antrian lagi untuk buku A
3. **Expected**: Error "Anda sudah terdaftar dalam antrian buku ini."

**Test 10.2 - Cancel antrian orang lain**:
1. User A daftar antrian
2. User B coba cancel antrian User A (manual via URL)
3. **Expected**: Error 403 Unauthorized

**Screenshot Checklist**:
- [ ] Validasi duplikat antrian bekerja
- [ ] Validasi ownership cancel bekerja

---

## 📊 Data Test

### Buat User Test
```sql
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Test User 1', 'user1@test.com', '$2y$12$LQv3c1yYqBWVHxkSfDoSQOqb.7nU8Z3xVxOZjJXJfKQQJhNQkJQSa', 'user', NOW(), NOW()),
('Test User 2', 'user2@test.com', '$2y$12$LQv3c1yYqBWVHxkSfDoSQOqb.7nU8Z3xVxOZjJXJfKQQJhNQkJQSa', 'user', NOW(), NOW()),
('Test User 3', 'user3@test.com', '$2y$12$LQv3c1yYqBWVHxkSfDoSQOqb.7nU8Z3xVxOZjJXJfKQQJhNQkJQSa', 'user', NOW(), NOW());
-- Password: password
```

### Set Stok Buku = 0
```sql
UPDATE books SET stock = 0 WHERE id = 1;
```

### Cek Data Reservasi
```sql
SELECT r.id, u.name, b.title, r.status, r.created_at, r.notified_at, r.expires_at
FROM book_reservations r
JOIN users u ON r.user_id = u.id
JOIN books b ON r.book_id = b.id
ORDER BY r.created_at;
```

---

## 🐛 Troubleshooting

**Error: Class BookReservation not found**
```bash
composer dump-autoload
php artisan optimize:clear
```

**Error: Route not found**
```bash
php artisan route:cache
php artisan route:clear
```

**Notifikasi tidak kirim email**
- Cek config `.env` MAIL_*
- Test: `php artisan tinker`
  ```php
  Mail::raw('test', fn($m) => $m->to('test@email.com')->subject('Test'));
  ```

**Posisi antrian salah**
- Clear cache: `php artisan optimize:clear`
- Cek query di view: status harus 'waiting' only

**Database error saat migrate**
- Tabel sudah ada → OK
- Jika perlu rebuild:
  ```bash
  php artisan migrate:rollback --step=1
  php artisan migrate
  ```

---

## ✨ Hasil Test yang Diharapkan

- ✅ User bisa daftar antrian saat stok = 0
- ✅ Posisi antrian akurat (FIFO)
- ✅ Dashboard menampilkan semua antrian aktif
- ✅ User bisa cancel antrian sendiri
- ✅ Staff return buku → auto-notify user pertama
- ✅ Notifikasi email + database terkirim
- ✅ Alert hijau "Buku Tersedia" dengan countdown
- ✅ User bisa pinjam dari notifikasi
- ✅ User berikutnya otomatis naik posisi
- ✅ Validasi duplikat & ownership bekerja

---

## 📸 Screenshot yang Harus Diambil

1. Tombol "Menunggu Antrian" (stok = 0)
2. Alert sukses setelah daftar
3. Badge "Antrian ke-1"
4. Dashboard dengan section reservasi
5. Notifikasi database (badge 🔔)
6. Email "Buku Tersedia"
7. Alert hijau "Buku Tersedia untuk Anda"
8. Countdown 48 jam
9. Form pinjam buku (dari notified)
10. Validasi error (duplikat antrian)

---

**SELAMAT TESTING! 🎉**
