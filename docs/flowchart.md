# Flowchart Proses Peminjaman & Pengembalian Buku (Tugas 5)

Berikut adalah algoritma proses Peminjaman dan Perhitungan Denda Pengembalian yang digambarkan menggunakan diagram alir (Flowchart) Mermaid.

*Tugas Mandiri untuk Anda: Buka preview dokumen ini (jika VS Code Anda menggunakan ekstensi Markdown Preview Mermaid Support) lalu ambil screenshot diagram di bawah ini dan simpan sebagai `docs/flowchart.png`.*

```mermaid
flowchart TD
    Start((Mulai)) --> A[Petugas Memilih Buku & Anggota]
    A --> B{Apakah Stok Buku > 0?}
    
    B -- Tidak --> C[Sistem Menampilkan Error 'Stok Kosong']
    C --> A
    
    B -- Ya --> D[Input Tanggal Pinjam & Batas Kembali]
    D --> E[Simpan Data Peminjaman ke DB]
    E --> F[Kurangi Stok Buku (-1)]
    F --> G[Peminjaman Berhasil]
    
    G --> H[Anggota Mengembalikan Buku]
    H --> I[Petugas Klik Proses Kembali]
    I --> J{Apakah Tgl. Sekarang > Batas Kembali?}
    
    J -- Ya (Terlambat) --> K[Hitung Selisih Hari Keterlambatan]
    K --> L[Hitung Denda: Hari Terlambat x Rp 1.000]
    L --> M[Sistem Tampilkan Denda ke Petugas]
    
    J -- Tidak (Tepat Waktu) --> N[Denda = Rp 0]
    N --> M
    
    M --> O[Petugas Menerima Buku & Denda jika ada]
    O --> P[Sistem Mengubah Status Peminjaman = 'Dikembalikan']
    P --> Q[Tambah Stok Buku kembali (+1)]
    Q --> End((Selesai))
```
