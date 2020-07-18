-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 08, 2020 at 11:11 PM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vue-router`
--

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `number` int(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `number`, `type`, `created_at`, `updated_at`) VALUES
(3, 1, 'car', '2020-02-29 16:19:38', '2020-03-08 20:36:48'),
(4, 2, 'car', '2020-02-29 16:20:25', '2020-03-08 20:37:11'),
(5, 3, 'car', '2020-02-29 16:22:17', '2020-03-08 20:36:38'),
(6, 4, 'car', '2020-02-29 16:22:21', '2020-03-08 20:36:24'),
(8, 6, 'car', '2020-02-29 16:22:30', '2020-03-08 20:36:14'),
(9, 7, 'van', '2020-02-29 16:23:14', '2020-03-08 20:36:02'),
(10, 10, 'car', '2020-02-29 17:08:21', '2020-03-08 20:35:42'),
(29, 15, 'car', '2020-03-07 20:34:43', '2020-03-08 21:42:25'),
(30, 22, 'car', '2020-03-07 21:25:31', '2020-03-08 21:20:19');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
