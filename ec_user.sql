-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2020 年 8 月 27 日 09:44
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
-- テーブルの構造 `ec_user`
--

CREATE TABLE `ec_user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `genre` int(11) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `update_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `ec_user`
--

INSERT INTO `ec_user` (`user_id`, `user_name`, `password`, `genre`, `create_datetime`, `update_datetime`) VALUES
(1, 'masa1995', 'masa0302', 2, '2020-06-29 07:39:44', '2020-06-29 07:39:44'),
(2, 'kazu1995', 'tarou256', 0, '2020-06-29 07:48:30', '2020-06-29 07:48:30'),
(11, 'osushi03', 'nigiri04', 6, '2020-06-29 21:28:49', '2020-06-29 21:28:49'),
(12, 'tanaka02', 'osoba08', 2, '2020-07-06 21:02:14', '2020-07-06 21:02:14'),
(13, 'codecamo23', 'jceinsad34', 4, '2020-07-06 21:06:58', '2020-07-06 21:06:58'),
(14, 'keikom', 'keiko03', 3, '2020-07-12 16:36:59', '2020-07-12 16:36:59'),
(15, 'sakasaka02', 'sakasaka04', 5, '2020-07-14 21:36:26', '2020-07-14 21:36:26'),
(16, 'kazu03', 'kazu032', 3, '2020-08-21 15:16:14', '2020-08-21 15:16:14'),
(17, 'kobukuro03', 'oobukuro04', 0, '2020-08-21 16:54:03', '2020-08-21 16:54:03'),
(18, 'just03', 'just04', 4, '2020-08-21 16:56:28', '2020-08-21 16:56:28'),
(19, 'sample1', 'sample2', 5, '2020-08-21 16:59:28', '2020-08-21 16:59:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ec_user`
--
ALTER TABLE `ec_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ec_user`
--
ALTER TABLE `ec_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
