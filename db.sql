-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 24-04-2021 a las 23:46:52
-- Versión del servidor: 10.3.28-MariaDB-log-cll-lve
-- Versión de PHP: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `DB_AWare`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Infecteds`
--

CREATE TABLE `Infecteds` (
  `id` int(11) NOT NULL,
  `PCName` text NOT NULL,
  `DecryptionKey` varchar(35) NOT NULL,
  `PrivateKey` text NOT NULL,
  `SessionID` text NOT NULL,
  `UID` text NOT NULL,
  `Payment_Status` text DEFAULT NULL,
  `Payment_Code` text NOT NULL,
  `Payment_Link` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Infecteds`
--
ALTER TABLE `Infecteds`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Infecteds`
--
ALTER TABLE `Infecteds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
