-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2015 at 06:10 PM
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
-- Table structure for table `at_autotestovi`
--

CREATE TABLE IF NOT EXISTS `at_autotestovi` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAZIV` varchar(512) COLLATE utf8_slovenian_ci NOT NULL,
  `KOMENTAR` varchar(1024) COLLATE utf8_slovenian_ci NOT NULL,
  `CODE` text COLLATE utf8_slovenian_ci NOT NULL,
  `REZULTAT` text COLLATE utf8_slovenian_ci NOT NULL,
  `ALT_REZULTAT1` text COLLATE utf8_slovenian_ci NOT NULL,
  `ALT_REZULTAT2` text COLLATE utf8_slovenian_ci NOT NULL,
  `ALT_REZULTAT3` text COLLATE utf8_slovenian_ci NOT NULL,
  `CASE_SENSITIVE` tinyint(1) NOT NULL DEFAULT '1',
  `DATUM` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ZADATAK` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ZADATAK_AT` (`ZADATAK`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `at_autotestovi`
--

INSERT INTO `at_autotestovi` (`ID`, `NAZIV`, `KOMENTAR`, `CODE`, `REZULTAT`, `ALT_REZULTAT1`, `ALT_REZULTAT2`, `ALT_REZULTAT3`, `CASE_SENSITIVE`, `DATUM`, `ZADATAK`) VALUES
(1, 'AT 1', '', 'var x = 5;\r\nconsole.log (x);', '5', '', '', '', 1, '2015-06-27 15:59:45', 1),
(2, 'AT 2', 'Neki komentar xD', 'var y = 10;\r\nconsole.log (y);', '10', '', '', '', 1, '2015-06-27 15:59:45', 1),
(3, 'AT 1', '', 'var y = "y";\r\nconsole.log (y);', 'y', '', '', '', 1, '2015-06-27 15:59:45', 2),
(4, 'AT 1', '', 'var xx;', '', '', '', '', 1, '2015-06-27 15:59:45', 4),
(5, 'AT 3', 'Neki koment', 'var y = "hehe";\r\nvar z = ''hehhe'';\r\n//test tooltip\r\n\\"\r\n\\''', '1', '', '', '', 1, '2015-06-27 19:00:35', 1),
(6, 'Neki testni naziv', 'Neki komentar, šta radi atest?', '//neki kôd', 'neki rezz', '', '', '', 1, '2015-06-27 23:52:04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `at_tutorijali`
--

CREATE TABLE IF NOT EXISTS `at_tutorijali` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAZIV` varchar(512) COLLATE utf8_slovenian_ci NOT NULL,
  `AK_GODINA` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `at_tutorijali`
--

INSERT INTO `at_tutorijali` (`ID`, `NAZIV`, `AK_GODINA`) VALUES
(1, 'Tutorijal 1 (HTML)', 2015),
(2, 'Tutorijal 2 (CSS)', 2015),
(3, 'Moj projekat (TEST)', 2015);

-- --------------------------------------------------------

--
-- Table structure for table `at_zadaci`
--

