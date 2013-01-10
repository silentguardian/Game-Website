-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2013 at 03:25 PM
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

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id_comment`, `id_game`, `id_user`, `body`, `created`) VALUES
(1, 3, 1, 'A nice game!', 1357569141),
(3, 3, 1, 'What did you say?!', 1357569872),
(5, 5, 1, 'Oh! Who created this game?!', 1357570354),
(6, 3, 1, 'Where did no comments go?!', 1357570844);

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

--
-- Dumping data for table `customize`
--

INSERT INTO `customize` (`id_customize`, `id_game`, `id_level`, `id_item`, `value`) VALUES
(1, 3, 1, 1, 'cool code!'),
(2, 3, 1, 5, 'another cool code!'),
(3, 3, 1, 9, 'very cool code!'),
(4, 3, 5, 1, 'this is the coolest'),
(5, 3, 5, 2, 'you'),
(6, 3, 5, 3, 'can'),
(7, 3, 5, 4, 'get'),
(8, 3, 5, 5, 'when'),
(9, 3, 5, 6, 'you'),
(10, 3, 5, 7, 'try'),
(11, 3, 5, 8, 'that'),
(12, 3, 5, 9, 'really'),
(13, 3, 5, 10, 'serious');

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

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id_game`, `name`, `description`, `id_user`, `created`, `played`, `comments`) VALUES
(2, 'Nice Game', 'Very very very nice game!', 1, 0, 251, 0),
(3, 'Bad Game', 'Very very very bad game!', 1, 0, 2, 3),
(5, 'Sample Game', 'Super cool description!\r\n\r\nYou wanted this! :D', 1, 1357478053, 1, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `id_unique`, `username`, `password`, `email_address`, `registered`, `admin`) VALUES
(1, 'cd9cd682ab', 'sinan', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'test@test.com', 1357408612, 0),
(2, '29cd447b04', 'test', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'test2@test.com', 1357827100, 0),
(3, '9f6a79bfcd', 'test2', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'test2a@test.com', 1357827307, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
