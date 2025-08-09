<?php
require_once "../assets/API/config.php";

$id = $_GET['id'] ?? 0;
$id = (int) $id;

if ($id > 0) {
    $stmt = $conn->prepare("UPDATE menu SET status = 'tersedia' WHERE id_menu = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: menu.php");
exit;
