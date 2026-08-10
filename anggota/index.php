<?php
require_once '../includes/header.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
    $stmt = $conn->prepare("SELECT * FROM anggota WHERE nama LIKE :search OR kode_anggota LIKE :search ORDER BY id DESC");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $conn->query("SELECT * FROM anggota ORDER BY id DESC");
}
$anggota_list = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Kelola Data Anggota</h4>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i> Tambah Anggota</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Nama atau Kode Anggota..." value="<?= htmlspecialchars($search) ?>">
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
                        <th>Kode Anggota</th>
                        <th>Nama</th>
                        <th>L/P</th>
                        <th>No Telp</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($anggota_list) > 0): ?>
                        <?php foreach($anggota_list as $index => $anggota): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($anggota->kode_anggota) ?></td>
                            <td><?= htmlspecialchars($anggota->nama) ?></td>
                            <td><?= $anggota->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                            <td><?= htmlspecialchars($anggota->no_telp) ?></td>
                            <td><?= htmlspecialchars($anggota->email) ?></td>
                            <td><?= htmlspecialchars($anggota->alamat) ?></td>
                            <td>
                                <a href="edit.php?id=<?= $anggota->id ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                                <a href="hapus.php?id=<?= $anggota->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')"><i class="bi bi-trash"></i> Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Data anggota tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
