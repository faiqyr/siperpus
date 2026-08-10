<?php
/**
 * File: laporan.php
 * Deskripsi: Halaman antarmuka untuk menampilkan laporan keterlambatan dan buku yang belum dikembalikan.
 * Author: @faiqyr
 * Tanggal Dibuat: 2026-08-10
 * Versi: 1.0.0
 */
require_once '../includes/header.php';

// Filter is strictly handled inside DataTables or with simple PHP if we want.
// Since DataTables has search, we can just dump all active and late loans and let the user search/sort.
// But the spec says "Laporan peminjaman yang belum kembali dan yang terlambat".

$query = "SELECT p.*, a.nama as nama_anggota, a.kode_anggota, b.judul as judul_buku, a.no_telp 
          FROM peminjaman p 
          JOIN anggota a ON p.anggota_id = a.id 
          JOIN buku b ON p.buku_id = b.id 
          WHERE p.status != 'Dikembalikan'
          ORDER BY p.batas_kembali ASC";

$stmt = $conn->query($query);
$laporanList = $stmt->fetchAll(PDO::FETCH_OBJ);

$tanggalSekarang = date('Y-m-d');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Laporan Peminjaman</h4>
    <button onclick="window.print()" class="btn btn-secondary d-print-none"><i class="bi bi-printer me-1"></i> Cetak Laporan</button>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="text-center mb-4">
            Laporan Buku yang Sedang Dipinjam & Terlambat
            <br>
            <small class="text-muted">Per Tanggal: <?= date('d-m-Y') ?></small>
        </h5>

        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode Anggota</th>
                        <th>Nama Anggota</th>
                        <th>No Telp</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Status / Keterlambatan</th>
                        <th>Estimasi Denda</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($laporanList as $index => $row): ?>
                    <?php 
                        require_once '../functions/pinjam.func.php';
                        $estimasiDenda = hitungDenda($row->batas_kembali, $tanggalSekarang);
                        $hariTerlambat = $estimasiDenda > 0 ? ($estimasiDenda / 1000) : 0;
                    ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($row->kode_anggota) ?></td>
                        <td><?= htmlspecialchars($row->nama_anggota) ?></td>
                        <td><?= htmlspecialchars($row->no_telp) ?></td>
                        <td><?= htmlspecialchars($row->judul_buku) ?></td>
                        <td><?= htmlspecialchars($row->tanggal_pinjam) ?></td>
                        <td><?= htmlspecialchars($row->batas_kembali) ?></td>
                        <td>
                            <?php if($hariTerlambat > 0): ?>
                                <span class="badge bg-danger">Terlambat <?= $hariTerlambat ?> Hari</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Belum Kembali</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($estimasiDenda > 0): ?>
                                Rp <?= number_format($estimasiDenda, 0, ',', '.') ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        .sidebar { display: none !important; }
        .content { padding: 0 !important; width: 100% !important; margin: 0 !important; }
        .container-fluid { padding: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
        .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate { display: none !important; }
    }
</style>

<?php require_once '../includes/footer.php'; ?>
