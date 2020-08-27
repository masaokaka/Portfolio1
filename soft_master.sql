-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2020 年 8 月 27 日 09:45
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
-- テーブルの構造 `soft_master`
--

CREATE TABLE `soft_master` (
  `soft_id` int(11) NOT NULL,
  `soft` varchar(255) NOT NULL,
  `genre` int(11) NOT NULL,
  `console` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `img` varchar(1000) NOT NULL,
  `status` int(11) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `update_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `soft_master`
--

INSERT INTO `soft_master` (`soft_id`, `soft`, `genre`, `console`, `price`, `img`, `status`, `create_datetime`, `update_datetime`) VALUES
(4, 'どうぶつの森', 6, 1, 5000, '61e67db17f2fda8fa54a4de64bf658ddad85d28a.jpg', 1, '2020-06-29 22:08:16', '2020-06-29 22:08:16'),
(5, 'ダークソウル3', 0, 0, 4000, '9949ea5d24c04599dddf8af9c845329ad945d422.jpeg', 1, '2020-07-06 20:27:22', '2020-07-06 20:27:22'),
(6, 'コールオブデューティ', 2, 0, 3000, '40f7ca10e58795ddd85395a3368f99af7f422089.jpg', 1, '2020-07-13 20:53:12', '2020-07-13 20:53:12'),
(7, 'Lasr of us 2', 1, 0, 7000, 'd285e38d87e895e3c5737183111094b291955bb5.jpg', 1, '2020-08-11 13:31:43', '2020-08-11 13:31:43'),
(8, 'Ghost of Tsushima', 1, 0, 7000, '5b6563f50c35dd16f0314a15e7aef6b709f4e45e.jpg', 1, '2020-08-11 13:32:26', '2020-08-11 13:32:26'),
(9, 'バイオハザード　RE:2', 5, 0, 6500, '20ef693f45cc988c2dead2a699d3e88e14cfbc92.jpg', 1, '2020-08-11 14:04:37', '2020-08-11 14:04:37'),
(10, 'スプラトゥーン2', 2, 1, 4000, '1814d746a80cb03dfc42c418e08c947475deb50a.jpg', 1, '2020-08-11 14:12:35', '2020-08-11 14:12:35'),
(12, 'FIFA20', 3, 0, 4000, '6ce6fe0b1abb0f662f7ac5cd930acea8aca30bad.jpg', 1, '2020-08-11 14:15:02', '2020-08-11 14:15:02'),
(13, 'Fall Out 3', 0, 2, 4000, 'c99230098e4bc399c8c788f33f2a3dfcdce4d81e.png', 1, '2020-08-11 15:09:55', '2020-08-11 15:09:55'),
(14, 'GRAN TURISMO 6', 4, 0, 6000, '6437723a3fe8a4008c2e9d60b2ae53fa3f8f485f.jpg', 1, '2020-08-16 15:14:28', '2020-08-16 15:14:28'),
(15, 'ゼルダの伝説　Breath of The Wild', 0, 1, 4000, '61326b78533f4ada54068410366bd854c4cc31c0.jpg', 1, '2020-08-16 15:16:08', '2020-08-16 15:16:08'),
(16, 'Dead By Daylight', 5, 0, 5000, '9596aa932d0e51b644da0727d2da3f13afba233a.jpg', 1, '2020-08-19 23:11:03', '2020-08-19 23:11:03'),
(17, 'Detroit become human', 6, 0, 3000, 'f8097d6f58f9ab04bd074ffacc386c0444bb72a7.jpg', 1, '2020-08-20 20:48:42', '2020-08-20 20:48:42'),
(18, 'Witcher3 WildHunt', 0, 0, 5000, 'c03566cee4addd6cc428ea8af27122e5d165c704.jpg', 1, '2020-08-20 20:52:05', '2020-08-20 20:52:05'),
(19, 'ポケットモンスター　ソード', 0, 1, 6000, '347f0859c137d3a1faf93d06a3304a3dff536ca8.jpg', 1, '2020-08-21 17:44:29', '2020-08-21 17:44:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `soft_master`
--
ALTER TABLE `soft_master`
  ADD PRIMARY KEY (`soft_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `soft_master`
--
ALTER TABLE `soft_master`
  MODIFY `soft_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
