-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2020 年 8 月 27 日 10:42
-- サーバのバージョン： 5.5.62
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `codecamp35870`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `soft_stock`
--

CREATE TABLE `soft_stock` (
  `soft_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `update_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `soft_stock`
--

INSERT INTO `soft_stock` (`soft_id`, `stock`, `create_datetime`, `update_datetime`) VALUES
(4, 80, '2020-06-29 22:08:16', '2020-08-10 16:58:09'),
(5, 84, '2020-07-06 20:27:22', '2020-08-10 16:57:08'),
(6, 197, '2020-07-13 20:53:12', '2020-08-21 17:15:19'),
(7, 99, '2020-08-11 13:31:43', '2020-08-21 17:09:56'),
(8, 199, '2020-08-11 13:32:26', '2020-08-21 17:09:56'),
(9, 100, '2020-08-11 14:04:37', '2020-08-11 14:04:37'),
(10, 197, '2020-08-11 14:12:35', '2020-08-21 17:15:19'),
(12, 200, '2020-08-11 14:15:02', '2020-08-11 14:15:02'),
(13, 200, '2020-08-11 15:09:55', '2020-08-11 15:09:55'),
(14, 100, '2020-08-16 15:14:28', '2020-08-16 15:14:28'),
(15, 199, '2020-08-16 15:16:08', '2020-08-21 17:15:19'),
(16, 500, '2020-08-19 23:11:03', '2020-08-19 23:11:03'),
(17, 200, '2020-08-20 20:48:42', '2020-08-20 20:48:42'),
(18, 200, '2020-08-20 20:52:05', '2020-08-20 20:52:05'),
(19, 200, '2020-08-21 17:44:29', '2020-08-21 17:44:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `soft_stock`
--
ALTER TABLE `soft_stock`
  ADD PRIMARY KEY (`soft_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `soft_stock`
--
ALTER TABLE `soft_stock`
  MODIFY `soft_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
