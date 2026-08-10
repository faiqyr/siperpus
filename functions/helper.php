<?php
/**
 * File: helper.php
 * Deskripsi: Kumpulan fungsi pembantu (helper) untuk sesi dan UI flash messages.
 * Author: @faiqyr
 * Tanggal Dibuat: 2026-08-10
 * Versi: 1.0.0
 */

/**
 * Menyimpan pesan sementara (flash message) ke dalam sesi.
 * 
 * @param string $type Jenis pesan (success, error, warning).
 * @param string $message Isi pesan yang akan ditampilkan.
 * @return void
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'tipe' => $type, // 'success', 'error', 'warning', 'info'
        'pesan' => $message
    ];
}

/**
 * Mengambil dan menampilkan flash message dari sesi menggunakan SweetAlert2.
 * 
 * Fungsi ini akan mencetak script JS yang merender SweetAlert jika ada pesan di sesi,
 * dan langsung menghapus pesan tersebut dari sesi setelah dirender agar tidak berulang.
 * 
 * @return void
 */
function showFlash() {
    if (isset($_SESSION['flash'])) {
        $tipe = $_SESSION['flash']['tipe'];
        $pesan = addslashes($_SESSION['flash']['pesan']);
        
        $icon = 'success';
        if ($tipe == 'error' || $tipe == 'danger') $icon = 'error';
        if ($tipe == 'warning') $icon = 'warning';
        if ($tipe == 'info') $icon = 'info';

        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '$icon',
                    title: 'Informasi',
                    text: '$pesan',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>";
        unset($_SESSION['flash']);
    }
}
?>
