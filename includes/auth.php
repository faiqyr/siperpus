<?php
/**
 * File: auth.php
 * Deskripsi: Middleware untuk memastikan pengguna sudah login sebelum mengakses halaman.
 * Author: @faiqyr
 * Tanggal Dibuat: 2026-08-10
 * Versi: 1.0.0
 */
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /sertifikasi/login.php");
    exit();
}
?>
