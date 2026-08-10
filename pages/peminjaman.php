<?php
require_once '../includes/header.php';
require_once '../functions/pinjam.func.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

if ($action == 'add') {
    // Ambil data anggota dan buku
    $anggotaList = $conn->query("SELECT id, kode_anggota, nama FROM anggota ORDER BY nama ASC")->fetchAll();
    $bukuList = $conn->query("SELECT id, judul, stok FROM buku WHERE stok > 0 ORDER BY judul ASC")->fetchAll();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $conn->beginTransaction();
            $success = insertPeminjaman($_POST, $_SESSION['user_id']);
            if ($success) {
                $conn->commit();
                setFlash('success', 'Transaksi peminjaman berhasil disimpan!');
                header("Location: peminjaman.php");
                exit();
            } else {
                $conn->rollBack();
                setFlash('error', 'Stok buku kosong!');
            }
        } catch(PDOException $e) {
            $conn->rollBack();
            setFlash('error', 'Gagal memproses transaksi. Kode Peminjaman mungkin sudah ada.');
        }
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Tambah Transaksi Peminjaman</h4>
        <a href="peminjaman.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="kode_peminjaman" class="form-label">Kode Peminjaman</label>
                    <input type="text" class="form-control" id="kode_peminjaman" name="kode_peminjaman" placeholder="Contoh: PJ001" required>
                </div>
                <div class="mb-3">
                    <label for="anggota_id" class="form-label">Pilih Anggota</label>
                    <select class="form-select" id="anggota_id" name="anggota_id" required>
                        <option value="">-- Pilih Anggota --</option>
                        <?php foreach($anggotaList as $anggota): ?>
                            <option value="<?= $anggota->id ?>"><?= htmlspecialchars($anggota->kode_anggota . ' - ' . $anggota->nama) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="buku_id" class="form-label">Pilih Buku</label>
                    <select class="form-select" id="buku_id" name="buku_id" required>
                        <option value="">-- Pilih Buku (Stok Tersedia) --</option>
                        <?php foreach($bukuList as $buku): ?>
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
<?php
} elseif ($action == 'return') {
    $id = $_GET['id'];
    $transaksi = getPeminjamanById($id);

    if (!$transaksi || $transaksi->status == 'Dikembalikan') {
        header("Location: peminjaman.php");
        exit();
    }

    $tanggalSekarang = date('Y-m-d');
    $hariTerlambat = 0;
    $denda = 0;

    if (strtotime($tanggalSekarang) > strtotime($transaksi->batas_kembali)) {
        $diff = strtotime($tanggalSekarang) - strtotime($transaksi->batas_kembali);
        $hariTerlambat = floor($diff / (60 * 60 * 24));
        $denda = $hariTerlambat * 1000;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $conn->beginTransaction();
            prosesKembali($id, $tanggalSekarang, $denda, $transaksi->buku_id);
            $conn->commit();
            setFlash('success', 'Buku berhasil dikembalikan!');
            header("Location: peminjaman.php");
            exit();
        } catch(PDOException $e) {
            $conn->rollBack();
            setFlash('error', 'Gagal memproses pengembalian.');
        }
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Proses Pengembalian Buku</h4>
        <a href="peminjaman.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><th width="150">Nama Anggota</th><td>: <?= htmlspecialchars($transaksi->nama_anggota) ?></td></tr>
                        <tr><th>Judul Buku</th><td>: <?= htmlspecialchars($transaksi->judul) ?></td></tr>
                        <tr><th>Tanggal Pinjam</th><td>: <?= htmlspecialchars($transaksi->tanggal_pinjam) ?></td></tr>
                        <tr><th>Batas Kembali</th><td>: <span class="fw-bold"><?= htmlspecialchars($transaksi->batas_kembali) ?></span></td></tr>
                        <tr><th>Tanggal Hari Ini</th><td>: <?= $tanggalSekarang ?></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="alert <?= $hariTerlambat > 0 ? 'alert-danger' : 'alert-info' ?>">
                        <h5 class="alert-heading">Informasi Keterlambatan</h5>
                        <p class="mb-0">Terlambat: <strong><?= $hariTerlambat ?> hari</strong></p>
                        <p class="mb-0">Denda: <strong>Rp <?= number_format($denda, 0, ',', '.') ?></strong></p>
                    </div>
                </div>
            </div>
            <form method="POST" action="" id="formReturn">
                <button type="button" class="btn btn-success btn-lg w-100" onclick="confirmReturn()">
                    <i class="bi bi-check2-circle me-1"></i> Konfirmasi Pengembalian Buku
                </button>
            </form>
        </div>
    </div>
    <script>
        function confirmReturn() {
            Swal.fire({
                title: 'Proses Pengembalian?',
                text: "Pastikan buku sudah diterima dan denda (jika ada) dibayar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Kembalikan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formReturn').submit();
                }
            });
        }
    </script>
<?php
} else {
    // List view
    $peminjamanList = getAllPeminjaman();
    
    // Implementasi Requirement: Fungsi pengurutan array
    $peminjamanList = urutkanPeminjaman($peminjamanList);
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Data Transaksi Peminjaman</h4>
        <a href="?action=add" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Transaksi Baru</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped datatable">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($peminjamanList as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row->kode_peminjaman) ?></td>
                            <td><?= htmlspecialchars($row->nama_anggota) ?> <br><small class="text-muted">(<?= htmlspecialchars($row->kode_anggota) ?>)</small></td>
                            <td><?= htmlspecialchars($row->judul_buku) ?></td>
                            <td><?= htmlspecialchars($row->tanggal_pinjam) ?></td>
                            <td><?= htmlspecialchars($row->batas_kembali) ?></td>
                            <td>
                                <?php if($row->status == 'Dipinjam'): ?>
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                <?php elseif($row->status == 'Terlambat'): ?>
                                    <span class="badge bg-danger">Terlambat</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Dikembalikan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($row->status == 'Dikembalikan'): ?>
                                    Rp <?= number_format($row->denda, 0, ',', '.') ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row->nama_petugas) ?></td>
                            <td>
                                <?php if($row->status != 'Dikembalikan'): ?>
                                    <a href="?action=return&id=<?= $row->id ?>" class="btn btn-sm btn-success"><i class="bi bi-check2-square"></i> Proses</a>
                                <?php else: ?>
                                    <span class="text-muted">Selesai</span>
                                <?php endif; ?>
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
