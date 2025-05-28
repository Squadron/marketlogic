-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para marketlogic
CREATE DATABASE IF NOT EXISTS `marketlogic` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `marketlogic`;

-- Volcando estructura para tabla marketlogic.carga_masiva_log
CREATE TABLE IF NOT EXISTS `carga_masiva_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int DEFAULT NULL,
  `archivo_nombre` varchar(255) DEFAULT NULL,
  `registros_procesados` int DEFAULT NULL,
  `registros_exitosos` int DEFAULT NULL,
  `registros_fallidos` int DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `carga_masiva_log_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla marketlogic.carga_masiva_log: ~0 rows (aproximadamente)

-- Volcando estructura para tabla marketlogic.productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `estado` enum('activo','inactivo','agotado') NOT NULL DEFAULT 'activo',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `categoria` varchar(50) DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_categoria` (`categoria`),
  KEY `idx_estado` (`estado`),
  KEY `idx_visible` (`visible`),
  CONSTRAINT `chk_nombre_length` CHECK ((length(`nombre`) >= 3)),
  CONSTRAINT `productos_chk_1` CHECK ((`precio` >= 0)),
  CONSTRAINT `productos_chk_2` CHECK ((`stock` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla marketlogic.productos: ~33 rows (aproximadamente)
INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `stock`, `estado`, `visible`, `categoria`, `imagen_url`, `fecha_creacion`, `fecha_actualizacion`) VALUES
	(1, 'Producto1', 'Joker', 100.00, 7, 'inactivo', 1, 'electronica', 'uploads/productos/6835228c90f3f.jpg', '2025-05-27 02:24:35', '2025-05-28 00:08:26'),
	(2, 'Producto2', 'Loco 2', 120.00, 1, 'activo', 1, 'electronica', 'uploads/productos/6835223b1c78a.jpg', '2025-05-27 02:23:19', '2025-05-28 00:08:40'),
	(33, 'Teclado', 'Descripción del producto teclado.', 759.03, 82, 'activo', 1, 'electronica', NULL, '2025-05-27 19:51:45', '2025-05-27 14:51:45'),
	(34, 'Ratón', 'Descripción del producto ratón.', 824.03, 50, 'agotado', 1, 'hogar', NULL, '2025-05-27 19:51:45', '2025-05-27 14:51:45'),
	(35, 'Monitor', 'Descripción del producto monitor.', 879.16, 54, 'inactivo', 1, 'ropa', NULL, '2025-05-27 19:51:45', '2025-05-27 14:52:37'),
	(36, 'Portátil', 'Descripción del producto portátil.', 311.03, 44, 'agotado', 1, 'hogar', NULL, '2025-05-27 19:51:45', '2025-05-27 14:52:36'),
	(37, 'Tablet', 'Descripción del producto tablet.', 227.35, 76, 'agotado', 1, 'ropa', NULL, '2025-05-27 19:51:45', '2025-05-27 14:51:45'),
	(38, 'Smartphone', 'Descripción del producto smartphone.', 1536.56, 41, 'agotado', 1, 'electronica', NULL, '2025-05-27 19:51:45', '2025-05-27 14:51:45'),
	(39, 'Cargador', 'Descripción del producto cargador.', 1264.98, 96, 'activo', 1, 'electronica', NULL, '2025-05-27 19:51:45', '2025-05-27 14:51:45'),
	(40, 'Auriculares', 'Descripción del producto auriculares.', 1008.09, 9, 'inactivo', 1, 'electronica', NULL, '2025-05-27 19:51:45', '2025-05-27 14:52:39'),
	(41, 'Altavoz', 'Descripción del producto altavoz.', 309.75, 20, 'activo', 1, 'electronica', NULL, '2025-05-27 19:51:45', '2025-05-27 14:51:45'),
	(42, 'Impresora', 'Descripción del producto impresora.', 244.00, 73, 'agotado', 1, 'hogar', NULL, '2025-05-27 19:51:46', '2025-05-27 14:52:35'),
	(43, 'Webcam', 'Descripción del producto webcam.', 834.81, 91, 'inactivo', 1, 'hogar', NULL, '2025-05-27 19:51:46', '2025-05-27 14:51:46'),
	(44, 'Micrófono', 'Descripción del producto micrófono.', 688.37, 17, 'inactivo', 1, 'hogar', NULL, '2025-05-27 19:51:46', '2025-05-27 14:51:46'),
	(45, 'Disco Duro', 'Descripción del producto disco duro.', 1268.17, 18, 'activo', 1, 'ropa', NULL, '2025-05-27 19:51:46', '2025-05-27 14:51:46'),
	(46, 'Memoria USB', 'Descripción del producto memoria usb.', 1102.33, 66, 'activo', 1, 'ropa', NULL, '2025-05-27 19:51:46', '2025-05-27 14:52:31'),
	(47, 'Tarjeta Gráfica', 'Descripción del producto tarjeta gráfica.', 1636.15, 53, 'inactivo', 1, 'hogar', NULL, '2025-05-27 19:51:46', '2025-05-27 14:52:30'),
	(48, 'Procesador', 'Descripción del producto procesador.', 1973.13, 74, 'inactivo', 1, 'electronica', NULL, '2025-05-27 19:51:46', '2025-05-27 14:51:46'),
	(49, 'Placa Base', 'Descripción del producto placa base.', 1748.91, 51, 'agotado', 1, 'electronica', NULL, '2025-05-27 19:51:46', '2025-05-27 14:52:29'),
	(50, 'Fuente de Poder', 'Descripción del producto fuente de poder.', 1682.27, 69, 'inactivo', 1, 'electronica', NULL, '2025-05-27 19:51:46', '2025-05-27 14:52:28'),
	(51, 'Caja de PC', 'Descripción del producto caja de pc.', 1889.77, 72, 'activo', 1, 'ropa', NULL, '2025-05-27 19:51:46', '2025-05-27 14:52:27'),
	(52, 'Ventilador', 'Descripción del producto ventilador.', 1138.47, 26, 'activo', 1, 'hogar', NULL, '2025-05-27 19:51:46', '2025-05-27 14:51:46'),
	(53, 'Router', 'Descripción del producto router.', 1520.44, 11, 'activo', 1, 'hogar', NULL, '2025-05-27 19:51:46', '2025-05-27 14:51:46'),
	(54, 'Switch', 'Descripción del producto switch.', 1131.80, 87, 'agotado', 1, 'ropa', NULL, '2025-05-27 19:51:46', '2025-05-27 14:52:27'),
	(55, 'Cámara de Seguridad', 'Descripción del producto cámara de seguridad.', 1733.84, 32, 'activo', 1, 'ropa', NULL, '2025-05-27 19:51:46', '2025-05-27 14:51:46'),
	(56, 'Lámpara LED', 'Descripción del producto lámpara led.', 1165.88, 66, 'agotado', 1, 'hogar', NULL, '2025-05-27 19:51:46', '2025-05-27 14:51:46'),
	(57, 'Proyector', 'Descripción del producto proyector.', 1452.06, 51, 'inactivo', 1, 'electronica', NULL, '2025-05-27 19:51:46', '2025-05-27 14:52:25'),
	(58, 'Soporte Monitor', 'Descripción del producto soporte monitor.', 865.17, 30, 'inactivo', 1, 'ropa', NULL, '2025-05-27 19:51:46', '2025-05-27 14:51:46'),
	(59, 'Cinta LED', 'Descripción del producto cinta led.', 1243.09, 79, 'agotado', 1, 'ropa', NULL, '2025-05-27 19:51:46', '2025-05-27 14:51:46'),
	(60, 'Gamepad', 'Descripción del producto gamepad.', 1908.39, 59, 'agotado', 1, 'hogar', NULL, '2025-05-27 19:51:46', '2025-05-27 14:52:24'),
	(61, 'Joystick', 'Descripción del producto joystick.', 76.58, 45, 'inactivo', 1, 'hogar', NULL, '2025-05-27 19:51:46', '2025-05-27 14:52:23'),
	(62, 'SSD Externo', 'Descripción del producto ssd externo.', 140.83, 74, 'activo', 1, 'ropa', NULL, '2025-05-27 19:51:46', '2025-05-27 14:51:46'),
	(63, 'Producto3', 'Descripcion del Producto 3', 177.00, 7, 'activo', 1, 'electronica', 'uploads/productos/683653c9b11ef.jpg', '2025-05-28 00:07:04', '2025-05-28 00:07:37');

-- Volcando estructura para tabla marketlogic.productos_estadisticas
CREATE TABLE IF NOT EXISTS `productos_estadisticas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `producto_id` int NOT NULL,
  `ventas` int DEFAULT '0',
  `ingresos` decimal(10,2) DEFAULT '0.00',
  `ultima_venta` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_producto_id` (`producto_id`),
  CONSTRAINT `productos_estadisticas_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla marketlogic.productos_estadisticas: ~11 rows (aproximadamente)
