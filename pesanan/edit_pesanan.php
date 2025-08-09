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

        // Ambil data pesanan berdasarkan id_pesanan
        $qPesanan = $conn->query("SELECT * FROM pesanan WHERE id_pesanan = $id_pesanan LIMIT 1");
        $pesanan  = $qPesanan->fetch_assoc();

        if (! $pesanan) {
            echo "<script>alert('Pesanan tidak ditemukan!'); window.location.href='pesanan.php';</script>";
            exit;
        }

        // Ambil data item pesanan yang sudah ada
        $qOrderItems = $conn->query("SELECT oi.id_menu, oi.qty, m.nama_menu, m.harga
                                 FROM order_items oi
                                 JOIN menu m ON oi.id_menu = m.id_menu
                                 WHERE oi.id_pesanan = $id_pesanan");

        $order_items = [];
        while ($row = $qOrderItems->fetch_assoc()) {
            $order_items[$row['id_menu']] = ['qty' => $row['qty'], 'nama_menu' => $row['nama_menu'], 'harga' => $row['harga']];
        }

        // Ambil daftar menu yang tersedia untuk ditambahkan ke pesanan
        $qMenu = $conn->query("SELECT id_menu, nama_menu, harga FROM menu WHERE status = 'tersedia'");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $status_pesanan = $_POST['status_pesanan'];
        $quantities     = $_POST['quantities'];

        // Update status pesanan
        $stmt = $conn->prepare("UPDATE pesanan SET status_pesanan = ? WHERE id_pesanan = ?");
        $stmt->bind_param("si", $status_pesanan, $id_pesanan);
        $stmt->execute();

        // Update item pesanan yang sudah ada
        foreach ($_POST['items'] as $item_id => $is_checked) {
            if (isset($quantities[$item_id]) && $quantities[$item_id] > 0) {
                $qty           = (int) $quantities[$item_id];
                $orderItemStmt = $conn->prepare("UPDATE order_items SET qty = ? WHERE id_pesanan = ? AND id_menu = ?");
                $orderItemStmt->bind_param("iii", $qty, $id_pesanan, $item_id);
                $orderItemStmt->execute();
            }
        }

        // Menambahkan item baru yang dipilih ke pesanan
        foreach ($_POST['new_items'] as $new_item_id => $new_is_checked) {
            if ($new_is_checked && isset($quantities['new_' . $new_item_id]) && $quantities['new_' . $new_item_id] > 0) {
                $new_qty      = (int) $_POST['quantities']['new_' . $new_item_id];
                $menuQuery    = $conn->query("SELECT harga FROM menu WHERE id_menu = $new_item_id");
                $menu         = $menuQuery->fetch_assoc();
                $harga_satuan = $menu['harga'];

                // Insert item baru ke order_items
                $newOrderItemStmt = $conn->prepare("INSERT INTO order_items (id_pesanan, id_menu, qty, harga_satuan) VALUES (?, ?, ?, ?)");
                $newOrderItemStmt->bind_param("iiid", $id_pesanan, $new_item_id, $new_qty, $harga_satuan);
                $newOrderItemStmt->execute();
            }
        }

        header("Location: pesanan.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Pesanan - SI-RERE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .form-container {
            background: #222;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            max-width: 600px;
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

        .form-container .items-container {
            margin-bottom: 1.5rem;
        }

        .form-container .items-container div {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .form-container .items-container input[type="number"] {
            width: 80px;
        }

        .form-container .items-container input[type="checkbox"] {
            margin-right: 10px;
        }

        .form-container .new-items-container {
            margin-top: 2rem;
        }

        .form-container .new-items-container label {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #fff;
        }

        /* Hidden styles for items that have already been ordered */
        .form-container .items-container .ordered-item {
            display: block;
        }

        .form-container .new-items-container .ordered-item {
            display: none;
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
                <h2 class="main-title">Edit Pesanan</h2>
            </div>

            <div class="form-container">
                <form method="POST">
                    <label>Status Pesanan</label>
                    <select name="status_pesanan" required>
                        <option value="baru"                                             <?php echo $pesanan['status_pesanan'] == 'baru' ? 'selected' : ''; ?>>Baru</option>
                        <option value="diproses"                                                 <?php echo $pesanan['status_pesanan'] == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                        <option value="selesai"                                                <?php echo $pesanan['status_pesanan'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                        <option value="dibatalkan"                                                   <?php echo $pesanan['status_pesanan'] == 'dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                    </select>

                    <label>Items Pesanan</label>
                    <div class="items-container">
                        <?php foreach ($order_items as $item_id => $item): ?>
                            <div class="ordered-item">
                                <input type="checkbox" name="items[<?php echo $item_id; ?>]" value="1" checked>
                                <?php echo $item['nama_menu']; ?> - Rp<?php echo number_format($item['harga'], 0, ',', '.'); ?>
                                <input type="number" name="quantities[<?php echo $item_id; ?>]" value="<?php echo $item['qty']; ?>" min="1">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="new-items-container">
                        <label>Tambah Menu Baru</label>
                        <?php while ($menu = $qMenu->fetch_assoc()): ?>
                            <div>
                                <input type="checkbox" name="new_items[<?php echo $menu['id_menu']; ?>]" value="1">
                                <?php echo $menu['nama_menu']; ?> - Rp<?php echo number_format($menu['harga'], 0, ',', '.'); ?>
                                <input type="number" name="quantities[new_<?php echo $menu['id_menu']; ?>]" placeholder="Jumlah" min="1" style="width: 100px;">
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <button type="submit" class="btn-primary">Update</button>
                    <a href="pesanan.php" class="btn-secondary">Batal</a>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
