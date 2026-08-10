<?php
require_once '../includes/header.php';

$error = "";
$success = "";

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
            $stmt = $conn->prepare("INSERT INTO buku (kode_buku, judul, pengarang, penerbit, tahun_terbit, kategori, stok) VALUES (:kode_buku, :judul, :pengarang, :penerbit, :tahun_terbit, :kategori, :stok)");
            $stmt->execute([
                'kode_buku' => $kode_buku,
                'judul' => $judul,
                'pengarang' => $pengarang,
                'penerbit' => $penerbit,
                'tahun_terbit' => $tahun_terbit,
                'kategori' => $kategori,
                'stok' => $stok
            ]);
            $success = "Buku berhasil ditambahkan!";
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) { // Integrity constraint violation
                $error = "Kode Buku sudah terdaftar!";
            } else {
                $error = "Gagal menambahkan buku: " . $e->getMessage();
            }
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Tambah Data Buku</h4>
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
                    <input type="text" class="form-control" id="kode_buku" name="kode_buku" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label for="judul" class="form-label">Judul Buku</label>
                    <input type="text" class="form-control" id="judul" name="judul" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="pengarang" class="form-label">Pengarang</label>
                    <input type="text" class="form-control" id="pengarang" name="pengarang" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                    <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" min="1900" max="2099" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="stok" class="form-label">Stok Buku</label>
                    <input type="number" class="form-control" id="stok" name="stok" min="0" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Buku</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
