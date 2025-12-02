-- ================================================
--  BASE DE DATOS: db_negocios_2025
--  Sistema de negocios + roles + permisos + productos
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
    id_usuario        INT AUTO_INCREMENT PRIMARY KEY,
    cedula_identidad  CHAR(8) UNIQUE NOT NULL,
    nombre            VARCHAR(255) NOT NULL,
    correo            VARCHAR(255) UNIQUE NOT NULL,
    telefono          VARCHAR(20) NULL,
    password_hash     VARCHAR(255) NOT NULL,
    estado            ENUM('activo','inactivo') DEFAULT 'activo',
    -- fecha_registro    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT ck_cedula_identidad CHECK (cedula_identidad REGEXP '^[0-9]{8}$')
);


CREATE TABLE roles (
    id_rol        INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(100) UNIQUE NOT NULL,
    -- descripcion   VARCHAR(255),
    asociado      ENUM('superadmin','admin','proveedor') NOT NULL DEFAULT 'proveedor',
    estado        ENUM('activo','inactivo') DEFAULT 'activo'
);

-- CREATE TABLE permisos (
--     id_permiso    INT AUTO_INCREMENT PRIMARY KEY,
--     nombre        VARCHAR(150) NOT NULL,
--     ruta          VARCHAR(255) NOT NULL,
--     descripcion   VARCHAR(255)
-- );

CREATE TABLE usuario_rol (
    id_usuario INT NOT NULL,
    id_rol     INT NOT NULL,
    PRIMARY KEY(id_usuario, id_rol),
    FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY(id_rol)     REFERENCES roles(id_rol)
);

CREATE TABLE rol_permiso (
    id_rol     INT NOT NULL,
    permiso  VARCHAR(150) NOT NULL,
    PRIMARY KEY(id_rol, permiso),
    FOREIGN KEY(id_rol) REFERENCES roles(id_rol)
);

-- ================================================
-- 2. NEGOCIOS Y HORARIOS
-- ================================================

