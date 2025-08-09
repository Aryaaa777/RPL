<?php
    require_once "../assets/API/config.php";
    session_start();

    // Cek login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit;
    }

    // Cek parameter no_meja
    if (! isset($_GET['no_meja'])) {
        header("Location: meja.php");
        exit;
    }

    $no_meja = $_GET['no_meja'];

    // Ambil data meja
    $stmt = $conn->prepare("SELECT no_meja, status_meja, kapasitas, keterangan FROM meja WHERE no_meja = ?");
    $stmt->bind_param("s", $no_meja);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Data meja tidak ditemukan'); window.location='meja.php';</script>";
        exit;
    }

    $meja = $result->fetch_assoc();

    // Proses update
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $status_meja = trim($_POST['status_meja']);
        $kapasitas   = (int) $_POST['kapasitas'];
        $keterangan  = trim($_POST['keterangan']);

        if ($status_meja == "" || $kapasitas <= 0) {
            echo "<script>alert('Status dan kapasitas wajib diisi!'); window.history.back();</script>";
            exit;
        }

        $stmt = $conn->prepare("UPDATE meja SET status_meja = ?, kapasitas = ?, keterangan = ? WHERE no_meja = ?");
        $stmt->bind_param("siss", $status_meja, $kapasitas, $keterangan, $no_meja);

        if ($stmt->execute()) {
            header("Location: meja.php");
            exit;
        } else {
            echo "<script>alert('Gagal mengupdate meja: " . addslashes($conn->error) . "'); window.history.back();</script>";
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Meja - SI-RERE</title>
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
                <li><a href="../dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../menu/menu.php"><i class="fas fa-book-open"></i> Menu</a></li>
                <li><a href="meja.php" class="active"><i class="fas fa-chair"></i> Meja</a></li>
                <li><a href="../pesanan/pesanan.php"><i class="fas fa-file-alt"></i> Pemesanan</a></li>
                <!-- <li><a href="#"><i class="fas fa-dollar-sign"></i> Pembayaran</a></li> -->
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="main-header">
            <h2 class="main-title">Edit Meja -                                                                                             <?php echo htmlspecialchars($meja['no_meja']); ?></h2>
        </div>

        <div class="form-container">
            <form method="POST">
                <label>Nomor Meja</label>
                <input type="text" value="<?php echo htmlspecialchars($meja['no_meja']); ?>" disabled>

                <label>Status Meja</label>
                <select name="status_meja" required>
                    <option value="tersedia"                                                                                         <?php if ($meja['status_meja'] == 'tersedia') {
                                                                                                 echo 'selected';
                                                                                         }
                                                                                         ?>>Tersedia</option>
                    <option value="terisi"                                                                                     <?php if ($meja['status_meja'] == 'terisi') {
                                                                                             echo 'selected';
                                                                                     }
                                                                                     ?>>Terisi</option>
                    <option value="rusak"                                                                                   <?php if ($meja['status_meja'] == 'rusak') {
                                                                                           echo 'selected';
                                                                                   }
                                                                                   ?>>Rusak</option>
                </select>

                <label>Kapasitas</label>
                <input type="number" name="kapasitas" value="<?php echo (int) $meja['kapasitas']; ?>" required>

                <label>Keterangan</label>
                <textarea name="keterangan"><?php echo htmlspecialchars($meja['keterangan']); ?></textarea>

                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="meja.php" class="btn-secondary">Batal</a>
            </form>
        </div>
    </main>
</div>
</body>
</html>
