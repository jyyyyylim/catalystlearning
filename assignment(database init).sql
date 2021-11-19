-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2021 at 05:04 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assignment`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `handle` varchar(15) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `acctype` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE `article` (
  `id` int(10) UNSIGNED NOT NULL,
  `topic` varchar(255) NOT NULL,
  `handle` varchar(15) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `handle` varchar(15) NOT NULL,
  `topic` varchar(255) DEFAULT NULL,
  `bio` longtext DEFAULT NULL,
  `theme` tinytext DEFAULT NULL,
  `phnum` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `topic` varchar(255) NOT NULL,
  `handle` varchar(15) NOT NULL,
  `quizuid` int(11) NOT NULL,
  `question` text NOT NULL,
  `ans_qty` int(1) NOT NULL,
  `answers` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `quizdetails`
--

CREATE TABLE `quizdetails` (
  `quizidentifier` int(11) NOT NULL,
  `quizname` tinytext NOT NULL,
  `quizdescription` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Table structure for table `scorecard`
--

CREATE TABLE `scorecard` (
  `handle` varchar(15) NOT NULL,
  `quizid` int(11) NOT NULL,
  `record_indx` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE `topic` (
  `topic` varchar(255) NOT NULL,
  `topicdesc` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`handle`),
  ADD UNIQUE KEY `handle` (`handle`);

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic` (`topic`),
  ADD KEY `handle` (`handle`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`handle`),
  ADD KEY `topic` (`topic`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD KEY `topic` (`topic`),
  ADD KEY `handle` (`handle`),
  ADD KEY `uidindx` (`quizuid`);

--
-- Indexes for table `quizdetails`
--
ALTER TABLE `quizdetails`
  ADD PRIMARY KEY (`quizidentifier`),
  ADD UNIQUE KEY `quizname` (`quizname`) USING HASH;

--
-- Indexes for table `scorecard`
--
ALTER TABLE `scorecard`
  ADD PRIMARY KEY (`record_indx`),
  ADD KEY `handle` (`handle`),
  ADD KEY `quizrel` (`quizid`);

--
-- Indexes for table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`topic`),
  ADD UNIQUE KEY `topic` (`topic`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quizdetails`
--
ALTER TABLE `quizdetails`
  MODIFY `quizidentifier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `scorecard`
--
ALTER TABLE `scorecard`
  MODIFY `record_indx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`handle`) REFERENCES `account` (`handle`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `article_ibfk_2` FOREIGN KEY (`topic`) REFERENCES `topic` (`topic`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`handle`) REFERENCES `account` (`handle`) ON UPDATE CASCADE,
  ADD CONSTRAINT `profile_ibfk_2` FOREIGN KEY (`topic`) REFERENCES `topic` (`topic`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `quizauthor` FOREIGN KEY (`handle`) REFERENCES `account` (`handle`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `quizidlink` FOREIGN KEY (`quizuid`) REFERENCES `quizdetails` (`quizidentifier`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quiztopic` FOREIGN KEY (`topic`) REFERENCES `topic` (`topic`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `scorecard`
--
ALTER TABLE `scorecard`
  ADD CONSTRAINT `handle` FOREIGN KEY (`handle`) REFERENCES `account` (`handle`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `quizrel` FOREIGN KEY (`quizid`) REFERENCES `quizdetails` (`quizidentifier`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
