<?php
    session_start();
    require_once "../assets/API/config.php"; // koneksi DB

    // Cek login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit();
    }

    // Ambil data pelanggan aktif
    $qPelanggan = $conn->query("SELECT id_pelanggan, nama_pelanggan, jumlah_orang, waktu_kedatangan
                           FROM pelanggan
                           ORDER BY id_pelanggan DESC");

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan - SI-RERE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
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
                    <li><a href="../meja/meja.php"><i class="fas fa-chair"></i> Ketersediaan Meja</a></li>
                    <li><a href="../pelanggan/pelanggan.php"><i class="fas fa-person"></i> Pelanggan</a></li>
                    <li><a href="../pesanan/pesanan.php"><i class="fas fa-file-alt"></i> Pesanan</a></li>
                    <!-- <li><a href="../pembayaran/pembayaran.php"><i class="fas fa-dollar-sign"></i> Pembayaran</a></li> -->
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="main-header">
                <h2 class="main-title">Data Pelanggan</h2>
                <a href="tambah.php" class="btn-tambah"><i class="fas fa-plus"></i> Tambah Pelanggan</a>
            </div>

            <!-- Pelanggan Aktif -->
            <h3>Pelanggan</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pelanggan</th>
                            <th>Jumlah Orang</th>
                            <th>Waktu Kedatangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($qPelanggan->num_rows > 0): ?>
<?php while ($row = $qPelanggan->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id_pelanggan']; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_pelanggan']); ?></td>
                                    <td><?php echo $row['jumlah_orang']; ?></td>
                                    <td><?php echo date("d-m-Y H:i", strtotime($row['waktu_kedatangan'])); ?></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $row['id_pelanggan'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="hapus.php?id=<?php echo $row['id_pelanggan'] ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
<?php else: ?>
                            <tr><td colspan="5" style="text-align:center; color:#bbb;">Tidak ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="footer">&copy; 2025 SI-RERE Restaurant Management System</div>
        </main>
    </div>
</body>

</html>
