<?php
require_once __DIR__ . '/../config/koneksi.php';

/**
 * Fungsi pengurutan (sorting) daftar peminjaman berdasarkan batas_kembali (ascending).
 * 
 * Alasan Pemilihan Struktur Data: Array of Objects digunakan karena lebih representatif 
 * untuk data hasil query database. Objek memudahkan akses nilai seperti $item->batas_kembali.
 * 
 * Kompleksitas Algoritma (Time Complexity): O(N log N)
 * Fungsi usort() pada PHP menggunakan algoritma pengurutan (seperti Quicksort/Mergesort)
 * yang memiliki kompleksitas waktu rata-rata O(N log N). Ini merupakan cara yang 
 * sangat efisien dan standar di industri untuk mengurutkan kumpulan data dalam memori.
 */
function urutkanPeminjaman($arrayPeminjaman) {
    usort($arrayPeminjaman, function($a, $b) {
        $waktuA = strtotime($a->batas_kembali);
        $waktuB = strtotime($b->batas_kembali);
        if ($waktuA == $waktuB) {
            return 0;
        }
        return ($waktuA < $waktuB) ? -1 : 1;
    });
    return $arrayPeminjaman;
}

function getAllPeminjaman() {
    global $conn;
    $query = "SELECT p.*, a.nama as nama_anggota, a.kode_anggota, b.judul as judul_buku, u.nama_lengkap as nama_petugas 
              FROM peminjaman p 
              JOIN anggota a ON p.anggota_id = a.id 
              JOIN buku b ON p.buku_id = b.id 
              JOIN users u ON p.user_id = u.id
              ORDER BY p.id DESC"; // Default order from DB
    $stmt = $conn->query($query);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function insertPeminjaman($data, $userId) {
    global $conn;
    
    // Check stock
    $cekStok = $conn->prepare("SELECT stok FROM buku WHERE id = :buku_id FOR UPDATE");
    $cekStok->execute(['buku_id' => $data['buku_id']]);
    $buku = $cekStok->fetch();

    if ($buku && $buku->stok > 0) {
        $stmt = $conn->prepare("INSERT INTO peminjaman (kode_peminjaman, anggota_id, buku_id, user_id, tanggal_pinjam, batas_kembali, status) VALUES (:kode, :anggota_id, :buku_id, :user_id, :tanggal_pinjam, :batas_kembali, 'Dipinjam')");
        $stmt->execute([
            'kode' => $data['kode_peminjaman'],
            'anggota_id' => $data['anggota_id'],
            'buku_id' => $data['buku_id'],
            'user_id' => $userId,
            'tanggal_pinjam' => $data['tanggal_pinjam'],
            'batas_kembali' => $data['batas_kembali']
        ]);

        $updateStok = $conn->prepare("UPDATE buku SET stok = stok - 1 WHERE id = :buku_id");
        $updateStok->execute(['buku_id' => $data['buku_id']]);
        return true;
    }
    return false; // Stock empty
}

function getPeminjamanById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT p.*, b.judul, b.id as buku_id, a.nama as nama_anggota FROM peminjaman p JOIN buku b ON p.buku_id = b.id JOIN anggota a ON p.anggota_id = a.id WHERE p.id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_OBJ);
}

function prosesKembali($id, $tanggalKembali, $denda, $bukuId) {
    global $conn;
    $updatePeminjaman = $conn->prepare("UPDATE peminjaman SET status = 'Dikembalikan', tanggal_dikembalikan = :tanggal_kembali, denda = :denda WHERE id = :id");
    $updatePeminjaman->execute([
        'tanggal_kembali' => $tanggalKembali,
        'denda' => $denda,
        'id' => $id
    ]);

    $updateStok = $conn->prepare("UPDATE buku SET stok = stok + 1 WHERE id = :buku_id");
    $updateStok->execute(['buku_id' => $bukuId]);
    return true;
}
?>