CREATE TABLE IF NOT EXISTS `at_zadaci` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAZIV` varchar(512) COLLATE utf8_slovenian_ci NOT NULL,
  `TUTORIJAL` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `TUTORIJAL` (`TUTORIJAL`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `at_zadaci`
--

INSERT INTO `at_zadaci` (`ID`, `NAZIV`, `TUTORIJAL`) VALUES
(1, 'Zadatak 1', 1),
(2, 'Zadatak 2', 1),
(3, 'Zadatak 3', 1),
(4, 'Zadatak 1', 2),
(5, 'Zadatak 2', 2);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci AUTO_INCREMENT=40 ;

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
(17, 'Komentar', 'm\r\nl\r\nkomentar\r\nmlr', '', '2015-05-18 20:51:23', 3),
(18, 'ENIL', 'RADI!\r\nBravo, radi! Iz prve :O', 'epajic1@etf.unsa.ba', '2015-05-23 21:20:55', 9),
(25, 'NekoNeko', 'Neki evil kom''entar hihihii', '', '2015-05-24 00:54:32', 15),
(26, 'hop', 'Radi li biiiiii? :P', '', '2015-05-25 23:58:00', 16),
(27, 'Enil', 'CCC :P\r\nCCC :P\r\nCCC :P\r\nCCC :P', 'nebitno@webmail.com', '2015-05-26 00:21:25', 17),
(28, 'Enil', 'biiiiiiiiiiiiiiii ^^', '', '2015-05-26 15:39:59', 17),
(29, 'Bhi', 'Bii će te bojkotovat u buduće. :''(', '', '2015-05-26 16:42:08', 17),
(30, 'ahjoj', 'ma neeeeeeee, nipošto :(', '', '2015-05-26 18:46:48', 17),
(31, 'tužnaaa', 'jecam očiju mi :(', '', '2015-05-26 19:11:27', 17),
(32, 'ni slučajno', 'da smiješ biti tužnaaaaaaa :(', '', '2015-05-26 21:23:30', 17),
(33, 'Blaugrana Gent', 'Samo da ti kažem jednu stvar.\n95. minuta, Andres Iniesta', 'mquc@rulzz.edu', '2015-05-29 00:09:30', 1),
(34, 'Faruk', 'Jel radi ovo? :P Pear mail zakon, sve sam za petu zavrsio :D', 'fljuca1@etf.unsa.ba', '2015-05-29 02:12:09', 19),
(35, 'Faruk', 'Kada se stavi manje od 10 znakova ovdje u tekst komentara, otvori se prozor na kojem dugme nazad ne radi :P Issue :P', 'fljuca1@etf.unsa.ba', '2015-05-29 02:14:02', 19),
(37, 'Enil', 'Ma radi link nazad na Mozilli, u biti uradi sljedeće: \n"window.history.back();"\nNe znam što na chromeu radi :O', 'epajic1@etf.unsa.ba', '2015-06-05 19:47:19', 19),
(38, 'Enil', 'Bože sačuvaj, inkonzistentno ponašanje na različitim browserima, zato ja izbjegavam DOM :P\r\nUgl, window.history.go (-1) je rješenje... popravim nekad :)', 'epajic1@etf.unsa.ba', '2015-06-05 19:53:11', 19),
(39, 'ehon', 'Šta je, nisi otvarala ovu stranicu više? :(', '', '2015-06-05 19:54:19', 17);

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

CREATE TABLE IF NOT EXISTS `korisnici` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IME` varchar(32) COLLATE utf8_slovenian_ci NOT NULL,
  `PASS` varchar(64) COLLATE utf8_slovenian_ci NOT NULL,
  `NIVO` int(11) NOT NULL DEFAULT '0' COMMENT 'Možda u budućnosti bude potrebno regulirati novi pristupa, ne znam je li ovo dobra ideja.',
  `EMAIL` varchar(256) COLLATE utf8_slovenian_ci NOT NULL,
  `NICK` varchar(32) COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `IME` (`IME`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`ID`, `IME`, `PASS`, `NIVO`, `EMAIL`, `NICK`) VALUES
(3, 'admin', 'd5e8b8864620528df53c848fc27a1a89', 0, 'epajic1@etf.unsa.ba', 'Enil'),
(4, 'enil', 'd5e8b8864620528df53c848fc27a1a89', 0, 'epajic1@etf.unsa.ba', 'Enil Pajić'),
(5, 'memi~', '63e4e7b63421263f014e75a4eb677a10', 2, 'egazetic1@etf.unsa.ba', 'Elma'),
(6, 'biiiiii', '438709f3c5aaaea5af9d2fe9aae7f6ed', 0, 'bcocalic1@etf.unsa.ba', 'Berinček'),
(7, 'dibi', 'b074b331bca05d5fc09058e4fce41f38', 2, 'ezugor1@etf.unsa.ba', 'Dibac'),
(8, 'ice', 'a5984d626d8519a3df5f76e1a78e8774', 2, 'adajic1@etf.unsa.ba', 'IceVII'),
(9, 'faruk', '900d0da027b62670d1e0085f6657f07e', 1, 'fljuca1@etf.unsa.ba', 'Lala'),
(10, 'neko', 'fb90aead5d7e3ea86381a7747a325bf0', 0, 'epajic1@etf.unsa.ba', 'Nekić'),
(11, 'vedran', 'fadc4fab86f476ee1d78f8564e1f7955', 0, 'vljubovic@etf.unsa.ba', 'Vedran');

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
  `KOMENTARISANJE` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Da li je dozvoljeno pisanje komentara na ovu novost',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci COMMENT='Tabela NOVOSTI, baza ''WTLearn'', 5. spirala' AUTO_INCREMENT=24 ;

