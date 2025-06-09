-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Jun 2025 pada 03.52
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
-- Database: `sinarumischoolmli`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `absensi_id` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `murid_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `guru_id` int(11) NOT NULL,
  `guru_nama` varchar(255) NOT NULL,
  `guru_username` varchar(255) DEFAULT NULL,
  `guru_password` varchar(255) NOT NULL DEFAULT 'changeme',
  `guru_role` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`guru_id`, `guru_nama`, `guru_username`, `guru_password`, `guru_role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'Lidya Nur Aprillia', 'lidya', 'changeme', 1, '2025-06-04 23:40:26', '2025-06-04 23:41:35', NULL),
(3, 'M. Taufik Aji', 'dupik', '$2y$10$zrN9w4tEkEVQ2XdjjLGjnenj8ySHbTO9hRWRpiS4s.6EXoLtG6jCu', 1, '2025-06-04 23:46:27', '2025-06-08 08:51:20', NULL),
(8, 'Aurelia Putri Widodo, S.Pd', 'pingping', '$2y$10$hvebsaiA5/LHSMHOx6y1w.Prri2B/58o/c1q6fcTlF2cb4OWgsZvm', 1, '2025-06-08 07:12:36', '2025-06-08 07:39:13', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hari`
--

CREATE TABLE `hari` (
  `hari_id` int(11) NOT NULL,
  `ta_id` int(11) DEFAULT NULL,
  `hari_tgl` date DEFAULT NULL,
  `hari_keterangan` varchar(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hari`
--

INSERT INTO `hari` (`hari_id`, `ta_id`, `hari_tgl`, `hari_keterangan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '2025-06-08', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil`
--

CREATE TABLE `hasil` (
  `id` int(11) NOT NULL,
  `teacher_id` varchar(255) DEFAULT NULL,
  `fase_id` int(11) DEFAULT NULL,
  `tujuan_agama_1` int(11) DEFAULT NULL,
  `tujuan_jati_1` int(11) DEFAULT NULL,
  `tujuan_literasi_1` int(11) DEFAULT NULL,
  `tujuan_agama_2` int(11) DEFAULT NULL,
  `tujuan_jati_2` int(11) DEFAULT NULL,
  `tujuan_literasi_2` int(11) DEFAULT NULL,
  `id_peta` int(11) DEFAULT NULL,
  `alat` text DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `minggu` int(11) DEFAULT NULL,
  `tanggal` int(11) DEFAULT NULL,
  `bulan` varchar(255) DEFAULT NULL,
  `tahun` int(11) DEFAULT NULL,
  `topik_id` int(11) DEFAULT NULL,
  `subtopik` int(11) DEFAULT NULL,
  `b` text DEFAULT NULL,
  `c` text DEFAULT NULL,
  `d` text DEFAULT NULL,
  `d2` text DEFAULT NULL,
  `d3` text DEFAULT NULL,
  `d4` text DEFAULT NULL,
  `d5` text DEFAULT NULL,
  `e` text DEFAULT NULL,
  `f` text DEFAULT NULL,
  `g` text DEFAULT NULL,
  `tanggal2` int(11) DEFAULT NULL,
  `bulan2` varchar(255) DEFAULT NULL,
  `tahun2` int(11) DEFAULT NULL,
  `tanggal3` int(11) DEFAULT NULL,
  `bulan3` varchar(255) DEFAULT NULL,
  `tahun3` int(11) DEFAULT NULL,
  `tanggal4` int(11) DEFAULT NULL,
  `bulan4` varchar(255) DEFAULT NULL,
  `tahun4` int(11) DEFAULT NULL,
  `tanggal5` int(11) DEFAULT NULL,
  `bulan5` varchar(255) DEFAULT NULL,
  `tahun5` int(11) DEFAULT NULL,
  `konteks1` text DEFAULT NULL,
  `konteks2` text DEFAULT NULL,
  `konteks3` text DEFAULT NULL,
  `konteks4` text DEFAULT NULL,
  `konteks5` text DEFAULT NULL,
  `konteks6` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelompok`
--

CREATE TABLE `kelompok` (
  `kelompok_id` int(11) NOT NULL,
  `kelompok_nama` varchar(255) DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelompok`
--

INSERT INTO `kelompok` (`kelompok_id`, `kelompok_nama`, `deskripsi`, `guru_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'P3A', '8-9 tahun', 8, '2025-06-04 00:13:01', '2025-06-04 22:58:42', NULL),
(2, 'P3B', '', 2, '2025-06-04 18:26:48', '2025-06-04 23:42:57', NULL),
(3, 'P3C', '', 3, '2025-06-04 18:32:15', '2025-06-04 23:46:38', NULL),
(4, 'P3D', 'desc', NULL, '2025-06-04 22:56:33', '2025-06-04 22:56:33', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `modul`
--

CREATE TABLE `modul` (
  `modul_id` int(11) NOT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `kelompok_id` int(11) DEFAULT NULL,
  `jumlah_anak` int(11) DEFAULT NULL,
  `petakonsep_id` int(11) DEFAULT NULL,
  `subjek_1` int(11) DEFAULT NULL,
  `subjek_2` int(11) DEFAULT NULL,
  `subjek_3` int(11) DEFAULT NULL,
  `subjek_4` int(11) DEFAULT NULL,
  `subjek_5` int(11) DEFAULT NULL,
  `subjek_6` int(11) DEFAULT NULL,
  `alat` text DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `minggu` int(11) DEFAULT NULL,
  `topik_id` int(11) DEFAULT NULL,
  `subtopik_id` int(11) DEFAULT NULL,
  `b` text DEFAULT NULL,
  `c` text DEFAULT NULL,
  `d1` text DEFAULT NULL,
  `d1tanggal` date DEFAULT NULL,
  `d2` text DEFAULT NULL,
  `d2tanggal` date DEFAULT NULL,
  `d3` text DEFAULT NULL,
  `d3tanggal` date DEFAULT NULL,
  `d4` text DEFAULT NULL,
  `d4tanggal` date DEFAULT NULL,
  `d5` text DEFAULT NULL,
  `d5tanggal` date DEFAULT NULL,
  `e` text DEFAULT NULL,
  `f` text DEFAULT NULL,
  `g` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `murid`
--

CREATE TABLE `murid` (
  `murid_id` int(11) NOT NULL,
  `murid_nama` varchar(255) DEFAULT NULL,
  `kelompok_id` varchar(5) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `murid`
--

INSERT INTO `murid` (`murid_id`, `murid_nama`, `kelompok_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'AABIDAH CHOO MING XIA', '1', '2025-06-04 00:14:52', '2025-06-04 21:45:06', NULL),
(2, 'ALESHA HIBATILLAH INARA', '1', '2025-06-04 00:15:00', '2025-06-04 00:15:00', NULL),
(3, 'ALESHA PUTRI INNARA', '1', '2025-06-04 00:15:13', '2025-06-04 00:15:13', NULL),
(4, 'CALEB JEREMY NATANAEL', '1', '2025-06-04 00:15:25', '2025-06-04 00:15:25', NULL),
(5, 'CHENDRAVANGSA VARUNANDITYA', '1', '2025-06-04 00:15:36', '2025-06-04 00:15:36', NULL),
(6, 'CINTA BHAKTI HIDAYAT', '1', '2025-06-04 00:15:43', '2025-06-04 00:15:43', NULL),
(7, 'DASTAN RAHARDJO RIESTANOVA', '1', '2025-06-04 00:15:50', '2025-06-04 00:15:50', NULL),
(8, 'EDISON KRISTOFER NATAWIJAYA', '1', '2025-06-04 00:15:59', '2025-06-04 00:15:59', NULL),
(9, 'EUGENIA DINESHCARA ANANDA', '1', '2025-06-04 00:16:06', '2025-06-04 00:16:06', NULL),
(10, 'ISYANA SARASVATI PRAYASA', '1', '2025-06-04 00:16:12', '2025-06-04 00:16:12', NULL),
(11, 'KANIA NANCY MAHESWARI', '1', '2025-06-04 00:16:20', '2025-06-04 00:16:20', NULL),
(12, 'KATYALUNA HANA MALIQA', '1', '2025-06-04 00:16:26', '2025-06-04 00:16:26', NULL),
(13, 'KINTA MADUSWARA MAHADEWI D. SANTOSO', '1', '2025-06-04 00:16:35', '2025-06-04 00:16:35', NULL),
(14, 'KYRIOS ALFINA JOHANNIS', '1', '2025-06-04 00:16:45', '2025-06-04 00:16:45', NULL),
(15, 'MIKKEL NICOS WILSON', '1', '2025-06-04 00:16:55', '2025-06-04 00:16:55', NULL),
(16, 'NICHOLAS YUKI ARIASATYA', '1', '2025-06-04 00:17:02', '2025-06-04 00:17:02', NULL),
(17, 'PANDE RADJENDRIA SHAILENDRA ARIANTA', '1', '2025-06-04 00:17:09', '2025-06-04 00:17:09', NULL),
(18, 'RAKA DIRGA ADHISETYA', '1', '2025-06-04 00:17:17', '2025-06-04 00:17:17', NULL),
(19, 'SAMUEL FERNANDO TOVANI', '1', '2025-06-04 00:17:23', '2025-06-04 00:17:23', NULL),
(20, 'SAMUEL RENATO DARMAJAYA', '1', '2025-06-04 00:17:28', '2025-06-04 00:17:28', NULL),
(21, 'TIFFANY CALYSTA INDRAYANA', '1', '2025-06-04 00:17:34', '2025-06-04 00:17:34', NULL),
(22, 'VALENTINO NICHOLAS SAPUTRA', '1', '2025-06-04 00:17:39', '2025-06-04 00:17:39', NULL),
(23, 'ABIGAIL NAVYA BELLVANI', '3', '2025-06-09 01:48:36', '2025-06-09 01:48:36', NULL),
(24, 'ABIYYA RAMAZAN', '3', '2025-06-09 01:48:46', '2025-06-09 01:48:46', NULL),
(25, 'AISHA HIJA AZZAHRA', '3', '2025-06-09 01:48:54', '2025-06-09 01:48:54', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `petakonsep`
--

CREATE TABLE `petakonsep` (
  `petakonsep_id` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting`
--

CREATE TABLE `setting` (
  `setting_id` int(11) NOT NULL,
  `sekolah` varchar(255) DEFAULT NULL,
  `kepala` varchar(255) DEFAULT NULL,
  `b` text DEFAULT NULL,
  `c` text DEFAULT NULL,
  `d1` text DEFAULT NULL,
  `d2` text DEFAULT NULL,
  `d3` text DEFAULT NULL,
  `d4` text DEFAULT NULL,
  `d5` text DEFAULT NULL,
  `e` text DEFAULT NULL,
  `f` text DEFAULT NULL,
  `g` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `subjek`
--

CREATE TABLE `subjek` (
  `subjek_id` int(11) NOT NULL,
  `subjek_nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `subtopik`
--

CREATE TABLE `subtopik` (
  `subtopik_id` int(11) NOT NULL,
  `topik_id` int(11) DEFAULT NULL,
  `subtopik_isi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ta`
--

CREATE TABLE `ta` (
  `ta_id` int(11) NOT NULL,
  `ta_label` varchar(25) DEFAULT NULL,
  `ta_mulai` date DEFAULT NULL,
  `ta_akhir` date DEFAULT NULL,
  `ta_status` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ta`
--

INSERT INTO `ta` (`ta_id`, `ta_label`, `ta_mulai`, `ta_akhir`, `ta_status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2025/2026', '2025-07-01', '2026-06-30', 'active', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `topik`
--

CREATE TABLE `topik` (
  `topik_id` int(11) NOT NULL,
  `topik_isi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tujuan`
--

CREATE TABLE `tujuan` (
  `tujuan_id` int(11) NOT NULL,
  `isi` text DEFAULT NULL,
  `kelompok_id` int(11) DEFAULT NULL,
  `subjek_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`absensi_id`);

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`guru_id`),
  ADD UNIQUE KEY `guru_username` (`guru_username`);

--
-- Indeks untuk tabel `hari`
--
ALTER TABLE `hari`
  ADD PRIMARY KEY (`hari_id`);

--
-- Indeks untuk tabel `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kelompok`
--
ALTER TABLE `kelompok`
  ADD PRIMARY KEY (`kelompok_id`);

--
-- Indeks untuk tabel `modul`
--
ALTER TABLE `modul`
  ADD PRIMARY KEY (`modul_id`);

--
-- Indeks untuk tabel `murid`
--
ALTER TABLE `murid`
  ADD PRIMARY KEY (`murid_id`);

--
-- Indeks untuk tabel `petakonsep`
--
ALTER TABLE `petakonsep`
  ADD PRIMARY KEY (`petakonsep_id`);

--
-- Indeks untuk tabel `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indeks untuk tabel `subjek`
--
ALTER TABLE `subjek`
  ADD PRIMARY KEY (`subjek_id`);

--
-- Indeks untuk tabel `subtopik`
--
ALTER TABLE `subtopik`
  ADD PRIMARY KEY (`subtopik_id`);

--
-- Indeks untuk tabel `ta`
--
ALTER TABLE `ta`
  ADD PRIMARY KEY (`ta_id`);

--
-- Indeks untuk tabel `topik`
--
ALTER TABLE `topik`
  ADD PRIMARY KEY (`topik_id`);

--
-- Indeks untuk tabel `tujuan`
--
ALTER TABLE `tujuan`
  ADD PRIMARY KEY (`tujuan_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `absensi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `guru_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `hari`
--
ALTER TABLE `hari`
  MODIFY `hari_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kelompok`
--
ALTER TABLE `kelompok`
  MODIFY `kelompok_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `modul`
--
ALTER TABLE `modul`
  MODIFY `modul_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `murid`
--
ALTER TABLE `murid`
  MODIFY `murid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `petakonsep`
--
ALTER TABLE `petakonsep`
  MODIFY `petakonsep_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `setting`
--
ALTER TABLE `setting`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `subjek`
--
ALTER TABLE `subjek`
  MODIFY `subjek_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `subtopik`
--
ALTER TABLE `subtopik`
  MODIFY `subtopik_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ta`
--
ALTER TABLE `ta`
  MODIFY `ta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `topik`
--
ALTER TABLE `topik`
  MODIFY `topik_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tujuan`
--
ALTER TABLE `tujuan`
  MODIFY `tujuan_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
