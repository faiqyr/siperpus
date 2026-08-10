<?php
require_once '../includes/header.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'semua_belum_kembali';

$query = "SELECT p.*, a.nama as nama_anggota, a.nomor_anggota, b.judul as judul_buku, a.no_telp 
          FROM peminjaman p 
          JOIN anggota a ON p.anggota_id = a.id 
          JOIN buku b ON p.buku_id = b.id 
          WHERE p.status = 'Dipinjam'";

if ($filter == 'terlambat') {
    $query .= " AND p.batas_kembali < CURDATE()";
}

$query .= " ORDER BY p.batas_kembali ASC";

$stmt = $conn->query($query);
$laporan_list = $stmt->fetchAll();

$tanggal_sekarang = date('Y-m-d');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Laporan Peminjaman</h4>
    <button onclick="window.print()" class="btn btn-secondary d-print-none"><i class="bi bi-printer me-1"></i> Cetak Laporan</button>
</div>

<div class="card d-print-none mb-4">
    <div class="card-body">
        <form method="GET" class="d-flex gap-2 align-items-end">
            <div>
                <label for="filter" class="form-label">Filter Laporan</label>
                <select name="filter" id="filter" class="form-select">
                    <option value="semua_belum_kembali" <?= $filter == 'semua_belum_kembali' ? 'selected' : '' ?>>Semua Belum Kembali</option>
                    <option value="terlambat" <?= $filter == 'terlambat' ? 'selected' : '' ?>>Hanya yang Terlambat</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Terapkan</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="text-center mb-4">
            <?= $filter == 'terlambat' ? 'Laporan Keterlambatan Pengembalian Buku' : 'Laporan Buku yang Sedang Dipinjam' ?>
            <br>
            <small class="text-muted">Per Tanggal: <?= date('d-m-Y') ?></small>
        </h5>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nomor Anggota</th>
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
                    <?php if (count($laporan_list) > 0): ?>
                        <?php foreach($laporan_list as $index => $row): ?>
                        <?php 
                            $hari_terlambat = 0;
                            $estimasi_denda = 0;
                            if (strtotime($tanggal_sekarang) > strtotime($row->batas_kembali)) {
                                $diff = strtotime($tanggal_sekarang) - strtotime($row->batas_kembali);
                                $hari_terlambat = floor($diff / (60 * 60 * 24));
                                $estimasi_denda = $hari_terlambat * 1000;
                            }
                        ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($row->nomor_anggota) ?></td>
                            <td><?= htmlspecialchars($row->nama_anggota) ?></td>
                            <td><?= htmlspecialchars($row->no_telp) ?></td>
                            <td><?= htmlspecialchars($row->judul_buku) ?></td>
                            <td><?= htmlspecialchars($row->tanggal_pinjam) ?></td>
                            <td><?= htmlspecialchars($row->batas_kembali) ?></td>
                            <td>
                                <?php if($hari_terlambat > 0): ?>
                                    <span class="text-danger fw-bold">Terlambat <?= $hari_terlambat ?> Hari</span>
                                <?php else: ?>
                                    <span class="text-warning">Belum Kembali</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($estimasi_denda > 0): ?>
                                    Rp <?= number_format($estimasi_denda, 0, ',', '.') ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data untuk laporan ini.</td>
                        </tr>
                    <?php endif; ?>
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
        .table-dark th { background-color: #f8f9fa !important; color: #000 !important; border-color: #dee2e6 !important; }
    }
</style>

<?php require_once '../includes/footer.php'; ?>
