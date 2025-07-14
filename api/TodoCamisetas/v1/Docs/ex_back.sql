-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 13-06-2025 a las 03:37:36
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ex_back`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `camisetas`
--

CREATE TABLE `camisetas` (
  `id` int NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `club` varchar(100) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `precio` int DEFAULT NULL,
  `detalles` text,
  `sku` varchar(20) DEFAULT NULL,
  `estado_disponible` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `camisetas`
--

INSERT INTO `camisetas` (`id`, `titulo`, `club`, `pais`, `tipo`, `color`, `precio`, `detalles`, `sku`, `estado_disponible`) VALUES
(1, 'Camiseta Local 2025 – Selección Chilena', 'Selección Chilena', 'Chile', 'Local', 'Rojo y Azul', 45000, 'Edición aniversario 2025', 'SCL2025L', 1),
(6, 'Camiseta Local 2025 – Selección Chilena', 'Selección Chilena', 'Chile', 'Local', 'Rojo y Azul', 45000, 'Edición aniversario 2025', 'SCL2025XL', 1),
(7, 'Camiseta Visita 2025 – Real Madrid', 'Real Madrid', 'España', 'Visita', 'Blanco y Gris', 47500, 'Modelo internacional 2025', 'RM2025V', 0),
(8, 'Camiseta Local 2025 – Selección Chilena', 'Selección Chilena', 'Chile', 'Local', 'Rojo y Azul', 45000, 'Edición aniversario 2025', 'PRUEBA', 0),
(9, 'Camiseta Local 2025 – Selección Chilena', 'Selección Chilena', 'Chile', 'Local', 'Rojo y Azul', 45000, 'Edición aniversario 2025', 'sdgdL', 1),
(10, 'Camiseta Visita 2025 – Real Madrid', 'Real Madrid', 'España', 'Visita', 'Blanco y Gris', 49000, 'Modelo internacional 2025', 'RMsdgdsg', 0),
(13, 'NUEBVA', 'XX', 'CL', 'WWW', 'RED', 12333, 'DDDD', '1123', 1),
(14, 'sw', 'sw', 'sss', 'sss', 'rrr', 133333, '222', '333', 1),
(15, 'sss', '333', 'ff', 'ff', '22', 1111, 'ss', 'ss', 0),
(16, 'update desde postman', '2222', 'sdgds', 'dsgdgfggg', 'ffff', 169696969, '444', '0', 1),
(17, 'Nueva Camiseta desde Postman', 'nuevo', 'cl', 'uwu', 'morado', 150100, 'nuevo', '123', 1),
(18, 'Update camiseta 1 desde Swagger', 'Update camiseta 1 desde Swagger', 'Update camiseta 1 desde Swagger', 'Update camiseta 1 desde Swagger', 'r', 150000, 'Update camiseta 1 desde Swagger', '33', 0),
(19, 'Update camiseta 2 desde Swagger', 'Update camiseta 2 desde Swagger', 'Update camiseta 2 desde Swagger', 'Update camiseta 2 desde Swagger', 'o', 350000, 'Update camiseta 2 desde Swagger', '3567', 0),
(23, 'Update camiseta 3 desde Swagger', 'Update camiseta 3 desde Swagger', 'Update camiseta 3 desde Swagger', 'Update camiseta 3 desde Swagger', 'r', 750000, 'Update camiseta 3 desde Swagger', '34456', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `camiseta_talla`
--

CREATE TABLE `camiseta_talla` (
  `camiseta_id` int NOT NULL,
  `talla_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `camiseta_talla`
--

INSERT INTO `camiseta_talla` (`camiseta_id`, `talla_id`) VALUES
(1, 1),
(17, 1),
(19, 1),
(23, 1),
(1, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(23, 2),
(1, 3),
(17, 3),
(18, 3),
(19, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int NOT NULL,
  `nombre_comercial` varchar(100) DEFAULT NULL,
  `rut` varchar(15) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `categoria` enum('Regular','Preferencial') DEFAULT NULL,
  `contacto_nombre` varchar(100) DEFAULT NULL,
  `contacto_email` varchar(100) DEFAULT NULL,
  `porcentaje_oferta` int DEFAULT NULL,
  `estado_disponible` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre_comercial`, `rut`, `direccion`, `categoria`, `contacto_nombre`, `contacto_email`, `porcentaje_oferta`, `estado_disponible`) VALUES
(1, '90minutos', '76.123.456-7', 'Providencia, Santiago', 'Preferencial', 'Pedro Soto', 'pedro@90minutos.cl', 15, 1),
(2, 'tdeportes', '77.234.567-8', 'Viña del Mar, Valparaíso', 'Regular', 'Carla Díaz', 'carla@tdeportes.cl', 0, 1),
(3, 'Update postman', '6910101', 'xddddddddddd', 'Regular', 'dgdsg', 'dsgdsg', 10, 0),
(4, '2', '2', 'calle falsa 123', 'Preferencial', '22', '222', 2, 0),
(5, 'AwA de UwU Deportes', '691234', 'Calle Falsa 123', 'Preferencial', 'Hector el Father', 'Tu papa oite', 50, 1),
(6, 'Update desde Swagger 1', 'update rut 1', 'Calle falsa 123', 'Regular', 'update', 'update', 10, 0),
(7, 'Update desde Swagger 2', 'update rut 2', 'Nueva 123', 'Preferencial', 'update', 'update', 70, 0),
(8, 'Update desde Swagger 3', 'update rut 3', '321 tiempo', 'Regular', 'update', 'update', 50, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas`
--

CREATE TABLE `tallas` (
  `id` int NOT NULL,
  `talla` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tallas`
--

INSERT INTO `tallas` (`id`, `talla`) VALUES
(1, 'S'),
(2, 'M'),
(3, 'L'),
(4, 'XL');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `camisetas`
--
ALTER TABLE `camisetas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`);

--
-- Indices de la tabla `camiseta_talla`
--
ALTER TABLE `camiseta_talla`
  ADD PRIMARY KEY (`camiseta_id`,`talla_id`),
  ADD KEY `talla_id` (`talla_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tallas`
--
ALTER TABLE `tallas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `camisetas`
--
ALTER TABLE `camisetas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tallas`
--
ALTER TABLE `tallas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `camiseta_talla`
--
ALTER TABLE `camiseta_talla`
  ADD CONSTRAINT `camiseta_talla_ibfk_1` FOREIGN KEY (`camiseta_id`) REFERENCES `camisetas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `camiseta_talla_ibfk_2` FOREIGN KEY (`talla_id`) REFERENCES `tallas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
