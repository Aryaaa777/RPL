<?php
    require_once "../assets/API/config.php";
    session_start();

    // Cek login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit;
    }

    // Proses simpan data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $no_meja     = trim($_POST['no_meja']);
        $status_meja = trim($_POST['status_meja']);
        $kapasitas   = (int) $_POST['kapasitas'];
        $keterangan  = trim($_POST['keterangan']);

        if ($no_meja == "" || $status_meja == "" || $kapasitas <= 0) {
            echo "<script>alert('Nomor meja, status, dan kapasitas wajib diisi!'); window.history.back();</script>";
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO meja (no_meja, status_meja, kapasitas, keterangan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $no_meja, $status_meja, $kapasitas, $keterangan);

        if ($stmt->execute()) {
            header("Location: meja.php");
            exit;
        } else {
            echo "<script>alert('Gagal menambahkan meja: " . addslashes($conn->error) . "'); window.history.back();</script>";
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Meja - SI-RERE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .form-container {
            background: #222;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            max-width: 500px;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #fff;
        }
        .form-container input,
        .form-container select,
        .form-container textarea {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 1.2rem;
            border: none;
            border-radius: 8px;
            background: #2a2a2a;
            color: white;
            font-size: 1rem;
            font-family: "Poppins";
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }
        .form-container input:focus,
        .form-container select:focus,
        .form-container textarea:focus {
            background: #333;
            outline: none;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.4), 0 0 0 2px #555;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #2563eb;
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
                <li><a href="../index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="../menu.php"><i class="fas fa-book-open"></i> Menu</a></li>
                    <li><a href="meja/meja.php"><i class="fas fa-chair"></i> Ketersediaan Meja</a></li>
                    <li><a href="../pelanggan/pelanggan.php"><i class="fas fa-person"></i> Pelanggan</a></li>
                    <li><a href="../pesanan/pesanan.php"><i class="fas fa-file-alt"></i> Pemesanan</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="main-header">
            <h2 class="main-title">Tambah Meja</h2>
        </div>

        <div class="form-container">
            <form method="POST">
                <label>Nomor Meja</label>
                <input type="text" name="no_meja" placeholder="Contoh: A1" required>

                <label>Status Meja</label>
                <select name="status_meja" required>
                    <option value="tersedia">Tersedia</option>
                    <option value="terisi">Terisi</option>
                    <option value="rusak">Rusak</option>
                </select>

                <label>Kapasitas</label>
                <input type="number" name="kapasitas" placeholder="Masukkan jumlah kursi" required>

                <label>Keterangan</label>
                <textarea name="keterangan" placeholder="Opsional"></textarea>

                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="meja.php" class="btn-secondary">Batal</a>
            </form>
        </div>
    </main>
</div>
</body>
</html>
