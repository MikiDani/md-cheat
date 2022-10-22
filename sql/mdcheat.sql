-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2022. Okt 22. 19:56
-- Kiszolgáló verziója: 10.4.25-MariaDB
-- PHP verzió: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `mdcheat`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `messages`
--

CREATE TABLE `messages` (
  `messageid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `roomid` int(11) NOT NULL,
  `message` text NOT NULL,
  `messagedatetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `messages`
--

INSERT INTO `messages` (`messageid`, `userid`, `roomid`, `message`, `messagedatetime`) VALUES
(1, 0, 0, 'Hello Word! First Admin message.', '2022-08-21 11:30:31'),
(77, 1, 1, 'Dear traveler! Welcome to my world!', '2022-10-22 19:53:20');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `rooms`
--

CREATE TABLE `rooms` (
  `roomid` int(11) NOT NULL,
  `roomname` varchar(100) NOT NULL,
  `roomadminid` int(11) NOT NULL,
  `roomdatetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `rooms`
--

INSERT INTO `rooms` (`roomid`, `roomname`, `roomadminid`, `roomdatetime`) VALUES
(1, 'rootroom', 0, '2022-08-21 11:21:04');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `roomusers`
--

CREATE TABLE `roomusers` (
  `roomusersid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `roomid` int(11) NOT NULL,
  `activeepoch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `roomusers`
--

INSERT INTO `roomusers` (`roomusersid`, `userid`, `roomid`, `activeepoch`) VALUES
(447, 2, 1, 1666461393);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `tokens`
--

CREATE TABLE `tokens` (
  `tokenid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `tokencode` varchar(32) NOT NULL,
  `tokendatetimestart` datetime NOT NULL,
  `tokendatetimeend` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `tokens`
--

INSERT INTO `tokens` (`tokenid`, `userid`, `tokencode`, `tokendatetimestart`, `tokendatetimeend`) VALUES
(1, 10, 'abcdef', '2022-08-21 17:22:26', '2222-08-22 17:24:02'),
(33, 1, '23dc2ca226b069f5947b460469442f35', '2022-10-22 17:55:35', '2022-10-22 23:55:35'),
(34, 2, 'cd03e4061ab1c80a9963b509aef616cd', '2022-10-22 17:55:55', '2022-10-22 23:55:55');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `useremail` varchar(100) NOT NULL,
  `userpassword` varchar(100) NOT NULL,
  `userrank` int(1) NOT NULL,
  `userdatetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`userid`, `username`, `useremail`, `userpassword`, `userrank`, `userdatetime`) VALUES
(1, 'admin', 'admin@mdcheat.com', '123456', 0, '2022-08-21 10:59:58'),
(2, 'dani', 'dani@dani.hu', '123456', 1, '2022-08-21 20:40:01'),
(3, 'zoli', 'zoli@zoli.hu', '123456', 1, '2022-08-22 17:30:05'),
(10, 'abcdef', 'abcdef@abcdef.com', '123456', 0, '2022-08-23 20:28:46');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`messageid`);

--
-- A tábla indexei `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`roomid`);

--
-- A tábla indexei `roomusers`
--
ALTER TABLE `roomusers`
  ADD PRIMARY KEY (`roomusersid`);

--
-- A tábla indexei `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`tokenid`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `messages`
--
ALTER TABLE `messages`
  MODIFY `messageid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT a táblához `rooms`
--
ALTER TABLE `rooms`
  MODIFY `roomid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `roomusers`
--
ALTER TABLE `roomusers`
  MODIFY `roomusersid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=448;

--
-- AUTO_INCREMENT a táblához `tokens`
--
ALTER TABLE `tokens`
  MODIFY `tokenid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
