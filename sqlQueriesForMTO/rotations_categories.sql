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
-- Table structure for table `rotations_categories`
--

DROP TABLE IF EXISTS `rotations_categories`;
CREATE TABLE IF NOT EXISTS `rotations_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rotations_categories`
--

INSERT INTO `rotations_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Regular Day Shift Rotations', '2020-02-28 20:25:27', '2020-02-28 20:25:27'),
(2, 'Hallcon Day Shift Rotations', '2020-02-28 20:25:59', '2020-02-28 20:25:59'),
(3, 'Regular Night Shift Rotations', '2020-02-28 20:25:59', '2020-02-28 20:25:59'),
(4, 'Hallcon Night Shift Rotations', '2020-02-28 20:26:23', '2020-02-28 20:26:23'),
(5, 'Agassiz Rotations', '2020-02-28 20:26:23', '2020-03-07 15:27:32');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
