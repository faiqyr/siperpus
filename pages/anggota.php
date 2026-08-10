<?php
require_once '../includes/header.php';
require_once '../functions/anggota.func.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

if ($action == 'add') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            insertAnggota($_POST);
            setFlash('success', 'Anggota berhasil ditambahkan!');
            header("Location: anggota.php");
            exit();
        } catch (PDOException $e) {
            setFlash('error', 'Gagal menambahkan anggota. Kode/Email mungkin sudah terdaftar.');
        }
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Tambah Data Anggota</h4>
        <a href="anggota.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="kode_anggota" class="form-label">Kode Anggota</label>
                        <input type="text" class="form-control" id="kode_anggota" name="kode_anggota" placeholder="Contoh: AG001" required>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih...</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="no_telp" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="Contoh: 0812..." required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="email@contoh.com" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat Lengkap</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Anggota</button>
            </form>
        </div>
    </div>
<?php
} elseif ($action == 'edit') {
    $id = $_GET['id'];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $_POST['id'] = $id;
            updateAnggota($_POST);
            setFlash('success', 'Data anggota berhasil diperbarui!');
            header("Location: anggota.php");
            exit();
        } catch (PDOException $e) {
            setFlash('error', 'Gagal memperbarui anggota.');
        }
    }
    $anggota = getAnggotaById($id);
    if (!$anggota) {
        setFlash('error', 'Mohon maaf, data anggota yang Anda cari tidak ditemukan atau sudah dihapus.');
        header("Location: anggota.php");
        exit();
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Edit Data Anggota</h4>
        <a href="anggota.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="kode_anggota" class="form-label">Kode Anggota</label>
                        <input type="text" class="form-control" id="kode_anggota" name="kode_anggota" value="<?= htmlspecialchars($anggota['kode_anggota']) ?>" required>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($anggota['nama']) ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L" <?= $anggota['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= $anggota['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="no_telp" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?= htmlspecialchars($anggota['no_telp']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($anggota['email']) ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat Lengkap</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($anggota['alamat']) ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Anggota</button>
            </form>
        </div>
    </div>
<?php
} elseif ($action == 'delete') {
    $id = $_GET['id'];
    try {
        deleteAnggota($id);
        setFlash('success', 'Anggota berhasil dihapus!');
    } catch (PDOException $e) {
        setFlash('error', 'Gagal menghapus anggota. Pastikan anggota ini tidak memiliki riwayat peminjaman.');
    }
    header("Location: anggota.php");
    exit();
} else {
    // List view
    $anggotaList = getAllAnggota();
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Kelola Data Anggota</h4>
        <a href="?action=add" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i> Tambah Anggota</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped datatable">
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
                        <?php foreach($anggotaList as $index => $anggota): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($anggota->kode_anggota) ?></td>
                            <td><?= htmlspecialchars($anggota->nama) ?></td>
                            <td><?= $anggota->jenis_kelamin == 'L' ? 'L' : 'P' ?></td>
                            <td><?= htmlspecialchars($anggota->no_telp) ?></td>
                            <td><?= htmlspecialchars($anggota->email) ?></td>
                            <td><?= htmlspecialchars($anggota->alamat) ?></td>
                            <td>
                                <a href="?action=edit&id=<?= $anggota->id ?>" class="btn btn-sm btn-warning mb-1"><i class="bi bi-pencil"></i> Edit</a>
                                <a href="?action=delete&id=<?= $anggota->id ?>" class="btn btn-sm btn-danger mb-1 btn-delete"><i class="bi bi-trash"></i> Hapus</a>
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
