<?php
/**
 * File: koneksi.php
 * Deskripsi: Konfigurasi koneksi database.
 *            - Di Railway: membaca dari environment variables otomatis.
 *            - Di lokal  : menggunakan nilai default development.
 *
 * CATATAN KEAMANAN:
 *   File ini AMAN di-push ke GitHub karena tidak mengandung password.
 *   Semua kredensial production disimpan di Railway Environment Variables.
 *   Untuk lokal, ubah $password di bawah sesuai setup MySQL Anda,
 *   atau buat config/koneksi_local.php (sudah ada di .gitignore).
 *
 * Author: @faiqyr
 * Versi: 1.3.0
 */

// --- Deteksi environment ---
// Railway selalu menyediakan MYSQLHOST saat MySQL service ditambahkan ke project.
$isRailway = !empty(getenv('MYSQLHOST')) || !empty(getenv('RAILWAY_ENVIRONMENT'));

if ($isRailway) {
    // =========================================================
    //  PRODUCTION — Railway
    // =========================================================
    define('ENVIRONMENT', 'production');

    // Gunakan MYSQL_PUBLIC_URL jika tersedia (public endpoint)
    // Format: mysql://user:password@host:port/database
    $publicUrl = getenv('MYSQL_PUBLIC_URL');
    if ($publicUrl) {
        $parsed   = parse_url($publicUrl);
        $host     = $parsed['host'];
        $username = $parsed['user'];
        $password = $parsed['pass'];
        $database = ltrim($parsed['path'], '/');
        $port     = $parsed['port'] ?? '3306';
    } else {
        // Fallback ke individual vars (private network Railway)
        $host     = getenv('MYSQLHOST');
        $username = getenv('MYSQLUSER');
        $password = getenv('MYSQLPASSWORD');
        $database = getenv('MYSQLDATABASE');
        $port     = getenv('MYSQLPORT') ?: '3306';
    }

    $baseUrl = getenv('APP_URL') ?: '';

} else {
    // =========================================================
    //  DEVELOPMENT — Lokal (XAMPP / Laragon)
    // =========================================================

    // Jika ada koneksi_local.php, file itu yang dipakai
    // sehingga tidak perlu mengubah file ini.
    if (file_exists(__DIR__ . '/koneksi_local.php')) {
        require_once __DIR__ . '/koneksi_local.php';
        return;
    }

    define('ENVIRONMENT', 'development');

    $host     = 'localhost';
    $username = 'root';
    $password = '';            // ganti jika MySQL lokal Anda pakai password
    $database = 'db_perpustakaan';
    $port     = '3306';
    $baseUrl  = 'http://localhost/perpustakaan';
}

// --- Error reporting ---
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/error.log');
}

// --- Koneksi PDO ---
try {
    $dsn  = "mysql:host={$host};port={$port};dbname={$database};charset=utf8";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (PDOException $e) {
    if (ENVIRONMENT === 'development') {
        die('<h1>Koneksi Database Gagal</h1><p>Detail: ' . htmlspecialchars($e->getMessage()) . '</p>');
    } else {
        error_log('Database connection failed: ' . $e->getMessage());
        die('<h1>Sistem Sedang Gangguan</h1><p>Mohon maaf, layanan kami sedang mengalami gangguan internal. Silakan kembali beberapa saat lagi.</p>');
    }
}
?>
