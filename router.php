<?php
/**
 * router.php
 * Entry point untuk PHP built-in server saat deploy di Railway.
 * Menangani static files (CSS, JS, gambar) dan meneruskan request PHP ke file yang sesuai.
 *
 * Cara kerja:
 *   - Jika request adalah file statis yang ada di disk → serve langsung
 *   - Jika request adalah file PHP yang ada → jalankan file tersebut
 *   - Jika tidak ada → kembalikan 404
 *
 * Digunakan oleh Procfile:
 *   web: php -S 0.0.0.0:$PORT -t . router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Hapus query string, ambil path bersih
$filePath = __DIR__ . $uri;

// Serve file statis (CSS, JS, gambar, font, dll.) langsung tanpa diproses PHP
$staticExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico',
                     'woff', 'woff2', 'ttf', 'eot', 'map', 'pdf', 'txt'];

if (is_file($filePath)) {
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    if (in_array($ext, $staticExtensions)) {
        return false; // Biarkan PHP built-in server yang serve
    }
}

// Default: arahkan ke index.php jika path adalah root
if ($uri === '/') {
    require __DIR__ . '/index.php';
    return true;
}

// Jalankan file PHP jika ada
if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'php') {
    require $filePath;
    return true;
}

// 404 jika file tidak ditemukan
http_response_code(404);
echo '<h1>404 — Halaman tidak ditemukan</h1>';
echo '<p><a href="/">Kembali ke Dashboard</a></p>';
return true;
