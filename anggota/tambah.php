<?php
require_once '../includes/header.php';

$error = "";
$success = "";

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
            $stmt = $conn->prepare("INSERT INTO anggota (nomor_anggota, nama, jenis_kelamin, no_telp, alamat) VALUES (:nomor_anggota, :nama, :jenis_kelamin, :no_telp, :alamat)");
            $stmt->execute([
                'nomor_anggota' => $nomor_anggota,
                'nama' => $nama,
                'jenis_kelamin' => $jenis_kelamin,
                'no_telp' => $no_telp,
                'alamat' => $alamat
            ]);
            $success = "Anggota berhasil ditambahkan!";
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Nomor Anggota sudah terdaftar!";
            } else {
                $error = "Gagal menambahkan anggota: " . $e->getMessage();
            }
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Tambah Data Anggota</h4>
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
                    <input type="text" class="form-control" id="nomor_anggota" name="nomor_anggota" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">Pilih...</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="no_telp" class="form-label">Nomor Telepon</label>
                    <input type="text" class="form-control" id="no_telp" name="no_telp" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Anggota</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
