<?php
require_once '../includes/auth.php';
require_once '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $conn->prepare("DELETE FROM anggota WHERE id = :id");
        $stmt->execute(['id' => $id]);
    } catch(PDOException $e) {
        // Handle constraint violation etc if needed
    }
}

header("Location: index.php");
exit();
?>
