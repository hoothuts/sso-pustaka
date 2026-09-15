# Sistem Informasi Perpustakaan — Politeknik Kesehatan Riau

Aplikasi manajemen perpustakaan berbasis **CodeIgniter 3** (PHP), dengan integrasi Single Sign-On (SSO) kampus untuk login staf/admin.

## Kebutuhan Sistem

- PHP **7.4** (disarankan — beberapa library seperti PHPExcel bawaan aplikasi ini belum kompatibel penuh dengan PHP 8)
- MySQL/MariaDB 10.6+
- Ekstensi PHP: `mysqli`, `gd`, `zip`, `intl`, `curl`, `mbstring`, `bcmath`
- Apache dengan `mod_rewrite` aktif (untuk URL bersih via `.htaccess`)

## Cara Menjalankan — Opsi 1: XAMPP / Laragon (Manual)

1. Clone/salin folder project ini ke dalam `htdocs` (XAMPP) atau folder `www` (Laragon).
2. Buat database MySQL baru, mis. `sim_perpustakaan`.
3. Import file `.sql` database ke dalamnya. **File database TIDAK ikut di-commit di repo ini** (ukurannya besar) — minta filenya secara terpisah ke tim, lalu import lewat phpMyAdmin/HeidiSQL.
   - Aplikasi ini juga punya beberapa VIEW yang menggabungkan data dari database `sim_akademik` (data mahasiswa/dosen kampus) — kalau VIEW itu ingin berfungsi penuh, database `sim_akademik` juga perlu diimport terpisah dengan nama database yang sama persis (`sim_akademik`).
4. Edit `application/config/database.php`, sesuaikan `hostname`, `username`, `password`, `database` dengan kredensial MySQL lokal Anda.
5. Kalau pakai Laragon: otomatis dapat domain `http://nama-folder.test`. Kalau XAMPP: akses lewat `http://localhost/nama-folder/`.
6. Login admin default ada di tabel `siperpus_sysuser` — minta kredensial ke tim, atau buat user baru langsung lewat query INSERT ke tabel tersebut untuk akses awal.

> Catatan: `base_url` produksi di `application/config/config.php` mengarah ke domain asli (`lib.pkr.ac.id`). Untuk override ke domain lokal Anda tanpa mengubah file produksi, buat file `application/config/development/config.php` berisi:
> ```php
> <?php
> defined('BASEPATH') OR exit('No direct script access allowed');
> $config['base_url'] = 'http://nama-domain-lokal-anda/';
> ```
> File di folder `development/` otomatis dipakai kalau environment CodeIgniter = `development` (default kalau tidak diset lewat variabel server `CI_ENV`).

## Cara Menjalankan — Opsi 2: Docker (Direkomendasikan untuk testing cepat)

Sudah disiapkan `docker-compose.yml` lengkap dengan PHP 7.4 + Apache, MariaDB, dan phpMyAdmin.

1. Pastikan Docker Desktop terpasang dan berjalan.
2. Siapkan file database (`sim_akademik.sql`, `sim_perpustakaan.sql`) di folder **satu level di atas** folder project ini, di dalam folder bernama `Database` (path relatif yang dipakai `docker-compose.yml`: `../Database/`). Sesuaikan path ini di `docker-compose.yml` kalau struktur folder Anda berbeda.
3. Tambahkan domain lokal ke hosts file:
   - Windows: `C:\Windows\System32\drivers\etc\hosts` (edit sebagai Administrator)
   - Tambahkan baris: `127.0.0.1 lib.pkr.local`
4. Jalankan:
   ```
   docker compose up -d --build
   ```
   Proses pertama kali akan meng-import database secara otomatis — bisa memakan waktu cukup lama tergantung ukuran file SQL.
5. Akses aplikasi di `http://lib.pkr.local`, phpMyAdmin di `http://localhost:8081`.

## Integrasi SSO (Login Staf/Admin)

Konfigurasi ada di `application/config/sso.php` (`sso_base_url`, `sso_app_key`). Untuk aktif, aplikasi ini perlu didaftarkan di panel admin SSO pusat (`sso.pkr.ac.id/admin.php`) dengan:
- **Target Key**: harus sama persis dengan `sso_app_key` di config
- **URL Redirect Callback**: `http://<domain-anda>/sso/callback`

Login SSO hanya berlaku untuk **pegawai** yang NIP-nya sudah ditautkan ke akun staf lewat kolom `nip_pegawai` di tabel `siperpus_sysuser` — bisa ditautkan lewat menu **Administrator → Daftar User → Edit User → Pilih dari Data Pegawai**.

## Rencana Pengembangan Selanjutnya

### Sinkronisasi Data Pegawai
Tabel `pegawai` saat ini dikelola manual (CRUD + import Excel) lewat menu Data Pegawai, tidak ada sinkronisasi otomatis ke sistem HR/e-office kampus. Kalau nanti dibuatkan sinkronisasi otomatis:
- **Aman** selama sinkronisasi berupa **UPSERT berdasarkan kolom `nip`** (update kalau NIP sudah ada, insert kalau belum) — fitur SSO dan "Pilih dari Data Pegawai" sama-sama mengunci ke NIP, bukan `pegawai_id`.
- **Berisiko** kalau sinkronisasi berupa **truncate + insert ulang**, atau NIP seseorang berubah/dihapus di sumber data — akun staf yang sudah ditautkan ke NIP lama akan "putus" (SSO login gagal, fitur "Lihat Data Pegawai" akan menampilkan "tidak terhubung").
- **Rekomendasi**: pakai strategi UPSERT by NIP, jangan pernah mengubah/menghapus nilai `nip` untuk pegawai yang sudah pernah ditautkan ke akun sistem tanpa proses migrasi eksplisit.

### Efek Konversi Akun Manual → SSO (via Edit User)
- Username (ID User) tidak berubah saat konversi — riwayat log lama tetap konsisten.
- Password lokal akun tersebut menjadi string acak yang tidak pernah ditampilkan — akun jadi praktis **SSO-only**.
- Hak akses (permission per role/`idsysgroup`) ditentukan ulang setiap login, tidak terpengaruh oleh metode login (lokal vs SSO).
- **Risiko**: kalau admin menautkan akun ke pegawai yang **belum/tidak punya akun SSO aktif** di sistem pusat, akun tersebut jadi terkunci (password lokal tidak diketahui, SSO juga menolak).
- **Mitigasi yang tersedia**: buka Edit User lagi → pindah toggle ke "Input Manual" → set password baru untuk memulihkan akses lokal.
- **Belum ada, ide pengembangan**: validasi otomatis ke server SSO saat memilih pegawai (cek apakah NIP tersebut benar-benar terdaftar aktif di SSO) sebelum admin menyimpan penautan, supaya risiko terkunci di atas bisa dicegah sejak awal.
