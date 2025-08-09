<?php
// api.php - Versi lengkap dengan penanganan pesanan
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once "config.php"; // pastikan menghasilkan $conn (mysqli)

// --- util ---
// function untuk mengambil input JSON
function getJsonInput()
{
    $d = file_get_contents("php://input");
    return $d ? json_decode($d, true) : [];
}

function send($data, $code = 200)
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// --- resource router ---
$resource = $_GET['resource'] ?? null;

// Aliases untuk memastikan frontend tetap aman jika resource menggunakan nama yang berbeda
$aliases = [
    'pesanan' => 'pesanan', // alias untuk resource pesanan
];

if ($resource !== null && isset($aliases[$resource])) {
    $resource = $aliases[$resource];
}

// --- PESANAN (menampilkan pesanan selesai) ---
switch ($resource) {
    case 'pesanan':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Ambil data pesanan yang sudah selesai, dan total harga pesanan
            $query = "
                SELECT p.id_pesanan, p.id_pelanggan, p.no_meja, p.status_pesanan, p.created_at,
                GROUP_CONCAT(m.nama_menu SEPARATOR ', ') AS menu_dipesan,
                SUM(oi.qty * oi.harga_satuan) AS total_harga
                FROM pesanan p
                LEFT JOIN order_items oi ON p.id_pesanan = oi.id_pesanan
                LEFT JOIN menu m ON oi.id_menu = m.id_menu
                WHERE p.status_pesanan = 'selesai'
                GROUP BY p.id_pesanan
                ORDER BY p.created_at DESC
            ";

            // Mengambil hasil query pesanan yang sudah selesai
            $result = fetch_all_safe($conn, $query);
            send($result);
        }
        break;

    default:
        send(["error" => "Unknown resource"], 404);
}
?>