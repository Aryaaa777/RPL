<?php
    require_once "../assets/API/config.php";
    session_start();

    // Cek kalau belum login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit;
    }

    // Proses simpan data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama_menu = trim($_POST['nama_menu']);
        $kategori  = trim($_POST['kategori']);
        $harga     = (float) $_POST['harga'];

        // Validasi sederhana
        if ($nama_menu == "" || $kategori == "" || $harga == "") {
            echo "<script>alert('Semua field wajib diisi!'); window.history.back();</script>";
            exit;
        }
        if (! is_numeric($harga)) {
            echo "<script>alert('Harga harus berupa angka!'); window.history.back();</script>";
            exit;
        }

        // Insert pakai prepared statement
        $stmt = $conn->prepare("INSERT INTO menu (nama_menu, kategori, harga) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $nama_menu, $kategori, $harga);

        if ($stmt->execute()) {
            header("Location: menu.php");
            exit;
        } else {
            echo "<script>alert('Gagal menambahkan menu: " . addslashes($conn->error) . "'); window.history.back();</script>";
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Menu - SI-RERE</title>
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
                <li><a href="../index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="menu.php"><i class="fas fa-book-open"></i> Menu</a></li>
                    <li><a href="../meja/meja.php"><i class="fas fa-chair"></i> Ketersediaan Meja</a></li>
                    <li><a href="../pelanggan/pelanggan.php"><i class="fas fa-person"></i> Pelanggan</a></li>
                    <li><a href="../pesanan/pesanan.php"><i class="fas fa-file-alt"></i> Pemesanan</a></li>
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
            <h2 class="main-title">Tambah Menu</h2>
        </div>

        <div class="form-container">
            <form method="POST">
                <label>Nama Menu</label>
                <input type="text" name="nama_menu" placeholder="Masukkan nama menu" required>

                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Makanan">Makanan</option>
                    <option value="Minuman">Minuman</option>
                    <option value="Snack">Snack</option>
                </select>

                <label>Harga</label>
                <input type="number" name="harga" placeholder="Masukkan harga" required>

                <button type="submit" class="btn-primary">Simpan</button>
                <a href="menu.php" class="btn-secondary">Batal</a>
            </form>
        </div>
    </main>
</div>
</body>
</html>
