-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2013 at 06:36 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `game`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id_comment` mediumint(8) NOT NULL AUTO_INCREMENT,
  `id_game` mediumint(8) NOT NULL DEFAULT '0',
  `id_user` mediumint(8) NOT NULL DEFAULT '0',
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `created` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_comment`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customize`
--

CREATE TABLE IF NOT EXISTS `customize` (
  `id_customize` mediumint(8) NOT NULL AUTO_INCREMENT,
  `id_game` mediumint(8) NOT NULL DEFAULT '0',
  `id_level` tinyint(4) NOT NULL DEFAULT '0',
  `id_item` tinyint(4) NOT NULL DEFAULT '0',
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id_customize`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE IF NOT EXISTS `game` (
  `id_game` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `id_user` mediumint(8) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `played` int(10) NOT NULL DEFAULT '0',
  `comments` mediumint(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_game`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE IF NOT EXISTS `score` (
  `id_score` int(10) NOT NULL AUTO_INCREMENT,
  `id_game` mediumint(8) NOT NULL DEFAULT '0',
  `id_user` mediumint(8) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL DEFAULT '0',
  `value` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_score`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` mediumint(8) NOT NULL AUTO_INCREMENT,
  `id_unique` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `registered` int(10) NOT NULL DEFAULT '0',
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
