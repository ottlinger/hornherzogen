-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET time_zone = "+00:00";

-- /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
-- /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
-- /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
-- /*!40101 SET NAMES utf8 */;

--
-- Datenbank: `herzogenhorn`
--
-- --------------------------------------------------------


--
-- Tabellenstruktur für Tabelle `status`
--
CREATE TABLE IF NOT EXISTS `status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- https://github.com/ottlinger/hornherzogen/issues/3
-- APPLIED - form data is saved
-- REGISTERED - mail is sent successfully
-- CONFIRMED - admin checked and verified registration
-- WAITING_FOR_PAYMENT - we are waiting for payment to come in
-- CANCELLED - manually withdrawn from seminar
-- PAID - paid successfully
-- BOOKED - final confirmation is sent
-- SPAM - in case we have to delete stuff
INSERT INTO status (name) VALUES ('APPLIED');
INSERT INTO status (name) VALUES ('REGISTERED');
INSERT INTO status (name) VALUES ('CONFIRMED');
INSERT INTO status (name) VALUES ('WAITING_FOR_PAYMENT');
INSERT INTO status (name) VALUES ('CANCELLED');
INSERT INTO status (name) VALUES ('PAID');
INSERT INTO status (name) VALUES ('SPAM');

--
-- Tabellenstruktur für Tabelle `applicants`
--
CREATE TABLE IF NOT EXISTS `applicants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `week` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `vorname` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `nachname` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `combinedName` varchar(400) COLLATE utf8_bin DEFAULT NULL,
  `street` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `houseno` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `plz` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `city` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `country` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `dojo` varchar(256) COLLATE utf8_bin DEFAULT NULL,
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
  `verified` timestamp NULL,
  `paymentmailed` timestamp NULL,
  `paymentreceived` timestamp NULL,
  `booked` timestamp NULL,
  `cancelled` timestamp NULL,
  -- https://github.com/ottlinger/hornherzogen/issues/2
  `statusId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT status_id_fk
  FOREIGN KEY (statusId)
  REFERENCES status(id) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- TABLE: ROOMS
-- Holds information about the rooms available
-- Capacity should be 1, 2,3 depending on how many beds there are
-- e.g. Room 4711, 2 people
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `capacity` integer COLLATE utf8_bin DEFAULT 3,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- Replace with real data if available
INSERT INTO rooms (name, capacity) VALUES ('Zimmer1',1);
INSERT INTO rooms (name, capacity) VALUES ('Zimmer2',2);
INSERT INTO rooms (name, capacity) VALUES ('Zimmer3',3);
INSERT INTO rooms (name, capacity) VALUES ('Zimmer4',3);
INSERT INTO rooms (name, capacity) VALUES ('Zimmer5',3);

-- TABLE booking
-- https://github.com/ottlinger/hornherzogen/issues/1
CREATE TABLE IF NOT EXISTS `roombooking` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `roomId` int(10) unsigned NOT NULL,
  `applicantId` int(10) unsigned NOT NULL,
  CONSTRAINT room_id_fk
  FOREIGN KEY (roomId)
  REFERENCES rooms(id) ON DELETE NO ACTION,
  CONSTRAINT applicant_id_fk
  FOREIGN KEY (applicantId)
  REFERENCES applicants(id) ON DELETE NO ACTION,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
