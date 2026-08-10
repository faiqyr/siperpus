# Manual Penggunaan SIPERpus

Selamat datang di Panduan Penggunaan Sistem Informasi Perpustakaan (SIPERpus). Dokumen ini disusun untuk membantu **Petugas Perpustakaan** dalam menjalankan tugas sehari-hari melalui aplikasi web.

---

## 1. Cara Login ke Aplikasi
Sebelum dapat mengakses menu-menu perpustakaan, Anda harus masuk ke dalam sistem.

1. Buka browser dan kunjungi tautan aplikasi (contoh: `http://localhost/sertifikasi`).
2. Masukkan username Anda (contoh: `admin`).
3. Masukkan password rahasia Anda.
4. Klik tombol **Login**.

> ![Halaman Login](../assets/img/login_placeholder.png) *Screenshot: Tampilan halaman login petugas.*

---

## 2. Cara Menambahkan Data Buku
Setiap buku baru yang tiba di perpustakaan harus dicatat ke dalam sistem agar stoknya terdeteksi.

1. Pada menu di sebelah kiri (Sidebar), klik **Data Buku**.
2. Klik tombol biru bertuliskan **+ Tambah Buku** di bagian atas tabel.
3. Isi seluruh formulir (Kode Buku, Judul, Pengarang, Penerbit, Tahun Terbit, Kategori, dan Stok).
4. Klik tombol **Simpan Data**.
5. Akan muncul animasi pemberitahuan (Ceklis Hijau) jika buku berhasil ditambahkan.

> ![Halaman Tambah Buku](../assets/img/tambah_buku_placeholder.png) *Screenshot: Formulir penambahan data buku baru.*

---

## 3. Cara Mencatat Peminjaman Buku
Gunakan fitur ini ketika ada anggota yang membawa buku ke meja administrasi untuk dipinjam.

1. Pada menu di sebelah kiri, klik **Transaksi**.
2. Klik tombol biru bertuliskan **+ Transaksi Baru**.
3. Buatlah **Kode Peminjaman** yang unik (contoh: `TRX-001`).
4. Pada menu *dropdown*, pilih **Nama Anggota** yang meminjam.
5. Pada menu *dropdown* sebelahnya, pilih **Judul Buku** yang akan dipinjam (Hanya buku dengan stok > 0 yang bisa dipilih).
6. Tanggal Pinjam dan Batas Kembali (otomatis 7 hari) akan terisi. Anda bisa mengubahnya jika diperlukan.
7. Klik **Proses Peminjaman**. Stok buku akan otomatis berkurang.

> ![Proses Peminjaman](../assets/img/peminjaman_placeholder.png) *Screenshot: Proses pencatatan peminjaman.*

---

## 4. Cara Mencatat Pengembalian Buku & Perhitungan Denda
Gunakan fitur ini ketika anggota datang untuk mengembalikan buku. Sistem akan otomatis melacak apakah ia terlambat atau tidak.

1. Buka menu **Transaksi**.
2. Cari nama anggota atau buku pada kolom *Search* di atas tabel.
3. Pada baris data peminjaman yang masih berstatus *Dipinjam* (warna kuning), klik tombol hijau **Proses**.
4. Anda akan melihat layar ringkasan. Jika anggota terlambat, akan muncul kotak merah muda bertuliskan **Informasi Keterlambatan**, lengkap dengan total hari dan nominal **Denda** (Rp. 1000 per hari).
5. Terima uang denda (jika ada) beserta bukunya.
6. Klik tombol **Konfirmasi Pengembalian Buku**.
7. Akan muncul dialog peringatan *Apakah Anda yakin?*. Klik **Ya, Kembalikan!**. Stok buku akan otomatis bertambah kembali.

> ![Proses Pengembalian](../assets/img/pengembalian_placeholder.png) *Screenshot: Tampilan kalkulasi denda pengembalian otomatis.*

---

## 5. Cara Melihat Laporan Peminjaman
Petugas dapat memantau buku mana saja yang belum dikembalikan atau yang sedang masuk masa denda keterlambatan melalui fitur Laporan.

1. Buka menu **Laporan** dari sidebar.
2. Anda akan langsung melihat daftar semua transaksi peminjaman yang berstatus **Belum Kembali**.
3. Data akan otomatis terurut (*sorting*) dari tanggal **Batas Kembali** yang paling lampau hingga yang paling baru.
4. Anda dapat melihat secara persis selisih keterlambatan hari di kolom *Status* (Berwarna merah jika terlambat) dan estimasi tagihan denda sementara.
5. Anda dapat mencetak halaman ini dengan menekan tombol **Cetak Laporan** di pojok kanan atas.

> ![Halaman Laporan](../assets/img/laporan_placeholder.png) *Screenshot: Tampilan laporan peminjaman yang terlambat.*
