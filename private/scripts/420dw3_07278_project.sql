-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2024 at 07:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `420dw3_07278_project`
--
CREATE DATABASE IF NOT EXISTS `420dw3_07278_project` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `420dw3_07278_project`;

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
CREATE TABLE IF NOT EXISTS `authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `date_created` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `date_last_modified` datetime(6) DEFAULT NULL ON UPDATE current_timestamp(6),
  `date_deleted` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `first_name`, `last_name`, `date_created`, `date_last_modified`, `date_deleted`) VALUES
(1, 'Phillip K.', 'Dick', '2024-04-06 15:31:10.764184', NULL, NULL),
(2, 'Frank', 'Herbert', '2024-04-06 15:31:38.717578', NULL, NULL),
(3, 'Robert A.', 'Heinlein', '2024-04-06 17:32:33.015072', '2024-04-06 17:42:50.117977', NULL),
(4, 'Isaac', 'Asimov', '2024-04-06 17:34:56.562491', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `author_books`
--

DROP TABLE IF EXISTS `author_books`;
CREATE TABLE IF NOT EXISTS `author_books` (
  `author_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  UNIQUE KEY `AUTHOR_BOOK_IDS_UNIQ` (`author_id`,`book_id`),
  KEY `FK_BOOKS_AUTHORBOOKS` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `author_books`
--

INSERT INTO `author_books` (`author_id`, `book_id`) VALUES
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `isbn` varchar(32) NOT NULL,
  `publication_year` int(11) NOT NULL,
  `date_created` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `date_last_modified` datetime(6) DEFAULT NULL ON UPDATE current_timestamp(6),
  `date_deleted` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `description`, `isbn`, `publication_year`, `date_created`, `date_last_modified`, `date_deleted`) VALUES
(1, 'Dune', 'The original.', 'isbn-12345-4433-9856', 1965, '2024-04-06 18:54:04.461087', NULL, NULL),
(2, 'Foundation', 'The novel that started it all.', 'isbn-12345-9988-5432', 1951, '2024-04-06 18:55:59.690410', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `examples`
--

DROP TABLE IF EXISTS `examples`;
CREATE TABLE IF NOT EXISTS `examples` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dayOfTheWeek` enum('sunday','monday','tuesday','wednesday','thursday','friday','saturday') NOT NULL,
  `description` varchar(256) NOT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `last_modified_at` datetime(6) DEFAULT NULL ON UPDATE current_timestamp(6),
  `deleted_at` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `examples`
--

INSERT INTO `examples` (`id`, `dayOfTheWeek`, `description`, `created_at`, `last_modified_at`, `deleted_at`) VALUES
(1, 'monday', 'The worst day of the week!', '2024-03-21 10:35:38.000000', '2024-04-04 11:27:54.663419', NULL),
(2, 'friday', 'Party day! testing update...', '2024-04-04 00:16:57.242090', '2024-04-04 00:36:11.951780', '2024-04-04 00:36:11.951739'),
(3, 'friday', 'Woohoo! Party time! Love it!', '2024-04-04 10:08:31.638749', '2024-04-04 10:29:44.457674', '2024-04-04 10:29:44.457549'),
(4, 'monday', 'Tuesday', '2024-04-04 11:28:22.495385', NULL, NULL),
(5, 'wednesday', ';iajreb vnh-v4ui; jvwrp7u9gfq3 vkjjveailu bWH V', '2024-04-04 11:30:21.125908', NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `author_books`
--
ALTER TABLE `author_books`
  ADD CONSTRAINT `FK_AUTHORS_AUTHORBOOKS` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_BOOKS_AUTHORBOOKS` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
