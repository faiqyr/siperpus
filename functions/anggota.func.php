<?php
require_once __DIR__ . '/../config/koneksi.php';

function getAllAnggota() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM anggota ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getAnggotaById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM anggota WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insertAnggota($data) {
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

function updateAnggota($data) {
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

function deleteAnggota($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM anggota WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}
?>
