<?php
require_once '../includes/header.php';
require_once '../functions/buku.func.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

if ($action == 'add') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            insertBuku($_POST);
            setFlash('success', 'Buku berhasil ditambahkan!');
            header("Location: buku.php");
            exit();
        } catch (PDOException $e) {
            setFlash('error', 'Gagal menambahkan buku. Kode Buku mungkin sudah ada.');
        }
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Tambah Data Buku</h4>
        <a href="buku.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="kode_buku" class="form-label">Kode Buku</label>
                        <input type="text" class="form-control" id="kode_buku" name="kode_buku" placeholder="Contoh: BK001" required>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="judul" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan judul buku" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="pengarang" class="form-label">Pengarang</label>
                        <input type="text" class="form-control" id="pengarang" name="pengarang" placeholder="Nama pengarang" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="penerbit" class="form-label">Penerbit</label>
                        <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="Nama penerbit" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" placeholder="Contoh: Komputer, Novel" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" min="1900" max="2099" placeholder="YYYY" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stok" class="form-label">Stok Buku</label>
                        <input type="number" class="form-control" id="stok" name="stok" min="0" placeholder="Jumlah stok" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Buku</button>
            </form>
        </div>
    </div>
<?php
} elseif ($action == 'edit') {
    $id = $_GET['id'];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $_POST['id'] = $id;
            updateBuku($_POST);
            setFlash('success', 'Data buku berhasil diperbarui!');
            header("Location: buku.php");
            exit();
        } catch (PDOException $e) {
            setFlash('error', 'Gagal memperbarui buku.');
        }
    }
    $buku = getBukuById($id);
    if (!$buku) {
        setFlash('error', 'Mohon maaf, data buku yang Anda cari tidak ditemukan atau sudah dihapus.');
        header("Location: buku.php");
        exit();
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Edit Data Buku</h4>
        <a href="buku.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="kode_buku" class="form-label">Kode Buku</label>
                        <input type="text" class="form-control" id="kode_buku" name="kode_buku" value="<?= htmlspecialchars($buku['kode_buku']) ?>" required>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="judul" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control" id="judul" name="judul" value="<?= htmlspecialchars($buku['judul']) ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="pengarang" class="form-label">Pengarang</label>
                        <input type="text" class="form-control" id="pengarang" name="pengarang" value="<?= htmlspecialchars($buku['pengarang']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="penerbit" class="form-label">Penerbit</label>
                        <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?= htmlspecialchars($buku['penerbit']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" value="<?= htmlspecialchars($buku['kategori']) ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" min="1900" max="2099" value="<?= htmlspecialchars($buku['tahun_terbit']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stok" class="form-label">Stok Buku</label>
                        <input type="number" class="form-control" id="stok" name="stok" min="0" value="<?= htmlspecialchars($buku['stok']) ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Buku</button>
            </form>
        </div>
    </div>
<?php
} elseif ($action == 'delete') {
    $id = $_GET['id'];
    try {
        deleteBuku($id);
        setFlash('success', 'Buku berhasil dihapus!');
    } catch (PDOException $e) {
        setFlash('error', 'Gagal menghapus buku. Pastikan buku tidak sedang dipinjam.');
    }
    header("Location: buku.php");
    exit();
} else {
    // List view
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $bukuList = getAllBuku();
    if ($search) {
        $bukuList = cariBuku($bukuList, $search);
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Kelola Data Buku</h4>
        <a href="?action=add" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Tambah Buku</a>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Custom Search (Non-DataTable search simulation as requested) -->
            <form method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Judul atau Pengarang..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                    <?php if($search): ?>
                        <a href="buku.php" class="btn btn-outline-danger">Reset</a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped datatable">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode Buku</th>
                            <th>Judul Buku</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($bukuList as $index => $buku): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($buku->kode_buku) ?></td>
                            <td><?= htmlspecialchars($buku->judul) ?></td>
                            <td><?= htmlspecialchars($buku->pengarang) ?></td>
                            <td><?= htmlspecialchars($buku->penerbit) ?></td>
                            <td><?= htmlspecialchars($buku->tahun_terbit) ?></td>
                            <td><?= htmlspecialchars($buku->kategori) ?></td>
                            <td>
                                <?php if($buku->stok > 0): ?>
                                    <span class="badge bg-success"><?= $buku->stok ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?action=edit&id=<?= $buku->id ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                                <a href="?action=delete&id=<?= $buku->id ?>" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i> Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php require_once '../includes/footer.php'; ?>
