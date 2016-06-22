-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2016 at 12:22 PM
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
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file_server` smallint(5) UNSIGNED NOT NULL,
  `file_folder` int(10) UNSIGNED NOT NULL,
  `file_code` varchar(64) NOT NULL,
  `file_size` bigint(20) UNSIGNED NOT NULL,
  `file_meta` mediumtext,
  `file_status` enum('inprogress','ready','temp','delete','unavailable') NOT NULL,
  `file_createdate` datetime NOT NULL,
  `date_modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `files_filecode_unique` (`file_code`) USING BTREE,
  ADD KEY `files_folder_index` (`file_folder`) USING BTREE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

ALTER TABLE `files`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
