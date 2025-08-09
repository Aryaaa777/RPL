<?php
    require_once "../assets/API/config.php";
    session_start();

    // Cek login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit;
    }

    // Periksa apakah ada id pesanan yang diberikan di URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id_pesanan = (int) $_GET['id'];

        // Ambil data pesanan berdasarkan id_pesanan
        $qPesanan = $conn->query("SELECT * FROM pesanan WHERE id_pesanan = $id_pesanan LIMIT 1");
        $pesanan  = $qPesanan->fetch_assoc();

        // Cek jika pesanan tidak ditemukan
        if (! $pesanan) {
            echo "<script>alert('Pesanan tidak ditemukan atau belum selesai!'); window.location.href='pembayaran.php';</script>";
            exit;
        }

        // Kalkulasi total harga dari order_items untuk pesanan ini
        $qTotalHarga = $conn->query("SELECT SUM(oi.qty * oi.harga_satuan) AS total_harga
                                     FROM order_items oi
                                     WHERE oi.id_pesanan = $id_pesanan");
        $totalHarga = $qTotalHarga->fetch_assoc()['total_harga'];

        // Cek apakah totalHarga ada dan valid
        if (! $totalHarga) {
            echo "<script>alert('Total harga tidak ditemukan!'); window.location.href='pembayaran.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('ID Pesanan tidak valid!'); window.location.href='pembayaran.php';</script>";
        exit;
    }

    // Proses form pembayaran
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $status_pembayaran = $_POST['status_pembayaran'];
        $metode_pembayaran = $_POST['metode_pembayaran'];
        $jumlah_bayar      = $totalHarga; // Jumlah bayar otomatis sama dengan total harga pesanan

        // Insert pembayaran ke tabel data_pembayaran
        $stmt = $conn->prepare("INSERT INTO data_pembayaran (id_pesanan, jumlah_bayar, status_pembayaran, metode_pembayaran)
                                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $id_pesanan, $jumlah_bayar, $status_pembayaran, $metode_pembayaran);

        if ($stmt->execute()) {
            echo "<script>alert('Pembayaran berhasil ditambahkan!'); window.location.href='pembayaran.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal menambahkan pembayaran: " . addslashes($conn->error) . "'); window.history.back();</script>";
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pembayaran - SI-RERE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <aside class="sidebar">
            <div class="logo-container">
                <div class="logo-title">SI-RERE</div>
                <img src="../assets/image/LOGO_PUTIH.png" alt="logo" class="logo-img">
            </div>
            <nav class="sidebar-menu">
                <ul>
                    <li><a href="../dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="menu.php"><i class="fas fa-book-open"></i> Menu</a></li>
                    <li><a href="pelanggan.php"><i class="fas fa-users"></i> Pelanggan</a></li>
                    <li><a href="pesanan.php"><i class="fas fa-file-alt"></i> Pesanan</a></li>
                    <li><a href="pembayaran.php" class="active"><i class="fas fa-dollar-sign"></i> Pembayaran</a></li>
                    <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <div class="main-header">
                <h2 class="main-title">Tambah Pembayaran</h2>
            </div>

            <div class="form-container">
                <form method="POST">
                    <label>ID Pesanan</label>
                    <input type="text" name="id_pesanan" value="<?php echo $pesanan['id_pesanan']; ?>" disabled>

                    <label>Total Harga Pesanan</label>
                    <input type="text" value="Rp<?php echo number_format($totalHarga, 0, ',', '.'); ?>" disabled>

                    <label>Status Pembayaran</label>
                    <select name="status_pembayaran" required>
                        <option value="lunas">Lunas</option>
                        <option value="belum_lunas">Belum Lunas</option>
                    </select>

                    <label>Metode Pembayaran</label>
                    <select name="metode_pembayaran" required>
                        <option value="debit">Debit</option>
                        <option value="cash">Cash</option>
                        <option value="kredit">Kredit</option>
                        <option value="qr">QR</option>
                    </select>

                    <button type="submit" class="btn-primary">Tambah Pembayaran</button>
                    <a href="pembayaran.php" class="btn-secondary">Batal</a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
