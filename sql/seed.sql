-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 29, 2018 at 11:48 AM
-- Server version: 5.7.24-0ubuntu0.18.10.1
-- PHP Version: 7.2.13-1+ubuntu18.10.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `LXD_Manager`
--
CREATE DATABASE IF NOT EXISTS `LXD_Manager` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `LXD_Manager`;

-- --------------------------------------------------------

--
-- Table structure for table `Cloud_Config`
--

CREATE TABLE `Cloud_Config` (
  `CC_ID` int(11) NOT NULL,
  `CC_Name` varchar(255) NOT NULL,
  `CC_Namespace` varchar(255) NOT NULL,
  `CC_Description` text
);

-- --------------------------------------------------------

--
-- Table structure for table `Cloud_Config_Data`
--

CREATE TABLE `Cloud_Config_Data` (
  `CCD_ID` int(11) NOT NULL,
  `CCD_Cloud_Config_ID` int(11) NOT NULL,
  `CCD_Data` longtext NOT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `Hosts`
--

CREATE TABLE `Hosts` (
  `Host_ID` int(11) NOT NULL,
  `Host_Url_And_Port` varchar(255) NOT NULL,
  `Host_Cert_Path` varchar(255) NOT NULL
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Cloud_Config`
--
ALTER TABLE `Cloud_Config`
  ADD PRIMARY KEY (`CC_ID`);

--
-- Indexes for table `Cloud_Config_Data`
--
ALTER TABLE `Cloud_Config_Data`
  ADD PRIMARY KEY (`CCD_ID`),
  ADD KEY `Cloud_Config_Data_ibfk_1` (`CCD_Cloud_Config_ID`);

--
-- Indexes for table `Hosts`
--
ALTER TABLE `Hosts`
  ADD PRIMARY KEY (`Host_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Cloud_Config`
--
ALTER TABLE `Cloud_Config`
  MODIFY `CC_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Cloud_Config_Data`
--
ALTER TABLE `Cloud_Config_Data`
  MODIFY `CCD_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Hosts`
--
ALTER TABLE `Hosts`
  MODIFY `Host_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Cloud_Config_Data`
--
ALTER TABLE `Cloud_Config_Data`
  ADD CONSTRAINT `Cloud_Config_Data_ibfk_1` FOREIGN KEY (`CCD_Cloud_Config_ID`) REFERENCES `Cloud_Config` (`CC_ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
