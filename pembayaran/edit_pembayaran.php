<?php
    require_once "../assets/API/config.php";
    session_start();

    // Cek login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit;
    }

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id_pesanan = (int) $_GET['id'];

        // Ambil data pembayaran berdasarkan id_pesanan
        $qPembayaran = $conn->query("SELECT * FROM data_pembayaran WHERE id_pesanan = $id_pesanan LIMIT 1");
        $pembayaran  = $qPembayaran->fetch_assoc();

        if (! $pembayaran) {
            echo "<script>alert('Pembayaran tidak ditemukan!'); window.location.href='pembayaran.php';</script>";
            exit;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $status_pembayaran = $_POST['status_pembayaran'];
        $metode_pembayaran = $_POST['metode_pembayaran'];

        // Update status pembayaran dan metode pembayaran
        $stmt = $conn->prepare("UPDATE data_pembayaran SET status_pembayaran = ?, metode_pembayaran = ? WHERE id_pesanan = ?");
        $stmt->bind_param("ssi", $status_pembayaran, $metode_pembayaran, $id_pesanan);
        $stmt->execute();

        header("Location: pembayaran.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Pembayaran - SI-RERE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
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
                    <li><a href="pelanggan.php"><i class="fas fa-users"></i> Pelanggan</a></li>
                    <li><a href="pesanan.php"><i class="fas fa-file-alt"></i> Pesanan</a></li>
                    <li><a href="pembayaran.php" class="active"><i class="fas fa-dollar-sign"></i> Pembayaran</a></li>
                    <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="main-header">
                <h2 class="main-title">Edit Pembayaran</h2>
            </div>

            <div class="form-container">
                <form method="POST">
                    <label>ID Pesanan</label>
                    <input type="text" name="id_pesanan" value="<?php echo $pembayaran['id_pesanan']; ?>" disabled>

                    <label>Status Pembayaran</label>
                    <select name="status_pembayaran" required>
                        <option value="lunas"                                                                                           <?php echo $pembayaran['status_pembayaran'] == 'lunas' ? 'selected' : ''; ?>>Lunas</option>
                        <option value="belum_lunas"                                                                                                       <?php echo $pembayaran['status_pembayaran'] == 'belum_lunas' ? 'selected' : ''; ?>>Belum Lunas</option>
                    </select>

                    <label>Metode Pembayaran</label>
                    <select name="metode_pembayaran" required>
                        <option value="debit"                                                                                           <?php echo $pembayaran['metode_pembayaran'] == 'debit' ? 'selected' : ''; ?>>Debit</option>
                        <option value="cash"                                                                                         <?php echo $pembayaran['metode_pembayaran'] == 'cash' ? 'selected' : ''; ?>>Cash</option>
                        <option value="kredit"                                                                                             <?php echo $pembayaran['metode_pembayaran'] == 'kredit' ? 'selected' : ''; ?>>Kredit</option>
                        <option value="qr"                                                                                     <?php echo $pembayaran['metode_pembayaran'] == 'qr' ? 'selected' : ''; ?>>QR</option>
                    </select>

                    <button type="submit" class="btn-primary">Update Pembayaran</button>
                    <a href="pembayaran.php" class="btn-secondary">Batal</a>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
