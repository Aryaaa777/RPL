<?php
require_once "../assets/API/config.php";
session_start();

// Cek login
if (! isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_pesanan = (int) $_GET['id'];

    // Hapus item pesanan dari order_items
    $orderItemsDelete = $conn->prepare("DELETE FROM order_items WHERE id_pesanan = ?");
    $orderItemsDelete->bind_param("i", $id_pesanan);
    $orderItemsDelete->execute();

    // Hapus pesanan dari tabel pesanan
    $pesananDelete = $conn->prepare("DELETE FROM pesanan WHERE id_pesanan = ?");
    $pesananDelete->bind_param("i", $id_pesanan);
    $pesananDelete->execute();

    header("Location: pesanan.php");
    exit;
} else {
    echo "<script>alert('Pesanan tidak ditemukan!'); window.location.href='pesanan.php';</script>";
    exit;
}
