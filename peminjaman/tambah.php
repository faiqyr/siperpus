<?php
require_once '../includes/header.php';

$error = "";
$success = "";

// Ambil data anggota dan buku untuk pilihan
$anggota_stmt = $conn->query("SELECT id, nomor_anggota, nama FROM anggota ORDER BY nama ASC");
$anggota_list = $anggota_stmt->fetchAll();

$buku_stmt = $conn->query("SELECT id, judul, stok FROM buku WHERE stok > 0 ORDER BY judul ASC");
$buku_list = $buku_stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $anggota_id = $_POST['anggota_id'];
    $buku_id = $_POST['buku_id'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $batas_kembali = $_POST['batas_kembali'];
    $user_id = $_SESSION['user_id'];

    if (empty($anggota_id) || empty($buku_id) || empty($tanggal_pinjam) || empty($batas_kembali)) {
        $error = "Semua field harus diisi!";
    } elseif (strtotime($batas_kembali) < strtotime($tanggal_pinjam)) {
        $error = "Batas kembali tidak boleh kurang dari tanggal pinjam!";
    } else {
        try {
            $conn->beginTransaction();

            // Cek stok buku lagi untuk keamanan
            $cek_stok = $conn->prepare("SELECT stok FROM buku WHERE id = :buku_id FOR UPDATE");
            $cek_stok->execute(['buku_id' => $buku_id]);
            $buku = $cek_stok->fetch();

            if ($buku->stok > 0) {
                // Insert transaksi
                $stmt = $conn->prepare("INSERT INTO peminjaman (anggota_id, buku_id, user_id, tanggal_pinjam, batas_kembali, status) VALUES (:anggota_id, :buku_id, :user_id, :tanggal_pinjam, :batas_kembali, 'Dipinjam')");
                $stmt->execute([
                    'anggota_id' => $anggota_id,
                    'buku_id' => $buku_id,
                    'user_id' => $user_id,
                    'tanggal_pinjam' => $tanggal_pinjam,
                    'batas_kembali' => $batas_kembali
                ]);

                // Kurangi stok buku
                $update_stok = $conn->prepare("UPDATE buku SET stok = stok - 1 WHERE id = :buku_id");
                $update_stok->execute(['buku_id' => $buku_id]);

                $conn->commit();
                $success = "Transaksi peminjaman berhasil disimpan! Stok buku telah dikurangi.";
                
                // Refresh list buku karena stok berkurang
                $buku_stmt = $conn->query("SELECT id, judul, stok FROM buku WHERE stok > 0 ORDER BY judul ASC");
                $buku_list = $buku_stmt->fetchAll();
            } else {
                $conn->rollBack();
                $error = "Stok buku sedang kosong!";
            }
        } catch(PDOException $e) {
            $conn->rollBack();
            $error = "Gagal memproses transaksi: " . $e->getMessage();
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Tambah Transaksi Peminjaman</h4>
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
            <div class="mb-3">
                <label for="anggota_id" class="form-label">Pilih Anggota</label>
                <select class="form-select" id="anggota_id" name="anggota_id" required>
                    <option value="">-- Pilih Anggota --</option>
                    <?php foreach($anggota_list as $anggota): ?>
                        <option value="<?= $anggota->id ?>"><?= htmlspecialchars($anggota->nomor_anggota . ' - ' . $anggota->nama) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="buku_id" class="form-label">Pilih Buku</label>
                <select class="form-select" id="buku_id" name="buku_id" required>
                    <option value="">-- Pilih Buku (Stok Tersedia) --</option>
                    <?php foreach($buku_list as $buku): ?>
                        <option value="<?= $buku->id ?>"><?= htmlspecialchars($buku->judul . ' (Stok: ' . $buku->stok . ')') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                    <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="batas_kembali" class="form-label">Batas Kembali</label>
                    <input type="date" class="form-control" id="batas_kembali" name="batas_kembali" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Proses Peminjaman</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
