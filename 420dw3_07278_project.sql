-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2024 at 10:40 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_permission` varchar(64) NOT NULL,
  `permission_name` varchar(64) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `last_modified_at` datetime(6) DEFAULT NULL ON UPDATE current_timestamp(6),
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_identifier` (`unique_permission`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `unique_permission`, `permission_name`, `description`, `created_at`, `last_modified_at`) VALUES
(1, 'LOGIN_ALLOWED', 'LOGIN ALLOWED', 'allows users to log-in', '2024-05-05 13:59:16.290071', NULL),
(2, 'MANAGE_USERS', 'MANAGE USERS', 'allows access to management of user entities, including CRUD', '2024-05-05 13:59:45.394262', NULL),
(3, 'MANAGE_PERMISSIONS', 'MANAGE PERMISSIONS', 'allows access to management of user group entities, including CRUD operations', '2024-05-05 14:00:14.349563', '2024-05-05 17:46:06.231938'),
(4, 'MANAGE_USERGROUPS', 'MANAGE USERGROUPS', 'allows access to management of user entities, including CRUD operations', '2024-05-05 14:00:35.294158', '2024-05-05 17:46:26.893715');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password_hash` varchar(72) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `last_modified_at` datetime(6) DEFAULT NULL ON UPDATE current_timestamp(6),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `email`, `created_at`, `last_modified_at`) VALUES
(1, 'Viraj', '$2y$10$.0VpX3aINp9JNp3rxWvI.e4XeBxv2KN.xpeFcgSn5XtAp3XvYHU8C', 'viraj@gmail.com', '2024-05-05 13:55:37.661727', '2024-05-05 14:47:06.493024'),
(2, 'Marc', '$2y$10$lnvidl.Xvt2JOQcQOW7JL.fEh/otBJHdmCQHLQGAEsISDc.1hF/4m', 'marc@gmail.com', '2024-05-05 14:45:18.866844', '2024-05-05 20:58:11.614889'),
(3, 'James', '$2y$10$G7r230OzoqW551Q30ZTnV.YJE0zjM4TrCU17I9KG4shazbSvPxz42', 'james@gmail.com', '2024-05-05 14:45:54.655185', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(64) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `last_modified_at` datetime(6) DEFAULT NULL ON UPDATE current_timestamp(6),
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `description`, `created_at`, `last_modified_at`) VALUES
(1, 'Admin', 'All permission', '2024-05-05 14:07:07.663243', '2024-05-05 14:43:25.841251'),
(2, 'Permission Group', 'Permission Group', '2024-05-05 14:43:16.206516', NULL),
(3, 'User Group', 'User Group', '2024-05-05 22:33:46.625163', '2024-05-05 22:35:04.433956');

-- --------------------------------------------------------

--
-- Table structure for table `user_group_permissions`
--

DROP TABLE IF EXISTS `user_group_permissions`;
CREATE TABLE IF NOT EXISTS `user_group_permissions` (
  `user_group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`user_group_id`,`permission_id`) USING BTREE,
  KEY `fk_permissions_user_group_permissions` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_group_permissions`
--

INSERT INTO `user_group_permissions` (`user_group_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 3),
(3, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

DROP TABLE IF EXISTS `user_permissions`;
CREATE TABLE IF NOT EXISTS `user_permissions` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`permission_id`) USING BTREE,
  KEY `fk_permissions_user_permissions` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`user_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 3),
(3, 4);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_group_permissions`
--
ALTER TABLE `user_group_permissions`
  ADD CONSTRAINT `fk_permissions_user_group_permissions` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_groups_user_group_permissions` FOREIGN KEY (`user_group_id`) REFERENCES `user_groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `fk_permissions_user_permissions` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_user_permissions` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
