<?php
session_start();
require_once "../assets/API/config.php";

// Cek login
if (! isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Pastikan ada parameter no_meja
if (! isset($_GET['no_meja']) || $_GET['no_meja'] == '') {
    header("Location: meja.php");
    exit();
}

$no_meja = $_GET['no_meja'];

// Query hapus permanen
$stmt = $conn->prepare("DELETE FROM meja WHERE no_meja = ?");
$stmt->bind_param("s", $no_meja);

if ($stmt->execute()) {
    // Berhasil hapus
    header("Location: meja.php");
    exit();
} else {
    echo "<script>alert('Gagal menghapus meja: " . addslashes($conn->error) . "'); window.history.back();</script>";
    exit();
}
