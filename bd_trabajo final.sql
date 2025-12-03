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
    estado          ENUM('activo','inactivo') DEFAULT 'activo'
);

CREATE TABLE roles (
    id_rol        INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(100) UNIQUE NOT NULL,
    estado          ENUM('activo','inactivo') DEFAULT 'activo'
    -- consultar que es esto
    );

CREATE TABLE permisos (
    id_permiso    INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(150) NOT NULL,
    tag           VARCHAR(255)
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

-- EL ATRIBUTO DERIVADO DE DISONIBILIDAD SE  PONE EN MODELS NEGOCIO.PHP
CREATE TABLE negocios (
    id_negocio      INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(255) NOT NULL,
    descripcion     VARCHAR(255),
    -- telefono        VARCHAR(50) NULL,
    estado          ENUM('activo','inactivo') DEFAULT 'activo',
    imagen_logo     VARCHAR(255),
    hora_apertura  TIME NOT NULL,
    hora_cierre    TIME NOT NULL,
    id_propietario  INT NOT NULL,
    activo          BOOLEAN DEFAULT 1,
    FOREIGN KEY(id_propietario) REFERENCES usuarios(id_usuario),
    CONSTRAINT check_horario CHECK (hora_cierre > hora_apertura )
);

CREATE TABLE horarios_negocio (
    id_horario    INT AUTO_INCREMENT PRIMARY KEY,
    -- id_negocio    INT NOT NULL,
    dia_semana    ENUM('lunes','martes','miercoles','jueves','viernes','sabado','domingo'),
    estado          ENUM('activo','inactivo') DEFAULT 'inactivo',
    hora_apertura TIME NOT NULL,
    hora_cierre   TIME NOT NULL,
    inactivo       BOOLEAN DEFAULT 0
    -- FOREIGN KEY(id_negocio) REFERENCES negocios(id_negocio)
);

CREATE TABLE categorias (
    id_categoria  INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(255) NOT NULL,
    descripcion   VARCHAR(255),
    estado          ENUM('activo','inactivo') DEFAULT 'inactivo',
    activo        BOOLEAN DEFAULT 0
);

CREATE TABLE productos (
    id_producto    INT AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(255) NOT NULL,
    -- codigo         VARCHAR(100) NOT NULL,
    precio         DECIMAL(10,2) NOT NULL,
    url_imagen     VARCHAR(255),
    estado          ENUM('activo','inactivo') DEFAULT 'activo',
    activo         BOOLEAN DEFAULT 1,
    id_categoria   INT NOT NULL,
    id_negocio     INT NOT NULL,
    FOREIGN KEY(id_categoria) REFERENCES categorias(id_categoria),
    FOREIGN KEY(id_negocio)   REFERENCES negocios(id_negocio)
);

CREATE TABLE parametros_imagenes (
    id_parametro_imagen INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(255) NULL,
    etiqueta        VARCHAR(100) NOT NULL,
    alto_px         INT,
    ancho_px        INT,
    categoria ENUM('negocios','usuarios','productos') NOT NULL,
    formatos_validos ENUM('jpg','png','webp','gif') NOT NULL
);

INSERT INTO roles (nombre, estado) VALUES
('super_admin',      'activo'),
('admin_negocio',    'activo'),
('operador_negocio', 'activo'),
('invitado_reportes','activo');

-- =========================================
INSERT INTO permisos (nombre, tag) VALUES
-- USUARIOS
('crear',        'usuario'),
('editar',       'usuario'),
('visualizar',   'usuario'),

-- ROLES
('crear',            'rol'),
('editar',           'rol'),
('visualizar',       'rol'),

-- CATEGORÍAS
('crear',      'categoria'),
('editar',     'categoria'),
('visualizar', 'categoria'),

-- NEGOCIOS
('crear',        'negocio'),
('editar',       'negocio'),
('visualizar',   'negocio'),

-- PRODUCTOS
('crear',       'producto'),
('editar',      'producto'),
('visualizar',  'producto'),

-- REPORTES
('visualizar',   'reporte');

-- =========================================
-- USUARIOS
-- (uno con TODOS los permisos: Angel)
-- =========================================
INSERT INTO usuarios (nombre, correo, identificacion, telefono, password_hash, estado) VALUES
('Angelo_gen', 'angel@gmail.com',      '00000001', '999999999', 'perro123', 'activo'),
('Admin Negocios',    'admin_negocio@demo.com','00000002','988888888', 'admin123', 'activo'),
('Operador Demo',     'operador@demo.com',    '00000003', '977777777', 'oper123',  'activo'),
('Invitado Reportes', 'invitado@demo.com',    '00000004', '966666666', 'invitado123','activo');


-- =========================================
-- USUARIO_ROL
-- =========================================
INSERT INTO usuario_rol (id_usuario, id_rol) VALUES
(1, 1), -- Angel -> super_admin (TODOS los permisos)
(2, 2), -- Admin Negocios -> admin_negocio
(3, 3), -- Operador Demo  -> operador_negocio
(4, 4); -- Invitado       -> invitado_reportes

INSERT INTO rol_permiso (id_rol, id_permiso) VALUES
(1, 1),(1, 2),(1, 3),
(1, 4),(1, 5),(1, 6),
(1, 7),(1, 8),(1, 9),
(1,10),(1,11),(1,12),
(1,13),(1,14),(1,15),
(1,16);

INSERT INTO rol_permiso (id_rol, id_permiso) VALUES
(2, 1),(2, 2),(2, 3),          -- usuarios
(2, 7),(2, 8),(2, 9),          -- categorías
(2,10),(2,11),(2,12),          -- negocios
(2,13),(2,14),(2,15),          -- productos
(2,16);                        -- ver reportes

INSERT INTO rol_permiso (id_rol, id_permiso) VALUES
(3, 9),                        -- ver categorías
(3,12),                        -- ver negocios
(3,13),(3,14),(3,15),          -- productos
(3,16);                        -- ver reportes

-- 4) INVITADO_REPORTES: solo ver reportes
INSERT INTO rol_permiso (id_rol, id_permiso) VALUES
(4,16);

