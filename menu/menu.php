<?php
    session_start();
    require_once "../assets/API/config.php"; // koneksi DB

    // Cek login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit();
    }

    // Ambil data menu aktif
    $qMenu = $conn->query("SELECT id_menu, nama_menu, kategori, harga
                       FROM menu
                       WHERE status = 'tersedia'
                       ORDER BY id_menu DESC");

    // Ambil data menu nonaktif
    $qMenuNonaktif = $conn->query("SELECT id_menu, nama_menu, kategori, harga
                               FROM menu
                               WHERE status = 'tidak aktif'
                               ORDER BY id_menu DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Menu - SI-RERE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .btn-edit { background-color: #facc15; color: #000; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .btn-edit:hover { background-color: #eab308; }
        .btn-hapus { background-color: #ef4444; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .btn-hapus:hover { background-color: #dc2626; }
        .btn-tambah { background-color: #3b82f6; color: white; padding: 8px 14px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .btn-tambah:hover { background-color: #2563eb; }
        .btn-aktifkan { background-color: #22c55e; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .btn-aktifkan:hover { background-color: #16a34a; }
        h3 { margin-top: 40px; }
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
                    <li><a href="../pesanan/pesanan.php"><i class="fas fa-file-alt"></i> Pesanan</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="main-header">
            <h2 class="main-title">Data Menu</h2>
            <a href="tambah.php" class="btn-tambah"><i class="fas fa-plus"></i> Tambah Menu</a>
        </div>

        <!-- Menu Aktif -->
        <h3>Menu Aktif</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($qMenu->num_rows > 0): ?>
<?php while ($row = $qMenu->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_menu']; ?></td>
                            <td><?php echo htmlspecialchars($row['nama_menu']); ?></td>
                            <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                            <td>Rp                                                                     <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id_menu'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                <a href="hapus.php?id=<?php echo $row['id_menu'] ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus menu ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
<?php else: ?>
                    <tr><td colspan="5" style="text-align:center; color:#bbb;">Tidak ada data</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Menu Nonaktif -->
        <h3>Menu Nonaktif</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($qMenuNonaktif->num_rows > 0): ?>
<?php while ($row = $qMenuNonaktif->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_menu']; ?></td>
                            <td><?php echo htmlspecialchars($row['nama_menu']); ?></td>
                            <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                            <td>Rp                                                                     <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td>
                                <a href="aktifkan.php?id=<?php echo $row['id_menu'] ?>" class="btn-aktifkan" onclick="return confirm('Aktifkan kembali menu ini?')">Aktifkan</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
<?php else: ?>
                    <tr><td colspan="5" style="text-align:center; color:#bbb;">Tidak ada menu nonaktif</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="footer">&copy; 2025 SI-RERE Restaurant Management System</div>
    </main>
</div>
</body>
</html>
