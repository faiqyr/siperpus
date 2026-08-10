# Daftar Library dan Komponen Pre-Existing

Berikut adalah daftar library eksternal yang digunakan pada Sistem Informasi Perpustakaan ini untuk memenuhi TUGAS 4 (Unit: J.620100.019.02 — Menggunakan Library atau Komponen Pre-Existing).

Semua library ini diintegrasikan melalui CDN dan berfungsi normal tanpa menyebabkan error pada console browser.

| No | Nama Library | Versi | Sumber (URL) | Lisensi | Fungsi dalam Aplikasi |
|----|--------------|-------|--------------|---------|-----------------------|
| 1 | **Bootstrap** | 5.3.0 | [jsdelivr CDN](https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/) | MIT | Digunakan sebagai kerangka kerja tampilan (framework CSS) utama untuk memastikan layout responsif (grid system), komponen antarmuka yang rapi (navbar, sidebar, form, tombol, alert), serta konsistensi tipografi di seluruh ukuran layar (mobile 360px hingga desktop). |
| 2 | **DataTables** | 1.13.6 | [DataTables CDN](https://cdn.datatables.net/1.13.6/) | MIT | Digunakan bersama jQuery untuk menyulap tabel HTML statis menjadi tabel dinamis dengan fitur *paging* (halaman), *sorting* (pengurutan kolom), dan pencarian seketika (*instant search*) pada data Buku, Anggota, Transaksi, dan Laporan. |
| 3 | **SweetAlert2** | 11.7.32 | [jsdelivr CDN](https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/) | MIT | Digunakan untuk menggantikan dialog bawaan browser. Library ini menangani pop-up *Flash Messages* (notifikasi sukses/gagal yang elegan) setelah proses simpan/ubah data, dan juga menangani konfirmasi aksi kritis (seperti menghapus buku atau memproses pengembalian) sebelum form dikirimkan. |
| 4 | **jQuery** | 3.7.0 | [jQuery Code](https://code.jquery.com/jquery-3.7.0.min.js) | MIT | Merupakan dependensi inti (*core dependency*) yang diwajibkan oleh DataTables agar dapat memanipulasi DOM tabel secara efisien. |
| 5 | **Bootstrap Icons**| 1.10.5| [jsdelivr CDN](https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/) | MIT | Digunakan untuk menyediakan ikon-ikon vektor (SVG font) pada sidebar, tombol-tombol aksi (tambah, edit, hapus), dan elemen antarmuka lainnya agar UI terlihat lebih menarik. |

## Cara Verifikasi Penggunaan
1. Buka aplikasi di browser.
2. Klik kanan -> *Inspect* -> pilih tab **Console**.
3. Pastikan tidak ada pesan *error* berwarna merah yang berasal dari kegagalan memuat file library atau konflik JavaScript.
4. Coba hapus salah satu data buku (pastikan *SweetAlert* muncul).
5. Coba lakukan pencarian atau ubah jumlah baris (Show entries) pada tabel (pastikan *DataTables* bekerja).
6. Perkecil layar browser (ukuran mobile), pastikan sidebar dan grid *Bootstrap* menyesuaikan layar.
