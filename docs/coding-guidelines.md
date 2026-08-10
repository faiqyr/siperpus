# Coding Guidelines & Best Practices

Dokumen ini mendefinisikan standar penulisan kode (Coding Guidelines) yang ditetapkan untuk proyek Sistem Informasi Perpustakaan, sesuai dengan tuntutan **TUGAS 6** (Unit: J.620100.016.01).

## 1. Standar Acuan (Standard Reference)
Proyek ini mengacu pada standar **PSR-12 (PHP Standard Recommendation)** untuk memastikan konsistensi penulisan kode PHP di seluruh file.

## 2. Aturan Penamaan (Naming Conventions)
Agar kode lebih deskriptif, mudah dibaca, dan konsisten, berikut adalah aturan baku penamaan:

- **Variabel dan Fungsi**: Menggunakan format **`camelCase`**. (Contoh: `$kodeBuku`, `$totalDenda`, `cariBuku()`, `urutkanPeminjaman()`). Tidak diperbolehkan menggunakan penamaan singkatan yang tidak bermakna seperti `$x`, `$td`, atau `$b`.
- **Konstanta**: Menggunakan format **`UPPER_SNAKE_CASE`**. (Contoh: `BASE_URL`, `MAX_LOGIN_ATTEMPTS`).
- **Tabel dan Kolom Database**: Menggunakan format **`snake_case`**. (Contoh: tabel `peminjaman`, kolom `tanggal_pinjam`, `kode_anggota`). *Catatan: Variabel PHP yang menampung langsung hasil baris database (`fetch_assoc`) akan tetap menyesuaikan key database (`snake_case`) hanya saat akses array.*
- **Nama File**: Menggunakan format **`snake_case`** atau nama tunggal kecil secara konsisten dengan ekstensi yang sesuai (Contoh: `index.php`, `buku.func.php`, `buku.php`).

## 3. Format dan Indentasi
- **Indentasi**: Menggunakan **4 spasi** (*4 spaces*) per level indentasi, bukan *tabs*.
- **Panjang Baris (Line Length)**: Maksimal **120 karakter** per baris. Jika lebih panjang, harus dipecah ke baris berikutnya.
- Tag pembuka PHP (`<?php`) harus selalu digunakan, hindari *short open tags* (`<?`).

## 4. Aturan Penulisan Komentar
- Gunakan *DocBlock* (`/** ... */`) untuk mendokumentasikan setiap fungsi kompleks di atas deklarasinya (menjelaskan parameter, *return type*, dan kompleksitas algoritma bila ada).
- Gunakan komentar sebaris (`//`) untuk menjelaskan alur logika spesifik (*business logic*) yang tidak langsung terlihat jelas dari nama variabel/fungsi.
- Hindari komentar yang berlebihan jika kode sudah *self-explanatory*.

## 5. Security & Best Practices (Keamanan & Praktik Terbaik)
- **Hardcoded Credentials**: Tidak boleh ada kredensial database yang tersebar di sembarang file. Semuanya harus terpusat pada file `config/koneksi.php` (dan tidak dilacak di version control publik melalui pengecualian di `.gitignore`).
- **SQL Injection Prevention**: Segala bentuk query ke database yang melibatkan input pengguna **WAJIB** menggunakan *Prepared Statements* via PDO (menggunakan `prepare` dan `execute`). Dilarang keras merangkai query langsung dengan variabel.
- **Cross-Site Scripting (XSS) Prevention**: Semua data yang bersumber dari pengguna atau database yang dicetak ke dalam HTML (View) **WAJIB** di-*escape* menggunakan fungsi `htmlspecialchars()`.
- **Manajemen Password**: Password pengguna tidak pernah disimpan dalam format teks terang (*plaintext*). Semua password di-*hash* dengan `password_hash()` (algoritma bawaan PHP seperti Bcrypt) dan diverifikasi menggunakan `password_verify()`.
- **Kode Mati (Dead Code)**: Tidak boleh ada perintah *debugging* seperti `var_dump()`, `print_r()`, `die()`, komentar blok kode yang tak terpakai, atau file sisa pengujian (*scratch files*) pada *repository* akhir.
