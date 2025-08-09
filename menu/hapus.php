<?php
require_once "../assets/API/config.php";
session_start();

// Cek login
if (! isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

// Validasi ID
if (! isset($_GET['id']) || ! is_numeric($_GET['id'])) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'ID menu tidak valid'];
    header("Location: menu.php");
    exit;
}

$id_menu = (int) $_GET['id'];

// Pastikan menu ada
$cek = $conn->prepare("SELECT id_menu FROM menu WHERE id_menu = ?");
$cek->bind_param("i", $id_menu);
$cek->execute();
$hasil = $cek->get_result();
if ($hasil->num_rows === 0) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Menu tidak ditemukan'];
    header("Location: menu.php");
    exit;
}

// Soft delete: ubah status
$stmt = $conn->prepare("UPDATE menu SET status = 'tidak aktif' WHERE id_menu = ?");
$stmt->bind_param("i", $id_menu);

if ($stmt->execute()) {
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Menu berhasil dinonaktifkan'];
} else {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Gagal menonaktifkan menu'];
}

header("Location: menu.php");
exit;