INSERT INTO negocios (nombre, descripcion, estado, imagen_logo, hora_apertura, hora_cierre, id_propietario, activo) VALUES
('Restaurante Doña Pacha', 'Comida criolla y menú diario', 'activo', NULL, '09:00:00', '16:00:00', 2, 1),
('Pollería El Buen Sabor', 'Pollos a la brasa y parrillas', 'activo', NULL, '12:00:00', '23:00:00', 2, 1),
('Cevichería El Marino',  'Ceviches y mariscos frescos',   'activo', NULL, '10:00:00', '18:00:00', 3, 1);


INSERT INTO horarios_negocio (dia_semana, estado, hora_apertura, hora_cierre, inactivo) VALUES
('lunes',    'activo', '09:00:00', '16:00:00', 0),
('martes',   'activo', '09:00:00', '16:00:00', 0),
('miercoles','activo', '09:00:00', '16:00:00', 0),
('jueves',   'activo', '09:00:00', '16:00:00', 0),
('viernes',  'activo', '09:00:00', '16:00:00', 0),
('sabado',   'activo', '10:00:00', '15:00:00', 0),
('domingo',  'inactivo', '00:00:00', '00:00:00', 1);


INSERT INTO categorias (nombre, descripcion, estado, activo) VALUES
('Entradas',         'Platos ligeros para empezar', 'activo', 1),
('Platos de fondo',  'Platos principales',          'activo', 1),
('Bebidas',          'Bebidas frías y calientes',   'activo', 1),
('Postres',          'Dulces y postres',            'activo', 1);


INSERT INTO productos (nombre, precio, url_imagen, estado, activo, id_categoria, id_negocio) VALUES
('Ceviche clásico',  25.00, NULL, 'activo', 1, 1, 3),
('Lomo saltado',     28.50, NULL, 'activo', 1, 2, 1),
('Inca Kola 500ml',   5.00, NULL, 'activo', 1, 3, 1),
('Mazamorra morada',  6.50, NULL, 'activo', 1, 4, 1);


INSERT INTO parametros_imagenes (nombre, etiqueta, alto_px, ancho_px, categoria, formatos_validos) VALUES
('Logo Negocio',   'logo_negocio',   300, 300, 'negocios', 'png'),
('Foto Producto',  'foto_producto',  600, 600, 'productos','jpg'),
('Avatar Usuario', 'avatar_usuario', 200, 200, 'usuarios', 'jpg');



