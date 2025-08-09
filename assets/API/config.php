<?php
$host = "localhost";
$user = "root";    // username MySQL lu
$pass = "";        // password MySQL lu
$db   = "si_rere"; // nama database

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode([
        "status"  => "error",
        "message" => "Koneksi gagal: " . $conn->connect_error,
    ]));
}
