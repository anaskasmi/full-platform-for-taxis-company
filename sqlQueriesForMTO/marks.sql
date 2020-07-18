-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 08, 2020 at 11:13 PM
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
-- Table structure for table `marks`
--

DROP TABLE IF EXISTS `marks`;
CREATE TABLE IF NOT EXISTS `marks` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(30) NOT NULL,
  `rotation_category_id` int(30) NOT NULL,
  `rotation_category_name` varchar(100) NOT NULL,
  `marks` int(30) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`id`, `vehicle_id`, `rotation_category_id`, `rotation_category_name`, `marks`, `created_at`, `updated_at`) VALUES
(82, 10, 3, 'Regular Night Shift Rotations', 35, '2020-03-08 20:35:42', '2020-03-08 20:35:42'),
(81, 10, 2, 'Hallcon Day Shift Rotations', 53, '2020-03-08 20:35:42', '2020-03-08 20:35:42'),
(80, 10, 1, 'Regular Day Shift Rotations', 54, '2020-03-08 20:35:42', '2020-03-08 20:35:42'),
(79, 30, 5, 'Agassiz Rotations', 52, '2020-03-08 20:35:16', '2020-03-08 20:35:16'),
(78, 30, 3, 'Regular Night Shift Rotations', 45, '2020-03-08 20:35:16', '2020-03-08 20:35:16'),
(77, 30, 2, 'Hallcon Day Shift Rotations', 44, '2020-03-08 20:35:16', '2020-03-08 20:35:16'),
(76, 30, 1, 'Regular Day Shift Rotations', 53, '2020-03-08 20:35:16', '2020-03-08 20:35:16'),
(75, 29, 5, 'Agassiz Rotations', 45, '2020-03-08 20:35:01', '2020-03-08 20:35:01'),
(74, 29, 4, 'Hallcon Night Shift Rotations', 1, '2020-03-08 20:35:01', '2020-03-08 21:42:25'),
(73, 29, 3, 'Regular Night Shift Rotations', 55, '2020-03-08 20:35:01', '2020-03-08 20:35:01'),
(72, 29, 2, 'Hallcon Day Shift Rotations', 54, '2020-03-08 20:35:01', '2020-03-08 20:35:01'),
(71, 29, 1, 'Regular Day Shift Rotations', 75, '2020-03-08 20:35:01', '2020-03-08 20:35:01'),
(70, 30, 4, 'Hallcon Night Shift Rotations', 1, '2020-03-08 13:53:39', '2020-03-08 21:20:19'),
(83, 10, 4, 'Hallcon Night Shift Rotations', 65, '2020-03-08 20:35:42', '2020-03-08 20:35:42'),
(84, 10, 5, 'Agassiz Rotations', 44, '2020-03-08 20:35:42', '2020-03-08 20:35:42'),
(85, 9, 1, 'Regular Day Shift Rotations', 56, '2020-03-08 20:36:02', '2020-03-08 20:36:02'),
(86, 9, 2, 'Hallcon Day Shift Rotations', 44, '2020-03-08 20:36:02', '2020-03-08 20:36:02'),
(87, 9, 3, 'Regular Night Shift Rotations', 54, '2020-03-08 20:36:02', '2020-03-08 20:36:02'),
(88, 9, 4, 'Hallcon Night Shift Rotations', 42, '2020-03-08 20:36:02', '2020-03-08 20:36:02'),
(89, 9, 5, 'Agassiz Rotations', 24, '2020-03-08 20:36:02', '2020-03-08 20:36:02'),
(90, 8, 1, 'Regular Day Shift Rotations', 23, '2020-03-08 20:36:14', '2020-03-08 20:36:14'),
(91, 8, 2, 'Hallcon Day Shift Rotations', 65, '2020-03-08 20:36:14', '2020-03-08 20:36:14'),
(92, 8, 3, 'Regular Night Shift Rotations', 54, '2020-03-08 20:36:14', '2020-03-08 20:36:14'),
(93, 8, 4, 'Hallcon Night Shift Rotations', 36, '2020-03-08 20:36:14', '2020-03-08 20:36:14'),
(94, 8, 5, 'Agassiz Rotations', 66, '2020-03-08 20:36:14', '2020-03-08 20:36:14'),
(95, 6, 1, 'Regular Day Shift Rotations', 56, '2020-03-08 20:36:24', '2020-03-08 20:36:24'),
(96, 6, 2, 'Hallcon Day Shift Rotations', 65, '2020-03-08 20:36:24', '2020-03-08 20:36:24'),
(97, 6, 3, 'Regular Night Shift Rotations', 54, '2020-03-08 20:36:24', '2020-03-08 20:36:24'),
(98, 6, 4, 'Hallcon Night Shift Rotations', 36, '2020-03-08 20:36:24', '2020-03-08 20:36:24'),
(99, 6, 5, 'Agassiz Rotations', 66, '2020-03-08 20:36:24', '2020-03-08 20:36:24'),
(100, 5, 1, 'Regular Day Shift Rotations', 45, '2020-03-08 20:36:38', '2020-03-08 20:36:38'),
(101, 5, 2, 'Hallcon Day Shift Rotations', 45, '2020-03-08 20:36:38', '2020-03-08 20:36:38'),
(102, 5, 3, 'Regular Night Shift Rotations', 53, '2020-03-08 20:36:38', '2020-03-08 20:36:38'),
(103, 5, 4, 'Hallcon Night Shift Rotations', 39, '2020-03-08 20:36:38', '2020-03-08 20:36:38'),
(104, 5, 5, 'Agassiz Rotations', 65, '2020-03-08 20:36:38', '2020-03-08 20:36:38'),
(105, 3, 1, 'Regular Day Shift Rotations', 55, '2020-03-08 20:36:48', '2020-03-08 20:36:48'),
(106, 3, 2, 'Hallcon Day Shift Rotations', 44, '2020-03-08 20:36:48', '2020-03-08 20:36:48'),
(107, 3, 3, 'Regular Night Shift Rotations', 45, '2020-03-08 20:36:48', '2020-03-08 20:36:48'),
(108, 3, 4, 'Hallcon Night Shift Rotations', 65, '2020-03-08 20:36:48', '2020-03-08 20:36:48'),
(109, 3, 5, 'Agassiz Rotations', 54, '2020-03-08 20:36:48', '2020-03-08 20:36:48'),
(110, 4, 1, 'Regular Day Shift Rotations', 55, '2020-03-08 20:37:11', '2020-03-08 20:37:11'),
(111, 4, 2, 'Hallcon Day Shift Rotations', 63, '2020-03-08 20:37:11', '2020-03-08 20:37:11'),
(112, 4, 3, 'Regular Night Shift Rotations', 55, '2020-03-08 20:37:11', '2020-03-08 20:37:11'),
(113, 4, 4, 'Hallcon Night Shift Rotations', 22, '2020-03-08 20:37:11', '2020-03-08 20:37:11'),
(114, 4, 5, 'Agassiz Rotations', 25, '2020-03-08 20:37:11', '2020-03-08 20:37:11');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
