<?php
require_once '../includes/header.php';

$error = "";
$success = "";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Get data peminjaman
$stmt = $conn->prepare("SELECT p.*, b.judul, b.id as buku_id, a.nama as nama_anggota FROM peminjaman p 
                        JOIN buku b ON p.buku_id = b.id 
                        JOIN anggota a ON p.anggota_id = a.id 
                        WHERE p.id = :id");
$stmt->execute(['id' => $id]);
$transaksi = $stmt->fetch();

if (!$transaksi || $transaksi->status == 'Dikembalikan') {
    header("Location: index.php");
    exit();
}

// Calculate Denda (Rp 1000 per hari keterlambatan)
$tanggal_sekarang = date('Y-m-d');
$batas_kembali = $transaksi->batas_kembali;

$denda = 0;
$hari_terlambat = 0;

if (strtotime($tanggal_sekarang) > strtotime($batas_kembali)) {
    $diff = strtotime($tanggal_sekarang) - strtotime($batas_kembali);
    $hari_terlambat = floor($diff / (60 * 60 * 24));
    $denda = $hari_terlambat * 1000;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn->beginTransaction();

        // Update transaksi
        $update_peminjaman = $conn->prepare("UPDATE peminjaman SET status = 'Dikembalikan', tanggal_dikembalikan = :tanggal_kembali, denda = :denda WHERE id = :id");
        $update_peminjaman->execute([
            'tanggal_kembali' => $tanggal_sekarang,
            'denda' => $denda,
            'id' => $id
        ]);

        // Tambah stok buku
        $update_stok = $conn->prepare("UPDATE buku SET stok = stok + 1 WHERE id = :buku_id");
        $update_stok->execute(['buku_id' => $transaksi->buku_id]);

        $conn->commit();
        $success = "Buku berhasil dikembalikan. Stok buku telah bertambah.";
        $transaksi->status = 'Dikembalikan'; // Update local object for display
    } catch(PDOException $e) {
        $conn->rollBack();
        $error = "Gagal memproses pengembalian: " . $e->getMessage();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Proses Pengembalian Buku</h4>
    <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Transaksi</a>
</div>

<div class="card">
    <div class="card-body">
        <?php if($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if($transaksi->status != 'Dikembalikan'): ?>
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><th width="150">Nama Anggota</th><td>: <?= htmlspecialchars($transaksi->nama_anggota) ?></td></tr>
                        <tr><th>Judul Buku</th><td>: <?= htmlspecialchars($transaksi->judul) ?></td></tr>
                        <tr><th>Tanggal Pinjam</th><td>: <?= htmlspecialchars($transaksi->tanggal_pinjam) ?></td></tr>
                        <tr><th>Batas Kembali</th><td>: <span class="fw-bold"><?= htmlspecialchars($transaksi->batas_kembali) ?></span></td></tr>
                        <tr><th>Tanggal Hari Ini</th><td>: <?= $tanggal_sekarang ?></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="alert <?= $hari_terlambat > 0 ? 'alert-danger' : 'alert-info' ?>">
                        <h5 class="alert-heading">Informasi Keterlambatan</h5>
                        <p class="mb-0">Terlambat: <strong><?= $hari_terlambat ?> hari</strong></p>
                        <p class="mb-0">Denda: <strong>Rp <?= number_format($denda, 0, ',', '.') ?></strong></p>
                        <small>(Tarif denda: Rp 1.000 / hari keterlambatan)</small>
                    </div>
                </div>
            </div>

            <form method="POST" action="">
                <button type="submit" class="btn btn-success btn-lg w-100" onclick="return confirm('Proses pengembalian buku? Pastikan denda (jika ada) sudah dibayarkan.')">
                    <i class="bi bi-check2-circle me-1"></i> Konfirmasi Pengembalian Buku
                </button>
            </form>
        <?php else: ?>
            <div class="text-center py-4">
                <h4 class="text-success"><i class="bi bi-check-circle-fill"></i> Transaksi Selesai</h4>
                <p>Buku <strong><?= htmlspecialchars($transaksi->judul) ?></strong> telah dikembalikan oleh <strong><?= htmlspecialchars($transaksi->nama_anggota) ?></strong> pada <strong><?= htmlspecialchars($tanggal_sekarang) ?></strong>.</p>
                <p>Total Denda yang dibayar: <strong>Rp <?= number_format($denda, 0, ',', '.') ?></strong></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
