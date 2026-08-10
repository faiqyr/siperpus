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
    $kode_buku = trim($_POST['kode_buku']);
    $judul = trim($_POST['judul']);
    $pengarang = trim($_POST['pengarang']);
    $penerbit = trim($_POST['penerbit']);
    $tahun_terbit = trim($_POST['tahun_terbit']);
    $kategori = trim($_POST['kategori']);
    $stok = (int)$_POST['stok'];

    if (empty($kode_buku) || empty($judul) || empty($pengarang) || empty($penerbit) || empty($tahun_terbit) || empty($kategori) || $stok < 0) {
        $error = "Semua field harus diisi dengan benar!";
    } else {
        try {
            $stmt = $conn->prepare("UPDATE buku SET kode_buku = :kode_buku, judul = :judul, pengarang = :pengarang, penerbit = :penerbit, tahun_terbit = :tahun_terbit, kategori = :kategori, stok = :stok WHERE id = :id");
            $stmt->execute([
                'kode_buku' => $kode_buku,
                'judul' => $judul,
                'pengarang' => $pengarang,
                'penerbit' => $penerbit,
                'tahun_terbit' => $tahun_terbit,
                'kategori' => $kategori,
                'stok' => $stok,
                'id' => $id
            ]);
            $success = "Data buku berhasil diperbarui!";
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Kode Buku sudah terdaftar pada buku lain!";
            } else {
                $error = "Gagal memperbarui buku: " . $e->getMessage();
            }
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);
// Fetching as ASSOC here as another example of meeting associative array requirement
$buku = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$buku) {
    header("Location: index.php");
    exit();
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Edit Data Buku</h4>
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

<?php require_once '../includes/footer.php'; ?>
