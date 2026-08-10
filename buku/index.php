<?php
require_once '../includes/header.php';

// Fungsi pencarian data buku di sisi aplikasi (array) tanpa menggunakan query LIKE
function cariBuku($array_buku, $keyword) {
    $hasil = [];
    $keyword = strtolower(trim($keyword));
    
    foreach ($array_buku as $buku) {
        // Karena array di bawah berupa array of object
        $judul = strtolower($buku->judul);
        $pengarang = strtolower($buku->pengarang);
        
        if (strpos($judul, $keyword) !== false || strpos($pengarang, $keyword) !== false) {
            $hasil[] = $buku;
        }
    }
    return $hasil;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

// 2. Mengambil semua data dari database menjadi array of objects (kumpulan data)
$stmt = $conn->query("SELECT * FROM buku ORDER BY id DESC");
$buku_list = $stmt->fetchAll(PDO::FETCH_OBJ); // array of object (memenuhi kriteria ke-2)

// Jika ada parameter pencarian, panggil fungsi cariBuku
if ($search) {
    $buku_list = cariBuku($buku_list, $search);
}

// 1. Contoh penggunaan Array Asosiatif untuk menampung SATU BARIS data (memenuhi kriteria ke-1)
// Kita ambil data buku pertama secara spesifik dengan fetch ASSOC sebagai demonstrasi
$contoh_buku_assoc = null;
if (count($buku_list) > 0) {
    $id_pertama = $buku_list[0]->id;
    $stmt_assoc = $conn->prepare("SELECT * FROM buku WHERE id = :id LIMIT 1");
    $stmt_assoc->execute(['id' => $id_pertama]);
    $contoh_buku_assoc = $stmt_assoc->fetch(PDO::FETCH_ASSOC); // array asosiatif (memenuhi kriteria ke-1)
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Kelola Data Buku</h4>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Tambah Buku</a>
</div>

<?php if ($contoh_buku_assoc): ?>
    <div class="alert alert-info d-none d-md-block">
        <strong>Demo Array Asosiatif (Data Buku Teratas):</strong><br>
        Judul: <?= htmlspecialchars($contoh_buku_assoc['judul']) ?> | 
        Pengarang: <?= htmlspecialchars($contoh_buku_assoc['pengarang']) ?> | 
        Kategori: <?= htmlspecialchars($contoh_buku_assoc['kategori']) ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Judul atau Pengarang..." value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                <?php if($search): ?>
                    <a href="index.php" class="btn btn-outline-danger">Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
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
                    <?php if (count($buku_list) > 0): ?>
                        <?php foreach($buku_list as $index => $buku): ?>
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
                                <a href="edit.php?id=<?= $buku->id ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                                <a href="hapus.php?id=<?= $buku->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')"><i class="bi bi-trash"></i> Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Data buku tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
