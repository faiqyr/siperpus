<?php
/**
 * File: header.php
 * Deskripsi: Template bagian atas (head, sidebar, dan navbar) yang digunakan di seluruh halaman.
 * Author: @faiqyr
 * Tanggal Dibuat: 2026-08-10
 * Versi: 1.0.0
 */
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../functions/helper.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Perpustakaan</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; padding-top: 20px; transition: all 0.3s; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 12px 20px; font-weight: 500; transition: 0.2s; }
        .sidebar a:hover, .sidebar a.active { background-color: #343a40; color: #fff; border-left: 4px solid #0d6efd; }
        .content { padding: 30px; transition: all 0.3s; }
        .card { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); border: none; border-radius: 8px; }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar { min-height: auto; padding-bottom: 20px; }
            .content { padding: 15px; }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row flex-nowrap flex-column flex-md-row">
        <!-- Sidebar -->
        <div class="col-12 col-md-3 col-lg-2 px-0 sidebar text-white">
            <h5 class="text-center mb-4 mt-2 fw-bold text-white"><i class="bi bi-book-half text-primary"></i> SIPERpus</h5>
            <?php
            // Tentukan root path relatif berdasarkan lokasi file yang sedang diakses.
            // Jika file berada di subfolder /pages/, root adalah '../'
            // Jika file berada di root, root adalah './'
            $isInPages = strpos($_SERVER['SCRIPT_FILENAME'], DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR) !== false;
            $root = $isInPages ? '../' : './';
            ?>
            <div class="d-flex flex-row flex-md-column overflow-auto">
                <a href="<?= $root ?>index.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                <a href="<?= $root ?>pages/buku.php"><i class="bi bi-book me-2"></i> Data Buku</a>
                <a href="<?= $root ?>pages/anggota.php"><i class="bi bi-people me-2"></i> Data Anggota</a>
                <a href="<?= $root ?>pages/peminjaman.php"><i class="bi bi-arrow-left-right me-2"></i> Transaksi</a>
                <a href="<?= $root ?>pages/laporan.php"><i class="bi bi-file-earmark-text me-2"></i> Laporan</a>
                <a href="<?= $root ?>logout.php" class="text-danger mt-md-5"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-12 col-md-9 col-lg-10 content">
            <!-- Header for user -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h4 class="mb-0 text-dark fw-bold">Selamat Datang, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?></h4>
                <span class="badge bg-primary px-3 py-2 rounded-pill"><i class="bi bi-person-circle me-1"></i> <?= ucfirst($_SESSION['role']) ?></span>
            </div>
            
            <?php showFlash(); ?>
