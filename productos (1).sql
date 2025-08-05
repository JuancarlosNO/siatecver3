-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-08-2025 a las 19:56:45
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `siatecver3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idproducto` int(11) NOT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `producto` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idproducto`, `categoria`, `producto`, `precio`, `descripcion`) VALUES
(1, 'Electrónica', 'Laptop Lenovo Ideapad', 1899.99, 'Laptop con procesador Intel Core i5, 8GB RAM, SSD 512GB.'),
(2, 'Ropa', 'Camisa de algodón', 59.90, 'Camisa blanca de algodón orgánico, disponible en varias tallas.'),
(3, 'Alimentos', 'Quinua orgánica 1kg', 22.75, 'Quinua peruana seleccionada, sin pesticidas ni conservantes.'),
(4, 'Hogar', 'Ventilador de pie 3 velocidades', 145.50, 'Ventilador silencioso con control remoto y oscilación.'),
(5, 'Deportes', 'Bicicleta de montaña 26”', 799.00, 'Bicicleta con marco de aluminio, doble suspensión y frenos de disco.'),
(6, 'Alimentos', 'Arroz Faraon 2kg', 13.90, 'arroz peruano recien graneado y perfectamente empacado'),
(7, 'Ropa', 'Pantalon escolar', 25.00, 'Pantalon escolar para que su hijo vaya al colegio'),
(8, 'Electrónica', 'Nintendo Switch', 2599.99, 'Nintendo switch con juegos incluidos');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idproducto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
