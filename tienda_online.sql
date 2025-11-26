-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-11-2025 a las 01:07:21
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
-- Base de datos: `tienda_online`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_de_compras`
--

CREATE TABLE `carrito_de_compras` (
  `id_producto_en_carrito` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito_de_compras`
--

INSERT INTO `carrito_de_compras` (`id_producto_en_carrito`, `id_usuario`, `id_producto`, `cantidad`) VALUES
(10, 1, 18, 1),
(11, 1, 14, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`) VALUES
(1, 'Limpiadores (Aceite)'),
(2, 'Limpiadores (Agua)'),
(3, 'Exfoliantes'),
(4, 'Tónicos'),
(5, 'Esencias'),
(6, 'Sérums y Ampollas'),
(7, 'Mascarillas (Sheet Masks)'),
(8, 'Cuidado de Ojos'),
(9, 'Hidratantes'),
(10, 'Protector Solar'),
(11, 'Limpiadores (Aceite)'),
(12, 'Limpiadores (Agua)'),
(13, 'Exfoliantes'),
(14, 'Tónicos'),
(15, 'Esencias'),
(16, 'Sérums y Ampollas'),
(17, 'Mascarillas (Sheet Masks)'),
(18, 'Cuidado de Ojos'),
(19, 'Hidratantes'),
(20, 'Protector Solar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_de_compras`
--

CREATE TABLE `historial_de_compras` (
  `id_historial` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario_compra` decimal(10,2) NOT NULL,
  `fecha_compra` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_de_compras`
--

INSERT INTO `historial_de_compras` (`id_historial`, `id_compra`, `id_usuario`, `id_producto`, `cantidad`, `precio_unitario_compra`, `fecha_compra`) VALUES
(1, 1764092053, 1, 21, 3, 75.00, '2025-11-25 11:34:13'),
(2, 1764092053, 1, 11, 1, 490.00, '2025-11-25 11:34:13'),
(3, 1764092053, 1, 27, 1, 850.00, '2025-11-25 11:34:13'),
(4, 1764098155, 1, 30, 2, 360.00, '2025-11-25 13:15:55'),
(5, 1764098155, 1, 18, 1, 390.00, '2025-11-25 13:15:55'),
(6, 1764109731, 4, 1, 1, 450.00, '2025-11-25 16:28:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fotos` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad_en_almacen` int(11) NOT NULL,
  `fabricante` varchar(100) DEFAULT NULL,
  `origen` varchar(100) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `fotos`, `precio`, `cantidad_en_almacen`, `fabricante`, `origen`, `id_categoria`) VALUES
(1, 'Banila Co Clean It Zero', 'Bálsamo limpiador sorbete original.', 'images/banila_cream.webp', 450.00, 49, 'Banila Co', 'Corea del Sur', 1),
(2, 'Beauty of Joseon Ginseng Oil', 'Aceite de limpieza con ginseng.', 'images/boj_oil.webp', 380.00, 40, 'Beauty of Joseon', 'Corea del Sur', 1),
(3, 'Softymo Speedy Cleansing Oil', 'Aceite limpiador rápido y efectivo.', 'images/softymo.jpg', 290.00, 60, 'Kose', 'Japón', 1),
(4, 'COSRX Low pH Good Morning', 'Gel limpiador suave de pH bajo.', 'images/cosrx_lowph.jpg', 250.00, 100, 'COSRX', 'Corea del Sur', 2),
(5, 'Etude House SoonJung Whip', 'Espuma hipoalergénica suave.', 'images/etude_whip.jpg', 320.00, 30, 'Etude House', 'Corea del Sur', 2),
(6, 'Innisfree Volcanic Pore BHA', 'Espuma de limpieza de poros volcánica.', 'images/innisfree_volcanic.jpg', 280.00, 45, 'Innisfree', 'Corea del Sur', 2),
(7, 'Skinfood Black Sugar Mask', 'Mascarilla exfoliante de azúcar.', 'images/skinfood_sugar.webp', 310.00, 25, 'Skinfood', 'Corea del Sur', 3),
(8, 'Neogen Bio-Peel Gauze Lemon', 'Pads exfoliantes de limón.', 'images/neogen_lemon.jpg', 550.00, 20, 'Neogen', 'Corea del Sur', 3),
(9, 'COSRX BHA Blackhead Power', 'Líquido exfoliante para puntos negros.', 'images/cosrx_bha.webp', 480.00, 35, 'COSRX', 'Corea del Sur', 3),
(10, 'Klairs Supple Preparation', 'Tónico facial hidratante.', 'images/klairs_toner.webp', 420.00, 50, 'Klairs', 'Corea del Sur', 4),
(11, 'Anua Heartleaf 77%', 'Tónico calmante viral.', 'images/anua_toner.jpg', 490.00, 79, 'Anua', 'Corea del Sur', 4),
(12, 'I\'m From Rice Toner', 'Tónico de arroz iluminador.', 'images/imfrom_rice.jpg', 580.00, 30, 'I\'m From', 'Corea del Sur', 4),
(13, 'COSRX Advanced Snail 96', 'Esencia de mucina de caracol.', 'images/cosrx_snail.jpg', 450.00, 150, 'COSRX', 'Corea del Sur', 5),
(14, 'Missha Time Revolution', 'Esencia de tratamiento intensivo.', 'images/missha_time.jpg', 750.00, 20, 'Missha', 'Corea del Sur', 5),
(15, 'Mixsoon Bean Essence', 'Esencia fermentada de soja.', 'images/mixsoon_bean.jpg', 520.00, 40, 'Mixsoon', 'Corea del Sur', 5),
(16, 'Beauty of Joseon Glow Serum', 'Sérum de propóleo y niacinamida.', 'images/boj_glow.jpg', 360.00, 90, 'Beauty of Joseon', 'Corea del Sur', 6),
(17, 'Torriden Dive-In Serum', 'Sérum de ácido hialurónico.', 'images/torriden_dive.jpg', 410.00, 60, 'Torriden', 'Corea del Sur', 6),
(18, 'Axis-Y Dark Spot Glow', 'Sérum corrector de manchas oscuras.', 'images/axisy_glow.webp', 390.00, 74, 'Axis-Y', 'Corea del Sur', 6),
(19, 'Mediheal Teatree Care', 'Mascarilla esencial de árbol de té.', 'images/mediheal_tea.webp', 45.00, 200, 'Mediheal', 'Corea del Sur', 7),
(20, 'Dr. Jart+ Cicapair Calming', 'Mascarilla calmante de cica.', 'images/drjart_cica.webp', 80.00, 100, 'Dr. Jart+', 'Corea del Sur', 7),
(21, 'Abib Gummy Sheet Mask', 'Mascarilla de hoja tipo goma.', 'images/abib_gum.webp', 75.00, 77, 'Abib', 'Corea del Sur', 7),
(22, 'Mizon Snail Repair Eye', 'Crema de ojos de caracol.', 'images/mizon_eye.jpg', 320.00, 40, 'Mizon', 'Corea del Sur', 8),
(23, 'Benton Fermentation Eye', 'Crema de ojos fermentada.', 'images/benton_eye.jpg', 450.00, 25, 'Benton', 'Corea del Sur', 8),
(24, 'Heimish Marine Care', 'Crema de ojos rica marina.', 'images/heimish_eye.webp', 510.00, 15, 'Heimish', 'Corea del Sur', 8),
(25, 'Illiyoon Ceramide Ato', 'Crema concentrada de ceramidas.', 'images/illiyoon_ato.jpg', 400.00, 60, 'Illiyoon', 'Corea del Sur', 9),
(26, 'Etude SoonJung 2x Barrier', 'Crema intensiva de barrera 2x.', 'images/etude_2x.webp', 350.00, 70, 'Etude House', 'Corea del Sur', 9),
(27, 'Belif The True Cream', 'Aqua Bomb hidratante.', 'images/belif_aqua.jpg', 850.00, 19, 'Belif', 'Corea del Sur', 9),
(28, 'Beauty of Joseon Rice Probiotics', 'Protector solar de arroz y probióticos.', 'images/boj_sun.jpg', 380.00, 300, 'Beauty of Joseon', 'Corea del Sur', 10),
(29, 'Round Lab Birch Juice', 'Protector solar de jugo de abedul.', 'images/roundlab_sun.webp', 420.00, 150, 'Round Lab', 'Corea del Sur', 10),
(30, 'Skin1004 Hyalu-Cica Sun', 'Sérum solar de agua.', 'images/skin1004_sun.webp', 360.00, 120, 'Skin1004', '0', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `tarjeta_bancaria` varchar(50) DEFAULT NULL,
  `direccion_postal` text DEFAULT NULL,
  `es_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `correo_electronico`, `contrasena`, `fecha_nacimiento`, `tarjeta_bancaria`, `direccion_postal`, `es_admin`) VALUES
(1, 'Usuario 1', 'usuario1@correo.com', '$2y$10$etyP4xOZ6Eqw8ryUtkoUrekUkiAYMc4Yvzu4RGXVonkeI2oRr9HcS', '2000-01-01', '', '05963', 0),
(4, 'admin', 'admin@kbeauty.com', '$2y$10$Ztq4tUo7lNMqky.DneCQnu6WWNaINdUk5WbKw0zp9o3bbxQM3F7e6', '2025-11-25', '', 'Central', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito_de_compras`
--
ALTER TABLE `carrito_de_compras`
  ADD PRIMARY KEY (`id_producto_en_carrito`),
  ADD KEY `fk_carrito_usuario` (`id_usuario`),
  ADD KEY `fk_carrito_producto` (`id_producto`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `historial_de_compras`
--
ALTER TABLE `historial_de_compras`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `fk_historial_usuario` (`id_usuario`),
  ADD KEY `fk_historial_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_producto_categoria` (`id_categoria`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito_de_compras`
--
ALTER TABLE `carrito_de_compras`
  MODIFY `id_producto_en_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `historial_de_compras`
--
ALTER TABLE `historial_de_compras`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito_de_compras`
--
ALTER TABLE `carrito_de_compras`
  ADD CONSTRAINT `fk_carrito_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_carrito_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_de_compras`
--
ALTER TABLE `historial_de_compras`
  ADD CONSTRAINT `fk_historial_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historial_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_producto_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
