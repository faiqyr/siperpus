<?php
/**
 * File: logout.php
 * Deskripsi: Script untuk mengakhiri sesi pengguna (logout).
 * Author: @faiqyr
 * Tanggal Dibuat: 2026-08-10
 * Versi: 1.0.0
 */
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>