CREATE TABLE negocios (
    id_negocio      INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(255) NOT NULL,
    descripcion     VARCHAR(255),
    imagen_logo     VARCHAR(255),
    -- CORRECIONES PERTINENTES
    disponibilidad  ENUM('abierto','cerrado') DEFAULT 'cerrado',
    estado          ENUM('activo','inactivo') DEFAULT 'activo',
    id_propietario  INT NOT NULL,
  --  fecha_creacion  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo          BOOLEAN DEFAULT 1,
    FOREIGN KEY(id_propietario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE horarios_negocio (
    id_horario    INT AUTO_INCREMENT PRIMARY KEY,
    id_negocio    INT NOT NULL,
    -- dia_semana    ENUM('lunes','martes','miercoles','jueves','viernes','sabado','domingo'),
    hora_apertura TIME,
    hora_cierre   TIME,
    estado          ENUM('abierto','cerrado') DEFAULT 'abierto',
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
    estado        ENUM('activo','inactivo') DEFAULT 'activo'
);

CREATE TABLE productos (
    id_producto   INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(255) NOT NULL,
    -- codigo        VARCHAR(100) UNIQUE,
    precio        DECIMAL(10,2) NOT NULL,
    url_imagen    VARCHAR(255),
    id_categoria  INT NOT NULL,
    id_negocio    INT NOT NULL,
    estado        ENUM('activo','inactivo') DEFAULT 'activo',
    FOREIGN KEY(id_categoria) REFERENCES categorias(id_categoria),
    FOREIGN KEY(id_negocio)   REFERENCES negocios(id_negocio)
);

-- ================================================
-- 4. PARÁMETROS DE IMÁGENES
-- ================================================

CREATE TABLE parametros_imagenes (
    id_parametro_imagen INT AUTO_INCREMENT PRIMARY KEY,
    nombre           VARCHAR(100) NOT NULL,
    etiqueta         VARCHAR(100) NOT NULL,
    alto_px          INT,
    ancho_px         INT,
    categoria_admin  ENUM('negocios','usuarios','productos') NOT NULL,
    formatos_validos VARCHAR(255)
    -- estado           ENUM('activo','inactivo') DEFAULT 'activo'
);

-- ================================================
-- 5. CARGAS MASIVAS
-- ================================================

-- CREATE TABLE cargas_masivas (
--     id_carga        INT AUTO_INCREMENT PRIMARY KEY,
--     id_usuario      INT NOT NULL,           -- quien hizo la carga
--     id_negocio      INT NOT NULL,           -- negocio afectado
--     fecha_subida    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     file_path       VARCHAR(255) NOT NULL,  -- ruta del archivo .xlsx
--     total_rows      INT DEFAULT 0,
--     processed_rows  INT DEFAULT 0,
--     failed_rows     INT DEFAULT 0,
--     duplicated_rows INT DEFAULT 0,
--     estado          ENUM('pendiente','procesado','fallido','reprocesado') DEFAULT 'pendiente',
--     error_log       TEXT NULL,
--     FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
--     FOREIGN KEY(id_negocio) REFERENCES negocios(id_negocio)
-- );

-- ================================================
-- FIN DEL SCRIPT DE TABLAS
-- ================================================

-- ================================================
-- INSERTS DE PRUEBA
-- ================================================

-- ROLES
INSERT INTO roles (nombre, asociado, estado) VALUES
('super_admin', 'superadmin', 'activo'),
('admin',       'admin',      'activo'),
('proveedor',   'proveedor',  'activo');

-- USUARIOS
INSERT INTO usuarios (cedula_identidad, nombre, correo, password_hash, estado) VALUES
('00000001','Administrador General', 'admin@sistema.com',     'admin123', 'activo'),
('00000002','Proveedor Demo',        'proveedor@sistema.com', 'prove123', 'activo'),
('00000003','Proveedor 2',           'proveedor2@sistema.com','prove234', 'activo');

-- USUARIO_ROL
INSERT INTO usuario_rol (id_usuario, id_rol) VALUES
(1, 1),  -- admin general -> super_admin
(2, 3),  -- proveedor demo -> proveedor
(3, 3);  -- proveedor 2 -> proveedor

-- NEGOCIOS
INSERT INTO negocios (nombre, descripcion, imagen_logo, disponibilidad, estado, id_propietario) VALUES 
('Restaurante Doña Pacha', 'Comida criolla y menú diario',  NULL, 'abierto', 'activo', 2),
('Pollería El Buen Sabor', 'Pollos a la brasa y parrillas', NULL, 'cerrado', 'activo', 3),
('Cevichería El Marino',   'Ceviches y mariscos frescos',  NULL, 'abierto', 'activo', 2);

-- HORARIOS_NEGOCIO
INSERT INTO horarios_negocio (id_negocio, dia_semana, hora_apertura, hora_cierre, cerrado) VALUES
(1, 'lunes',     '09:00:00', '16:00:00', 0),
(1, 'martes',    '09:00:00', '16:00:00', 0),
(1, 'miercoles', '09:00:00', '16:00:00', 0),
(1, 'jueves',    '09:00:00', '16:00:00', 0),
(1, 'viernes',   '09:00:00', '16:00:00', 0),
(1, 'sabado',    '10:00:00', '15:00:00', 0),
(1, 'domingo',   NULL,       NULL,       1);  -- cerrado

-- CATEGORÍAS
INSERT INTO categorias (nombre, descripcion) VALUES
('Entradas',        'Platos ligeros para empezar'),
('Platos de fondo', 'Platos principales'),
('Bebidas',         'Bebidas frías y calientes'),
('Postres',         'Dulces y postres');

-- PRODUCTOS
INSERT INTO productos (nombre, precio, url_imagen, id_categoria, id_negocio, estado) VALUES
('Ceviche clásico',  25.00, NULL, 1, 1, 'activo'),  -- Entrada
('Lomo saltado',     28.50, NULL, 2, 1, 'activo'),  -- Plato de fondo
('Inca Kola 500ml',   5.00, NULL, 3, 1, 'activo'),  -- Bebida
('Mazamorra morada',  6.50, NULL, 4, 1, 'activo');  -- Postre

-- PARÁMETROS_IMAGENES
INSERT INTO parametros_imagenes 
(nombre, etiqueta, alto_px, ancho_px, categoria_admin, formatos_validos)
VALUES
('logo_negocio',   'Logo de negocio',  300, 300, 'negocios', 'jpg,png'),
('foto_producto',  'Foto de producto', 600, 600, 'productos','jpg,png,webp'),
('avatar_usuario', 'Avatar usuario',   200, 200, 'usuarios', 'jpg,png');

-- CARGAS_MASIVAS
-- (La tabla está comentada, por eso estos INSERTS también se dejan comentados
--  para que el script completo se ejecute sin errores.)
-- INSERT INTO cargas_masivas
-- (id_usuario, id_negocio, file_path, total_rows, processed_rows, failed_rows, duplicated_rows, estado, error_log)
-- VALUES
-- (2, 1, '/uploads/productos_enero.xlsx',   50, 48,  2, 0, 'procesado',   NULL),
-- (2, 1, '/uploads/productos_febrero.xlsx', 40, 40,  0, 0, 'procesado',   NULL),
-- (3, 1, '/uploads/productos_marzo.xlsx',   60,  0, 60, 0, 'fallido', 'Formato de columnas incorrecto');

-- ================================================
-- PROCEDIMIENTOS ALMACENADOS
-- ================================================

-- ENUNCIADO: crea un nuevo negocio
DELIMITER $$

CREATE PROCEDURE sp_insertar_negocio(
    IN p_nombre VARCHAR(255),
    IN p_descripcion VARCHAR(255),
    IN p_imagen_logo VARCHAR(255),
    IN p_estado_disponibilidad VARCHAR(20),  -- 'abierto' o 'cerrado'
    IN p_id_propietario INT
)
BEGIN
    INSERT INTO negocios (nombre, descripcion, imagen_logo, disponibilidad, id_propietario)
    VALUES (p_nombre, p_descripcion, p_imagen_logo, p_estado_disponibilidad, p_id_propietario);
END $$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_insertar_producto(
    IN p_nombre       VARCHAR(255),
    -- IN p_codigo       VARCHAR(100),   -- eliminado porque la columna está comentada
    IN p_precio       DECIMAL(10,2),
    IN p_url_imagen   VARCHAR(255),
    IN p_id_categoria INT,
    IN p_id_negocio   INT
)
BEGIN
    INSERT INTO productos (nombre, precio, url_imagen, id_categoria, id_negocio)
    VALUES (p_nombre, p_precio, p_url_imagen, p_id_categoria, p_id_negocio);
END $$

DELIMITER ;

-- ===========================================
-- REPORTES
-- ===========================================

DELIMITER $$

CREATE PROCEDURE sp_reporte_productos_por_negocio(
    IN p_id_negocio INT
)
BEGIN
    SELECT 
        n.id_negocio,
        n.nombre          AS negocio,
        p.id_producto,
        p.nombre          AS producto,
        -- p.codigo,   -- columna no existe
        p.precio,
        c.nombre          AS categoria
    FROM productos p
    INNER JOIN negocios n   ON p.id_negocio = n.id_negocio
    INNER JOIN categorias c ON p.id_categoria = c.id_categoria
    WHERE p.id_negocio = p_id_negocio
      AND p.estado = 'activo';
END $$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_reporte_productos_por_categoria(
    IN p_id_categoria INT
)
BEGIN
    SELECT 
        c.id_categoria,
        c.nombre       AS categoria,
        p.id_producto,
        p.nombre       AS producto,
        -- p.codigo,
        p.precio,
        n.nombre       AS negocio
    FROM productos p
    INNER JOIN categorias c ON p.id_categoria = c.id_categoria
    INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
    WHERE p.id_categoria = p_id_categoria
      AND p.estado = 'activo';
END $$

DELIMITER ;

DELIMITER $$

-- “Muéstrame todos los productos cuyo precio esté entre X e Y,
--  y opcionalmente de un negocio específico.”
CREATE PROCEDURE sp_reporte_productos_rango_precio(
    IN p_precio_min DECIMAL(10,2),
    IN p_precio_max DECIMAL(10,2),
    IN p_id_negocio INT  -- 0 = todos los negocios
)
BEGIN
    SELECT
        p.id_producto,
        p.nombre        AS producto,
        -- p.codigo,
        p.precio,
        c.nombre        AS categoria,
        n.id_negocio,
        n.nombre        AS negocio
    FROM productos p
    INNER JOIN categorias c ON p.id_categoria = c.id_categoria
    INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
    WHERE p.precio BETWEEN p_precio_min AND p_precio_max
      AND p.estado = 'activo'
      AND (p_id_negocio = 0 OR p.id_negocio = p_id_negocio);
END $$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_reporte_negocios_por_propietario(
    IN p_id_propietario INT
)
BEGIN
    SELECT 
        u.id_usuario,
        u.nombre        AS propietario,
        n.id_negocio,
        n.nombre        AS negocio,
        n.descripcion,
        n.disponibilidad,
        -- n.fecha_creacion,  -- columna comentada en la tabla
        n.activo
    FROM negocios n
    INNER JOIN usuarios u ON n.id_propietario = u.id_usuario
    WHERE n.id_propietario = p_id_propietario;
END $$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_reporte_cargas_masivas(
    IN p_id_usuario INT,        -- 0 = todos los usuarios
    IN p_estado     VARCHAR(20) -- 'pendiente','procesado','fallido','reprocesado','todos'
)
BEGIN
    -- OJO: esta SP usa la tabla cargas_masivas que está comentada arriba.
    -- La definición es correcta, pero solo funcionará cuando definas la tabla.
    SELECT 
        cm.id_carga,
        u.id_usuario,
        u.nombre          AS usuario,
        cm.fecha_subida,
        cm.file_path,
        cm.total_rows,
        cm.processed_rows,
        cm.failed_rows,
        cm.duplicated_rows,
        cm.estado,
        cm.error_log
    FROM cargas_masivas cm
    INNER JOIN usuarios u ON cm.id_usuario = u.id_usuario
    WHERE (p_id_usuario = 0 OR cm.id_usuario = p_id_usuario)
      AND (p_estado = 'todos' OR cm.estado = p_estado);
END $$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_obtener_usuario_login(
    IN p_correo VARCHAR(255)
)
BEGIN
    SELECT 
        u.id_usuario,
        u.nombre,
        u.correo,
        u.password_hash,
        u.estado,
        r.nombre AS rol
    FROM usuarios u
    LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
    LEFT JOIN roles r        ON ur.id_rol    = r.id_rol
    WHERE u.correo = p_correo
      AND u.estado = 'activo'
    LIMIT 1;
END $$

DELIMITER ;

-- ================================================
-- TRIGGERS
-- ================================================

DELIMITER $$

CREATE TRIGGER trg_no_desactivar_rol_con_usuarios
BEFORE UPDATE ON roles
FOR EACH ROW
BEGIN
    -- Si se intenta pasar de activo a inactivo
    IF NEW.estado = 'inactivo' AND OLD.estado = 'activo' THEN
        
        -- Verifica si hay usuarios con ese rol
        IF EXISTS (
            SELECT 1
            FROM usuario_rol ur
            WHERE ur.id_rol = OLD.id_rol
        ) THEN
            -- Lanza error y cancela el UPDATE
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se puede desactivar un rol con usuarios asociados';
        END IF;
    END IF;
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER trg_no_desactivar_categoria_con_productos
BEFORE UPDATE ON categorias
FOR EACH ROW
BEGIN
    -- Solo validamos cuando se quiere cambiar de 'activo' a 'inactivo'
    IF NEW.estado = 'inactivo' AND OLD.estado = 'activo' THEN
        
        -- Verificamos si existen productos asociados a esta categoría
        IF EXISTS (
            SELECT 1
            FROM productos
            WHERE id_categoria = OLD.id_categoria
        ) THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se puede desactivar la categoría porque tiene productos asociados.';
        END IF;

    END IF;
END $$

DELIMITER ;

-- ================================================
-- FIN DEL SCRIPT
-- ================================================
