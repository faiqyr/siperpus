<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #343a40; padding-top: 20px; }
        .sidebar a { color: #d1d5db; text-decoration: none; display: block; padding: 10px 20px; font-weight: 500; }
        .sidebar a:hover, .sidebar a.active { background-color: #495057; color: #fff; }
        .content { padding: 30px; }
        .card { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); border: none; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar text-white">
            <h5 class="text-center mb-4">SIP Perpustakaan</h5>
            <a href="<?= $base_url ?>/index.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
            <a href="<?= $base_url ?>/buku/index.php"><i class="bi bi-book me-2"></i> Kelola Buku</a>
            <a href="<?= $base_url ?>/anggota/index.php"><i class="bi bi-people me-2"></i> Kelola Anggota</a>
            <a href="<?= $base_url ?>/peminjaman/index.php"><i class="bi bi-arrow-left-right me-2"></i> Transaksi</a>
            <a href="<?= $base_url ?>/laporan/index.php"><i class="bi bi-file-earmark-text me-2"></i> Laporan</a>
            <hr>
            <a href="<?= $base_url ?>/logout.php" class="text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-10 content">
            <!-- Header for user -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h4 class="mb-0">Selamat Datang, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?></h4>
                <span class="badge bg-primary px-3 py-2"><?= ucfirst($_SESSION['role']) ?></span>
            </div>
