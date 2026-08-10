<?php
require_once 'includes/header.php';

// Get total books
$stmt = $conn->query("SELECT COUNT(*) as total FROM buku");
$total_buku = $stmt->fetch()->total;

// Get total members
$stmt = $conn->query("SELECT COUNT(*) as total FROM anggota");
$total_anggota = $stmt->fetch()->total;

// Get total active borrowings
$stmt = $conn->query("SELECT COUNT(*) as total FROM peminjaman WHERE status = 'Dipinjam'");
$total_peminjaman = $stmt->fetch()->total;

// Get total late returns (not returned yet, but past due date)
$stmt = $conn->query("SELECT COUNT(*) as total FROM peminjaman WHERE status = 'Dipinjam' AND batas_kembali < CURDATE()");
$total_terlambat = $stmt->fetch()->total;
?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Buku</h5>
                <h2 class="display-5"><?= $total_buku ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Anggota</h5>
                <h2 class="display-5"><?= $total_anggota ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Sedang Dipinjam</h5>
                <h2 class="display-5"><?= $total_peminjaman ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5 class="card-title">Terlambat</h5>
                <h2 class="display-5"><?= $total_terlambat ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Peminjaman Terakhir</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Batas Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT p.*, a.nama as nama_anggota, b.judul as judul_buku 
                                      FROM peminjaman p 
                                      JOIN anggota a ON p.anggota_id = a.id 
                                      JOIN buku b ON p.buku_id = b.id 
                                      ORDER BY p.id DESC LIMIT 5");
                while($row = $stmt->fetch()):
                ?>
                <tr>
                    <td><?= htmlspecialchars($row->nama_anggota) ?></td>
                    <td><?= htmlspecialchars($row->judul_buku) ?></td>
                    <td><?= htmlspecialchars($row->tanggal_pinjam) ?></td>
                    <td><?= htmlspecialchars($row->batas_kembali) ?></td>
                    <td>
                        <?php if($row->status == 'Dipinjam'): ?>
                            <span class="badge bg-warning text-dark">Dipinjam</span>
                        <?php else: ?>
                            <span class="badge bg-success">Dikembalikan</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
