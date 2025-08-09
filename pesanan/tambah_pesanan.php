<?php
    require_once "../assets/API/config.php";
    session_start();

    // Cek login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit;
    }

    // Ambil data pelanggan untuk dropdown
    $qPelanggan = $conn->query("SELECT id_pelanggan, nama_pelanggan FROM pelanggan");

    // Ambil data meja yang tersedia untuk dropdown
    $qMeja = $conn->query("SELECT no_meja, kapasitas FROM meja WHERE status_meja = 'tersedia'");

    // Ambil daftar menu yang tersedia untuk ditambahkan ke pesanan
    $qMenu = $conn->query("SELECT id_menu, nama_menu, harga FROM menu WHERE status = 'tersedia'");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_pelanggan   = (int) $_POST['id_pelanggan'];
        $no_meja        = trim($_POST['no_meja']);
        $status_pesanan = $_POST['status_pesanan'];
        $quantities     = $_POST['quantities']; // Array jumlah yang dipilih

        // Validasi sederhana
        if ($id_pelanggan == "" || $no_meja == "" || $status_pesanan == "") {
            echo "<script>alert('Semua field wajib diisi!'); window.history.back();</script>";
            exit;
        }

        // Insert pesanan ke tabel pesanan
        $stmt = $conn->prepare("INSERT INTO pesanan (id_pelanggan, no_meja, status_pesanan) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id_pelanggan, $no_meja, $status_pesanan);

        if ($stmt->execute()) {
            // Ambil id_pesanan yang baru saja ditambahkan
            $id_pesanan = $conn->insert_id;

            // Insert data item ke tabel order_items
            // Pastikan ada checkbox yang dipilih
            if (isset($_POST['items']) && is_array($_POST['items'])) {
                foreach ($_POST['items'] as $item_id => $is_checked) {
                    if ($is_checked && isset($quantities[$item_id]) && $quantities[$item_id] > 0) {
                        // Ambil harga menu berdasarkan item_id
                        $menuQuery    = $conn->query("SELECT harga FROM menu WHERE id_menu = $item_id");
                        $menu         = $menuQuery->fetch_assoc();
                        $harga_satuan = $menu['harga'];

                        // Ambil jumlah pesanan untuk item ini
                        $qty = (int) $quantities[$item_id];

                        // Insert item pesanan ke order_items
                        $orderItemStmt = $conn->prepare("INSERT INTO order_items (id_pesanan, id_menu, qty, harga_satuan) VALUES (?, ?, ?, ?)");
                        $orderItemStmt->bind_param("iiid", $id_pesanan, $item_id, $qty, $harga_satuan);
                        $orderItemStmt->execute();
                    }
                }
            }

            header("Location: pesanan.php");
            exit;
        } else {
            echo "<script>alert('Gagal menambahkan pesanan: " . addslashes($conn->error) . "'); window.history.back();</script>";
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Pesanan - SI-RERE</title>
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
                    <li><a href="../menu/menu.php"><i class="fas fa-book-open"></i> Menu</a></li>
                    <li><a href="../meja/meja.php"><i class="fas fa-chair"></i> Ketersediaan Meja</a></li>
                    <li><a href="../pelanggan/pelanggan.php"><i class="fas fa-person"></i> Pelanggan</a></li>
                    <li><a href="pesanan.php"><i class="fas fa-file-alt"></i> Pemesanan</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="main-header">
                <h2 class="main-title">Tambah Pesanan</h2>
            </div>

            <div class="form-container">
                <form method="POST">
                    <label>ID Pelanggan</label>
                    <select name="id_pelanggan" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php while ($row = $qPelanggan->fetch_assoc()): ?>
                            <option value="<?php echo $row['id_pelanggan']; ?>"><?php echo htmlspecialchars($row['nama_pelanggan']); ?></option>
                        <?php endwhile; ?>
                    </select>

                    <label>No Meja</label>
                    <select name="no_meja" required>
                        <option value="">-- Pilih Meja --</option>
                        <?php while ($row = $qMeja->fetch_assoc()): ?>
                            <option value="<?php echo $row['no_meja']; ?>"><?php echo $row['no_meja'] . " (Kapasitas: " . $row['kapasitas'] . " orang)"; ?></option>
                        <?php endwhile; ?>
                    </select>

                    <label>Status Pesanan</label>
                    <select name="status_pesanan" required>
                        <option value="baru">Baru</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>

                    <label>Items Pesanan</label>
                    <?php while ($menu = $qMenu->fetch_assoc()): ?>
                        <div>
                            <input type="checkbox" name="items[<?php echo $menu['id_menu']; ?>]" value="1">
                            <?php echo $menu['nama_menu']; ?> - Rp<?php echo number_format($menu['harga'], 0, ',', '.'); ?>
                            <input type="number" name="quantities[<?php echo $menu['id_menu']; ?>]" placeholder="Jumlah" min="1" style="width: 100px;">
                        </div>
                    <?php endwhile; ?>

                    <button type="submit" class="btn-primary">Simpan</button>
                    <a href="pesanan.php" class="btn-secondary">Batal</a>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