--
-- Dumping data for table `novosti`
--

INSERT INTO `novosti` (`ID`, `NASLOV`, `K_TEXT`, `D_TEXT`, `AUTOR`, `DATUM`, `SLIKA`, `KOMENTARISANJE`) VALUES
(1, 'Chelsea osvojio titulu BPL', 'Po peti put u historiji, a po treći put pod menadžerskom palicom Jose Mourinha, engleski fundbalski klub Chelsea osigurao je potreban broj poena za prvaka...', 'Po peti put u historiji, a po treći put pod menadžerskom palicom Jose Mourinha, engleski fundbalski klub Chelsea osigurao je potreban broj poena za prvaka 4 kola prije kraja prvenstva.\r\n\r\nJohn Terry ponosno je izveo svoje suigrače na teren, a publika je bila, kao i uvijek, euforična...', 'Enil Pajić', '2015-05-18 13:47:56', 'http://cdn.soccerreviews.com/wp-content/uploads/Chelsea-title-winners1.jpg', 1),
(2, 'Nema dugi text', 'Kratki text', '', 'Enil', '2015-05-18 17:41:11', '', 1),
(3, 'Ima dugi, nema sliku', 'Kratki', 'Dugi\r\nDugi\r\nDugi\r\nDugi', 'Enil', '2015-05-18 17:41:11', '', 0),
(4, 'Nema slike', 'Nmea dugi', '', 'ENIL', '2015-05-18 17:41:11', '', 1),
(5, 'Nezzzzz', 'iandfdskjnfiandfdskjnfiandfdskjnfiandfdskjnfiandfdskjnfiandfdskjnfiandfdskjnfiandfdskjnf\r\n', 'iandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\niandfdskjnf\r\n', 'Enil', '2015-05-18 17:41:11', '', 1),
(6, 'Današnja novost xD', 'Jedan primjer današnje novosti', 'Ima dugi text', 'Enil Pajić', '2015-05-23 12:17:58', '', 1),
(7, 'Jučerašnja novost xD', 'Neki tekst, test', 'Bla ba', 'Enil Pajić', '2015-05-21 22:00:00', '', 1),
(8, 'Prekjučerašnja novost xD', 'Neki tekst, test', 'Bla ba', 'Enil Pajić', '2015-05-20 22:00:00', '', 1),
(9, 'Anes je neko', 'Da vidimo radi li dodavanje novosti preko admin panela, so excited!', 'Da vidimo radi li dodavanje novosti preko admin panela, so excited!\r\nDa vidimo radi li dodavanje novosti preko admin panela, so excited!\r\nDa vidimo radi li dodavanje novosti preko admin panela, so excited!\r\nDa vidimo radi li dodavanje novosti preko admin panela, so excited!\r\nDa vidimo radi li dodavanje novosti preko admin panela, so excited!\r\nDa vidimo radi li dodavanje novosti preko admin panela, so excited!\r\nDa vidimo radi li dodavanje novosti preko admin panela, so excited!\r\nDa vidimo radi li dodavanje novosti preko admin panela, so excited!\r\nDa vidimo radi li dodavanje novosti preko admin panela, so excited!', 'Enil, Anes', '2015-05-23 21:19:55', '', 0),
(15, 'Re: [stavi_neki_subject]', 'Neki Text Bla bla bla', 'ndskjfnkds', 'Enil', '2015-05-24 00:54:07', '', 1),
(16, 'Dodajem vijest kao novu :3', 'Neki kratki text od 15+ znakova :D', 'Neki \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nDugi text', 'Enil Pajić', '2015-05-25 19:44:26', '', 1),
(17, 'Zovem se Patetika, drago mi je.', 'Bhi voli kad joj Ehon ovako da da se igra sa ovim simpa RI stvarčicama.', 'Bhi voli kad joj Ehon ovako da da se igra sa ovim simpa RI stvarčicama.\r\nA posebno ga voli jer je uvijek prekrasan i iskren i fer i pošten i jer na njegovim kritikama prema njoj vidi da mu je koliko-toliko stalo do nje, onako iskreno, bez ikakvih uslova i slično. A i Bhii je stalo do Ehona jako.', 'Bhii', '2015-05-26 00:18:15', '', 1),
(18, 'Završeno commitanje ^^', 'Danas u 23:30 je završeno commitanje projekta...\r\nU nastavku pročitajte kako je moguće &quot;provaliti&quot; na stranicu mijenjanjem POST parametara', 'Naravno, ovdje neće biti pisano kako je to moguće uraditi, nego ćemo samo navesti hipotetski primjer (kojeg sam ja reproducirao i koji bi uspio da nisam napravio serversku validaciju), ali će biti naglašeno koliko je serverska validacija **obavezna**\r\n\r\nKomentar iz nekog od .php fajlova:\r\n\r\n/*\r\n * Mislim da ovdje nema propustâ, nikakvih :D\r\n * Čak sam vodio računa i o naknadnoj promjeni POST parametara,\r\n * npr. ako neko sa nivoom 1 uđe da dodaje administratore\r\n * on neće imati opciju da doda admina sa manjim nivoom (veća priviegija)\r\n * ali će, ako je vješt, biti u mogućnosti naknadno da promijeni POST\r\n * parametre, pa npr. ako na formi nije mogao da odabere nivo polje\r\n * da bude 0 (najveći prioritet) on naknadno može promijeniti POST\r\n * parametar (kao i cijelo zaglavlje) akcija=dodaj_admina&amp;nivo=0\r\n * i onda poslati takav zahtjev. Isto se dešava i ako izmijeni npr.\r\n * akcija=briši_admina&amp;admin=enil da izbriše glavnog admina.\r\n * Zbog toga je urađena server validacija, a istestirano je mijenjanjem\r\n * POST parametara :)\r\n * */\r\n', 'Enil Pajić', '2015-05-28 21:37:05', '', 1),
(19, 'Promijenjeno vrijeme trajanja sesije', 'Zbog možda iritantne poruke &quot;vaša sesija je istekla&quot; promijenjeno je vrijeme trajanje sesije sa 2 na 5 minuta.\r\nNaravno, vrijeme trajanja sesije se čuva u varijabli i lahko ga je izmijeniti. U realnoj situaciji, trajanje sesije treba da bude oko 15 minuta...', '', 'Enil Pajić', '2015-05-28 21:40:08', '', 1),
(20, 'mobile test', 'Hehehehe hehehehehe\r\n', '', 'beno', '2015-06-05 12:26:23', '', 1),
(21, 'mobile test', 'Hehehehe hehehehehe\r\n', '', 'beno', '2015-06-05 12:28:29', '', 1),
(23, 'Anes je neko', 'nekko nekko nekko nekko nekko nekko nekko', 'Hhahahahah', 'nekkkkkkkkkkkkkkkk', '2015-06-05 19:44:18', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reset_pwd`
--

CREATE TABLE IF NOT EXISTS `reset_pwd` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER` varchar(128) COLLATE utf8_slovenian_ci NOT NULL,
  `PASS` varchar(256) COLLATE utf8_slovenian_ci NOT NULL,
  `VRIJEME` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `STATUS` varchar(32) COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `at_autotestovi`
--
ALTER TABLE `at_autotestovi`
  ADD CONSTRAINT `zadatak_autotest` FOREIGN KEY (`ZADATAK`) REFERENCES `at_zadaci` (`ID`);

--
-- Constraints for table `at_zadaci`
--
ALTER TABLE `at_zadaci`
  ADD CONSTRAINT `tutorijal_zadatak` FOREIGN KEY (`TUTORIJAL`) REFERENCES `at_tutorijali` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `komentari`
--
ALTER TABLE `komentari`
  ADD CONSTRAINT `Vezemo komentar sa novosti` FOREIGN KEY (`NOVOST`) REFERENCES `novosti` (`ID`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
