<?php
    session_start();
    require_once "../assets/API/config.php"; // Koneksi DB

    // Cek login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit();
    }

    // Ambil data pesanan dan item-menu yang dipesan
    $qPesanan = $conn->query("SELECT p.id_pesanan, p.id_pelanggan, p.no_meja, p.status_pesanan, p.created_at,
                                     GROUP_CONCAT(m.nama_menu SEPARATOR ', ') AS menu_dipesan
                            FROM pesanan p
                            LEFT JOIN order_items oi ON p.id_pesanan = oi.id_pesanan
                            LEFT JOIN menu m ON oi.id_menu = m.id_menu
                            GROUP BY p.id_pesanan
                            ORDER BY p.id_pesanan DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Pesanan - SI-RERE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* Styling untuk tombol dan tabel */
        .btn-edit {
            background-color: #facc15;
            color: #000;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-edit:hover {
            background-color: #eab308;
        }

        .btn-hapus {
            background-color: #ef4444;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-hapus:hover {
            background-color: #dc2626;
        }

        .btn-tambah {
            background-color: #3b82f6;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-tambah:hover {
            background-color: #2563eb;
        }

        h3 {
            margin-top: 40px;
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
                    <li><a href="pesanan.php"><i class="fas fa-file-alt"></i> Pesanan</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="main-header">
                <h2 class="main-title">Data Pesanan</h2>
                <a href="tambah_pesanan.php" class="btn-tambah"><i class="fas fa-plus"></i> Tambah Pesanan</a>
            </div>

            <!-- Pesanan -->
            <h3>Pesanan</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Pelanggan</th>
                            <th>No Meja</th>
                            <th>Status</th>
                            <th>Menu yang Dipesan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($qPesanan->num_rows > 0): ?>
<?php while ($row = $qPesanan->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id_pesanan']; ?></td>
                                    <td><?php echo $row['id_pelanggan']; ?></td>
                                    <td><?php echo $row['no_meja']; ?></td>
                                    <td><?php echo $row['status_pesanan']; ?></td>
                                    <td><?php echo htmlspecialchars($row['menu_dipesan']); ?></td>
                                    <td><?php echo date("d-m-Y H:i", strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <a href="edit_pesanan.php?id=<?php echo $row['id_pesanan'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="hapus_pesanan.php?id=<?php echo $row['id_pesanan'] ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
<?php else: ?>
                            <tr><td colspan="7" style="text-align:center; color:#bbb;">Tidak ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="footer">&copy; 2025 SI-RERE Restaurant Management System</div>
        </main>
    </div>
</body>

</html>
