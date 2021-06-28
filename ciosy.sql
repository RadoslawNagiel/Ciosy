-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 15 Lis 2017, 00:28
-- Wersja serwera: 10.1.25-MariaDB
-- Wersja PHP: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `ciosy`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ekwipunek`
--

CREATE TABLE `ekwipunek` (
  `id_eq` int(11) NOT NULL,
  `sloty` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `ekwipunek`
--

INSERT INTO `ekwipunek` (`id_eq`, `sloty`) VALUES
(1, '0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_9.1|1|10_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_'),
(2, '0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_6|1|10_0|0|0_0|0|0_0|0|0_9.4|1|10_0|0|0_7|2|10_0|0|0_0|0|0_');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `postac`
--

CREATE TABLE `postac` (
  `id_postaci` int(11) NOT NULL,
  `ekwipunek` varchar(512) DEFAULT NULL,
  `zycie` int(11) DEFAULT NULL,
  `glod` int(11) DEFAULT NULL,
  `pragnienie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `postac`
--

INSERT INTO `postac` (`id_postaci`, `ekwipunek`, `zycie`, `glod`, `pragnienie`) VALUES
(1, '0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0', 100, 100, 100),
(2, '0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_7|2|10_8|20|20_8|12|20_9.2|1|10_0|0|0_', 86, 49, 76);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `profil`
--

CREATE TABLE `profil` (
  `id_profil` int(11) NOT NULL,
  `login` varchar(40) DEFAULT NULL,
  `haslo` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `profil`
--

INSERT INTO `profil` (`id_profil`, `login`, `haslo`) VALUES
(1, 'radek', 'nagiel'),
(2, 'login', 'haslo');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `ekwipunek`
--
ALTER TABLE `ekwipunek`
  ADD PRIMARY KEY (`id_eq`);

--
-- Indexes for table `postac`
--
ALTER TABLE `postac`
  ADD PRIMARY KEY (`id_postaci`);

--
-- Indexes for table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id_profil`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
