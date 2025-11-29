DROP DATABASE IF EXISTS db_negocios_2025;
CREATE DATABASE IF NOT EXISTS db_negocios_2025
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_negocios_2025;

CREATE TABLE usuarios (
    id_usuario      INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(255) NOT NULL,
    correo          VARCHAR(255) UNIQUE NOT NULL,
    identificacion  VARCHAR(50) NULL,
    telefono        VARCHAR(50) NULL,
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

CREATE TABLE negocios (
    id_negocio      INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(255) NOT NULL,
    descripcion     VARCHAR(255),
    telefono        VARCHAR(50) NULL,
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

CREATE TABLE categorias (
    id_categoria  INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(255) NOT NULL,
    descripcion   VARCHAR(255),
    activo        BOOLEAN DEFAULT 1
);

CREATE TABLE productos (
    id_producto    INT AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(255) NOT NULL,
    codigo         VARCHAR(100) NOT NULL,
    precio         DECIMAL(10,2) NOT NULL,
    url_imagen     VARCHAR(255),
    id_categoria   INT NOT NULL,
    id_negocio     INT NOT NULL,
    activo         BOOLEAN DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(id_categoria) REFERENCES categorias(id_categoria),
    FOREIGN KEY(id_negocio)   REFERENCES negocios(id_negocio)
);

CREATE TABLE parametros_imagenes (
    id_parametro_imagen INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(255) NULL,
    etiqueta        VARCHAR(100) NOT NULL,
    tipo            VARCHAR(50),
    alto_px         INT,
    ancho_px        INT,
    tamano_kb       INT NULL,
    categoria_admin ENUM('negocios','usuarios','productos') NOT NULL,
    formatos_validos VARCHAR(255),
    activo          BOOLEAN DEFAULT 1
);

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

CREATE TABLE imagenes (
    id_imagen     INT AUTO_INCREMENT PRIMARY KEY,
    ruta          VARCHAR(255) NOT NULL,
    id_parametro_imagen INT,
    tipo_recurso  ENUM('usuario','negocio','producto') NOT NULL,
    id_recurso    INT NOT NULL,
    FOREIGN KEY(id_parametro_imagen) REFERENCES parametros_imagenes(id_parametro_imagen)
);

INSERT INTO roles (nombre, descripcion, es_superadmin) VALUES
('super_admin', 'Acceso total al sistema', 1),
('admin', 'Administra usuarios y negocios', 0),
('proveedor', 'Administra sus propios negocios y productos', 0);

INSERT INTO usuarios (nombre, correo, identificacion, telefono, password_hash, estado) VALUES
('Administrador General', 'admin@sistema.com', '00000001', '999999999', 'admin123', 'activo'),
('Proveedor Demo', 'proveedor@sistema.com', '00000002', '988888888', 'prove123', 'activo'),
('Proveedor 2', 'proveedor2@sistema.com', '00000003', '977777777', 'prove234', 'activo');

INSERT INTO usuario_rol (id_usuario, id_rol) VALUES
(1, 1),
(2, 3),
(3, 3);

INSERT INTO negocios (nombre, descripcion, telefono, imagen_logo, estado_disponibilidad, id_propietario)
VALUES 
('Restaurante Doña Pacha', 'Comida criolla y menú diario', '951111111', NULL, 'abierto', 2),
('Pollería El Buen Sabor', 'Pollos a la brasa y parrillas', '952222222', NULL, 'cerrado', 3),
('Cevichería El Marino', 'Ceviches y mariscos frescos', '953333333', NULL, 'abierto', 2);

INSERT INTO horarios_negocio (id_negocio, dia_semana, hora_apertura, hora_cierre, cerrado) VALUES
(1, 'lunes',    '09:00:00', '16:00:00', 0),
(1, 'martes',   '09:00:00', '16:00:00', 0),
(1, 'miercoles','09:00:00', '16:00:00', 0),
(1, 'jueves',   '09:00:00', '16:00:00', 0),
(1, 'viernes',  '09:00:00', '16:00:00', 0),
(1, 'sabado',   '10:00:00', '15:00:00', 0),
(1, 'domingo',  NULL,       NULL,       1);

INSERT INTO categorias (nombre, descripcion) VALUES
('Entradas', 'Platos ligeros para empezar'),
('Platos de fondo', 'Platos principales'),
('Bebidas', 'Bebidas frías y calientes'),
('Postres', 'Dulces y postres');

INSERT INTO productos (nombre, codigo, precio, url_imagen, id_categoria, id_negocio) VALUES
('Ceviche clásico',      'PROD-001', 25.00, NULL, 1, 1),
('Lomo saltado',         'PROD-002', 28.50, NULL, 2, 1),
('Inca Kola 500ml',      'PROD-003',  5.00, NULL, 3, 1),
('Mazamorra morada',     'PROD-004',  6.50, NULL, 4, 1);

INSERT INTO parametros_imagenes 
(nombre, etiqueta, tipo, alto_px, ancho_px, tamano_kb, categoria_admin, formatos_validos)
VALUES
('Logo Negocio', 'logo_negocio',  'logo',     300, 300, 200, 'negocios', 'jpg,png'),
('Foto Producto','foto_producto', 'producto', 600, 600, 500, 'productos', 'jpg,png,webp'),
('Avatar Usuario','avatar_usuario','perfil',  200, 200, 150, 'usuarios',  'jpg,png');

INSERT INTO cargas_masivas 
(id_usuario, nombre_archivo, total_registros, registros_exitosos, registros_fallidos, estado, mensaje_error)
VALUES
(2, 'productos_enero.xlsx',   50, 48,  2, 'completado', NULL),
(2, 'productos_febrero.xlsx', 40, 40,  0, 'completado', NULL),
(3, 'productos_marzo.xlsx',   60,  0, 60, 'error', 'Formato de columnas incorrecto');


DELIMITER $$

CREATE PROCEDURE sp_insertar_negocio(
    IN p_nombre VARCHAR(255),
    IN p_descripcion VARCHAR(255),
    IN p_imagen_logo VARCHAR(255),
    IN p_estado_disponibilidad VARCHAR(20),
    IN p_id_propietario INT
)
BEGIN
    INSERT INTO negocios (nombre, descripcion, imagen_logo, estado_disponibilidad, id_propietario)
    VALUES (p_nombre, p_descripcion, p_imagen_logo, p_estado_disponibilidad, p_id_propietario);
END $$

CREATE PROCEDURE sp_insertar_producto(
    IN p_nombre       VARCHAR(255),
    IN p_codigo       VARCHAR(100),
    IN p_precio       DECIMAL(10,2),
    IN p_url_imagen   VARCHAR(255),
    IN p_id_categoria INT,
    IN p_id_negocio   INT
)
BEGIN
    INSERT INTO productos (nombre, codigo, precio, url_imagen, id_categoria, id_negocio)
    VALUES (p_nombre, p_codigo, p_precio, p_url_imagen, p_id_categoria, p_id_negocio);
END $$

CREATE PROCEDURE sp_reporte_productos_por_negocio(
    IN p_id_negocio INT
)
BEGIN
    SELECT 
        n.id_negocio,
        n.nombre          AS negocio,
        p.id_producto,
        p.nombre          AS producto,
        p.codigo,
        p.precio,
        c.nombre          AS categoria
    FROM productos p
    INNER JOIN negocios n   ON p.id_negocio = n.id_negocio
    INNER JOIN categorias c ON p.id_categoria = c.id_categoria
    WHERE p.id_negocio = p_id_negocio
      AND p.activo = 1;
END $$

CREATE PROCEDURE sp_reporte_productos_por_categoria(
    IN p_id_categoria INT
)
BEGIN
    SELECT 
        c.id_categoria,
        c.nombre       AS categoria,
        p.id_producto,
        p.nombre       AS producto,
        p.codigo,
        p.precio,
        n.nombre       AS negocio
    FROM productos p
    INNER JOIN categorias c ON p.id_categoria = c.id_categoria
    INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
    WHERE p.id_categoria = p_id_categoria
      AND p.activo = 1;
END $$

CREATE PROCEDURE sp_reporte_productos_rango_precio(
    IN p_precio_min DECIMAL(10,2),
    IN p_precio_max DECIMAL(10,2),
    IN p_id_negocio INT
)
BEGIN
    SELECT
        p.id_producto,
        p.nombre        AS producto,
        p.codigo,
        p.precio,
        c.nombre        AS categoria,
        n.id_negocio,
        n.nombre        AS negocio
    FROM productos p
    INNER JOIN categorias c ON p.id_categoria = c.id_categoria
    INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
    WHERE p.precio BETWEEN p_precio_min AND p_precio_max
      AND p.activo = 1
      AND (p_id_negocio = 0 OR p.id_negocio = p_id_negocio);
END $$

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
        n.estado_disponibilidad,
        n.fecha_creacion,
        n.activo
    FROM negocios n
    INNER JOIN usuarios u ON n.id_propietario = u.id_usuario
    WHERE n.id_propietario = p_id_propietario;
END $$

CREATE PROCEDURE sp_reporte_cargas_masivas(
    IN p_id_usuario INT,
    IN p_estado     VARCHAR(20)
)
BEGIN
    SELECT 
        cm.id_carga,
        u.id_usuario,
        u.nombre         AS usuario,
        cm.fecha_carga,
        cm.nombre_archivo,
        cm.total_registros,
        cm.registros_exitosos,
        cm.registros_fallidos,
        cm.estado,
        cm.mensaje_error
    FROM cargas_masivas cm
    INNER JOIN usuarios u ON cm.id_usuario = u.id_usuario
    WHERE (p_id_usuario = 0 OR cm.id_usuario = p_id_usuario)
      AND (p_estado = 'todos' OR cm.estado = p_estado);
END $$

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
    LEFT JOIN roles r ON ur.id_rol = r.id_rol
    WHERE u.correo = p_correo
      AND u.estado = 'activo'
    LIMIT 1;
END $$

DELIMITER ;
