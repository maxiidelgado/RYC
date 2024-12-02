-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: delivery_db
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `banner`
--

DROP TABLE IF EXISTS `banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imagen_url` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banner`
--

LOCK TABLES `banner` WRITE;
/*!40000 ALTER TABLE `banner` DISABLE KEYS */;
INSERT INTO `banner` VALUES (1,'https://example.com/banner1.jpg','https://example.com/1'),(2,'https://example.com/banner2.jpg','https://example.com/2'),(3,'https://example.com/banner1.jpg','https://example.com/1'),(4,'https://example.com/banner2.jpg','https://example.com/21'),(5,'1','1'),(6,'C:\\xampp\\htdocs\\RYC\\images\\pr1.jpg','C:\\xampp\\htdocs\\RYC\\images\\pr1.jpg'),(7,'../../uploads/banners/118204843_3308736755846624_8935054622677092612_n.jpg','maxi');
/*!40000 ALTER TABLE `banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barrio`
--

DROP TABLE IF EXISTS `barrio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barrio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `localidad_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `localidad_id` (`localidad_id`),
  CONSTRAINT `barrio_ibfk_1` FOREIGN KEY (`localidad_id`) REFERENCES `localidad` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barrio`
--

LOCK TABLES `barrio` WRITE;
/*!40000 ALTER TABLE `barrio` DISABLE KEYS */;
INSERT INTO `barrio` VALUES (1,'Centro',1),(2,'Centro',1);
/*!40000 ALTER TABLE `barrio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Comida','Categoría de comida'),(2,'Bebida','Categoría de bebida'),(3,'Comida','Categoría de comida'),(4,'Bebida','Categoría de bebida');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `color`
--

DROP TABLE IF EXISTS `color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `color` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `codigo_hex` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `color`
--

LOCK TABLES `color` WRITE;
/*!40000 ALTER TABLE `color` DISABLE KEYS */;
INSERT INTO `color` VALUES (1,'Rojo','#FF0000'),(2,'Verde','#00FF00'),(3,'Rojo','#FF0000'),(4,'Verde','#00FF00');
/*!40000 ALTER TABLE `color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contabilidad`
--

DROP TABLE IF EXISTS `contabilidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contabilidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `tipo_transaccion` enum('ingreso','egreso') DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `contabilidad_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contabilidad`
--

LOCK TABLES `contabilidad` WRITE;
/*!40000 ALTER TABLE `contabilidad` DISABLE KEYS */;
INSERT INTO `contabilidad` VALUES (1,1,1000.00,'ingreso','2024-06-14 19:49:14'),(2,1,500.00,'egreso','2024-06-14 19:49:14'),(3,1,1000.00,'ingreso','2024-08-22 12:07:57'),(4,1,500.00,'egreso','2024-08-22 12:07:57');
/*!40000 ALTER TABLE `contabilidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacto`
--

DROP TABLE IF EXISTS `contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `persona_id` int(11) DEFAULT NULL,
  `tipo_contacto_id` int(11) DEFAULT NULL,
  `valor` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `persona_id` (`persona_id`),
  KEY `tipo_contacto_id` (`tipo_contacto_id`),
  CONSTRAINT `contacto_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`),
  CONSTRAINT `contacto_ibfk_2` FOREIGN KEY (`tipo_contacto_id`) REFERENCES `tipocontacto` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacto`
--

LOCK TABLES `contacto` WRITE;
/*!40000 ALTER TABLE `contacto` DISABLE KEYS */;
INSERT INTO `contacto` VALUES (1,1,1,'admin@example.com'),(2,1,2,'123456789'),(3,1,1,'admin@example.com'),(4,1,2,'123456789');
/*!40000 ALTER TABLE `contacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `direccion`
--

DROP TABLE IF EXISTS `direccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `direccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `persona_id` int(11) DEFAULT NULL,
  `barrio_id` int(11) DEFAULT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `persona_id` (`persona_id`),
  KEY `barrio_id` (`barrio_id`),
  CONSTRAINT `direccion_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`),
  CONSTRAINT `direccion_ibfk_2` FOREIGN KEY (`barrio_id`) REFERENCES `barrio` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `direccion`
--

LOCK TABLES `direccion` WRITE;
/*!40000 ALTER TABLE `direccion` DISABLE KEYS */;
INSERT INTO `direccion` VALUES (1,1,1,'Calle Falsa',123),(2,1,1,'Calle Falsa',123);
/*!40000 ALTER TABLE `direccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `persona_id` int(11) DEFAULT NULL,
  `tipo_documento_id` int(11) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `persona_id` (`persona_id`),
  KEY `tipo_documento_id` (`tipo_documento_id`),
  CONSTRAINT `documento_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`),
  CONSTRAINT `documento_ibfk_2` FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipodocumento` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento`
--

LOCK TABLES `documento` WRITE;
/*!40000 ALTER TABLE `documento` DISABLE KEYS */;
INSERT INTO `documento` VALUES (1,1,1,'12345678'),(2,2,1,'43327789'),(3,3,1,'123123'),(4,5,1,'1234'),(5,6,1,'12345'),(6,7,1,'12345'),(7,1,1,'12345678'),(8,11,1,'0202020202'),(9,12,1,'0202020202'),(10,13,1,'42759805'),(11,14,1,'4242424'),(12,15,1,'42424242'),(13,16,1,'43327789'),(14,17,1,'45778909'),(16,19,1,'4040404040'),(17,20,1,'43327789'),(18,21,1,'4242424242'),(19,22,1,'4242424242');
/*!40000 ALTER TABLE `documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
INSERT INTO `feedback` VALUES (1,1,'Este es un feedback de prueba.','2024-06-14 19:49:14'),(2,1,'soy dios','2024-06-25 21:01:57'),(3,1,'asd','2024-06-26 00:15:58'),(4,1,'hola\r\n','2024-06-26 00:49:05'),(5,1,'Este es un feedback de prueba.','2024-08-22 12:07:57');
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario`
--

DROP TABLE IF EXISTS `inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `precio` varchar(45) DEFAULT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario`
--

LOCK TABLES `inventario` WRITE;
/*!40000 ALTER TABLE `inventario` DISABLE KEYS */;
INSERT INTO `inventario` VALUES (1,1,100,NULL,NULL,NULL),(2,2,200,NULL,NULL,NULL),(3,1,100,NULL,NULL,NULL),(4,2,200,NULL,NULL,NULL);
/*!40000 ALTER TABLE `inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `localidad`
--

DROP TABLE IF EXISTS `localidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `localidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `provincia_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `provincia_id` (`provincia_id`),
  CONSTRAINT `localidad_ibfk_1` FOREIGN KEY (`provincia_id`) REFERENCES `provincia` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `localidad`
--

LOCK TABLES `localidad` WRITE;
/*!40000 ALTER TABLE `localidad` DISABLE KEYS */;
INSERT INTO `localidad` VALUES (1,'La Plata',1),(2,'La Plata',1);
/*!40000 ALTER TABLE `localidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `precio` varchar(255) NOT NULL,
  `estado` int(11) NOT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,'asd','asd',0,NULL,0),(2,'ravioles','4000',0,NULL,0),(3,'ravioles','4000',0,'uploads/ravioles.jpeg',0),(4,'ravioles','4000',0,'uploads/ravioles.png',0),(5,'ravioles','4500',1,'uploads/ravioles.png',9),(6,'ñoquis','4000',0,'../../uploads/118204843_3308736755846624_8935054622677092612_n.jpg',5),(7,'lentejas','4000',0,'uploads/lentejas.png',0),(8,'Agustina Magali','4000',0,NULL,2);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `metodo_pago`
--

DROP TABLE IF EXISTS `metodo_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `metodo_pago` (
  `id_metodo_pago` int(11) NOT NULL AUTO_INCREMENT,
  `metodo_pago` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_metodo_pago`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metodo_pago`
--

LOCK TABLES `metodo_pago` WRITE;
/*!40000 ALTER TABLE `metodo_pago` DISABLE KEYS */;
INSERT INTO `metodo_pago` VALUES (1,'transferencia'),(2,'efectivo');
/*!40000 ALTER TABLE `metodo_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'Panel de Administrador','Módulo para gestionar usuarios','modulos/panel_administrador.php',2),(2,'Inicio','Módulo para gestionar productos','index.php',1),(3,'Gestión de Menus','Módulo para gestionar ventas','modulos/menu/vista_menu.php',3),(8,'Gestión de Pedidos','Módulo para gestionar pedidos','pedidos/pedidos_admin.php',6),(9,' Mi Perfil','Perfil','perfil.php',7);
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `moduloperfil`
--

DROP TABLE IF EXISTS `moduloperfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `moduloperfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `perfil_id` int(11) DEFAULT NULL,
  `modulo_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `perfil_id` (`perfil_id`),
  KEY `modulo_id` (`modulo_id`),
  CONSTRAINT `moduloperfil_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`id`),
  CONSTRAINT `moduloperfil_ibfk_2` FOREIGN KEY (`modulo_id`) REFERENCES `modulo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `moduloperfil`
--

LOCK TABLES `moduloperfil` WRITE;
/*!40000 ALTER TABLE `moduloperfil` DISABLE KEYS */;
INSERT INTO `moduloperfil` VALUES (1,1,1),(2,1,2),(3,1,3),(7,2,2),(8,1,8),(9,3,8),(10,3,8),(11,1,9),(12,2,9),(13,3,9);
/*!40000 ALTER TABLE `moduloperfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pais`
--

DROP TABLE IF EXISTS `pais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pais`
--

LOCK TABLES `pais` WRITE;
/*!40000 ALTER TABLE `pais` DISABLE KEYS */;
INSERT INTO `pais` VALUES (1,'Argentina'),(3,'Paraguay'),(5,'Argentina');
/*!40000 ALTER TABLE `pais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido`
--

DROP TABLE IF EXISTS `pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `rela_usuario` int(11) DEFAULT NULL,
  `direccion_entrega` varchar(255) DEFAULT NULL,
  `fecha_pedido` datetime DEFAULT current_timestamp(),
  `estado` varchar(50) DEFAULT 'pendiente',
  `total` decimal(10,2) DEFAULT NULL,
  `rela_metodo_pago` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `rela_usuario` (`rela_usuario`),
  CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`rela_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
INSERT INTO `pedido` VALUES (1,13,'Arturo Jauretche, Caballito, Buenos Aires, Comuna 6, Ciudad Autónoma de Buenos Aires, C1405BIK, Argentina','2024-09-20 15:35:45','entregado',NULL,NULL),(2,13,'388, Ferrari, Villa Crespo, Buenos Aires, Comuna 15, Ciudad Autónoma de Buenos Aires, C1414BYB, Argentina','2024-09-20 15:37:24','entregado',NULL,NULL),(3,13,'908, Ecuador, Abasto, Balvanera, Buenos Aires, Comuna 3, Ciudad Autónoma de Buenos Aires, C1215ACQ, Argentina','2024-09-20 15:37:48','entregado',NULL,NULL),(4,13,'Arturo Jauretche, Caballito, Buenos Aires, Comuna 6, Ciudad Autónoma de Buenos Aires, C1405BIK, Argentina','2024-09-20 15:42:44','entregado',NULL,NULL),(5,13,'5125, Avenida Rivadavia, Caballito, Buenos Aires, Comuna 6, Ciudad Autónoma de Buenos Aires, C1424CEG, Argentina','2024-09-20 15:43:56','entregado',NULL,NULL),(6,13,'Escuela 240 Francisca Zambonini de Zambrini, Juan José Silva, B° Independencia, Municipio de Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-09-20 15:52:42','entregado',NULL,NULL),(7,13,'1610, Av. González Lelong, Las Delicias, Municipio de Formosa, Departamento Formosa, Formosa, P3600IDK, Argentina','2024-09-20 16:32:01','entregado',NULL,NULL),(8,13,'Coronel Bogado, Libertad, Municipio de Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-09-20 18:18:32','entregado',NULL,NULL),(9,13,'Av. Italia, B° Vial, Municipio de Formosa, Departamento Formosa, Formosa, P3600IDK, Argentina','2024-09-20 19:50:13','entregado',NULL,NULL),(10,13,'761, Córdoba, B° Independencia, Municipio de Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-09-20 19:52:34','entregado',NULL,NULL),(11,4,'Carlos Girola, 2 de Abril, Municipio de Formosa, Departamento Formosa, Formosa, P3600IDK, Argentina','2024-09-27 20:21:09','cancelado',NULL,NULL),(12,4,'407, Jujuy, B° Independencia, Municipio de Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-09-27 20:27:37','cancelado',NULL,NULL),(13,4,'202, Fortín Yunka, B° Independencia, Municipio de Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-10-01 19:46:11','cancelado',NULL,NULL),(14,4,'47, Av. 9 de Julio, B° Independencia, Municipio de Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-10-04 19:50:31','entregado',NULL,NULL),(15,4,'544, Paraguay, Obrero, Municipio de Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-10-04 19:54:25','entregado',NULL,NULL),(16,4,'1422, Pringles, B° Independencia, Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-10-18 14:14:52','entregado',NULL,NULL),(17,4,'963, Saavedra, B° Independencia, Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-10-18 20:40:16','entregado',NULL,NULL),(18,4,'1537, Junín, B° Independencia, Formosa, Municipio de Formosa, Departamento Formosa, Formosa, P3600IDK, Argentina','2024-11-13 23:15:47','entregado',NULL,NULL),(19,4,'666, Jujuy, B° Independencia, Formosa, Municipio de Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-11-13 23:16:47','entregado',NULL,NULL),(20,4,'Escuela 240 Francisca Zambonini de Zambrini, Juan José Silva, B° Independencia, Formosa, Municipio de Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-11-14 01:55:56','entregado',NULL,NULL),(21,4,'401, Ejército Argentino, B° Fontana, Formosa, Municipio de Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-11-14 01:56:16','entregado',NULL,NULL),(22,4,'Pasaje Caracol, B° Fontana, Formosa, Municipio de Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-11-14 04:00:19','Pendiente',4500.00,2),(23,4,'Av. Antártida Argentina, B° Guadalupe, Formosa, Municipio de Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-11-14 04:03:04','Pendiente',4500.00,2),(24,4,'Escuela 380 Docentes Agentinos, Juan Manuel de Rosas, 2 de Abril, Formosa, Municipio de Formosa, Departamento Formosa, Formosa, 3600, Argentina','2024-11-14 04:07:04','Pendiente',4500.00,1);
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidodetalle`
--

DROP TABLE IF EXISTS `pedidodetalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidodetalle` (
  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `rela_pedido` int(11) DEFAULT NULL,
  `rela_menu` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_detalle`),
  KEY `rela_pedido` (`rela_pedido`),
  KEY `rela_menu` (`rela_menu`),
  CONSTRAINT `pedidodetalle_ibfk_1` FOREIGN KEY (`rela_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE,
  CONSTRAINT `pedidodetalle_ibfk_2` FOREIGN KEY (`rela_menu`) REFERENCES `menu` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidodetalle`
--

LOCK TABLES `pedidodetalle` WRITE;
/*!40000 ALTER TABLE `pedidodetalle` DISABLE KEYS */;
INSERT INTO `pedidodetalle` VALUES (1,3,6,1),(2,4,5,1),(3,5,6,1),(4,6,7,1),(5,7,7,3),(6,8,6,2),(7,9,6,1),(8,10,6,1),(9,11,7,1),(10,12,5,1),(11,13,6,2),(12,14,6,2),(13,15,5,1),(14,16,6,1),(15,17,6,3),(16,18,5,1),(17,19,5,1),(18,20,5,1),(19,21,5,5),(20,22,5,1),(21,23,5,1),(22,24,5,1);
/*!40000 ALTER TABLE `pedidodetalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1,'Administrador','Perfil con acceso total'),(2,'Cliente','Perfil acceso limitado'),(3,'Repartidor','Repartidor');
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `pais_id` int(11) NOT NULL,
  `provincia_id` int(11) NOT NULL,
  `documento_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (1,'Admin','Admin','2001-02-18',0,0,0),(2,'maxi','delgado','2001-02-18',1,1,0),(3,'luz','zaragoza','2001-03-15',1,1,0),(4,'administrado','administrador','2001-02-18',0,0,0),(5,'manu','diaz','2001-02-18',1,1,0),(6,'agus','de los santos','2000-02-12',1,1,0),(7,'alan','beck','2000-02-19',1,1,0),(9,'Admin','Admin','1990-01-01',0,0,0),(11,'lada','rodriguez','2002-02-02',1,1,0),(12,'agus','dls','2000-02-12',1,1,0),(13,'cliente','cliente','2001-02-18',1,1,0),(14,'repartidor','repartidor','2001-02-18',1,1,0),(15,'reparto','reparto','2001-02-18',1,1,0),(16,'Maximiliano','Delgado','2001-02-18',1,2,0),(17,'Jesus ','De Nazaret','2001-02-18',1,1,0),(19,'Agustina Magali','De Los Santos','2000-02-12',1,1,0),(20,'Maximiliano','Delgado','2001-02-18',1,1,0),(21,'Chat','gpt','2000-02-12',1,1,0),(22,'Chat','gpt','2000-02-12',1,1,0);
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_id` int(11) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (1,1,'Pizza','Pizza de queso',10.00,100),(2,2,'Coca-Cola','Bebida gaseosa',2.50,200),(3,1,'Pizza','Pizza de queso',10.00,100),(4,2,'Coca-Cola','Bebida gaseosa',2.50,190);
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provincia`
--

DROP TABLE IF EXISTS `provincia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provincia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `pais_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pais_id` (`pais_id`),
  CONSTRAINT `provincia_ibfk_1` FOREIGN KEY (`pais_id`) REFERENCES `pais` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provincia`
--

LOCK TABLES `provincia` WRITE;
/*!40000 ALTER TABLE `provincia` DISABLE KEYS */;
INSERT INTO `provincia` VALUES (1,'Buenos Aires',1),(2,'Formosa',1),(3,'Puerto Elsa',3),(4,'Buenos Aires',1);
/*!40000 ALTER TABLE `provincia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipocontacto`
--

DROP TABLE IF EXISTS `tipocontacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipocontacto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipocontacto`
--

LOCK TABLES `tipocontacto` WRITE;
/*!40000 ALTER TABLE `tipocontacto` DISABLE KEYS */;
INSERT INTO `tipocontacto` VALUES (1,'Email'),(2,'Teléfono'),(3,'Gmail'),(5,'Carta');
/*!40000 ALTER TABLE `tipocontacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipodocumento`
--

DROP TABLE IF EXISTS `tipodocumento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipodocumento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipodocumento`
--

LOCK TABLES `tipodocumento` WRITE;
/*!40000 ALTER TABLE `tipodocumento` DISABLE KEYS */;
INSERT INTO `tipodocumento` VALUES (1,'Cedula'),(2,'Pasaporte'),(3,'DNI');
/*!40000 ALTER TABLE `tipodocumento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `persona_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rela_perfil` int(11) DEFAULT NULL,
  `estado` int(45) DEFAULT 1,
  `correo` varchar(100) DEFAULT NULL,
  `codigo_verificacion` varchar(10) DEFAULT NULL,
  `correo_verificado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `persona_id` (`persona_id`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,1,'admin','8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918',1,1,NULL,NULL,0),(2,2,'maxiii4','$2y$10$nsAUQ2v2ga7p39Su9bbjDeQIINLAzaV6HKtps/f1wEToXT.wbpdEy',1,1,NULL,NULL,0),(3,3,'luz','$2y$10$Iztw6dyTv1R8uwPC2/nrueQArW04XKUAdL/dBCSvFgVR7hs.pdiam',1,0,NULL,NULL,0),(4,4,'administrador','$2y$10$C9NMTBFEMXZ0xYXbqup8zuqf9jwjOweitOzIUfyEk6JXlEykcv9c2',1,1,NULL,NULL,0),(5,5,'manu','$2y$10$On4.xgCVYHsJfEjzy7kIdOinh3ecC1Nmlcgo.Nr8rfqdWqpk5Ecm6',2,1,'manu@gmail.com',NULL,0),(6,6,'agustinita','$2y$10$OcvDQV270eZ4xqiHquDnbeNYDzH.tD45nU/upJjFhhDhVu6k2ripq',2,1,NULL,NULL,0),(7,7,'alanbeck','$2y$10$kbqQbG5yfbl5OHCnk0EB3eGZ5G7ruAELBax1P.lJ15sSG8dxS4oce',2,1,NULL,NULL,0),(9,1,'admin','8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918',NULL,1,NULL,NULL,0),(11,11,'lada02','$2y$10$3KWG7.eImYC/Me0JMR6qgeHNCPq6FEWLh8pzuL9/SxrSoZtPGsl2G',NULL,1,NULL,NULL,0),(12,12,'agusLamasLinda','$2y$10$E1n9dxc8bLW0Q0JFQ0a8be2gTNa0hPC3EkXmHLAGIOckajfhC5ScW',NULL,1,NULL,NULL,0),(13,13,'cliente','$2y$10$rlO3q3omZox/SVbt9XWrgeIvz0NmjlSvK3Qmtvft8wMsM69MEsaeq',2,1,NULL,NULL,0),(14,14,'repartidor','$2y$10$aKSp1nIKeJxNl2/STHcqvu.oSnS1I9wYeBAEVJbye8sUGiod600N6',2,1,NULL,NULL,0),(15,15,'reparto','$2y$10$5ssFFlckb3kdZLLPTbm1Hu99RWLIXNDZl0hSPZHsk3qsxEYedaY.G',3,1,NULL,NULL,0),(16,16,'maxii024','$2y$10$558Wpc/b5dEHuKs.T53TA.Ql.qmNAJd.Ta8knhSLAdj13ZFcB7IS6',2,1,'',NULL,1),(17,17,'HijoDeDios01','$2y$10$cPOai6GJeWwkr6nt0bvEVu6ZYIn2dJHcpXB1VaqZhS7GlkbDWTHmO',2,1,'asd',NULL,1),(19,19,'agusmagui18','$2y$10$4RJtE3ALI.duuoAuU8YYb.F4OUvN/P4eaqSDsXw5YADMHkz5aSkx.',2,1,'fafaf',NULL,1),(20,20,'maxii025','$2y$10$poAjno4BqM85.xIwFcs1qOBTGGXaGhxVhxID5tY5JMXwjqAF74PIm',2,1,'maxiidelgado043@gmail.com','fc3a496ed8',0),(21,21,'chatpgt','$2y$10$bPJZFqZG9rtnKyY1EX8CyeoxYSO65Q75fKalF9IwcxfxhNRvxyZ1q',2,1,'saaadboy01@gmail.com','9178d06cb0',0),(22,22,'chatpgt4','$2y$10$CPA6InxqNv6WVK1vDymOsOs6l.kSA00WH7t4V6qZhx/aJ/lxXWwWS',2,1,'agusmagui18@gmail.com','b088f0d6b6',0);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarioperfil`
--

DROP TABLE IF EXISTS `usuarioperfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarioperfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `perfil_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `perfil_id` (`perfil_id`),
  CONSTRAINT `usuarioperfil_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`),
  CONSTRAINT `usuarioperfil_ibfk_2` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarioperfil`
--

LOCK TABLES `usuarioperfil` WRITE;
/*!40000 ALTER TABLE `usuarioperfil` DISABLE KEYS */;
INSERT INTO `usuarioperfil` VALUES (1,1,1),(2,1,1),(3,4,1),(4,11,2),(5,12,2),(6,13,2),(7,14,2),(8,15,3),(9,16,2),(10,17,2),(11,19,2),(12,20,2),(13,3,2),(14,21,2),(15,22,2);
/*!40000 ALTER TABLE `usuarioperfil` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-23 14:55:10
