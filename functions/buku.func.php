<?php
/**
 * File: buku.func.php
 * Deskripsi: Kumpulan fungsi khusus untuk menangani operasi CRUD dan algoritma pencarian pada entitas Buku.
 * Author: @faiqyr
 * Tanggal Dibuat: 2026-08-10
 * Versi: 1.0.0
 */
require_once __DIR__ . '/../config/koneksi.php';

/**
 * Fungsi untuk mencari buku di memori (sisi aplikasi).
 * Mencari data buku berdasarkan judul atau pengarang (Sisi Aplikasi).
 * 
 * Implementasi algoritma pencarian substring secara serial. Pada worst-case scenario, fungsi ini
 * mengiterasi seluruh elemen array (O(N)), dan fungsi strpos() untuk setiap atribut (judul & pengarang)
 * berjalan pada waktu rata-rata O(M), maka kompleksitas akhirnya O(N * M). Ini sangat 
 * efisien untuk data dalam jumlah ratusan/ribuan di memori.
 *
 * @param array $arrayBuku Kumpulan objek buku yang akan dicari.
 * @param string $keyword Kata kunci pencarian (judul/pengarang).
 * @return array Array berisi objek buku yang cocok dengan kriteria pencarian.
 */
function cariBuku(array $arrayBuku, string $keyword): array {
    $hasil = [];
    // Standarisasi kata kunci pencarian ke huruf kecil untuk menghindari case-sensitivity
    $keyword = strtolower(trim($keyword));
    
    foreach ($arrayBuku as $buku) {
        $judul = strtolower($buku->judul);
        $pengarang = strtolower($buku->pengarang);
        
        // Memeriksa apakah substring keyword terdapat pada judul atau pengarang
        if (strpos($judul, $keyword) !== false || strpos($pengarang, $keyword) !== false) {
            $hasil[] = $buku;
        }
    }
    
    return $hasil;
}

/**
 * Mengambil seluruh data buku dari database yang tidak dihapus.
 * 
 * @return array Array of objects dari seluruh data buku.
 */
function getAllBuku(): array {
    global $conn;
    $stmt = $conn->query("SELECT * FROM buku ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

/**
 * Mengambil data satu buku spesifik berdasarkan ID.
 * 
 * @param int $id ID dari buku.
 * @return object|bool Objek data buku, atau false jika tidak ditemukan.
 */
function getBukuById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM buku WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Menyimpan data buku baru ke database.
 * 
 * @param array $data Data buku dari form POST ($_POST).
 * @return bool True jika berhasil, exception dilempar jika gagal.
 */
function insertBuku(array $data): bool {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO buku (kode_buku, judul, pengarang, penerbit, tahun_terbit, kategori, stok) VALUES (:kode_buku, :judul, :pengarang, :penerbit, :tahun_terbit, :kategori, :stok)");
    return $stmt->execute([
        'kode_buku' => $data['kode_buku'],
        'judul' => $data['judul'],
        'pengarang' => $data['pengarang'],
        'penerbit' => $data['penerbit'],
        'tahun_terbit' => $data['tahun_terbit'],
        'kategori' => $data['kategori'],
        'stok' => $data['stok']
    ]);
}

/**
 * Mengubah data buku yang sudah ada di database.
 * 
 * @param array $data Data perubahan buku dari form POST (harus menyertakan kunci 'id').
 * @return bool True jika berhasil.
 */
function updateBuku(array $data): bool {
    global $conn;
    $stmt = $conn->prepare("UPDATE buku SET kode_buku = :kode_buku, judul = :judul, pengarang = :pengarang, penerbit = :penerbit, tahun_terbit = :tahun_terbit, kategori = :kategori, stok = :stok WHERE id = :id");
    return $stmt->execute([
        'kode_buku' => $data['kode_buku'],
        'judul' => $data['judul'],
        'pengarang' => $data['pengarang'],
        'penerbit' => $data['penerbit'],
        'tahun_terbit' => $data['tahun_terbit'],
        'kategori' => $data['kategori'],
        'stok' => $data['stok'],
        'id' => $data['id']
    ]);
}

/**
 * Menghapus data buku (Hard Delete) dari database.
 * 
 * @param int $id ID buku yang akan dihapus.
 * @return bool True jika berhasil dihapus.
 */
function deleteBuku($id): bool {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM buku WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}
?>
