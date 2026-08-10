<?php
// Tentukan lingkungan aplikasi: 'development' atau 'production'
define('ENVIRONMENT', 'development');

if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/error.log');
}

$host = "localhost";
$username = "root";
$password = "";
$database = "db_perpustakaan";
$baseUrl = "http://localhost/sertifikasi";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to object
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch(PDOException $e) {
    // Penanganan kesalahan: pesan ramah pengguna ketika koneksi gagal (Tugas Debugging)
    if (ENVIRONMENT === 'development') {
        die("<h1>Sistem Sedang Gangguan</h1><p>Gagal terhubung ke database. Detail teknis (Development Only): " . htmlspecialchars($e->getMessage()) . "</p>");
    } else {
        error_log("Database connection failed: " . $e->getMessage());
        die("<h1>Sistem Sedang Gangguan</h1><p>Mohon maaf, layanan kami sedang mengalami gangguan internal. Tim teknis kami sedang menanganinya. Silakan kembali beberapa saat lagi.</p>");
    }
}
?>
