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
 *   Untuk lokal, ubah nilai $localPassword sesuai setup MySQL Anda,
 *   atau buat config/koneksi_local.php yang di-ignore git.
 *
 * Author: @faiqyr
 * Versi: 1.2.0
 */

// --- Deteksi environment ---
$isRailway = !empty(getenv('MYSQLHOST')) || !empty(getenv('RAILWAY_ENVIRONMENT'));

// DEBUG SEMENTARA — hapus setelah masalah terselesaikan
if (getenv('RAILWAY_ENVIRONMENT') === 'production') {
    $debugInfo  = "MYSQLHOST: "     . var_export(getenv('MYSQLHOST'), true)     . "\n";
    $debugInfo .= "MYSQLUSER: "     . var_export(getenv('MYSQLUSER'), true)     . "\n";
    $debugInfo .= "MYSQLDATABASE: " . var_export(getenv('MYSQLDATABASE'), true) . "\n";
    $debugInfo .= "MYSQLPORT: "     . var_export(getenv('MYSQLPORT'), true)     . "\n";
    $debugInfo .= "isRailway: "     . var_export($isRailway, true)              . "\n";
    die('<h2>DEBUG MODE</h2><pre>' . htmlspecialchars($debugInfo) . '</pre>');
}

if ($isRailway) {
    // =========================================================
    //  PRODUCTION — Railway
    //  Variabel ini otomatis tersedia setelah MySQL service
    //  ditambahkan ke project yang sama di Railway dashboard.
    // =========================================================
    define('ENVIRONMENT', 'production');

    $host     = getenv('MYSQLHOST');
    $username = getenv('MYSQLUSER');
    $password = getenv('MYSQLPASSWORD');
    $database = getenv('MYSQLDATABASE');
    $port     = getenv('MYSQLPORT') ?: '3306';
    $baseUrl  = getenv('APP_URL') ?: '';

} else {
    // =========================================================
    //  DEVELOPMENT — Lokal (XAMPP / Laragon)
    //  Jika ada file koneksi_local.php, file itu yang dipakai
    //  sehingga tidak perlu mengubah file ini.
    // =========================================================
    if (file_exists(__DIR__ . '/koneksi_local.php')) {
        require_once __DIR__ . '/koneksi_local.php';
        return;
    }

    define('ENVIRONMENT', 'development');

    $host     = 'localhost';
    $username = 'root';
    $password = '';                        // ganti jika MySQL lokal Anda pakai password
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
    if (ENVIRONMENT === 'development' || getenv('RAILWAY_DEBUG') === 'true') {
        die('<h1>Koneksi Database Gagal</h1><p>Detail: ' . htmlspecialchars($e->getMessage()) . '</p><pre>HOST: ' . htmlspecialchars($host) . "\nDB: " . htmlspecialchars($database) . "\nUSER: " . htmlspecialchars($username) . '</pre>');
    } else {
        error_log('Database connection failed: ' . $e->getMessage());
        die('<h1>Sistem Sedang Gangguan</h1><p>Mohon maaf, layanan kami sedang mengalami gangguan. Silakan kembali beberapa saat lagi.</p>');
    }
}
?>
