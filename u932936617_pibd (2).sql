-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 22-10-2025 a las 17:33:37
-- Versión del servidor: 11.8.3-MariaDB-log
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u932936617_pibd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Albumes`
--

CREATE TABLE `Albumes` (
  `IdAlbum` int(11) NOT NULL,
  `Titulo` varchar(255) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  `Portada` varchar(255) DEFAULT 'default-album.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Albumes`
--

INSERT INTO `Albumes` (`IdAlbum`, `Titulo`, `Descripcion`, `Portada`) VALUES
(1, 'Ceremonia', 'Fotos durante la Ceremonia', 'album_67eec3ecafc0a.jpg'),
(2, 'Coctel', 'Fotos durante el Coctel', 'album_67eed84fb1553.jpg'),
(3, 'Comida', 'Fotos durante la Comida', 'album_67eed88019ee0.jpg'),
(4, 'Tardeo', 'Fotos durante el Tardeo', 'album_67eedb0a7dffb.jpg'),
(5, 'Fiesta', 'Fotos durante la Fiesta', 'album_67eedbc1bd7ed.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Estilos`
--

CREATE TABLE `Estilos` (
  `IdEstilo` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  `Fichero` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Estilos`
--

INSERT INTO `Estilos` (`IdEstilo`, `Nombre`, `Descripcion`, `Fichero`) VALUES
(1, 'Alto Contraste', 'Estilo con alto contraste para mejorar la accesibilidad.', 'Alto_contraste.css'),
(2, 'Contraste Grande', 'Estilo con contraste alto y fuente grande.', 'Contraste_Grande.css'),
(3, 'Letra Grande', 'Estilo con fuente grande para mejor lectura.', 'letra_grande.css'),
(4, 'Modo Impreso', 'Estilo optimizado para impresión.', 'modo_impreso.css'),
(5, 'Modo Noche', 'Estilo con colores oscuros para lectura nocturna.', 'modo_noche.css'),
(6, 'Estilo Base', 'Estilo base del sitio web.', 'style.css');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Fotos`
--

CREATE TABLE `Fotos` (
  `IdFoto` int(11) NOT NULL,
  `Usuario` int(11) DEFAULT NULL,
  `Album` int(11) DEFAULT NULL,
  `Fichero` varchar(255) DEFAULT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Fotos`
--

INSERT INTO `Fotos` (`IdFoto`, `Usuario`, `Album`, `Fichero`, `FRegistro`) VALUES
(19, 36, 1, 'foto_67f3b54ec56839.18693577.jpg', '2025-04-07 11:21:50'),
(20, 36, 2, 'foto_67f3b56b995d95.39180409.jpg', '2025-04-07 11:22:19'),
(21, 36, 3, 'foto_67f3b57a6dc595.56831189.jpg', '2025-04-07 11:22:34'),
(22, 36, 4, 'foto_67f3b5bb210b81.08556107.jpg', '2025-04-07 11:23:39'),
(23, 36, 5, 'foto_67f3b5d5774ed1.66628840.jpg', '2025-04-07 11:24:05'),
(24, 43, 1, 'foto_67f3c533628ce8.04204850.jpg', '2025-04-07 12:29:39'),
(25, 43, 1, 'foto_67f3c53362d3b0.22298787.jpg', '2025-04-07 12:29:39'),
(26, 45, 5, 'foto_67f3cf3902ecf1.06927915.jpg', '2025-04-07 13:12:25'),
(27, 45, 5, 'foto_67f3cf39031cb0.47603167.jpg', '2025-04-07 13:12:25'),
(28, 45, 5, 'foto_67f3cf390342b3.64468800.jpg', '2025-04-07 13:12:25'),
(29, 45, 1, 'foto_67f3cfe364e017.42655215.jpg', '2025-04-07 13:15:15'),
(30, 45, 1, 'foto_67f3cfe36530f2.45558952.jpg', '2025-04-07 13:15:15'),
(31, 45, 1, 'foto_67f3cfe3658487.70430463.jpg', '2025-04-07 13:15:15'),
(32, 45, 1, 'foto_67f3cfe365b577.12675132.jpg', '2025-04-07 13:15:15'),
(33, 45, 1, 'foto_67f3cfe365e710.14458500.jpg', '2025-04-07 13:15:15'),
(34, 45, 1, 'foto_67f3cfe36615d3.76608473.jpg', '2025-04-07 13:15:15'),
(35, 45, 1, 'foto_67f3cfe3664347.33971912.jpg', '2025-04-07 13:15:15'),
(36, 45, 1, 'foto_67f3cfe3666e98.52703603.jpg', '2025-04-07 13:15:15'),
(37, 44, 1, 'foto_67f3f3c8ce99d7.09672530.jpg', '2025-04-07 15:48:24'),
(38, 44, 1, 'foto_67f3f3c8cf6be1.90586587.jpg', '2025-04-07 15:48:24'),
(39, 44, 1, 'foto_67f3f3c8d05659.92621924.jpg', '2025-04-07 15:48:24'),
(40, 44, 1, 'foto_67f3f48ea189a3.85621556.jpg', '2025-04-07 15:51:42'),
(41, 44, 1, 'foto_67f3f48ea275c0.41093822.jpg', '2025-04-07 15:51:42'),
(42, 44, 1, 'foto_67f3f48ea33467.32958001.jpg', '2025-04-07 15:51:42'),
(43, 44, 1, 'foto_67f3f48ea41d54.61930519.jpg', '2025-04-07 15:51:42'),
(44, 45, 2, 'foto_67f3f9c3e82b13.86236482.jpg', '2025-04-07 16:13:55'),
(45, 45, 2, 'foto_67f3f9c3e87ac3.80864912.jpg', '2025-04-07 16:13:55'),
(46, 45, 3, 'foto_67f40e802c3af8.04740249.jpg', '2025-04-07 17:42:24'),
(47, 45, 4, 'foto_67f40ecead5679.93531528.jpg', '2025-04-07 17:43:42'),
(48, 45, 4, 'foto_67f40ecead9381.41155409.jpg', '2025-04-07 17:43:42'),
(49, 45, 1, 'foto_67f40f69370f40.16630451.jpg', '2025-04-07 17:46:17'),
(50, 45, 1, 'foto_67f40f693749a0.88512941.jpg', '2025-04-07 17:46:17'),
(51, 45, 1, 'foto_67f40f69377869.07438158.jpg', '2025-04-07 17:46:17'),
(52, 45, 1, 'foto_67f40f6937a5e6.31849308.jpg', '2025-04-07 17:46:17'),
(53, 45, 1, 'foto_67f416c87cc250.38775340.jpg', '2025-04-07 18:17:44'),
(54, 45, 3, 'foto_67f416f200a487.20814276.jpg', '2025-04-07 18:18:26'),
(55, 45, 2, 'foto_67f41731263c32.12904027.jpg', '2025-04-07 18:19:29'),
(56, 45, 2, 'foto_67f41731267477.05172183.jpg', '2025-04-07 18:19:29'),
(57, 45, 2, 'foto_67f41731269dd4.93952024.jpg', '2025-04-07 18:19:29'),
(58, 43, 4, 'foto_67f42a3eddad47.64067883.jpg', '2025-04-07 19:40:46'),
(59, 43, 4, 'foto_67f42a3edde255.16171616.jpg', '2025-04-07 19:40:46'),
(60, 43, 4, 'foto_67f42b01e93b75.39543721.jpg', '2025-04-07 19:44:01'),
(61, 43, 4, 'foto_67f42b01e96f90.61752516.jpg', '2025-04-07 19:44:01'),
(62, 43, 4, 'foto_67f42b01e99554.56341440.jpg', '2025-04-07 19:44:01'),
(63, 43, 4, 'foto_67f42b01e9bd17.98733925.jpg', '2025-04-07 19:44:01'),
(64, 43, 4, 'foto_67f42b01e9ea42.53200139.jpg', '2025-04-07 19:44:01'),
(65, 43, 4, 'foto_67f42b01ea13c5.19831795.jpg', '2025-04-07 19:44:01'),
(66, 43, 4, 'foto_67f42b01ea4195.34830009.jpg', '2025-04-07 19:44:01'),
(67, 43, 4, 'foto_67f42b01ea68a0.66481000.jpg', '2025-04-07 19:44:01'),
(68, 43, 4, 'foto_67f42b01ea97b2.63944009.jpg', '2025-04-07 19:44:01'),
(69, 43, 4, 'foto_67f42b01eac3f6.21983414.jpg', '2025-04-07 19:44:01'),
(70, 43, 4, 'foto_67f42b01eaf2d2.30127410.jpg', '2025-04-07 19:44:01'),
(71, 43, 4, 'foto_67f42bc9a33c30.82805932.jpg', '2025-04-07 19:47:21'),
(72, 43, 4, 'foto_67f42bc9a376e9.55943711.jpg', '2025-04-07 19:47:21'),
(73, 43, 4, 'foto_67f42bc9a3a753.25309761.jpg', '2025-04-07 19:47:21'),
(74, 43, 4, 'foto_67f42bc9a3ccc1.91821656.jpg', '2025-04-07 19:47:21'),
(75, 43, 4, 'foto_67f42bc9a3f009.94908928.jpg', '2025-04-07 19:47:21'),
(76, 43, 4, 'foto_67f42bc9a416c0.28497843.jpg', '2025-04-07 19:47:21'),
(77, 43, 4, 'foto_67f42bc9a44431.92897843.jpg', '2025-04-07 19:47:21'),
(78, 43, 4, 'foto_67f42bc9a47004.10391871.jpg', '2025-04-07 19:47:21'),
(79, 43, 4, 'foto_67f42bc9a49bc5.35606895.jpg', '2025-04-07 19:47:21'),
(80, 43, 4, 'foto_67f42bc9a4c622.90872568.jpg', '2025-04-07 19:47:21'),
(81, 43, 4, 'foto_67f42bc9a4f100.37483024.jpg', '2025-04-07 19:47:21'),
(82, 44, 1, 'foto_67fd0ceb0a0a71.42596152.jpg', '2025-04-14 13:26:03'),
(83, 44, 1, 'foto_67fd0ceb0b13a8.15262170.jpg', '2025-04-14 13:26:03'),
(84, 44, 1, 'foto_67fd0ceb0c0ff7.87542677.jpg', '2025-04-14 13:26:03'),
(85, 44, 3, 'foto_67fd0d523f0346.12005930.jpg', '2025-04-14 13:27:46'),
(86, 44, 3, 'foto_67fd0d52407359.30484866.jpg', '2025-04-14 13:27:46'),
(87, 44, 5, 'foto_67fd0d8a7b5512.37669566.jpg', '2025-04-14 13:28:42'),
(88, 44, 5, 'foto_67fd0d8a7c0127.80945683.jpg', '2025-04-14 13:28:42'),
(89, 44, 5, 'foto_67fd0d8a7c9d72.38555045.jpg', '2025-04-14 13:28:42'),
(90, 44, 3, 'foto_67fd0dbdaf20e3.37750279.jpg', '2025-04-14 13:29:33'),
(91, 44, 3, 'foto_67fd0dbdb09252.36045742.jpg', '2025-04-14 13:29:33'),
(92, 44, 5, 'foto_67fd0f7e4b5807.13569838.jpg', '2025-04-14 13:37:02'),
(93, 44, 1, 'foto_67fd1116c75d19.41355177.jpg', '2025-04-14 13:43:50'),
(94, 44, 1, 'foto_67fd1116c78a79.62527473.jpg', '2025-04-14 13:43:50'),
(95, 44, 1, 'foto_67fd1116c7ac41.18738158.jpg', '2025-04-14 13:43:50'),
(96, 44, 1, 'foto_67fd1116c7cc84.78119010.jpg', '2025-04-14 13:43:50'),
(97, 44, 1, 'foto_67fd1116c7ec32.41814165.jpg', '2025-04-14 13:43:50'),
(98, 44, 1, 'foto_67fd1116c80df9.12878254.jpg', '2025-04-14 13:43:50'),
(99, 44, 1, 'foto_67fd1116c82c87.73705252.jpg', '2025-04-14 13:43:50'),
(100, 44, 1, 'foto_67fd1116c84c62.24401729.jpg', '2025-04-14 13:43:50'),
(101, 44, 1, 'foto_67fd1116c86de0.01080915.jpg', '2025-04-14 13:43:50'),
(102, 44, 1, 'foto_67fd1116c88c89.43533055.jpg', '2025-04-14 13:43:50'),
(103, 44, 1, 'foto_67fd1116c8aaf7.68010791.jpg', '2025-04-14 13:43:50'),
(104, 44, 1, 'foto_67fd1116c8c791.54024462.jpg', '2025-04-14 13:43:50'),
(105, 44, 1, 'foto_67fd1116c8e8b4.65248772.jpg', '2025-04-14 13:43:50'),
(106, 44, NULL, 'foto_67fd117e116092.64870029.jpg', '2025-04-14 13:45:34'),
(107, 44, NULL, 'foto_67fd117e119228.84590728.jpg', '2025-04-14 13:45:34'),
(108, 44, NULL, 'foto_67fd117e11b218.82479158.jpg', '2025-04-14 13:45:34'),
(109, 44, NULL, 'foto_67fd117e11d394.54544900.jpg', '2025-04-14 13:45:34'),
(110, 44, NULL, 'foto_67fd117e11f4c3.50479520.jpg', '2025-04-14 13:45:34'),
(111, 44, NULL, 'foto_67fd117e121276.41544502.jpg', '2025-04-14 13:45:34'),
(112, 44, NULL, 'foto_67fd117e123740.35835091.jpg', '2025-04-14 13:45:34'),
(113, 44, NULL, 'foto_67fd117e127b84.61548126.jpg', '2025-04-14 13:45:34'),
(114, 44, NULL, 'foto_67fd117e12feb8.53489097.jpg', '2025-04-14 13:45:34'),
(115, 44, NULL, 'foto_67fd117e132088.32437784.jpg', '2025-04-14 13:45:34'),
(116, 44, NULL, 'foto_67fd117e134179.42777646.jpg', '2025-04-14 13:45:34'),
(117, 44, NULL, 'foto_67fd117e135da3.82958622.jpg', '2025-04-14 13:45:34'),
(118, 44, 5, 'foto_67fd11c7e81c78.78096609.jpg', '2025-04-14 13:46:47'),
(119, 44, 5, 'foto_67fd11c7e852d6.81380760.jpg', '2025-04-14 13:46:47'),
(120, 44, 5, 'foto_67fd11c7e87b12.91456394.jpg', '2025-04-14 13:46:47'),
(121, 44, 5, 'foto_67fd11c7e8a415.65878593.jpg', '2025-04-14 13:46:47'),
(122, 44, 1, 'foto_67fd12736926c2.24386256.jpg', '2025-04-14 13:49:39'),
(123, 45, 1, 'foto_68030f401db124.64556043.jpg', '2025-04-19 02:49:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `IdUsuario` int(11) NOT NULL,
  `NomUsuario` varchar(15) NOT NULL,
  `Clave` varchar(255) NOT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `Estilo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`IdUsuario`, `NomUsuario`, `Clave`, `FRegistro`, `Estilo`) VALUES
(36, 'Admin3', '$2y$10$xbXgYV65SnYk/8rHoPCTJOg1kwiB0DhOlc/LSRMSbJpb4wTHI5ryu', '2025-04-07 11:05:01', 6),
(43, 'MJMS', '$2y$10$PUx1UkV3SElHg5VpR7IDre2fy1HY9bRm..GDbgzFiYe06AtDxJGKC', '2025-04-07 12:27:25', 6),
(44, 'Bego1', '$2y$10$APMseqHiW3TvtWNcuGeuXeUp1g6jFgahzs//KD/XmAoDNOHIBcQYu', '2025-04-07 13:07:02', 6),
(45, 'Carloslaborda', '$2y$10$9duFTTKsfD5i3FYUMSmO6.sB9AFPbZfkqgDf1otseGnR8C1p6PRe6', '2025-04-07 13:09:37', 6),
(46, 'Jla', '$2y$10$S2qlTpCeFUa28HMfIGKpduMmvlM9Np3WIDnXKPP..UDcKYzEuZZne', '2025-04-07 14:15:20', 6),
(47, 'NuevoUsuario', '$2y$10$n8kYSwsJzxYCU/Lvq9gBce1MbsZpt6oF8J6P/xMha1FC6v2RDs3ga', '2025-04-08 02:10:10', 6),
(48, 'Loreto', '$2y$10$okcA3xp1RHyGNTCjZdd/YOUz1wfB62Fg/4XO6Ti1sPjlx62ehM63C', '2025-04-11 19:57:00', 6),
(49, 'Carlos0', '$2y$10$vQMPFS1lfNzWDARBwwEYqu87dc.5m5n7t/w2KDq0pGbidWyW.leja', '2025-04-20 19:23:49', 6),
(50, 'JuanLaborda1', '$2y$10$sWxIZ5rzs43GJb2QwRrUfup78FN8Ui3JlW3Y0W41Tugr/0z8uo2ZS', '2025-04-20 19:25:36', 6),
(51, 'ISABEL', '$2y$10$OLKhWMZUFbfOLJAzi3y2nOJ67XmtCzMyiQT.4DHvfX.8WnzU/OJDq', '2025-04-20 19:51:30', 6),
(52, 'Carlos33', '$2y$10$pLGeHwnzUGkIaYVB.GmhX.uBNQGYR8m3G/i.6Jw2NnUo69sX44/w2', '2025-05-01 19:57:50', 6);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Albumes`
--
ALTER TABLE `Albumes`
  ADD PRIMARY KEY (`IdAlbum`);

--
-- Indices de la tabla `Estilos`
--
ALTER TABLE `Estilos`
  ADD PRIMARY KEY (`IdEstilo`);

--
-- Indices de la tabla `Fotos`
--
ALTER TABLE `Fotos`
  ADD PRIMARY KEY (`IdFoto`),
  ADD KEY `fk_fotos_usuario` (`Usuario`),
  ADD KEY `fk_fotos_album` (`Album`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`IdUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Albumes`
--
ALTER TABLE `Albumes`
  MODIFY `IdAlbum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `Fotos`
--
ALTER TABLE `Fotos`
  MODIFY `IdFoto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `IdUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Fotos`
--
ALTER TABLE `Fotos`
  ADD CONSTRAINT `fk_fotos_album` FOREIGN KEY (`Album`) REFERENCES `Albumes` (`IdAlbum`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fotos_usuario` FOREIGN KEY (`Usuario`) REFERENCES `Usuarios` (`IdUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
