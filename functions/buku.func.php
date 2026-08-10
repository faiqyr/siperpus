<?php
require_once __DIR__ . '/../config/koneksi.php';

/**
 * Fungsi untuk mencari buku di memori (sisi aplikasi).
 * Struktur Data: Array of Objects.
 * Alasan: Penggunaan array of objects memudahkan akses properti data layaknya 
 *         berinteraksi dengan entitas sesungguhnya (contoh: $buku->judul),
 *         yang mana lebih rapi secara konsep OOP dibandingkan array numerik.
 * 
 * Kompleksitas Waktu (Time Complexity): O(N * M)
 * Dimana N = jumlah total buku dalam array, dan M = panjang rata-rata string judul/pengarang.
 * Karena kita melakukan iterasi (O(N)) dan di dalamnya kita menggunakan strpos() yang 
 * berjalan pada waktu rata-rata O(M), maka kompleksitas akhirnya O(N * M). Ini sangat 
 * efisien untuk data dalam jumlah ratusan/ribuan di memori.
 */
function cariBuku($array_buku, $keyword) {
    $hasil = [];
    $keyword = strtolower(trim($keyword));
    
    foreach ($array_buku as $buku) {
        $judul = strtolower($buku->judul);
        $pengarang = strtolower($buku->pengarang);
        
        if (strpos($judul, $keyword) !== false || strpos($pengarang, $keyword) !== false) {
            $hasil[] = $buku;
        }
    }
    return $hasil;
}

function getAllBuku() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM buku ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getBukuById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM buku WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insertBuku($data) {
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

function updateBuku($data) {
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

function deleteBuku($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM buku WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}
?>
