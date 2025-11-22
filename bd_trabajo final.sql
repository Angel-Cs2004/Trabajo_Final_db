-- ================================================
--  BASE DE DATOS: db_negocios_2025
--  Sistema de negocios + roles + permisos + productos
--  Autor: Daniel Josue Guillen Valcarcel
-- ================================================
DROP DATABASE IF EXISTS db_negocios_2025;
CREATE DATABASE IF NOT EXISTS db_negocios_2025
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_negocios_2025;

-- ================================================
-- 1. USUARIOS / ROLES / PERMISOS
-- ================================================

CREATE TABLE usuarios (
    id_usuario      INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(255) NOT NULL,
    correo          VARCHAR(255) UNIQUE NOT NULL,
    password_hash   VARCHAR(255) NOT NULL,
    estado          ENUM('activo','inactivo') DEFAULT 'activo',
    fecha_registro  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE roles (
    id_rol        INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(100) UNIQUE NOT NULL,
    descripcion   VARCHAR(255),
    es_superadmin BOOLEAN DEFAULT 0
);

CREATE TABLE permisos (
    id_permiso    INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(150) NOT NULL,
    ruta          VARCHAR(255) NOT NULL,
    descripcion   VARCHAR(255)
);

CREATE TABLE usuario_rol (
    id_usuario INT NOT NULL,
    id_rol     INT NOT NULL,
    PRIMARY KEY(id_usuario, id_rol),
    FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY(id_rol)     REFERENCES roles(id_rol)
);

CREATE TABLE rol_permiso (
    id_rol     INT NOT NULL,
    id_permiso INT NOT NULL,
    PRIMARY KEY(id_rol, id_permiso),
    FOREIGN KEY(id_rol) REFERENCES roles(id_rol),
    FOREIGN KEY(id_permiso) REFERENCES permisos(id_permiso)
);

-- ================================================
-- 2. NEGOCIOS Y HORARIOS
-- ================================================

CREATE TABLE negocios (
    id_negocio      INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(255) NOT NULL,
    descripcion     VARCHAR(255),
    imagen_logo     VARCHAR(255),
    estado_disponibilidad ENUM('abierto','cerrado') DEFAULT 'cerrado',
    id_propietario  INT NOT NULL,
    fecha_creacion  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo          BOOLEAN DEFAULT 1,
    FOREIGN KEY(id_propietario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE horarios_negocio (
    id_horario    INT AUTO_INCREMENT PRIMARY KEY,
    id_negocio    INT NOT NULL,
    dia_semana    ENUM('lunes','martes','miercoles','jueves','viernes','sabado','domingo'),
    hora_apertura TIME,
    hora_cierre   TIME,
    cerrado       BOOLEAN DEFAULT 0,
    FOREIGN KEY(id_negocio) REFERENCES negocios(id_negocio)
);

-- ================================================
-- 3. CATEGORÍAS Y PRODUCTOS
-- ================================================

CREATE TABLE categorias (
    id_categoria  INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(255) NOT NULL,
    descripcion   VARCHAR(255),
    activo        BOOLEAN DEFAULT 1
);

CREATE TABLE productos (
    id_producto   INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(255) NOT NULL,
    codigo        VARCHAR(100) NOT NULL,
    precio        DECIMAL(10,2) NOT NULL,
    url_imagen    VARCHAR(255),
    id_categoria  INT NOT NULL,
    id_negocio    INT NOT NULL,
    activo        BOOLEAN DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(id_categoria) REFERENCES categorias(id_categoria),
    FOREIGN KEY(id_negocio)   REFERENCES negocios(id_negocio)
);

-- ================================================
-- 4. PARÁMETROS DE IMÁGENES
-- ================================================

CREATE TABLE parametros_imagenes (
    id_parametro_imagen INT AUTO_INCREMENT PRIMARY KEY,
    etiqueta        VARCHAR(100) NOT NULL,
    tipo            VARCHAR(50),
    alto_px         INT,
    ancho_px        INT,
    categoria_admin ENUM('negocios','usuarios','productos') NOT NULL,
    formatos_validos VARCHAR(255),
    activo          BOOLEAN DEFAULT 1
);

-- ================================================
-- 5. CARGAS MASIVAS
-- ================================================

CREATE TABLE cargas_masivas (
    id_carga           INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario         INT NOT NULL,
    fecha_carga        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nombre_archivo     VARCHAR(255),
    total_registros    INT,
    registros_exitosos INT,
    registros_fallidos INT,
    estado             ENUM('pendiente','completado','error') DEFAULT 'pendiente',
    mensaje_error      VARCHAR(255),
    FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario)
);

-- ================================================
-- 6. (Opcional) Tabla imagenes genérica
-- ================================================

CREATE TABLE imagenes (
    id_imagen     INT AUTO_INCREMENT PRIMARY KEY,
    ruta          VARCHAR(255) NOT NULL,
    id_parametro_imagen INT,
    tipo_recurso  ENUM('usuario','negocio','producto') NOT NULL,
    id_recurso    INT NOT NULL,
    FOREIGN KEY(id_parametro_imagen) REFERENCES parametros_imagenes(id_parametro_imagen)
);

-- ================================================
-- FIN DEL SCRIPT
-- ================================================

INSERT INTO roles (nombre, descripcion, es_superadmin) VALUES
('super_admin', 'Acceso total al sistema', 1),
('admin', 'Administra usuarios y negocios', 0),
('proveedor', 'Administra sus propios negocios y productos', 0);

INSERT INTO usuarios (nombre, correo, password_hash, estado) VALUES
('Administrador General', 'admin@sistema.com', 'admin123', 'activo'),
('Proveedor Demo', 'proveedor@sistema.com', 'prove123', 'activo'),
('Proveedor 2', 'proveedor2@sistema.com', 'prove234', 'activo');

INSERT INTO usuario_rol (id_usuario, id_rol) VALUES
(1, 1),  -- admin general -> super_admin
(2, 3),  -- proveedor demo -> proveedor
(3, 3);  -- proveedor 2 -> proveedor



-- PRUEBA 2
INSERT INTO negocios (nombre, descripcion, imagen_logo, estado_disponibilidad, id_propietario)
VALUES 
('Restaurante Doña Pacha', 'Comida criolla y menú diario', NULL, 'abierto', 2),
('Pollería El Buen Sabor', 'Pollos a la brasa y parrillas', NULL, 'cerrado', 3),
('Cevichería El Marino', 'Ceviches y mariscos frescos', NULL, 'abierto', 2);


-- PRUEBA 3
INSERT INTO horarios_negocio (id_negocio, dia_semana, hora_apertura, hora_cierre, cerrado) VALUES
(1, 'lunes',    '09:00:00', '16:00:00', 0),
(1, 'martes',   '09:00:00', '16:00:00', 0),
(1, 'miercoles','09:00:00', '16:00:00', 0),
(1, 'jueves',   '09:00:00', '16:00:00', 0),
(1, 'viernes',  '09:00:00', '16:00:00', 0),
(1, 'sabado',   '10:00:00', '15:00:00', 0),
(1, 'domingo',  NULL,       NULL,       1);  -- cerrado

-- PRUEBAS 4
INSERT INTO categorias (nombre, descripcion) VALUES
('Entradas', 'Platos ligeros para empezar'),
('Platos de fondo', 'Platos principales'),
('Bebidas', 'Bebidas frías y calientes'),
('Postres', 'Dulces y postres');

INSERT INTO productos (nombre, codigo, precio, url_imagen, id_categoria, id_negocio) VALUES
('Ceviche clásico',      'PROD-001', 25.00, NULL, 1, 1),  -- Entrada
('Lomo saltado',         'PROD-002', 28.50, NULL, 2, 1),  -- Plato de fondo
('Inca Kola 500ml',      'PROD-003',  5.00, NULL, 3, 1),  -- Bebida
('Mazamorra morada',     'PROD-004',  6.50, NULL, 4, 1);  -- Postre


-- PRUEBA 5
INSERT INTO parametros_imagenes 
(etiqueta, tipo, alto_px, ancho_px, categoria_admin, formatos_validos)
VALUES
('logo_negocio',  'logo',      300, 300, 'negocios', 'jpg,png'),
('foto_producto', 'producto',  600, 600, 'productos', 'jpg,png,webp'),
('avatar_usuario','perfil',    200, 200, 'usuarios',  'jpg,png');

INSERT INTO cargas_masivas 
(id_usuario, nombre_archivo, total_registros, registros_exitosos, registros_fallidos, estado, mensaje_error)
VALUES
(2, 'productos_enero.xlsx',   50, 48,  2, 'completado', NULL),
(2, 'productos_febrero.xlsx', 40, 40,  0, 'completado', NULL),
(3, 'productos_marzo.xlsx',   60,  0, 60, 'error', 'Formato de columnas incorrecto');

