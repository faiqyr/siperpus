CREATE DATABASE IF NOT EXISTS db_perpustakaan;
USE db_perpustakaan;

-- Table for Users (Petugas)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'petugas') DEFAULT 'petugas',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Books (Buku)
CREATE TABLE IF NOT EXISTS buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_buku VARCHAR(20) UNIQUE NOT NULL,
    judul VARCHAR(255) NOT NULL,
    pengarang VARCHAR(100) NOT NULL,
    penerbit VARCHAR(100) NOT NULL,
    tahun_terbit YEAR NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Members (Anggota)
CREATE TABLE IF NOT EXISTS anggota (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_anggota VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    alamat TEXT NOT NULL,
    no_telp VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Borrowing Transactions (Peminjaman)
CREATE TABLE IF NOT EXISTS peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_peminjaman VARCHAR(20) NOT NULL UNIQUE,
    anggota_id INT NOT NULL,
    buku_id INT NOT NULL,
    user_id INT NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    batas_kembali DATE NOT NULL,
    tanggal_dikembalikan DATE NULL DEFAULT NULL,
    status ENUM('Dipinjam', 'Dikembalikan', 'Terlambat') DEFAULT 'Dipinjam',
    denda INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (anggota_id) REFERENCES anggota(id) ON DELETE CASCADE,
    FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Insert Initial Data
-- --------------------------------------------------------

-- Insert Users
-- Password 'admin123' hashed using password_hash()
INSERT INTO users (username, password, nama_lengkap, role) VALUES
('admin', '$2y$10$7/O5sM6wE/.2iHkIqC1yLu0m9h/z8R8Z/5L2Z.80U9.w.z7.W7/Z6', 'Petugas Perpustakaan', 'admin');

-- Insert Books
INSERT INTO buku (kode_buku, judul, pengarang, penerbit, tahun_terbit, kategori, stok) VALUES
('BK001', 'Algoritma dan Pemrograman', 'Rinaldi Munir', 'Informatika', 2016, 'Komputer', 5),
('BK002', 'Basis Data Relasional', 'Fathansyah', 'Informatika', 2018, 'Komputer', 3),
('BK003', 'Pemrograman Web Dasar', 'Abdul Kadir', 'Andi', 2019, 'Komputer', 4),
('BK004', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang', 2005, 'Novel', 6),
('BK005', 'Bumi Manusia', 'Pramoedya A. T.', 'Hasta Mitra', 1980, 'Novel', 2),
('BK006', 'Fisika Dasar Jilid 1', 'Halliday & Resnick', 'Erlangga', 2010, 'Sains', 3),
('BK007', 'Sejarah Indonesia Modern', 'M. C. Ricklefs', 'Serambi', 2008, 'Sejarah', 2),
('BK008', 'Matematika Diskrit', 'Rinaldi Munir', 'Informatika', 2012, 'Komputer', 4),
('BK009', 'Filosofi Teras', 'Henry Manampiring', 'Kompas', 2018, 'Pengembangan Diri', 5),
('BK010', 'Atomic Habits', 'James Clear', 'Gramedia', 2019, 'Pengembangan Diri', 3);

-- Insert Members
INSERT INTO anggota (kode_anggota, nama, jenis_kelamin, alamat, no_telp, email) VALUES
('AG001', 'Ahmad Fauzi', 'L', 'Jl. Merdeka No. 12', '081234567801', 'ahmad@mail.com'),
('AG002', 'Siti Nurhaliza', 'P', 'Jl. Sudirman No. 5', '081234567802', 'siti@mail.com'),
('AG003', 'Budi Santoso', 'L', 'Jl. Diponegoro No. 8', '081234567803', 'budi@mail.com'),
('AG004', 'Dewi Lestari', 'P', 'Jl. Ahmad Yani No. 21', '081234567804', 'dewi@mail.com'),
('AG005', 'Rizky Pratama', 'L', 'Jl. Gatot Subroto No. 3', '081234567805', 'rizky@mail.com');

-- Insert Borrowing Transactions
-- Note: Assuming user_id = 1 for the admin, and using internal IDs 1-5 for anggota_id and buku_id based on insertion order
-- AG001 (id 1) borrows BK001 (id 1)
-- AG002 (id 2) borrows BK004 (id 4)
-- AG003 (id 3) borrows BK002 (id 2)
-- AG004 (id 4) borrows BK009 (id 9)
-- AG005 (id 5) borrows BK010 (id 10)

INSERT INTO peminjaman (kode_peminjaman, anggota_id, buku_id, user_id, tanggal_pinjam, batas_kembali, tanggal_dikembalikan, denda, status) VALUES
('PJ001', 1, 1, 1, '2026-07-20', '2026-07-27', '2026-08-01', 5000, 'Dikembalikan'),
('PJ002', 2, 4, 1, '2026-07-25', '2026-08-01', '2026-08-01', 0, 'Dikembalikan'),
('PJ003', 3, 2, 1, '2026-08-01', '2026-08-08', NULL, 0, 'Dipinjam'),
('PJ004', 4, 9, 1, '2026-07-28', '2026-08-04', NULL, 0, 'Terlambat'),
('PJ005', 5, 10, 1, '2026-08-08', '2026-08-15', NULL, 0, 'Dipinjam');
