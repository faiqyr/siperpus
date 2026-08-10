<?php
require_once '../includes/header.php';

$error = "";
$success = "";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomor_anggota = trim($_POST['nomor_anggota']);
    $nama = trim($_POST['nama']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_telp = trim($_POST['no_telp']);
    $alamat = trim($_POST['alamat']);

    if (empty($nomor_anggota) || empty($nama) || empty($jenis_kelamin) || empty($no_telp) || empty($alamat)) {
        $error = "Semua field harus diisi dengan benar!";
    } else {
        try {
            $stmt = $conn->prepare("UPDATE anggota SET nomor_anggota = :nomor_anggota, nama = :nama, jenis_kelamin = :jenis_kelamin, no_telp = :no_telp, alamat = :alamat WHERE id = :id");
            $stmt->execute([
                'nomor_anggota' => $nomor_anggota,
                'nama' => $nama,
                'jenis_kelamin' => $jenis_kelamin,
                'no_telp' => $no_telp,
                'alamat' => $alamat,
                'id' => $id
            ]);
            $success = "Data anggota berhasil diperbarui!";
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Nomor Anggota sudah terdaftar pada anggota lain!";
            } else {
                $error = "Gagal memperbarui anggota: " . $e->getMessage();
            }
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM anggota WHERE id = :id");
$stmt->execute(['id' => $id]);
$anggota = $stmt->fetch();

if (!$anggota) {
    header("Location: index.php");
    exit();
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Edit Data Anggota</h4>
    <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <?php if($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="nomor_anggota" class="form-label">Nomor Anggota</label>
                    <input type="text" class="form-control" id="nomor_anggota" name="nomor_anggota" value="<?= htmlspecialchars($anggota->nomor_anggota) ?>" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($anggota->nama) ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="L" <?= $anggota->jenis_kelamin == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= $anggota->jenis_kelamin == 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="no_telp" class="form-label">Nomor Telepon</label>
                    <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?= htmlspecialchars($anggota->no_telp) ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($anggota->alamat) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Anggota</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
