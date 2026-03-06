-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mar 06, 2026 alle 09:20
-- Versione del server: 10.11.14-MariaDB-0ubuntu0.24.04.1
-- Versione PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ApiSlimFramework`
--

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `1`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `1` (
`Pid` int(11)
,`Pnome` varchar(256)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `2`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `2` (
`Fid` int(11)
,`Fnome` varchar(256)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `3`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `3` (
`Fid` int(11)
,`Fnome` varchar(256)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `4`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `4` (
`Pid` int(11)
,`Pnome` varchar(256)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `5`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `5` (
`Fid` int(11)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `6`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `6` (
`Pid` int(11)
,`Pnome` varchar(256)
,`Fid` int(11)
,`Fnome` varchar(256)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `7`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `7` (
`Fid` int(11)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `8`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `8` (
`Fid` int(11)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `9`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `9` (
`Fid` int(11)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `10`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `10` (
`Pid` int(11)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `Catalogo`
--

CREATE TABLE `Catalogo` (
  `fid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `costo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Catalogo`
--

INSERT INTO `Catalogo` (`fid`, `pid`, `costo`) VALUES
(1, 1, 12),
(1, 2, 8),
(1, 3, 10),
(1, 4, 11),
(1, 5, 15),
(1, 6, 9),
(1, 7, 30),
(1, 8, 16),
(1, 9, 11),
(1, 10, 14),
(1, 11, 12),
(1, 12, 17),
(1, 13, 10),
(1, 14, 31),
(1, 15, 18),
(1, 16, 12),
(1, 17, 15),
(1, 18, 13),
(1, 19, 19),
(1, 20, 11),
(1, 21, 32),
(2, 1, 10),
(2, 2, 8),
(2, 3, 7),
(2, 4, 6),
(3, 1, 11),
(3, 3, 8),
(3, 5, 14),
(4, 1, 9),
(4, 3, 7),
(4, 5, 13),
(5, 1, 13),
(5, 2, 7),
(5, 3, 6),
(5, 4, 5),
(5, 5, 12),
(5, 6, 8),
(6, 1, 14),
(6, 2, 10),
(6, 6, 12),
(7, 8, 12),
(7, 9, 8),
(7, 10, 10),
(7, 11, 11),
(7, 12, 15),
(7, 13, 9),
(7, 14, 30),
(8, 8, 10),
(8, 9, 8),
(8, 10, 7),
(8, 11, 6),
(9, 8, 11),
(9, 10, 8),
(9, 12, 14),
(10, 8, 9),
(10, 10, 7),
(10, 12, 13),
(11, 8, 13),
(11, 9, 7),
(11, 10, 6),
(11, 11, 5),
(11, 12, 12),
(11, 13, 8),
(12, 8, 14),
(12, 9, 10),
(12, 13, 12),
(13, 15, 12),
(13, 16, 8),
(13, 17, 10),
(13, 18, 11),
(13, 19, 15),
(13, 20, 9),
(13, 21, 30),
(14, 15, 10),
(14, 16, 8),
(14, 17, 7),
(14, 18, 6),
(15, 15, 11),
(15, 17, 8),
(15, 19, 14),
(16, 15, 9),
(16, 17, 7),
(16, 19, 13),
(17, 15, 13),
(17, 16, 7),
(17, 17, 6),
(17, 18, 5),
(17, 19, 12),
(17, 20, 8),
(18, 15, 14),
(18, 16, 10),
(18, 20, 12);

-- --------------------------------------------------------

--
-- Struttura della tabella `Fornitori`
--

