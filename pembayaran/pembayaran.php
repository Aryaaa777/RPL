<?php
    require_once "../assets/API/config.php";
    session_start();

    // Cek login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit;
    }

    // Ambil data pembayaran yang statusnya 'selesai' dan sudah ada di tabel data_pembayaran
    $qPembayaran = $conn->query("SELECT p.id_pesanan, p.no_meja, p.status_pesanan, p.created_at,
                             SUM(oi.qty * oi.harga_satuan) AS total_harga, dp.status_pembayaran, dp.metode_pembayaran
                             FROM pesanan p
                             LEFT JOIN order_items oi ON p.id_pesanan = oi.id_pesanan
                             LEFT JOIN data_pembayaran dp ON p.id_pesanan = dp.id_pesanan
                             WHERE p.status_pesanan = 'selesai' AND dp.status_pembayaran IS NOT NULL
                             GROUP BY p.id_pesanan
                             ORDER BY p.created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pembayaran - SI-RERE</title>
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
                    <li><a href="../index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="../menu/menu.php"><i class="fas fa-book-open"></i> Menu</a></li>
                    <li><a href="../pelanggan/pelanggan.php"><i class="fas fa-users"></i> Pelanggan</a></li>
                    <li><a href="../pesanan/pesanan.php"><i class="fas fa-file-alt"></i> Pesanan</a></li>
                    <li><a href="../pembayaran/pembayaran.php" class="active"><i class="fas fa-dollar-sign"></i> Pembayaran</a></li>
                    <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="main-header">
                <h2 class="main-title">Pembayaran</h2>
                <a href="tambah_pembayaran.php?id=<?php echo $row['id_pesanan']; ?>" class="btn-edit">Bayar</a>

            </div>

            <h3>Pembayaran Pesanan Selesai</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>No Meja</th>
                            <th>Total Harga</th>
                            <th>Tanggal</th>
                            <th>Status Pembayaran</th>
                            <th>Metode Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($qPembayaran->num_rows > 0): ?>
<?php while ($row = $qPembayaran->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id_pesanan']; ?></td>
                                    <td><?php echo $row['no_meja']; ?></td>
                                    <td>Rp<?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                    <td><?php echo date("d-m-Y H:i", strtotime($row['created_at'])); ?></td>
                                    <td><?php echo $row['status_pembayaran'] == 'lunas' ? 'Lunas' : 'Belum Lunas'; ?></td>
                                    <td><?php echo $row['metode_pembayaran']; ?></td>
                                    <td>
                                        <a href="edit_pembayaran.php?id=<?php echo $row['id_pesanan']; ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="hapus_pembayaran.php?id=<?php echo $row['id_pesanan']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus pembayaran ini?')"><i class="fas fa-trash-alt"></i> Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
<?php else: ?>
                            <tr><td colspan="7" style="text-align:center; color:#bbb;">Tidak ada data pembayaran yang perlu diedit</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>
