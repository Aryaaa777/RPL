<?php
    session_start();
    require_once "../assets/API/config.php";

    // Cek login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit();
    }

    // Ambil semua data meja
    $qMeja = $conn->query("SELECT no_meja, kapasitas, keterangan FROM meja ORDER BY no_meja ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Meja - SI-RERE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .btn-edit { background-color: #facc15; color: #000; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .btn-edit:hover { background-color: #eab308; }
        .btn-hapus { background-color: #ef4444; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .btn-hapus:hover { background-color: #dc2626; }
        .btn-tambah { background-color: #3b82f6; color: white; padding: 8px 14px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .btn-tambah:hover { background-color: #2563eb; }
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
                    <li><a href="meja.php"><i class="fas fa-chair"></i> Ketersediaan Meja</a></li>
                    <li><a href="../pelanggan/pelanggan.php"><i class="fas fa-person"></i> Pelanggan</a></li>
                    <li><a href="../pesanan/pesanan.php"><i class="fas fa-file-alt"></i> Pemesanan</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="main-header">
            <h2 class="main-title">Data Meja</h2>
            <a href="tambah_meja.php" class="btn-tambah"><i class="fas fa-plus"></i> Tambah Meja</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No Meja</th>
                        <th>Kapasitas</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($qMeja->num_rows > 0): ?>
<?php while ($row = $qMeja->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['no_meja']); ?></td>
                            <td><?php echo (int) $row['kapasitas']; ?> orang</td>
                            <td><?php echo htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
                            <td>
                                <a href="edit_meja.php?no_meja=<?php echo urlencode($row['no_meja']); ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                <a href="hapus_meja.php?no_meja=<?php echo urlencode($row['no_meja']); ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus meja ini secara permanen?')"><i class="fas fa-trash"></i> Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
<?php else: ?>
                    <tr><td colspan="4" style="text-align:center; color:#bbb;">Tidak ada meja</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="footer">&copy; 2025 SI-RERE Restaurant Management System</div>
    </main>
</div>
</body>
</html>
