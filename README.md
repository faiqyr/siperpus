# Sistem Informasi Perpustakaan (SIPERpus)

SIPERpus adalah sebuah Sistem Informasi Perpustakaan berbasis web yang dirancang khusus untuk memfasilitasi petugas dalam mengelola data buku, data anggota, serta mencatat transaksi peminjaman dan pengembalian buku secara efisien dan akurat. Sistem ini mengotomatiskan pengurangan dan penambahan stok buku, serta perhitungan denda keterlambatan.

Proyek ini dibangun sebagai penyelesaian ujian sertifikasi (Tugas 1 hingga Tugas 8).

## 🚀 Fitur Utama
- **Autentikasi:** Login aman untuk petugas perpustakaan (Password di-hash dengan Bcrypt).
- **Manajemen Buku:** CRUD (Create, Read, Update, Delete) data buku dengan fitur pencarian cepat.
- **Manajemen Anggota:** CRUD data anggota perpustakaan.
- **Transaksi Peminjaman:** 
  - Pencatatan peminjaman dengan tanggal jatuh tempo otomatis.
  - Stok buku otomatis berkurang.
- **Transaksi Pengembalian:**
  - Perhitungan denda otomatis (Rp. 1.000 per hari keterlambatan).
  - Stok buku otomatis bertambah kembali.
- **Laporan:** Menampilkan daftar buku yang sedang dipinjam dan yang mengalami keterlambatan secara interaktif.
- **Keamanan:** Menerapkan pencegahan SQL Injection (PDO Prepared Statements) dan XSS (htmlspecialchars).

## 💻 Kebutuhan Sistem (System Requirements)
- **Web Server:** Apache atau Nginx (Direkomendasikan menggunakan XAMPP atau Laragon).
- **PHP:** Versi 8.0 atau lebih baru.
- **Database:** MySQL versi 5.7+ atau MariaDB.
- **Browser:** Google Chrome, Mozilla Firefox, atau Microsoft Edge versi terbaru (mendukung HTML5 dan ES6).
- **Koneksi Internet:** Dibutuhkan untuk memuat library eksternal (Bootstrap, DataTables, SweetAlert2) melalui CDN.

## 🛠️ Langkah Instalasi dan Konfigurasi
1. **Unduh/Clone Repository:** Pindahkan folder proyek ini (`sertifikasi`) ke dalam direktori root web server Anda (contoh: `htdocs` untuk XAMPP atau `www` untuk Laragon).
2. **Import Database:**
   - Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
   - Buat database baru bernama **`db_perpustakaan`**.
   - Klik tab **Import** dan pilih file `database/db_perpustakaan.sql` yang berada di dalam folder proyek ini.
   - Klik **Go** / **Kirim** untuk memulai import struktur dan data awal (seed).
3. **Penyesuaian Konfigurasi Database:**
   - Buka file `config/koneksi.php`.
   - Sesuaikan kredensial koneksi berikut jika Anda menggunakan sandi root database:
     ```php
     $username = "root";
     $password = ""; // Isi jika root MySQL Anda memiliki password
     $database = "db_perpustakaan";
     $baseUrl = "http://localhost/sertifikasi"; // Sesuaikan dengan URL lokal Anda
     ```
   - Pastikan variabel `ENVIRONMENT` diatur ke `'production'` jika Anda ingin meng-online-kan aplikasi, atau biarkan `'development'` untuk melihat error saat mengembangkan sistem.

## 🔑 Cara Menjalankan Aplikasi & Kredensial Demo
Buka browser dan akses alamat URL instalasi Anda, contoh:  
👉 **`http://localhost/sertifikasi/`**

Gunakan kredensial akun demo berikut untuk masuk sebagai Petugas:
- **Username:** `admin`
- **Password:** `admin123`

## 📁 Struktur Folder Proyek
```text
sertifikasi/
|-- config/                   # Konfigurasi database dan environment
|   |-- koneksi.php
|-- database/                 # Skema dan seed database
|   |-- db_perpustakaan.sql
|-- docs/                     # Dokumentasi proyek (Guidelines, Manual)
|   |-- coding-guidelines.md
|   |-- daftar-library.md
|   |-- manual-pengguna.md
|   |-- panduan-debugging.md
|-- functions/                # Logika bisnis (Business Logic & Query)
|   |-- anggota.func.php
|   |-- buku.func.php
|   |-- helper.php
|   |-- pinjam.func.php
|-- includes/                 # Komponen UI global (Header, Footer, Auth)
|   |-- auth.php
|   |-- footer.php
|   |-- header.php
|-- logs/                     # Folder penampung error log (Production)
|-- pages/                    # Halaman modul utama (Presentasi/Views)
|   |-- anggota.php
|   |-- buku.php
|   |-- laporan.php
|   |-- peminjaman.php
|-- .gitignore                # Pengecualian file untuk Git
|-- index.php                 # Halaman utama (Dashboard)
|-- login.php                 # Halaman Otentikasi
|-- logout.php                # Script keluar sesi
|-- README.md                 # Dokumentasi utama proyek
```
