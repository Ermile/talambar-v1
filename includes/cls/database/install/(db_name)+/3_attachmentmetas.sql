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
-- Table structure for table `attachmentmetas`
--

CREATE TABLE `attachmentmetas` (
  `id` int(10) UNSIGNED NOT NULL,
  `attachment_id` bigint(20) UNSIGNED NOT NULL,
  `attachmentmeta_cat` varchar(50) NOT NULL,
  `attachmentmeta_key` varchar(100) NOT NULL,
  `attachmentmeta_meta` mediumtext,
  `attachmentmeta_value` varchar(200) DEFAULT NULL,
  `attachmentmeta_status` enum('enable','disable','expire') NOT NULL DEFAULT 'enable',
  `date_modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachmentmetas`
--
ALTER TABLE `attachmentmetas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachmentmetas_attachments_id` (`attachment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- Constraints for table `attachmentmetas`
--
ALTER TABLE `attachmentmetas`
  ADD CONSTRAINT `attachmentmetas_attachments_id` FOREIGN KEY (`attachment_id`) REFERENCES `attachments` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
