-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2016 at 12:18 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `archive`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED DEFAULT NULL,
  `attachment_title` varchar(100) DEFAULT NULL,
  `attachment_desc` varchar(500) DEFAULT NULL,
  `attachment_type` enum('system','other','file','folder') NOT NULL,
  `attachment_addr` varchar(1000) DEFAULT NULL,
  `attachment_name` varchar(100) DEFAULT NULL,
  `attachment_ext` varchar(255) DEFAULT NULL,
  `attachment_size` float(12,0) UNSIGNED DEFAULT NULL,
  `attachment_meta` mediumtext,
  `attachment_parent` bigint(20) UNSIGNED DEFAULT NULL,
  `attachment_order` smallint(5) UNSIGNED DEFAULT NULL,
  `attachment_status` enum('normal','trash','deleted','inprogress','unavailable','hidden') NOT NULL DEFAULT 'normal',
  `attachment_fav` bit(1) DEFAULT NULL,
  `attachment_date` datetime DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `date_modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachments_files_id` (`file_id`),
  ADD KEY `attachments_users_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_files_id` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `attachments_users_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