INSERT INTO `productos_estadisticas` (`id`, `producto_id`, `ventas`, `ingresos`, `ultima_venta`) VALUES
	(1, 2, 6300, 3840.00, '2025-05-06 16:08:52'),
	(2, 33, 6300, 75143.97, '2025-05-25 16:08:52'),
	(3, 39, 4800, 16444.74, '2025-05-20 16:08:52'),
	(4, 41, 7800, 5575.50, '2025-05-10 16:08:52'),
	(5, 45, 3300, 119207.98, '2025-05-06 16:08:52'),
	(6, 46, 7200, 55116.50, '2025-05-17 16:08:52'),
	(7, 51, 2100, 5669.31, '2025-05-12 16:08:52'),
	(8, 52, 5500, 19353.99, '2025-05-21 16:08:52'),
	(9, 53, 5300, 7602.20, '2025-05-08 16:08:52'),
	(10, 55, 1200, 114433.44, '2025-04-29 16:08:52'),
	(11, 62, 7200, 11266.40, '2025-05-03 16:08:52');

-- Volcando estructura para tabla marketlogic.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla marketlogic.usuarios: ~3 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nombre_usuario`, `email`, `password`, `fecha_registro`) VALUES
	(1, 'Squadron', 'arkweb@gmail.com', '$2y$10$oiFiwV8OL8yBXI1njwcb7.kcjM1042QMt.uWzkm51Nv4e/jnuk4E6', '2025-05-26 23:05:13'),
	(2, 'Squadron2', 'arkweb1@gmail.com', '$2y$10$qNxbr0HKSpk7mHeEOeIjBeTY8k2CDx.ASTiRxOOYvxfPXKoDNDG4C', '2025-05-27 00:24:18'),
	(3, 'Squadron3', 'gohanweb@hotmail.com', '$2y$10$QandFccqirPBkriT7v5eMOB.5DD3INntVtkJVjA3Biw5JvuX5PZi2', '2025-05-27 23:59:53');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
