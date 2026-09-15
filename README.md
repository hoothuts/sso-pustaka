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

Bagian ini adalah arahan untuk partner developer yang melanjutkan — semua temuan di bawah sudah diverifikasi langsung ke kode (bukan dugaan), lengkap dengan lokasi file supaya bisa langsung ditindaklanjuti.

### 🔴 Temuan 1 (Prioritas Tinggi): Nonaktifkan/Hapus Pegawai tidak memutus akses SSO-nya
File: `application/controllers/dir/Manage_pegawai.php`, method `nonaktifkan()` dan `delete()`.

Kedua method ini **hanya mengubah tabel `pegawai`** (`status_anggota`, soft-delete via `status`), **tidak pernah menyentuh tabel `siperpus_sysuser`**. Sementara `Sso::callback()` (`application/controllers/Sso.php`) memvalidasi login SSO murni dari kolom `siperpus_sysuser.active`, tanpa pernah mengecek status pegawai terkait.

**Dampak nyata**: pegawai yang sudah di-nonaktifkan/dihapus dari menu Data Pegawai, tapi sebelumnya sempat ditautkan ke akun admin via SSO, **tetap bisa login penuh ke sistem perpustakaan**.

**Rekomendasi perbaikan**: di `nonaktifkan()` dan `delete()`, tambahkan langkah untuk ikut menonaktifkan (`active = 0`) baris `siperpus_sysuser` yang `nip_pegawai`-nya cocok dengan pegawai yang sedang diproses. Atau alternatif: `Sso::callback()` diubah supaya ikut mengecek `pegawai.status_anggota = 'Aktif'` sebelum mengizinkan login, bukan cuma cek tabel `siperpus_sysuser` saja.

### 🟡 Temuan 2: Import Excel Pegawai bukan alat sinkronisasi, hanya tambah data baru
File: `application/controllers/dir/Manage_pegawai.php`, method `import_excel()`.

Begitu ketemu 1 baris dengan NIP yang **sudah ada** di database, seluruh proses import langsung dibatalkan total (`trans_rollback(); exit;`) — bukan skip baris itu saja. Jadi fitur ini tidak bisa dipakai untuk "refresh" data pegawai dari file terbaru HR yang isinya campuran pegawai lama + baru — akan gagal 100% begitu ketemu 1 NIP lama.

**Rekomendasi perbaikan**: ubah logika jadi UPSERT by NIP (`cek_duplicate_nip()` yang sudah ada dipakai untuk UPDATE baris existing, bukan untuk membatalkan seluruh import), supaya bisa dipakai berkala untuk sinkronisasi data pegawai.

### 🟠 Temuan 3: SQL Injection di lookup User Admin
File: `application/models/Md_siperpus_sysuser.php`, method `getUserById()`:
```php
$hasil = $this->db->query("SELECT * FROM siperpus_sysuser where idsysuser='$id'");
```
`$id` di sini berasal langsung dari URL (dipakai endpoint `admin/daftar_user/edit/{id}` dan `.../pass/{id}`) tanpa sanitasi — beda dari method lain di file yang sama (`checkLogin()`, `getUserByNip()`) yang sudah pakai `get_where()` (aman, ter-parameterisasi otomatis). Butuh sesi admin untuk mengaksesnya, tapi tetap celah nyata untuk admin dengan role rendah.

**Rekomendasi perbaikan**: ganti ke pola yang sama seperti method lain di file ini —
```php
function getUserById($id) {
    return $this->db->get_where('siperpus_sysuser', array('idsysuser' => $id))->result();
}
```

### Efek Konversi Akun Manual → SSO (via Edit User)
- Username (ID User) tidak berubah saat konversi — riwayat log lama tetap konsisten.
- Password lokal akun tersebut menjadi string acak yang tidak pernah ditampilkan — akun jadi praktis **SSO-only**.
- Hak akses (permission per role/`idsysgroup`) ditentukan ulang setiap login, tidak terpengaruh oleh metode login (lokal vs SSO).
- **Risiko**: kalau admin menautkan akun ke pegawai yang **belum/tidak punya akun SSO aktif** di sistem pusat, akun tersebut jadi terkunci (password lokal tidak diketahui, SSO juga menolak).
- **Mitigasi yang tersedia**: buka Edit User lagi → pindah toggle ke "Input Manual" → set password baru untuk memulihkan akses lokal.
- **Belum ada, ide pengembangan**: validasi otomatis ke server SSO saat memilih pegawai (cek apakah NIP tersebut benar-benar terdaftar aktif di SSO) sebelum admin menyimpan penautan, supaya risiko terkunci di atas bisa dicegah sejak awal.