CREATE TABLE `Fornitori` (
  `fid` int(11) NOT NULL,
  `fnome` varchar(256) NOT NULL,
  `indirizzo` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Fornitori`
--

INSERT INTO `Fornitori` (`fid`, `fnome`, `indirizzo`) VALUES
(1, 'Acme', 'Via Roma 1'),
(2, 'BetaSupply', 'Via Milano 22'),
(3, 'GammaTools', 'Via Torino 9'),
(4, 'RossoOnly Srl', 'Via Napoli 7'),
(5, 'DeltaParts', 'Via Firenze 15'),
(6, 'VerdeRosso Spa', 'Via Bologna 31'),
(7, 'Acme Plus', 'Via Roma 101'),
(8, 'BetaSupply Plus', 'Via Milano 122'),
(9, 'GammaTools Plus', 'Via Torino 109'),
(10, 'RossoOnly Srl Plus', 'Via Napoli 107'),
(11, 'DeltaParts Plus', 'Via Firenze 115'),
(12, 'VerdeRosso Spa Plus', 'Via Bologna 131'),
(13, 'Acme Max', 'Via Roma 201'),
(14, 'BetaSupply Max', 'Via Milano 222'),
(15, 'GammaTools Max', 'Via Torino 209'),
(16, 'RossoOnly Srl Max', 'Via Napoli 207'),
(17, 'DeltaParts Max', 'Via Firenze 215'),
(18, 'VerdeRosso Spa Max', 'Via Bologna 231');

-- --------------------------------------------------------

--
-- Struttura della tabella `Pezzi`
--

CREATE TABLE `Pezzi` (
  `pid` int(11) NOT NULL,
  `pnome` varchar(256) NOT NULL,
  `colore` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Pezzi`
--

INSERT INTO `Pezzi` (`pid`, `pnome`, `colore`) VALUES
(1, 'Bullone', 'rosso'),
(15, 'Bullone Max', 'rosso'),
(8, 'Bullone Plus', 'rosso'),
(7, 'Cuscinetto', 'nero'),
(21, 'Cuscinetto Max', 'nero'),
(14, 'Cuscinetto Plus', 'nero'),
(3, 'Dado', 'rosso'),
(17, 'Dado Max', 'rosso'),
(10, 'Dado Plus', 'rosso'),
(5, 'Ingranaggio', 'rosso'),
(19, 'Ingranaggio Max', 'rosso'),
(12, 'Ingranaggio Plus', 'rosso'),
(6, 'Molla', 'verde'),
(20, 'Molla Max', 'verde'),
(13, 'Molla Plus', 'verde'),
(4, 'Rondella', 'blu'),
(18, 'Rondella Max', 'blu'),
(11, 'Rondella Plus', 'blu'),
(2, 'Vite', 'verde'),
(16, 'Vite Max', 'verde'),
(9, 'Vite Plus', 'verde');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Catalogo`
--
ALTER TABLE `Catalogo`
  ADD PRIMARY KEY (`fid`,`pid`),
  ADD KEY `pezzo` (`pid`);

--
-- Indici per le tabelle `Fornitori`
--
ALTER TABLE `Fornitori`
  ADD PRIMARY KEY (`fid`);

--
-- Indici per le tabelle `Pezzi`
--
ALTER TABLE `Pezzi`
  ADD PRIMARY KEY (`pid`),
  ADD UNIQUE KEY `pnome` (`pnome`,`colore`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Fornitori`
--
ALTER TABLE `Fornitori`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT per la tabella `Pezzi`
--
ALTER TABLE `Pezzi`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

-- --------------------------------------------------------

--
-- Struttura per vista `1`
--
DROP TABLE IF EXISTS `1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost`)) ;

-- --------------------------------------------------------

--
-- Struttura per vista `2`
--
DROP TABLE IF EXISTS `2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` limit 1) limit 1) ;

-- --------------------------------------------------------

--
-- Struttura per vista `3`
--
DROP TABLE IF EXISTS `3`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` = 'rosso' AND !exists(select 1 from `Catalogo` `c` where `c`.`fid` = `f`.`fid` AND `c`.`pid` = `p`.`pid` limit 1) limit 1) ;

-- --------------------------------------------------------

--
-- Struttura per vista `4`
--
DROP TABLE IF EXISTS `4`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` = 'Acme' AND !exists(select 1 from `Catalogo` `c2` where `c2`.`pid` = `p`.`pid` AND `c2`.`fid` <> `c`.`fid` limit 1) ;

-- --------------------------------------------------------

--
-- Struttura per vista `5`
--
DROP TABLE IF EXISTS `5`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost`) ;

-- --------------------------------------------------------

--
-- Struttura per vista `6`
--
DROP TABLE IF EXISTS `6`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` ASC ;

-- --------------------------------------------------------

--
-- Struttura per vista `7`
--
DROP TABLE IF EXISTS `7`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` <> 'rosso' then 1 else 0 end) = 0 ;

-- --------------------------------------------------------

--
-- Struttura per vista `8`
--
DROP TABLE IF EXISTS `8`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` = 'rosso') > 0 AND sum(`p`.`colore` = 'verde') > 0 ;

-- --------------------------------------------------------

--
-- Struttura per vista `9`
--
DROP TABLE IF EXISTS `9`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` in ('rosso','verde') ;

-- --------------------------------------------------------

--
-- Struttura per vista `10`
--
DROP TABLE IF EXISTS `10`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost`) >= 2 ;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Catalogo`
--
ALTER TABLE `Catalogo`
  ADD CONSTRAINT `fornitore` FOREIGN KEY (`fid`) REFERENCES `Fornitori` (`fid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pezzo` FOREIGN KEY (`pid`) REFERENCES `Pezzi` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
