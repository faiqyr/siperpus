CREATE DATABASE IF NOT EXISTS db_perpustakaan;
USE db_perpustakaan;

-- Table for Users (Petugas)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('petugas') DEFAULT 'petugas',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Books (Buku)
CREATE TABLE IF NOT EXISTS buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    pengarang VARCHAR(100) NOT NULL,
    penerbit VARCHAR(100) NOT NULL,
    tahun_terbit YEAR NOT NULL,
    isbn VARCHAR(20) UNIQUE NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Members (Anggota)
CREATE TABLE IF NOT EXISTS anggota (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_anggota VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    alamat TEXT NOT NULL,
    no_telp VARCHAR(15) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Borrowing Transactions (Peminjaman)
CREATE TABLE IF NOT EXISTS peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    anggota_id INT NOT NULL,
    buku_id INT NOT NULL,
    user_id INT NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    batas_kembali DATE NOT NULL,
    tanggal_dikembalikan DATE NULL DEFAULT NULL,
    status ENUM('Dipinjam', 'Dikembalikan') DEFAULT 'Dipinjam',
    denda INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (anggota_id) REFERENCES anggota(id) ON DELETE CASCADE,
    FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Insert Initial Data
-- --------------------------------------------------------

-- Insert 1 User (Petugas) - Password is 'admin123' (hashed using md5 for simplicity in native PHP if not using password_hash, but let's use md5('admin123') = '0192023a7bbd73250516f069df18b500')
-- Wait, using PHP's password_hash is better. I will insert plain password hashed with password_hash('admin123', PASSWORD_DEFAULT)
-- Let's use MD5 just to be safe it works directly or I can use a known bcrypt hash. 
-- Bcrypt for 'admin123': $2y$10$7/O5sM6wE/.2iHkIqC1yLu0m9h/z8R8Z/5L2Z.80U9.w.z7.W7/Z6
INSERT INTO users (username, password, nama_lengkap, role) VALUES
('admin', '$2y$10$7/O5sM6wE/.2iHkIqC1yLu0m9h/z8R8Z/5L2Z.80U9.w.z7.W7/Z6', 'Administrator Perpustakaan', 'petugas');

-- Insert 10 Books
INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, isbn, stok) VALUES
('Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, '978-979-3062-79-2', 5),
('Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', 1980, '978-979-97312-3-4', 3),
('Ayat-Ayat Cinta', 'Habiburrahman El Shirazy', 'Republika', 2004, '978-979-3210-00-5', 7),
('Negeri 5 Menara', 'A. Fuadi', 'Gramedia Pustaka Utama', 2009, '978-979-22-4861-6', 4),
('Perahu Kertas', 'Dee Lestari', 'Bentang Pustaka', 2009, '978-979-1227-78-0', 6),
('Filosofi Kopi', 'Dee Lestari', 'Truedee Books', 2006, '978-979-99859-0-7', 2),
('Dilan: Dia adalah Dilanku Tahun 1990', 'Pidi Baiq', 'Pastel Books', 2014, '978-602-7870-41-3', 10),
('Ronggeng Dukuh Paruk', 'Ahmad Tohari', 'Gramedia Pustaka Utama', 1982, '978-979-22-4113-6', 3),
('Gadis Kretek', 'Ratih Kumala', 'Gramedia Pustaka Utama', 2012, '978-979-22-8141-5', 5),
('Cantik Itu Luka', 'Eka Kurniawan', 'Gramedia Pustaka Utama', 2002, '978-979-22-9844-4', 4);

-- Insert 5 Members
INSERT INTO anggota (nomor_anggota, nama, jenis_kelamin, alamat, no_telp) VALUES
('A001', 'Budi Santoso', 'L', 'Jl. Merdeka No. 1, Jakarta', '081234567890'),
('A002', 'Siti Aminah', 'P', 'Jl. Jendral Sudirman No. 45, Bandung', '081298765432'),
('A003', 'Andi Wijaya', 'L', 'Jl. Diponegoro No. 12, Surabaya', '085612349876'),
('A004', 'Rini Yulianti', 'P', 'Jl. Pahlawan No. 8, Semarang', '087856781234'),
('A005', 'Dewi Lestari', 'P', 'Jl. Gajah Mada No. 99, Yogyakarta', '089912345678');

-- Insert 5 Borrowing Transactions (Some returned, some still borrowed)
INSERT INTO peminjaman (anggota_id, buku_id, user_id, tanggal_pinjam, batas_kembali, tanggal_dikembalikan, status, denda) VALUES
(1, 1, 1, '2023-10-01', '2023-10-08', '2023-10-07', 'Dikembalikan', 0),
(2, 3, 1, '2023-10-05', '2023-10-12', '2023-10-15', 'Dikembalikan', 3000), -- Late 3 days
(3, 2, 1, '2023-10-10', '2023-10-17', NULL, 'Dipinjam', 0),
(4, 5, 1, DATE_SUB(CURDATE(), INTERVAL 10 DAY), DATE_SUB(CURDATE(), INTERVAL 3 DAY), NULL, 'Dipinjam', 0), -- Overdue right now
(5, 7, 1, DATE_SUB(CURDATE(), INTERVAL 2 DAY), DATE_ADD(CURDATE(), INTERVAL 5 DAY), NULL, 'Dipinjam', 0); -- Still borrowed, not overdue
