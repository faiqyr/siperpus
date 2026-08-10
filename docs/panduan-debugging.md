# Panduan Debugging (Bagian B - Tugas 5, 6, 7)

Dokumen ini disusun sebagai panduan bagi asesor untuk memverifikasi kemampuan penanganan kesalahan (*error handling*) dan penguasaan teknik *debugging* pada aplikasi Sistem Informasi Perpustakaan.

## 1. Konfigurasi Environment & Error Log (Tugas 5)
Sistem memiliki kontrol lingkungan (*Environment Control*) yang terletak di file `config/koneksi.php`. 

### Cara Kerja:
- **Mode Development**: Ubah `define('ENVIRONMENT', 'development');`. Aplikasi akan mengaktifkan `error_reporting(E_ALL)` dan `display_errors`. Semua *error* PHP akan dicetak langsung ke layar browser, membantu developer mencari celah kode. Pesan error koneksi database akan mencetak pesan teknis yang spesifik.
- **Mode Production**: Ubah `define('ENVIRONMENT', 'production');`. Aplikasi mematikan tampilan *error* dari layar pengguna. Sebaliknya, setiap error PHP atau *Exception* akan dialihkan *(logged)* secara rahasia ke dalam file `logs/error.log`. Pengguna (atau peretas) hanya akan melihat halaman *"Sistem Sedang Gangguan"* yang ramah dan aman.

## 2. Teknik Debugging yang Dapat Didemonstrasikan (Tugas 6)
Saat presentasi dengan asesor, dua teknik berikut dapat diperagakan secara langsung:

### Teknik A: Blok `try...catch` pada Transaksi (Database Exception)
Aplikasi memanfaatkan fitur PDO Exception. Semua *query INSERT/UPDATE/DELETE* yang memodifikasi data dibungkus dalam konstruksi `try...catch`.
- **Cara Uji Coba**: 
  1. Buka menu **Data Buku**. 
  2. Cobalah menambah buku baru dengan mengisi **Kode Buku** yang sudah ada sebelumnya (misalnya `BK001`). 
  3. Sistem tidak akan *crash* atau menampilkan kode SQL mentah (SQL *Error 1062 Duplicate entry*). Sebaliknya, *catch block* di `buku.php` (baris 30) akan menangkap pengecualian (*Exception*) ini dan menampilkannya sebagai pesan `SweetAlert2` yang rapi: *"Gagal menyimpan data. Kode Buku mungkin sudah digunakan."*

### Teknik B: Browser DevTools (Tab Network & Console)
Aplikasi ini banyak bergantung pada interaksi *Frontend* (DataTables dan SweetAlert2).
- **Cara Uji Coba**: 
  1. Tekan tombol **F12** (atau Klik Kanan -> Inspect) pada browser.
  2. Buka tab **Console**. Tidak boleh ada pesan peringatan merah (*Javascript Error*). Jika *SweetAlert2* atau *DataTables* gagal dimuat dari CDN, error-nya akan langsung terlacak di sini.
  3. Buka tab **Network**. Coba lakukan pencarian nama buku di fitur *Search DataTables*. Anda akan melihat aktivitas DOM bekerja merender data di sisi klien tanpa mengirim *request HTTP* baru ke server, membuktikan algoritma pencarian berada di memori aplikasi sesuai spesifikasi tugas.

## 3. Pesan Error yang Ramah Pengguna (Tugas 7)
Selain perlindungan koneksi database di `config/koneksi.php`, aplikasi ini dilengkapi dengan validasi ID kosong (*Data Not Found*).

- **Cara Uji Coba**: 
  1. Masuk ke halaman **Data Anggota**. 
  2. Arahkan *mouse* ke tombol **Edit** pada salah satu anggota, lihat *ID* pada URL (contoh: `anggota.php?action=edit&id=2`).
  3. Secara manual ubah URL di address bar browser Anda ke *ID* yang tidak ada, contohnya: `anggota.php?action=edit&id=99999` lalu tekan *Enter*.
  4. Hasilnya: Daripada menampilkan pesan "*Fatal Error: Call to a member function on null*", aplikasi akan mencegat eksekusi (melalui pengecekan `if (!$anggota)`) dan memulangkan (*redirect*) Anda kembali ke tabel anggota dengan pesan *Flash Error* (warna merah) yang ramah: *"Mohon maaf, data anggota yang Anda cari tidak ditemukan atau sudah dihapus."*
