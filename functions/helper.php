<?php
function setFlash($tipe, $pesan) {
    $_SESSION['flash'] = [
        'tipe' => $tipe, // 'success', 'error', 'warning', 'info'
        'pesan' => $pesan
    ];
}

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
