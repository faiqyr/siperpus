<?php
require_once '../includes/header.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT p.*, a.nama as nama_anggota, a.nomor_anggota, b.judul as judul_buku, u.nama_lengkap as nama_petugas 
          FROM peminjaman p 
          JOIN anggota a ON p.anggota_id = a.id 
          JOIN buku b ON p.buku_id = b.id 
          JOIN users u ON p.user_id = u.id";

if ($search) {
    $query .= " WHERE a.nama LIKE :search OR b.judul LIKE :search OR a.nomor_anggota LIKE :search";
    $query .= " ORDER BY p.id DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute(['search' => "%$search%"]);
} else {
    $query .= " ORDER BY p.id DESC";
    $stmt = $conn->query($query);
}

$peminjaman_list = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Data Transaksi Peminjaman</h4>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Transaksi Baru</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Nama Anggota atau Judul Buku..." value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                <?php if($search): ?>
                    <a href="index.php" class="btn btn-outline-danger">Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($peminjaman_list) > 0): ?>
                        <?php foreach($peminjaman_list as $index => $row): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($row->nama_anggota) ?> <br><small class="text-muted">(<?= htmlspecialchars($row->nomor_anggota) ?>)</small></td>
                            <td><?= htmlspecialchars($row->judul_buku) ?></td>
                            <td><?= htmlspecialchars($row->tanggal_pinjam) ?></td>
                            <td><?= htmlspecialchars($row->batas_kembali) ?></td>
                            <td>
                                <?php if($row->status == 'Dipinjam'): ?>
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Dikembalikan</span>
                                    <br><small><?= htmlspecialchars($row->tanggal_dikembalikan) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($row->status == 'Dikembalikan'): ?>
                                    Rp <?= number_format($row->denda, 0, ',', '.') ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row->nama_petugas) ?></td>
                            <td>
                                <?php if($row->status == 'Dipinjam'): ?>
                                    <a href="kembali.php?id=<?= $row->id ?>" class="btn btn-sm btn-success" onclick="return confirm('Proses pengembalian buku ini?')"><i class="bi bi-check2-square"></i> Kembalikan</a>
                                <?php else: ?>
                                    <span class="text-muted">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Data transaksi tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
