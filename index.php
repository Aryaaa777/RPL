<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>SI-RERE Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo-container">
                <div class="logo-title">SI-RERE</div>
                <img src="assets/image/LOGO_PUTIH.png" alt="logo" class="logo-img">
            </div>

            <nav class="sidebar-menu">
                <ul>
                    <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="menu/menu.php"><i class="fas fa-book-open"></i> Menu</a></li>
                    <li><a href="meja/meja.php"><i class="fas fa-chair"></i> Ketersediaan Meja</a></li>
                    <li><a href="pelanggan/pelanggan.php"><i class="fas fa-person"></i> Pelanggan</a></li>
                    <li><a href="pesanan/pesanan.php"><i class="fas fa-file-alt"></i> Pemesanan</a></li>
                    <!-- <li><a href="pembayaran/pembayaran.php"><i class="fas fa-dollar-sign"></i> Pembayaran</a></li> -->
                </ul>
            </nav>

            <!-- Tombol Logout -->
            <div style="padding: 15px; text-align:center;">
                <a href="logout.php"
                style="display:inline-block; padding:10px 15px; background:#e74c3c; color:#fff; text-decoration:none; border-radius:6px; font-weight:600;">
                <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="main-header">
                <h2 class="main-title">Dashboard Admin</h2>
            </div>

            <!-- Card Statistik -->
            <section class="stats">
                <div class="card">
                    <p>Total Transaksi</p>
                    <h3 id="totalTransaksi">—</h3>
                </div>

                <div class="card">
                    <p>Total Pemesanan</p>
                    <h3 id="totalPemesanan">—</h3>
                </div>

                <div class="card">
                    <p>Total Pendapatan</p>
                    <h3 id="totalPendapatan">Rp —</h3>
                </div>
            </section>

            <div class="divider"></div>

            <!-- Tabel Data -->
            <div class="table-container">
                <h3 class="table-title"><i class="fas fa-history"></i> Riwayat Transaksi Terbaru</h3>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="transaksiTbody">
                        <!-- Rows of transaksi will be rendered here -->
                    </tbody>
                </table>
            </div>

            <div class="footer">&copy; 2025 SI-RERE Restaurant Management System</div>
        </main>
    </div>

    <script src="assets/js/dashboard.js"></script>
</body>
</html>
