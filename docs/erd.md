# Entity Relationship Diagram (ERD) - Tugas 2

Berikut adalah skema Entity Relationship Diagram untuk database `db_perpustakaan` menggunakan representasi Mermaid.

*Tugas Mandiri: Silakan gunakan fitur "Designer" pada phpMyAdmin Anda, lalu ekspor (save as image) menjadi file `docs/erd.png`. Atau Anda juga dapat melakukan screenshot dari diagram di bawah ini (menggunakan ekstensi Markdown Preview).*

```mermaid
erDiagram
    users {
        INT id PK
        VARCHAR username UK
        VARCHAR password
        VARCHAR nama_lengkap
        ENUM role "admin/petugas"
        TIMESTAMP created_at
    }
    
    anggota {
        INT id PK
        VARCHAR kode_anggota UK
        VARCHAR nama
        ENUM jenis_kelamin
        TEXT alamat
        VARCHAR no_telp
        VARCHAR email
        TIMESTAMP created_at
    }
    
    buku {
        INT id PK
        VARCHAR kode_buku UK
        VARCHAR judul
        VARCHAR pengarang
        VARCHAR penerbit
        YEAR tahun_terbit
        VARCHAR kategori
        INT stok
        TIMESTAMP created_at
    }
    
    peminjaman {
        INT id PK
        VARCHAR kode_peminjaman UK
        INT anggota_id FK
        INT buku_id FK
        INT user_id FK
        DATE tanggal_pinjam
        DATE batas_kembali
        DATE tanggal_dikembalikan
        ENUM status "Dipinjam/Dikembalikan/Terlambat"
        INT denda
        TIMESTAMP created_at
    }
    
    %% Relasi antar tabel
    users ||--o{ peminjaman : "Mencatat"
    anggota ||--o{ peminjaman : "Melakukan"
    buku ||--o{ peminjaman : "Dipinjam dalam"
```
