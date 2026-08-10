<?php
require_once '../includes/header.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
    $stmt = $conn->prepare("SELECT * FROM buku WHERE judul LIKE :search OR pengarang LIKE :search OR isbn LIKE :search ORDER BY id DESC");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $conn->query("SELECT * FROM buku ORDER BY id DESC");
}
$buku_list = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Kelola Data Buku</h4>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Tambah Buku</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Judul, Pengarang, atau ISBN..." value="<?= htmlspecialchars($search) ?>">
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
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>ISBN</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($buku_list) > 0): ?>
                        <?php foreach($buku_list as $index => $buku): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($buku->judul) ?></td>
                            <td><?= htmlspecialchars($buku->pengarang) ?></td>
                            <td><?= htmlspecialchars($buku->penerbit) ?></td>
                            <td><?= htmlspecialchars($buku->tahun_terbit) ?></td>
                            <td><?= htmlspecialchars($buku->isbn) ?></td>
                            <td>
                                <?php if($buku->stok > 0): ?>
                                    <span class="badge bg-success"><?= $buku->stok ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?= $buku->id ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                                <a href="hapus.php?id=<?= $buku->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')"><i class="bi bi-trash"></i> Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Data buku tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
