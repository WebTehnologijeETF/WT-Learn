-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2015 at 10:58 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wtlearn`
--

-- --------------------------------------------------------

--
-- Table structure for table `komentari`
--

CREATE TABLE IF NOT EXISTS `komentari` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `AUTOR` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `TEXT` text COLLATE utf8_slovenian_ci NOT NULL,
  `EMAIL` varchar(64) COLLATE utf8_slovenian_ci NOT NULL,
  `DATUM` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `NOVOST` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `NOVOST` (`NOVOST`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `komentari`
--

INSERT INTO `komentari` (`ID`, `AUTOR`, `TEXT`, `EMAIL`, `DATUM`, `NOVOST`) VALUES
(1, 'Enil', 'Neki \r\nkomentar', 'enil777@live.com', '2015-05-18 16:20:38', 1),
(6, 'Enil Pajić', 'Neki\r\ntekst\r\n', 'epajic1@etf.unsa.ba', '2015-05-18 16:27:15', 1),
(7, 'Enil Pajic', 'neki\ntekst\nnnnn', '', '2015-05-18 17:38:33', 1),
(8, 'EnilPajicUSER', 'Neki\r\nLong\r\n\r\nLine\r\n\r\nKomentar bla bla Komentar bla bla Komentar bla bla Komentar bla bla Komentar bla bla Komentar bla bla \r\n\r\n\r\nBla :)', 'epajic1@etf.unsa.ba', '2015-05-18 19:41:03', 1),
(9, 'Anonimni korisnik', 'Komentar &gt;_&lt;Komentar &gt;_&lt;Komentar &gt;_&lt;Komentar &gt;_&lt;Komentar &gt;_&lt;Komentar &gt;_&lt;Komentar &gt;_&lt;Komentar &gt;_&lt; Komentar &gt;_&lt;Komentar &gt;_&lt; Komentar &gt;_&lt;Komentar &gt;_&lt; Komentar &gt;_&lt;Komentar &gt;_&lt; Komentar &gt;_&lt;Komentar &gt;_&lt; Komentar &gt;_&lt;Komentar &gt;_&lt; Komentar &gt;_&lt;Komentar &gt;_&lt; Komentar &gt;_&lt;Komentar &gt;_&lt;', '', '2015-05-18 19:45:29', 1),
(12, 'Anonimni korisnik', 'Neko\r\n\r\nKoments', '', '2015-05-18 19:56:28', 3),
(13, 'ENil', 'Neki\r\nKomment', '', '2015-05-18 20:20:09', 5),
(14, 'Anonimni korisnik', 'Komentar!', 'email@neko.com', '2015-05-18 20:41:12', 3),
(15, '19', 'neki komentar 10 10 10', '', '2015-05-18 20:44:11', 3),
(16, 'dodajem', 'neki ml komentar', '', '2015-05-18 20:47:44', 3),
(17, 'Komentar', 'm\r\nl\r\nkomentar\r\nmlr', '', '2015-05-18 20:51:23', 3);

-- --------------------------------------------------------

--
-- Table structure for table `novosti`
--

CREATE TABLE IF NOT EXISTS `novosti` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NASLOV` varchar(200) COLLATE utf8_slovenian_ci NOT NULL,
  `K_TEXT` text COLLATE utf8_slovenian_ci NOT NULL,
  `D_TEXT` text COLLATE utf8_slovenian_ci NOT NULL,
  `AUTOR` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `DATUM` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SLIKA` varchar(1024) COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci COMMENT='Tabela NOVOSTI, baza ''WTLearn'', 5. spirala' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `novosti`
--

INSERT INTO `novosti` (`ID`, `NASLOV`, `K_TEXT`, `D_TEXT`, `AUTOR`, `DATUM`, `SLIKA`) VALUES
(1, 'Chelsea osvojio titulu BPL', 'Po peti put u historiji, a po treći put pod menadžerskom palicom Jose Mourinha, engleski fundbalski klub Chelsea osigurao je potreban broj poena za prvaka...', 'Po peti put u historiji, a po treći put pod menadžerskom palicom Jose Mourinha, engleski fundbalski klub Chelsea osigurao je potreban broj poena za prvaka 4 kola prije kraja prvenstva.\r\n\r\nJohn Terry ponosno je izveo svoje suigrače na teren, a publika je bila, kao i uvijek, euforična...', 'Enil Pajić', '2015-05-18 13:47:56', 'http://cdn.soccerreviews.com/wp-content/uploads/Chelsea-title-winners1.jpg'),
(2, 'Nema dugi text', 'Kratki text', '', 'Enil', '2015-05-18 17:41:11', ''),
(3, 'Ima dugi, nema sliku', 'Kratki', 'Dugi\r\nDugi\r\nDugi\r\nDugi', 'Enil', '2015-05-18 17:41:11', ''),
(4, 'Nema slike', 'Nmea dugi', '', 'ENIL', '2015-05-18 17:41:11', ''),
(5, 'Nezzzzz', 'iandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\n', 'iandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\n', 'Enil', '2015-05-18 17:41:11', '');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `komentari`
--
ALTER TABLE `komentari`
  ADD CONSTRAINT `Vezemo komentar sa novosti` FOREIGN KEY (`NOVOST`) REFERENCES `novosti` (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
