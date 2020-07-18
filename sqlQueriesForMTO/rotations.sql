-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 08, 2020 at 11:12 PM
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
-- Table structure for table `rotations`
--

DROP TABLE IF EXISTS `rotations`;
CREATE TABLE IF NOT EXISTS `rotations` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `vehicle_id` varchar(30) NOT NULL,
  `city_id` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  `job_id` int(30) NOT NULL,
  `badge_id` int(30) NOT NULL,
  `rotation_category_id` int(11) NOT NULL,
  `marks` int(30) NOT NULL,
  `date` date DEFAULT NULL,
  `note` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rotations`
--

INSERT INTO `rotations` (`id`, `vehicle_id`, `city_id`, `type`, `job_id`, `badge_id`, `rotation_category_id`, `marks`, `date`, `note`, `created_at`, `updated_at`) VALUES
(1, '8', '5', 'Return', 222314, 11, 4, 1, '2020-03-28', 'mm', '2020-03-01 17:11:17', '2020-03-04 20:02:34'),
(2, '6', '5', 'One-way', 222314, 979, 4, 2, '2020-03-18', 'ss', '2020-03-02 22:16:23', '2020-03-02 22:16:23'),
(3, '6', '4', 'Return', 999, 979, 3, 55, '2020-03-27', 'ssssss', '2020-03-04 17:34:57', '2020-03-04 17:34:57'),
(4, '7', '5', 'One-way', 654, 65465455, 5, 5, '2020-03-26', NULL, '2020-03-04 19:39:06', '2020-03-04 19:39:06'),
(5, '6', '10', 'One-way', 6666, 816, 2, 6, '2020-03-31', NULL, '2020-03-04 19:40:48', '2020-03-08 20:29:24');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
