# SIPERpus — Sistem Informasi Perpustakaan

Aplikasi web manajemen perpustakaan berbasis **PHP Native + MySQL** dengan antarmuka Bootstrap 5.  
Dibangun untuk keperluan **Uji Kompetensi Kejuruan (UJK)** Rekayasa Perangkat Lunak.

> **Author:** Muhammad Faiq Yusuf Raharja ([@faiqyr](https://github.com/faiqyr))  
> **Tanggal:** 10 Agustus 2026  
> **Versi:** 1.0.0

---

## Daftar Isi

- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Struktur Proyek](#struktur-proyek)
- [Prasyarat](#prasyarat)
- [Instalasi Lokal](#instalasi-lokal)
- [Konfigurasi Database](#konfigurasi-database)
- [Akun Default](#akun-default)
- [Screenshot](#screenshot)
- [Deploy ke Hosting](#deploy-ke-hosting)
- [Lisensi](#lisensi)

---

## Fitur

| Modul | Keterangan |
|---|---|
| **Autentikasi** | Login petugas dengan `password_hash` / `password_verify`, session management |
| **Dashboard** | Statistik real-time: total buku, anggota, peminjaman aktif, dan keterlambatan |
| **Data Buku** | CRUD lengkap — tambah, edit, hapus, cari berdasarkan judul/pengarang |
| **Data Anggota** | CRUD lengkap — tambah, edit, hapus data anggota perpustakaan |
| **Transaksi Peminjaman** | Catat peminjaman baru, proses pengembalian, hitung denda otomatis (Rp 1.000/hari) |
| **Laporan** | Daftar buku belum dikembalikan & yang terlambat, lengkap dengan estimasi denda dan fitur cetak |
| **Flash Message** | Notifikasi SweetAlert2 untuk setiap aksi (berhasil/gagal) |

---

## Teknologi

- **Backend:** PHP 8.x (Native, tanpa framework)
- **Database:** MySQL 5.7+ / MariaDB
- **Frontend:** Bootstrap 5.3, Bootstrap Icons
- **Library JS:** DataTables 1.13, SweetAlert2 11
- **Koneksi DB:** PDO dengan prepared statements (mencegah SQL Injection)
- **Server Lokal:** XAMPP / Laragon

---

## Struktur Proyek

```
perpustakaan/
├── assets/
│   ├── css/            # Custom stylesheet
│   ├── img/            # Gambar/aset statis
│   ├── js/             # Custom JavaScript
│   └── vendor/         # Library pihak ketiga (opsional lokal)
├── config/
│   ├── koneksi.php          # ⚠️  TIDAK di-push (ada di .gitignore)
│   └── koneksi.php.example  # ✅  Template — salin & rename menjadi koneksi.php
├── database/
│   └── db_perpustakaan.sql  # Schema + data awal (seed)
├── docs/                    # Dokumentasi teknis
├── functions/
│   ├── anggota.func.php     # CRUD anggota
│   ├── buku.func.php        # CRUD buku
│   ├── pinjam.func.php      # Logika peminjaman & denda
│   └── helper.php           # Flash message, utilitas sesi
├── includes/
│   ├── auth.php             # Penjaga akses (session guard)
│   ├── header.php           # Template header + sidebar
│   └── footer.php           # Template footer + script JS
├── logs/                    # Log error (di-ignore git)
├── pages/
│   ├── anggota.php          # Halaman kelola anggota
│   ├── buku.php             # Halaman kelola buku
│   ├── peminjaman.php       # Halaman transaksi peminjaman
│   └── laporan.php          # Halaman laporan
├── index.php                # Dashboard utama
├── login.php                # Halaman login
├── logout.php               # Proses logout
├── .gitignore
└── README.md
```

---

## Prasyarat

Pastikan software berikut sudah terinstal:

| Software | Versi Minimum |
|---|---|
| PHP | 8.0 |
| MySQL / MariaDB | 5.7 / 10.3 |
| Apache | 2.4 |
| XAMPP / Laragon | Versi terbaru |

---

## Instalasi Lokal

**1. Clone repository**

```bash
git clone https://github.com/faiqyr/siperpus.git
```

**2. Pindahkan ke folder htdocs**

Salin atau pindahkan folder `perpustakaan` ke dalam `C:\xampp\htdocs\` (XAMPP) atau `C:\laragon\www\` (Laragon).

```
C:\xampp\htdocs\perpustakaan\
```

**3. Konfigurasi database**

```bash
# Salin template konfigurasi
copy config\koneksi.php.example config\koneksi.php
```

Buka `config/koneksi.php` lalu sesuaikan nilai berikut:

```php
$host     = "localhost";
$username = "root";       // username MySQL Anda
$password = "";           // password MySQL Anda
$database = "db_perpustakaan";
$baseUrl  = "http://localhost/perpustakaan";
```

**4. Import database**

- Buka `http://localhost/phpmyadmin`
- Buat database baru bernama `db_perpustakaan`
- Klik **Import** → pilih file `database/db_perpustakaan.sql` → klik **Go**

**5. Jalankan aplikasi**

Buka browser dan akses:

```
http://localhost/perpustakaan
```

---

## Konfigurasi Database

Schema database terdiri dari 4 tabel:

```
users       — data petugas / admin
buku        — data koleksi buku
anggota     — data anggota perpustakaan
peminjaman  — transaksi peminjaman & pengembalian
```

Detail lengkap ada di file `database/db_perpustakaan.sql` dan `docs/erd.md`.

---

## Akun Default

Setelah import database, gunakan akun berikut untuk login:

| Field | Nilai |
|---|---|
| Username | `admin` |
| Password | `admin123` |
| Role | Admin |

> **Penting:** Ganti password default segera setelah pertama login, terutama di lingkungan production.

---

## Screenshot

| Halaman | Keterangan |
|---|---|
| Login | Autentikasi petugas |
| Dashboard | Statistik ringkasan |
| Data Buku | CRUD dengan pencarian |
| Transaksi | Peminjaman & pengembalian |
| Laporan | Cetak laporan keterlambatan |

*(Screenshot tersedia di folder `docs/`)*

---

## Deploy ke Hosting

Lihat panduan lengkap di bagian bawah dokumen ini atau di `docs/manual-pengguna.md`.

Ringkasan langkah deploy ke shared hosting (InfinityFree, Niagahoster, dll.):

1. Upload seluruh file via **File Manager** atau **FTP** ke folder `public_html/`
2. Buat database MySQL baru di **cPanel**
3. Import `database/db_perpustakaan.sql` via **phpMyAdmin**
4. Upload dan sesuaikan `config/koneksi.php` di server (jangan lewat git!)
5. Ubah `$baseUrl` menjadi URL domain Anda
6. Ubah `ENVIRONMENT` menjadi `'production'`

---

## Lisensi

Proyek ini dibuat untuk keperluan pendidikan (UJK SMK).  
Bebas digunakan dan dimodifikasi untuk keperluan belajar.

---

*Dibuat dengan ❤️ oleh [@faiqyr](https://github.com/faiqyr)*
