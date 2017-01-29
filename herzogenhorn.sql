-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 16. Mrz 2012 um 23:09
-- Server Version: 5.1.61
-- PHP-Version: 5.3.6-13ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `herzogenhorn`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `applicants`
--

CREATE TABLE IF NOT EXISTS `applicants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `week` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `vorname` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `nachname` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `street` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `houseno` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `plz` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `city` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `country` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `dojo` varchar(256) COLLATE utf8_bin DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `grad` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `gradsince` date DEFAULT NULL,
  `twano` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `room` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `together1` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `together2` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `essen` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `flexible` tinyint(1) DEFAULT NULL,
  `additionals` varchar(1024) COLLATE utf8_bin DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mailed` timestamp NULL,
  `paymentmailed` timestamp NULL,
  `paymentreceived` timestamp NULL,
  `confirmed` timestamp NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;


-- TABLE: ROOMS
-- Holds information about the rooms available
-- Capacity should be 1, 2,3 depending on how many beds there are
-- e.g. Room 4711, 2 people
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `capacity` integer COLLATE utf8_bin DEFAULT 2,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
