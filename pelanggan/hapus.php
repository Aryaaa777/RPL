<?php
    require_once "../assets/API/config.php";
    session_start();

    // Cek kalau belum login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit;
    }

    // Cek jika ID pelanggan ada di URL
    if (isset($_GET['id'])) {
        $id_pelanggan = $_GET['id'];

        // Ambil data pelanggan dari database untuk memastikan pelanggan ada
        $query = "SELECT * FROM pelanggan WHERE id_pelanggan = ?";
        $stmt  = $conn->prepare($query);
        $stmt->bind_param("i", $id_pelanggan);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            echo "<script>alert('Pelanggan tidak ditemukan!'); window.location.href = 'pelanggan.php';</script>";
            exit;
        }

        // Proses hapus pelanggan
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Hapus data pelanggan
            $deleteQuery = "DELETE FROM pelanggan WHERE id_pelanggan = ?";
            $stmt        = $conn->prepare($deleteQuery);
            $stmt->bind_param("i", $id_pelanggan);

            if ($stmt->execute()) {
                header("Location: pelanggan.php");
                exit;
            } else {
                echo "<script>alert('Gagal menghapus pelanggan: " . addslashes($conn->error) . "'); window.location.href = 'pelanggan.php';</script>";
                exit;
            }
        }
    } else {
        echo "<script>alert('ID pelanggan tidak valid!'); window.location.href = 'pelanggan.php';</script>";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Pelanggan - SI-RERE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .form-container {
            background: #222;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            margin: 0 auto;
        }
        .form-container h2 {
            color: white;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .btn-primary {
            background: #ef4444;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        .btn-primary:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #555;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            width: 100%;
        }
        .btn-secondary:hover {
            background: #666;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo-container">
            <div class="logo-title">SI-RERE</div>
            <img src="../assets/image/LOGO_PUTIH.png" alt="logo" class="logo-img">
        </div>
        <nav class="sidebar-menu">
            <ul>
                <li><a href="../dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="menu.php"><i class="fas fa-book-open"></i> Menu</a></li>
                <li><a href="pelanggan.php" class="active"><i class="fas fa-users"></i> Pelanggan</a></li>
                <li><a href="#"><i class="fas fa-chair"></i> Ketersediaan Meja</a></li>
                <li><a href="#"><i class="fas fa-file-alt"></i> Pemesanan</a></li>
                <li><a href="#"><i class="fas fa-dollar-sign"></i> Pembayaran</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="main-header">
            <h2 class="main-title">Hapus Pelanggan</h2>
        </div>

        <div class="form-container">
            <h2>Apakah Anda yakin ingin menghapus pelanggan ini?</h2>
            <form method="POST">
                <button type="submit" class="btn-primary">Hapus Pelanggan</button>
                <a href="pelanggan.php" class="btn-secondary">Batal</a>
            </form>
        </div>
    </main>
</div>
</body>
</html>