-- =================================================
--                    FUNCIONES
-- =================================================
DELIMITER $$

CREATE PROCEDURE sp_insertar_negocio(
    IN p_nombre         VARCHAR(255),
    IN p_descripcion    VARCHAR(255),
    IN p_imagen_logo    VARCHAR(255),
    IN p_hora_apertura  TIME,
    IN p_hora_cierre    TIME,
    IN p_id_propietario INT
)
BEGIN
    INSERT INTO negocios (nombre, descripcion, imagen_logo, hora_apertura, hora_cierre, id_propietario)
    VALUES (p_nombre, p_descripcion, p_imagen_logo, p_hora_apertura, p_hora_cierre, p_id_propietario);
END $$


CREATE PROCEDURE sp_insertar_producto(
    IN p_nombre       VARCHAR(255),
    IN p_precio       DECIMAL(10,2),
    IN p_url_imagen   VARCHAR(255),
    IN p_id_categoria INT,
    IN p_id_negocio   INT
)
BEGIN
    INSERT INTO productos (nombre, precio, url_imagen, id_categoria, id_negocio)
    VALUES (p_nombre, p_precio, p_url_imagen, p_id_categoria, p_id_negocio);
END $$

-- ==============================================
-- REPORTE: PRODUCTOS POR NEGOCIO
-- ==============================================
CREATE PROCEDURE sp_reporte_productos_por_negocio(
    IN p_id_negocio INT
)
BEGIN
    SELECT 
        n.id_negocio,
        n.nombre          AS negocio,
        p.id_producto,
        p.nombre          AS producto,
        p.precio,
        c.nombre          AS categoria,
        p.estado,
        p.activo
    FROM productos p
    INNER JOIN negocios n   ON p.id_negocio = n.id_negocio
    INNER JOIN categorias c ON p.id_categoria = c.id_categoria
    WHERE p.id_negocio = p_id_negocio
      AND p.activo = 1;
END $$

-- ==============================================
-- REPORTE: PRODUCTOS POR CATEGORÍA
-- ==============================================
CREATE PROCEDURE sp_reporte_productos_por_categoria(
    IN p_id_categoria INT
)
BEGIN
    SELECT 
        c.id_categoria,
        c.nombre       AS categoria,
        p.id_producto,
        p.nombre       AS producto,
        p.precio,
        n.nombre       AS negocio,
        p.estado,
        p.activo
    FROM productos p
    INNER JOIN categorias c ON p.id_categoria = c.id_categoria
    INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
    WHERE p.id_categoria = p_id_categoria
      AND p.activo = 1;
END $$

-- ==============================================
-- REPORTE: PRODUCTOS POR RANGO DE PRECIO
-- ==============================================
CREATE PROCEDURE sp_reporte_productos_rango_precio(
    IN p_precio_min DECIMAL(10,2),
    IN p_precio_max DECIMAL(10,2),
    IN p_id_negocio INT
)
BEGIN
    SELECT
        p.id_producto,
        p.nombre        AS producto,
        p.precio,
        c.nombre        AS categoria,
        n.id_negocio,
        n.nombre        AS negocio,
        p.estado,
        p.activo
    FROM productos p
    INNER JOIN categorias c ON p.id_categoria = c.id_categoria
    INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
    WHERE p.precio BETWEEN p_precio_min AND p_precio_max
      AND p.activo = 1
      AND (p_id_negocio = 0 OR p.id_negocio = p_id_negocio);
END $$

-- ==============================================
-- REPORTE: NEGOCIOS POR PROPIETARIO
-- (adaptado a columnas reales de negocios)
-- ==============================================
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
        n.estado,
        n.hora_apertura,
        n.hora_cierre,
        n.activo
    FROM negocios n
    INNER JOIN usuarios u ON n.id_propietario = u.id_usuario
    WHERE n.id_propietario = p_id_propietario;
END $$

-- ==============================================
-- OBTENER USUARIO PARA LOGIN
-- (sigue siendo válido con tu BD actual)
-- ==============================================
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


// triggers al 