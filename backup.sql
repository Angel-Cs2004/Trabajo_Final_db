-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: db_negocios_2025
-- ------------------------------------------------------
-- Server version	9.4.0

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
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci DEFAULT 'inactivo',
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Entradas','Platos ligeros para empezar','activo'),(2,'Platos de fondo','Platos principales','activo'),(3,'Bebidas','Bebidas frías y calientes','activo'),(4,'Postres','Dulces y postres','activo');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horarios_negocio`
--

DROP TABLE IF EXISTS `horarios_negocio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `horarios_negocio` (
  `id_horario` int NOT NULL AUTO_INCREMENT,
  `dia_semana` enum('lunes','martes','miercoles','jueves','viernes','sabado','domingo') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci DEFAULT 'inactivo',
  `hora_apertura` time NOT NULL,
  `hora_cierre` time NOT NULL,
  PRIMARY KEY (`id_horario`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horarios_negocio`
--

LOCK TABLES `horarios_negocio` WRITE;
/*!40000 ALTER TABLE `horarios_negocio` DISABLE KEYS */;
INSERT INTO `horarios_negocio` VALUES (1,'lunes','activo','09:00:00','16:00:00'),(2,'martes','activo','09:00:00','16:00:00'),(3,'miercoles','activo','09:00:00','16:00:00'),(4,'jueves','activo','09:00:00','16:00:00'),(5,'viernes','activo','09:00:00','16:00:00'),(6,'sabado','activo','10:00:00','15:00:00'),(7,'domingo','inactivo','00:00:00','00:00:00');
/*!40000 ALTER TABLE `horarios_negocio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `negocios`
--

DROP TABLE IF EXISTS `negocios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `negocios` (
  `id_negocio` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  `imagen_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hora_apertura` time NOT NULL,
  `hora_cierre` time NOT NULL,
  `id_propietario` int NOT NULL,
  PRIMARY KEY (`id_negocio`),
  UNIQUE KEY `unq_negocio_propietario_nombre` (`id_propietario`,`nombre`),
  CONSTRAINT `negocios_ibfk_1` FOREIGN KEY (`id_propietario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `check_horario` CHECK ((`hora_cierre` > `hora_apertura`))
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `negocios`
--

LOCK TABLES `negocios` WRITE;
/*!40000 ALTER TABLE `negocios` DISABLE KEYS */;
INSERT INTO `negocios` VALUES (1,'Restaurante Doña Pacha','Comida criolla y menú diario','activo',NULL,'09:00:00','16:00:00',2),(2,'Pollería El Buen Sabor','Pollos a la brasa y parrillas','activo',NULL,'12:00:00','23:00:00',2),(3,'Cevichería El Marino','Ceviches y mariscos frescos','activo',NULL,'10:00:00','18:00:00',3),(4,'Café Angelo','Cafetería de especialidad y postres','activo',NULL,'08:00:00','18:00:00',1),(5,'Pizzería Gen','Pizzas artesanales y pastas','activo',NULL,'12:00:00','23:00:00',1),(6,'Mini Market Angelo','Tienda de abarrotes y snacks','activo',NULL,'09:00:00','21:00:00',1),(7,'Anticuchos La Feria','Anticuchos y parrilla nocturna','activo',NULL,'17:00:00','23:30:00',5),(8,'Juguería Vitaminazo','Jugos naturales y sánguches','activo',NULL,'07:00:00','14:00:00',6),(9,'Pastelería Dulce Norte','Tortas, empanadas y café','activo',NULL,'09:00:00','20:00:00',7),(10,'Sanguchería El Buen Pan','Sánguches calientes y café','activo',NULL,'08:00:00','16:00:00',8),(11,'Chifa Dragón Rojo','Chifa tradicional y combos','activo',NULL,'12:00:00','22:00:00',9),(12,'Arepas Caribe','Arepas y bebidas','activo',NULL,'11:00:00','21:00:00',10),(13,'Hamburguesas La 13','Hamburguesas artesanales','activo',NULL,'16:00:00','23:00:00',11),(14,'Tacos Don Pepe','Tacos, nachos y salsas','activo',NULL,'13:00:00','22:30:00',12),(15,'Panadería Santa Rosa','Pan del día y postres','activo',NULL,'06:30:00','13:30:00',13),(16,'Bodega El Ahorro','Abarrotes y bebidas','activo',NULL,'08:00:00','21:30:00',14),(17,'Café Mirador','Café y brunch','activo',NULL,'08:00:00','19:00:00',15),(18,'Poke & Bowl','Bowls saludables y bebidas','activo',NULL,'11:00:00','20:00:00',16),(19,'Heladería Polar','Helados y cremoladas','activo',NULL,'12:00:00','21:00:00',17),(20,'Parrillas El Carbón','Parrillas y guarniciones','activo',NULL,'12:30:00','23:30:00',18),(21,'Ceviches La Ola','Ceviche y causas','activo',NULL,'10:00:00','17:00:00',19),(22,'Comedor Don Lucho','Menú casero','activo',NULL,'09:00:00','15:00:00',20),(23,'Pizza Napoli Express','Pizzas al paso','activo',NULL,'12:00:00','23:00:00',21),(24,'Market San Martín','Minimarket y limpieza','activo',NULL,'09:00:00','22:00:00',22),(25,'Café Central','Café de especialidad','activo',NULL,'07:30:00','18:30:00',23),(26,'Dulcería La Abuela','Postres tradicionales','activo',NULL,'10:00:00','19:30:00',24),(27,'Pollo Dorado','Pollo a la brasa y parrillas','activo',NULL,'12:00:00','23:00:00',25),(28,'Chifa Jade','Chifa y sopas','activo',NULL,'12:00:00','22:00:00',26),(29,'Cafetería Aurora','Café, sánguches y postres','activo',NULL,'08:00:00','20:00:00',27),(30,'La Barra Cevichera','Mariscos frescos','activo',NULL,'11:00:00','18:30:00',28),(31,'Pizzería La Esquina','Pizzas familiares','activo',NULL,'12:00:00','23:30:00',29),(32,'Tienda Express 24','Snacks, bebidas y abarrotes','activo',NULL,'09:00:00','23:00:00',30),(33,'Veggie Green','Comida vegetariana','activo',NULL,'11:00:00','20:00:00',31),(34,'Súper Snacks','Dulces, snacks y bebidas','activo',NULL,'10:00:00','22:00:00',32),(35,'Ramen House','Ramen y entradas','activo',NULL,'12:00:00','22:00:00',33),(36,'Café Andino','Café y panadería','activo',NULL,'07:00:00','17:00:00',34),(37,'La Sazón de Casa','Menú y platos criollos','activo',NULL,'09:00:00','16:00:00',35),(38,'Empanadas & Más','Empanadas al horno','activo',NULL,'09:00:00','18:00:00',36),(39,'Marisquería Puerto Azul','Mariscos y pescados','activo',NULL,'10:00:00','19:00:00',37),(40,'Cafetería La Estación','Café, jugos y postres','activo',NULL,'08:00:00','19:00:00',38),(41,'Bodega El Barrio','Abarrotes diarios','activo',NULL,'08:00:00','22:00:00',39),(42,'Parrillas La Casona','Parrillas y platos fuertes','activo',NULL,'12:00:00','23:30:00',40),(43,'Sandwich Club','Sándwiches gourmet','activo',NULL,'09:00:00','17:30:00',41),(44,'Tacos La Noche','Tacos nocturnos','activo',NULL,'17:00:00','23:45:00',42),(45,'Pastelería San José','Postres y tortas','activo',NULL,'09:00:00','20:00:00',43),(46,'Chicha & Tradición','Bebidas tradicionales','activo',NULL,'10:00:00','18:00:00',44),(47,'Cevichería Costa Viva','Ceviches y jaleas','activo',NULL,'10:30:00','18:30:00',45),(48,'Pizzería Trattoria Uno','Pastas y pizzas','activo',NULL,'12:00:00','23:00:00',46),(49,'Mini Market La Ruta','Abarrotes, bebidas y snacks','activo',NULL,'09:00:00','22:00:00',47),(50,'Café Bruma','Café, postres y desayuno','activo',NULL,'07:30:00','19:30:00',48);
/*!40000 ALTER TABLE `negocios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parametros_imagenes`
--

DROP TABLE IF EXISTS `parametros_imagenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parametros_imagenes` (
  `id_parametro_imagen` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etiqueta` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alto_px` int DEFAULT NULL,
  `ancho_px` int DEFAULT NULL,
  `categoria` enum('negocios','usuarios','productos') COLLATE utf8mb4_unicode_ci NOT NULL,
  `formatos_validos` enum('jpg','png','webp','gif') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_parametro_imagen`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parametros_imagenes`
--

LOCK TABLES `parametros_imagenes` WRITE;
/*!40000 ALTER TABLE `parametros_imagenes` DISABLE KEYS */;
INSERT INTO `parametros_imagenes` VALUES (1,'Logo Negocio','logo_negocio',300,300,'negocios','png'),(2,'Foto Producto','foto_producto',600,600,'productos','jpg'),(3,'avatar_usuario_1','avatar_usuario',200,200,'usuarios','jpg'),(4,'Perro','profile',200,300,'negocios','jpg');
/*!40000 ALTER TABLE `parametros_imagenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permisos` (
  `id_permiso` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CRUD` enum('CREATE','READ','UPDATE','DELETE') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_permiso`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (1,'crear','CREATE'),(2,'visualizar','READ'),(3,'editar','UPDATE'),(4,'eliminar','DELETE');
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `url_imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  `id_categoria` int NOT NULL,
  `id_negocio` int NOT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `id_categoria` (`id_categoria`),
  KEY `id_negocio` (`id_negocio`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`),
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_negocio`) REFERENCES `negocios` (`id_negocio`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,'Ceviche clásico',25.00,NULL,'activo',1,3),(2,'Lomo saltado',28.50,NULL,'activo',2,1),(3,'Inca Kola 500ml',5.00,NULL,'activo',3,1),(4,'Mazamorra morada',6.50,NULL,'activo',4,1),(5,'Papa a la huancaína',10.00,NULL,'activo',1,1),(6,'Ocopa arequipeña',11.00,NULL,'activo',1,1),(7,'Ají de gallina',18.50,NULL,'activo',2,1),(8,'Seco de res con frejoles',22.00,NULL,'activo',2,1),(9,'Chicha morada vaso',4.00,NULL,'activo',3,1),(10,'1/4 de pollo con papas',20.00,NULL,'activo',2,2),(11,'1/2 pollo familiar',36.00,NULL,'activo',2,2),(12,'Pollo broaster (porción)',18.00,NULL,'activo',2,2),(13,'Ensalada criolla',7.00,NULL,'activo',1,2),(14,'Gaseosa 1.5L',10.00,NULL,'activo',3,2),(15,'Ceviche mixto',30.00,NULL,'activo',2,3),(16,'Ceviche de pota',22.00,NULL,'activo',2,3),(17,'Jalea marina',32.00,NULL,'activo',2,3),(18,'Leche de tigre vaso',12.00,NULL,'activo',1,3),(19,'Limonada jarra',15.00,NULL,'activo',3,3),(20,'Americano',7.00,NULL,'activo',3,4),(21,'Capuccino',10.00,NULL,'activo',3,4),(22,'Latte de vainilla',11.50,NULL,'activo',3,4),(23,'Cheesecake de frutos rojos',14.00,NULL,'activo',4,4),(24,'Brownie con helado',12.00,NULL,'activo',4,4),(25,'Pizza margarita personal',18.00,NULL,'activo',2,5),(26,'Pizza pepperoni mediana',32.00,NULL,'activo',2,5),(27,'Pizza cuatro quesos grande',45.00,NULL,'activo',2,5),(28,'Pan de ajo (porción)',9.00,NULL,'activo',1,5),(29,'Gaseosa personal',5.00,NULL,'activo',3,5),(30,'Agua sin gas 625ml',3.50,NULL,'activo',3,6),(31,'Galletas de vainilla',2.50,NULL,'activo',1,6),(32,'Papas fritas en bolsa',4.00,NULL,'activo',1,6),(33,'Chocolate de leche barra',3.00,NULL,'activo',4,6),(34,'Energizante lata',7.50,NULL,'activo',3,6),(35,'Anticucho clásico',16.00,NULL,'activo',2,7),(36,'Choclo con queso',8.00,NULL,'activo',1,7),(37,'Jugo de naranja',7.50,NULL,'activo',3,8),(38,'Sándwich de pollo',12.00,NULL,'activo',2,9),(39,'Torta de chocolate',14.50,NULL,'activo',4,9),(40,'Chaufa especial',24.00,NULL,'activo',2,11),(41,'Wantán frito',10.00,NULL,'activo',1,11),(42,'Hamburguesa clásica',18.00,NULL,'activo',2,12),(43,'Papas nativas',9.00,NULL,'activo',1,12),(44,'Taco al pastor',13.00,NULL,'activo',2,13),(45,'Nachos con queso',15.00,NULL,'activo',1,13),(46,'Helado doble',9.50,NULL,'activo',4,16),(47,'Ceviche tradicional',29.00,NULL,'activo',2,19),(48,'Limonada frozen',11.00,NULL,'activo',3,19),(49,'Empanada de pollo',7.00,NULL,'activo',1,36),(50,'Menú del día',17.50,NULL,'activo',2,35);
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol_tag_permiso`
--

DROP TABLE IF EXISTS `rol_tag_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol_tag_permiso` (
  `id_rol` int NOT NULL,
  `id_tag` int NOT NULL,
  `id_permiso` int NOT NULL,
  PRIMARY KEY (`id_rol`,`id_tag`,`id_permiso`),
  KEY `id_tag` (`id_tag`),
  KEY `id_permiso` (`id_permiso`),
  CONSTRAINT `rol_tag_permiso_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`),
  CONSTRAINT `rol_tag_permiso_ibfk_2` FOREIGN KEY (`id_tag`) REFERENCES `tags` (`id_tag`),
  CONSTRAINT `rol_tag_permiso_ibfk_3` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol_tag_permiso`
--

LOCK TABLES `rol_tag_permiso` WRITE;
/*!40000 ALTER TABLE `rol_tag_permiso` DISABLE KEYS */;
INSERT INTO `rol_tag_permiso` VALUES (1,1,1),(1,1,2),(1,1,3),(1,1,4),(2,1,1),(2,1,2),(2,1,3),(5,1,1),(5,1,2),(5,1,3),(5,1,4),(6,1,2),(1,2,1),(1,2,2),(1,2,3),(1,2,4),(5,2,2),(6,2,1),(6,2,2),(6,2,3),(6,2,4),(1,3,1),(1,3,2),(1,3,3),(1,3,4),(7,3,1),(7,3,2),(7,3,3),(1,4,1),(1,4,2),(1,4,3),(1,4,4),(2,4,1),(2,4,2),(2,4,3),(3,4,2),(8,4,2),(11,4,2),(12,4,1),(12,4,2),(12,4,3),(12,4,4),(1,5,1),(1,5,2),(1,5,3),(1,5,4),(2,5,1),(2,5,2),(2,5,3),(3,5,2),(8,5,1),(8,5,2),(8,5,3),(8,5,4),(1,6,1),(1,6,2),(1,6,3),(1,6,4),(2,6,1),(2,6,2),(2,6,3),(3,6,1),(3,6,2),(3,6,3),(9,6,1),(9,6,2),(9,6,3),(1,7,1),(1,7,2),(1,7,3),(1,7,4),(2,7,2),(3,7,2),(4,7,2),(8,7,2),(11,7,1),(11,7,2),(11,7,3),(11,7,4),(1,8,1),(1,8,2),(1,8,3),(1,8,4),(9,8,2),(10,8,1),(10,8,2),(10,8,3),(1,9,1),(1,9,2),(1,9,3),(1,9,4),(8,9,2),(13,9,2),(1,10,1),(1,10,2),(1,10,3),(1,10,4),(13,10,2),(14,10,2);
/*!40000 ALTER TABLE `rol_tag_permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'super_admin','activo'),(2,'admin_negocio','activo'),(3,'operador_negocio','activo'),(4,'invitado_reportes','activo'),(5,'admin_usuarios','activo'),(6,'admin_roles','activo'),(7,'admin_parametros','activo'),(8,'Admin_negocios','activo'),(9,'Mis_negocios','activo'),(10,'Mis_Productos','activo'),(11,'Admin_Productos','activo'),(12,'Admin_categorias','activo'),(13,'Admin_reportes','activo'),(14,'Mis_reportes','activo');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id_tag` int NOT NULL AUTO_INCREMENT,
  `modulos` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_tag`),
  UNIQUE KEY `modulos` (`modulos`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (4,'categoria'),(3,'imagen'),(5,'negocio_gen'),(6,'negocio_prop'),(7,'producto_gen'),(8,'producto_prop'),(9,'reporte_gen'),(10,'reporte_prop'),(2,'rol'),(1,'usuario');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_rol`
--

DROP TABLE IF EXISTS `usuario_rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_rol` (
  `id_usuario` int NOT NULL,
  `id_rol` int NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_rol`),
  KEY `id_rol` (`id_rol`),
  CONSTRAINT `usuario_rol_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `usuario_rol_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_rol`
--

LOCK TABLES `usuario_rol` WRITE;
/*!40000 ALTER TABLE `usuario_rol` DISABLE KEYS */;
INSERT INTO `usuario_rol` VALUES (1,1),(2,2),(18,2),(3,3),(17,3),(4,4),(19,4),(9,5),(23,5),(32,5),(38,5),(46,5),(12,6),(31,6),(13,7),(30,7),(37,7),(11,8),(26,8),(34,8),(45,8),(5,9),(15,9),(24,9),(33,9),(39,9),(47,9),(6,10),(16,10),(25,10),(40,10),(48,10),(7,11),(20,11),(27,11),(36,11),(41,11),(10,12),(21,12),(35,12),(42,12),(14,13),(28,13),(43,13),(49,13),(8,14),(22,14),(29,14),(44,14),(50,14);
/*!40000 ALTER TABLE `usuario_rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identificacion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Angelo_gen','angel@gmail.com','00000001','999999999','perro123','activo'),(2,'Admin Negocios','admin_negocio@demo.com','00000002','988888888','admin123','activo'),(3,'Operador Demo','operador@demo.com','00000003','977777777','oper123','activo'),(4,'Invitado Reportes','invitado@demo.com','00000004','966666666','invitado123','activo'),(5,'Valeria Quispe','valeria.quispe@correo.pe','72384111','987321450','Vq!2025#Lima','activo'),(6,'Diego Huamán','diego.huaman@correo.pe','70458213','989110234','Dh*Arequipa22','activo'),(7,'Mariana Salazar','mariana.salazar@correo.pe','74821099','980443211','Ms$Cafe_09','activo'),(8,'Renzo Paredes','renzo.paredes@correo.pe','73999012','979332118','Rp_88!pollo','activo'),(9,'Camila Rojas','camila.rojas@correo.pe','75110034','992120676','Cr#Mkt_777','activo'),(10,'Javier Luna','javier.luna@correo.pe','73622019','985667210','Jl@negocio_1','activo'),(11,'Lucía Arias','lucia.arias@correo.pe','70844320','981331005','La%prod_2025','activo'),(12,'Sebastián Ortiz','sebastian.ortiz@correo.pe','70129987','997110802','So!report_88','activo'),(13,'Daniela Chávez','daniela.chavez@correo.pe','71003455','986990120','Dc^roles_12','activo'),(14,'Fabricio Molina','fabricio.molina@correo.pe','73440566','976540311','Fm&cat_321','activo'),(15,'Ana María Ríos','ana.rios@correo.pe','72999110','981555412','Ar@cafe_11','activo'),(16,'José Valdivia','jose.valdivia@correo.pe','71234098','982111509','Jv#piza_66','activo'),(17,'Brenda Cárdenas','brenda.cardenas@correo.pe','74888812','983012450','Bc$mini_70','activo'),(18,'Hugo Peña','hugo.pena@correo.pe','70221033','984115223','Hp!mkt_202','activo'),(19,'Paola Medina','paola.medina@correo.pe','71933010','985220119','Pm*neg_303','activo'),(20,'Santiago Flores','santiago.flores@correo.pe','74512087','986332144','Sf_444#prod','activo'),(21,'Ximena Cabrera','ximena.cabrera@correo.pe','70911345','987220981','Xc@img_900','activo'),(22,'Álvaro Torres','alvaro.torres@correo.pe','73655127','988553211','At%admin_07','activo'),(23,'Fiorella Vargas','fiorella.vargas@correo.pe','70400981','989663321','Fv^roles_55','activo'),(24,'Marco Gutiérrez','marco.gutierrez@correo.pe','70011223','990112009','Mg!report_10','activo'),(25,'Carolina Soto','carolina.soto@correo.pe','71199002','991022334','Cs#neg_88','activo'),(26,'Luis Alberto Núñez','luis.nunez@correo.pe','73322110','992788110','Ln$cat_19','activo'),(27,'Gabriela Herrera','gabriela.herrera@correo.pe','72234019','993554200','Gh*prod_77','activo'),(28,'Iván Muñoz','ivan.munoz@correo.pe','71678033','994112901','Im@usr_2025','activo'),(29,'Karla Pineda','karla.pineda@correo.pe','74400912','995009812','Kp!misNeg_12','activo'),(30,'Fernando Palomino','fernando.palomino@correo.pe','70322099','996120334','Fp#admNeg_23','activo'),(31,'Diana Lozano','diana.lozano@correo.pe','73001900','997443112','Dl$misProd_14','activo'),(32,'César Aguilar','cesar.aguilar@correo.pe','72888101','998221120','Ca%admProd_02','activo'),(33,'Ruth Navarro','ruth.navarro@correo.pe','70555123','979101201','Rn^admRep_09','activo'),(34,'Kevin Soto','kevin.soto@correo.pe','70100998','978331105','Ks*misRep_33','activo'),(35,'Mónica Cabrera','monica.cabrera@correo.pe','74011244','977222110','Mc@param_70','activo'),(36,'Joel Bustamante','joel.bustamante@correo.pe','70666001','976991223','Jb!roles_40','activo'),(37,'Patricia Mendoza','patricia.mendoza@correo.pe','74222119','975110876','Pm#usr_71','activo'),(38,'Rodrigo Barrera','rodrigo.barrera@correo.pe','71422091','974992300','Rb$negGen_01','activo'),(39,'Mayra Silva','mayra.silva@correo.pe','73551009','973450112','Ms*cat_202','activo'),(40,'Tomás Delgado','tomas.delgado@correo.pe','71833077','972330991','Td@prod_908','activo'),(41,'Andrea Figueroa','andrea.figueroa@correo.pe','74722001','971220662','Af!img_45','activo'),(42,'Óscar Roldán','oscar.roldan@correo.pe','70711999','970119980','Or#admUsr_10','activo'),(43,'Katherine León','katherine.leon@correo.pe','72000912','969001122','Kl$misNeg_88','activo'),(44,'Bruno Poma','bruno.poma@correo.pe','74666012','968990221','Bp%misProd_19','activo'),(45,'Rafael Castañeda','rafael.castaneda@correo.pe','73122018','967880112','Rc^admProd_55','activo'),(46,'Silvana Tapia','silvana.tapia@correo.pe','71344001','966771100','St*admCat_11','activo'),(47,'Elena Quispe','elena.quispe@correo.pe','72555110','965662299','Eq@admRep_77','activo'),(48,'Miguel Ángel Cano','miguel.cano@correo.pe','70988012','964553112','Mc!misRep_20','activo'),(49,'Piero Zamora','piero.zamora@correo.pe','74100987','963441190','Pz#admNeg_99','activo'),(50,'Nadia Rojas','nadia.rojas@correo.pe','71500771','962330221','Nr$usr_120','activo');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-15  7:27:55