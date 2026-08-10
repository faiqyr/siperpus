<?php
/**
 * File: anggota.func.php
 * Deskripsi: Kumpulan fungsi khusus untuk menangani operasi CRUD pada entitas Anggota.
 * Author: @faiqyr
 * Tanggal Dibuat: 2026-08-10
 * Versi: 1.0.0
 */
require_once __DIR__ . '/../config/koneksi.php';

/**
 * Mengambil seluruh data anggota dari database.
 * 
 * @return array Array of objects dari seluruh data anggota.
 */
function getAllAnggota(): array {
    global $conn;
    $stmt = $conn->query("SELECT * FROM anggota ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

/**
 * Mengambil data satu anggota spesifik berdasarkan ID.
 * 
 * @param int $id ID dari anggota.
 * @return array|bool Array data anggota, atau false jika tidak ditemukan.
 */
function getAnggotaById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM anggota WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Menyimpan data anggota baru ke database.
 * 
 * @param array $data Data anggota dari form POST ($_POST).
 * @return bool True jika berhasil.
 */
function insertAnggota(array $data): bool {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO anggota (kode_anggota, nama, jenis_kelamin, no_telp, email, alamat) VALUES (:kode_anggota, :nama, :jenis_kelamin, :no_telp, :email, :alamat)");
    return $stmt->execute([
        'kode_anggota' => $data['kode_anggota'],
        'nama' => $data['nama'],
        'jenis_kelamin' => $data['jenis_kelamin'],
        'no_telp' => $data['no_telp'],
        'email' => $data['email'],
        'alamat' => $data['alamat']
    ]);
}

/**
 * Mengubah data anggota yang sudah ada di database.
 * 
 * @param array $data Data perubahan anggota dari form POST (harus menyertakan kunci 'id').
 * @return bool True jika berhasil.
 */
function updateAnggota(array $data): bool {
    global $conn;
    $stmt = $conn->prepare("UPDATE anggota SET kode_anggota = :kode_anggota, nama = :nama, jenis_kelamin = :jenis_kelamin, no_telp = :no_telp, email = :email, alamat = :alamat WHERE id = :id");
    return $stmt->execute([
        'kode_anggota' => $data['kode_anggota'],
        'nama' => $data['nama'],
        'jenis_kelamin' => $data['jenis_kelamin'],
        'no_telp' => $data['no_telp'],
        'email' => $data['email'],
        'alamat' => $data['alamat'],
        'id' => $data['id']
    ]);
}

/**
 * Menghapus data anggota dari database.
 * 
 * @param int $id ID anggota yang akan dihapus.
 * @return bool True jika berhasil dihapus.
 */
function deleteAnggota($id): bool {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM anggota WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}
?>
