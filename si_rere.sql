-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Agu 2025 pada 10.12
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `si_rere`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bahan_baku`
--

CREATE TABLE `bahan_baku` (
  `id_bahan` int(11) NOT NULL,
  `nama_bahan` varchar(150) NOT NULL,
  `stok` decimal(12,2) DEFAULT 0.00,
  `satuan` varchar(32) DEFAULT 'pcs',
  `harga_satuan` decimal(12,2) DEFAULT 0.00,
  `last_update` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_pembayaran`
--

CREATE TABLE `data_pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pesanan` int(11) DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `tanggal_pembayaran` datetime DEFAULT current_timestamp(),
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `total_harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status_pembayaran` enum('pending','lunas','gagal','dikembalikan') DEFAULT 'pending',
  `id_pegawai` int(11) DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_keuangan`
--

CREATE TABLE `laporan_keuangan` (
  `id_laporan` int(11) NOT NULL,
  `periode_start` date DEFAULT NULL,
  `periode_end` date DEFAULT NULL,
  `total_pendapatan` decimal(14,2) DEFAULT 0.00,
  `dibuat_oleh` int(11) DEFAULT NULL,
  `id_pembayaran` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `meja`
--

CREATE TABLE `meja` (
  `no_meja` varchar(20) NOT NULL,
  `status_meja` enum('tersedia','terisi','rusak') DEFAULT 'tersedia',
  `kapasitas` int(11) DEFAULT 4,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `meja`
--

INSERT INTO `meja` (`no_meja`, `status_meja`, `kapasitas`, `keterangan`) VALUES
('M1', 'tersedia', 3, 'untuk 3 orang'),
('M2', 'tersedia', 5, 'untuk keluarga pak junaidi 5 orang'),
('M3', 'tersedia', 8, 'untuk 8 orang'),
('M4', 'rusak', 4, 'meja sedang rusak'),
('M5', 'terisi', 6, 'booking');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(200) NOT NULL,
  `kategori` enum('Makanan','Minuman') DEFAULT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('tersedia','habis','tidak aktif') DEFAULT 'tersedia',
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `kategori`, `harga`, `status`, `deskripsi`, `created_at`) VALUES
(5, 'Mie Goreng', 'Makanan', 150000.00, 'tersedia', NULL, '2025-08-08 19:44:50'),
(6, 'lele goreng', 'Makanan', 12000.00, 'tersedia', NULL, '2025-08-08 19:54:08'),
(7, 'jus sirsak', 'Minuman', 7000.00, 'tidak aktif', NULL, '2025-08-08 19:54:16'),
(8, 'Midok2', 'Makanan', 12000.00, 'tersedia', NULL, '2025-08-09 09:05:09'),
(9, 'Nasi Kucing', 'Makanan', 5000.00, 'tersedia', NULL, '2025-08-09 14:34:07'),
(10, 'Jus Mangga', 'Minuman', 8000.00, 'tersedia', NULL, '2025-08-09 14:34:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id_item` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) GENERATED ALWAYS AS (`qty` * `harga_satuan`) STORED,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id_item`, `id_pesanan`, `id_menu`, `qty`, `harga_satuan`, `keterangan`) VALUES
(2, 7, 5, 2, 150000.00, NULL),
(3, 7, 6, 1, 12000.00, NULL),
(4, 7, 8, 2, 12000.00, NULL),
(5, 7, 9, 3, 5000.00, NULL),
(6, 7, 10, 3, 8000.00, NULL),
(7, 6, 5, 2, 150000.00, NULL),
(8, 6, 6, 2, 12000.00, NULL),
(9, 6, 10, 1, 8000.00, NULL),
(10, 5, 8, 1, 12000.00, NULL),
(11, 5, 9, 1, 5000.00, NULL),
(12, 5, 10, 1, 8000.00, NULL),
(13, 4, 5, 3, 150000.00, NULL),
(14, 4, 8, 3, 12000.00, NULL),
(15, 4, 9, 1, 5000.00, NULL),
(18, 9, 5, 1, 150000.00, NULL),
(19, 9, 10, 1, 8000.00, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `nama_pegawai` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telepon` varchar(32) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `id_role` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama_pelanggan` varchar(150) NOT NULL,
  `jumlah_orang` int(11) DEFAULT 1,
  `waktu_kedatangan` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `jumlah_orang`, `waktu_kedatangan`, `created_at`) VALUES
(5, 'agus', 5, '2025-08-08 19:42:00', '2025-08-08 19:42:08'),
(6, 'asep pake z', 5, '2025-08-08 19:42:00', '2025-08-08 19:42:27'),
(7, 'DeanKT', 1, '2025-08-09 14:39:00', '2025-08-09 14:39:52'),
(8, 'Hamdan', 10, '2025-08-11 14:40:00', '2025-08-09 14:40:12'),
(9, 'Sisil', 5, '2025-08-16 14:41:00', '2025-08-09 14:41:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `no_meja` varchar(20) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `status_pesanan` enum('baru','diproses','selesai','dibatalkan') DEFAULT 'baru',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_pelanggan`, `no_meja`, `catatan`, `status_pesanan`, `created_at`, `updated_at`) VALUES
(4, 9, 'M3', NULL, 'diproses', '2025-08-09 14:43:16', '2025-08-09 14:43:16'),
(5, 7, 'M1', NULL, 'baru', '2025-08-09 14:43:38', '2025-08-09 14:43:38'),
(6, 5, 'M2', NULL, 'baru', '2025-08-09 14:43:58', '2025-08-09 14:43:58'),
(7, 6, 'M3', NULL, 'dibatalkan', '2025-08-09 14:44:26', '2025-08-09 14:44:26'),
(9, 7, 'M2', NULL, 'selesai', '2025-08-09 14:52:30', '2025-08-09 14:52:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `nama_role` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bahan_baku`
--
ALTER TABLE `bahan_baku`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indeks untuk tabel `data_pembayaran`
--
ALTER TABLE `data_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_pegawai` (`id_pegawai`),
  ADD KEY `idx_pembayaran_tanggal` (`tanggal_pembayaran`);

--
-- Indeks untuk tabel `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `dibuat_oleh` (`dibuat_oleh`),
  ADD KEY `id_pembayaran` (`id_pembayaran`);

--
-- Indeks untuk tabel `meja`
--
ALTER TABLE `meja`
  ADD PRIMARY KEY (`no_meja`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD KEY `id_role` (`id_role`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `no_meja` (`no_meja`),
  ADD KEY `idx_pesanan_status` (`status_pesanan`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bahan_baku`
--
ALTER TABLE `bahan_baku`
  MODIFY `id_bahan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_pembayaran`
--
ALTER TABLE `data_pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `data_pembayaran`
--
ALTER TABLE `data_pembayaran`
  ADD CONSTRAINT `data_pembayaran_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `data_pembayaran_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `data_pembayaran_ibfk_3` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  ADD CONSTRAINT `laporan_keuangan_ibfk_1` FOREIGN KEY (`dibuat_oleh`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `laporan_keuangan_ibfk_2` FOREIGN KEY (`id_pembayaran`) REFERENCES `data_pembayaran` (`id_pembayaran`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `pegawai_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`no_meja`) REFERENCES `meja` (`no_meja`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
