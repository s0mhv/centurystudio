-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2024 at 04:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `magang_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `komentar` text DEFAULT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `nama`, `komentar`, `waktu`) VALUES
(21, 'Yonias', 'Proses perekrutan untuk program magang masih kurang transparan, sebaiknya ada penjelasan yang lebih rinci mengenai tahapan dan kriteria yang digunakan.', '2024-10-30 14:05:27'),
(22, 'Raihan', 'Saya merasa fasilitas yang disediakan selama magang kurang memadai, sehingga akan lebih baik jika UPN dapat menyediakan sarana yang lebih lengkap untuk mendukung kegiatan tersebut.', '2024-10-30 14:07:59'),
(23, 'Adib', 'Kualitas bimbingan dari mentor tidak konsisten, sehingga pelatihan bagi mentor sangat diperlukan agar mereka dapat memberikan bimbingan yang lebih baik.', '2024-10-30 14:08:17'),
(24, 'Ari', 'Komunikasi antara pihak kampus dan perusahaan tempat magang perlu ditingkatkan, sehingga informasi mengenai program magang lebih jelas dan tepat waktu.', '2024-10-30 14:08:37'),
(25, 'Yefta', 'Pengalaman magang yang saya dapatkan kurang beragam, jadi UPN sebaiknya menjalin kerja sama dengan lebih banyak perusahaan di berbagai bidang.', '2024-10-30 14:09:00'),
(26, 'Habib', 'Waktu magang yang ditentukan terasa terlalu singkat, sehingga akan lebih baik jika durasi magang diperpanjang agar kami bisa lebih maksimal dalam belajar.', '2024-10-30 14:09:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(4, 'Yonias', 'yonias07'),
(5, 'Raihan', 'raihanjomok'),
(6, 'Ari', 'arijomok'),
(7, 'Adib', 'adibjomok'),
(8, 'Yefta', 'yeftajomok'),
(10, 'Habib', 'habibjomok');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
