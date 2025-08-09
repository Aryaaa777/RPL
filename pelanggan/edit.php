<?php
    require_once "../assets/API/config.php";
    session_start();

    // Cek kalau belum login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit;
    }

    // Ambil data pelanggan berdasarkan ID
    if (isset($_GET['id'])) {
        $id_pelanggan = $_GET['id'];

        // Ambil data pelanggan dari database
        $query = "SELECT * FROM pelanggan WHERE id_pelanggan = ?";
        $stmt  = $conn->prepare($query);
        $stmt->bind_param("i", $id_pelanggan);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            echo "<script>alert('Pelanggan tidak ditemukan!'); window.location.href = 'pelanggan.php';</script>";
            exit;
        }

        $pelanggan = $result->fetch_assoc();
    } else {
        echo "<script>alert('ID pelanggan tidak valid!'); window.location.href = 'pelanggan.php';</script>";
        exit;
    }

    // Proses update data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama_pelanggan   = trim($_POST['nama_pelanggan']);
        $jumlah_orang     = (int) $_POST['jumlah_orang'];
        $waktu_kedatangan = $_POST['waktu_kedatangan'];

        // Validasi sederhana
        if ($nama_pelanggan == "" || $jumlah_orang == "" || $waktu_kedatangan == "") {
            echo "<script>alert('Semua field wajib diisi!'); window.history.back();</script>";
            exit;
        }

        // Update pakai prepared statement
        $stmt = $conn->prepare("UPDATE pelanggan SET nama_pelanggan = ?, jumlah_orang = ?, waktu_kedatangan = ? WHERE id_pelanggan = ?");
        $stmt->bind_param("sisi", $nama_pelanggan, $jumlah_orang, $waktu_kedatangan, $id_pelanggan);

        if ($stmt->execute()) {
            header("Location: pelanggan.php");
            exit;
        } else {
            echo "<script>alert('Gagal memperbarui pelanggan: " . addslashes($conn->error) . "'); window.history.back();</script>";
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggan - SI-RERE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* Styling Form */
        .form-container {
            background: #222;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            margin: 0 auto;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #fff;
        }
        .form-container input,
        .form-container select {
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
        .form-container select:focus {
            background: #333;
            outline: none;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.4), 0 0 0 2px #555;
        }
        .form-container .btn-primary {
            background: #3b82f6;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .form-container .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }
        .form-container .btn-secondary {
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
        .form-container .btn-secondary:hover {
            background: #666;
        }

        .alert {
            padding: 12px 18px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .alert.success {
            background-color: #16a34a;
            color: white;
        }
        .alert.error {
            background-color: #dc2626;
            color: white;
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
                <li><a href="/menu/menu.php"><i class="fas fa-book-open"></i> Menu</a></li>
                <li><a href="../meja.php" class="active"><i class="fas fa-chair"></i> Meja</a></li>
                <li><a href="pelanggan.php" class="active"><i class="fas fa-chair"></i> Pelanggan</a></li>
                <li><a href="../pesanan/pesanan.php"><i class="fas fa-file-alt"></i> Pemesanan</a></li>
                <!-- <li><a href="#"><i class="fas fa-dollar-sign"></i> Pembayaran</a></li> -->
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert<?php echo $_SESSION['flash']['type']; ?>">
                <?php echo $_SESSION['flash']['msg']; ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

        <div class="main-header">
            <h2 class="main-title">Edit Pelanggan</h2>
        </div>

        <div class="form-container">
            <form method="POST">
                <label>Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" value="<?php echo htmlspecialchars($pelanggan['nama_pelanggan']); ?>" required>

                <label>Jumlah Orang</label>
                <input type="number" name="jumlah_orang" value="<?php echo $pelanggan['jumlah_orang']; ?>" required>

                <label>Waktu Kedatangan</label>
                <input type="datetime-local" name="waktu_kedatangan" value="<?php echo date('Y-m-d\TH:i', strtotime($pelanggan['waktu_kedatangan'])); ?>" required>

                <button type="submit" class="btn-primary">Simpan</button>
                <a href="pelanggan.php" class="btn-secondary">Batal</a>
            </form>
        </div>
    </main>
</div>
</body>
</html>
