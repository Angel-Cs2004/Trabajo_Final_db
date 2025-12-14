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
            estado        ENUM('activo','inactivo') DEFAULT 'activo'
        );

        CREATE TABLE permisos (
            id_permiso    INT AUTO_INCREMENT PRIMARY KEY,
            nombre        VARCHAR(150) NOT NULL,
            CRUD          ENUM('CREATE','READ','UPDATE','DELETE') NOT NULL
        );

        CREATE TABLE tags (
            id_tag   INT AUTO_INCREMENT PRIMARY KEY,
            modulos  VARCHAR(50) NOT NULL UNIQUE -- 'usuario','producto','negocio',...
        );

        CREATE TABLE rol_tag_permiso (
            id_rol     INT NOT NULL,
            id_tag     INT NOT NULL,
            id_permiso INT NOT NULL,
            PRIMARY KEY (id_rol, id_tag, id_permiso),
            FOREIGN KEY (id_rol)     REFERENCES roles(id_rol),
            FOREIGN KEY (id_tag)     REFERENCES tags(id_tag),
            FOREIGN KEY (id_permiso) REFERENCES permisos(id_permiso)
        );

        CREATE TABLE usuario_rol (
            id_usuario INT NOT NULL,
            id_rol     INT NOT NULL,
            PRIMARY KEY(id_usuario, id_rol),
            FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
            FOREIGN KEY(id_rol)     REFERENCES roles(id_rol)
        );

        -- EL ATRIBUTO DERIVADO DE DISPONIBILIDAD SE PONE EN MODELS NEGOCIO.PHP
        CREATE TABLE negocios (
            id_negocio      INT AUTO_INCREMENT PRIMARY KEY,
            nombre          VARCHAR(255) NOT NULL,
            descripcion     VARCHAR(255),
            estado          ENUM('activo','inactivo') DEFAULT 'activo',
            imagen_logo     VARCHAR(255),
            hora_apertura   TIME NOT NULL,
            hora_cierre     TIME NOT NULL,
            id_propietario  INT NOT NULL,
            CONSTRAINT unq_negocio_propietario_nombre
                UNIQUE (id_propietario, nombre),
                
            FOREIGN KEY(id_propietario) REFERENCES usuarios(id_usuario),
            CONSTRAINT check_horario CHECK (hora_cierre > hora_apertura)
        );

        CREATE TABLE horarios_negocio (
            id_horario    INT AUTO_INCREMENT PRIMARY KEY,
            dia_semana    ENUM('lunes','martes','miercoles','jueves','viernes','sabado','domingo'),
            estado        ENUM('activo','inactivo') DEFAULT 'inactivo',
            hora_apertura TIME NOT NULL,
            hora_cierre   TIME NOT NULL
        );

        CREATE TABLE categorias (
            id_categoria  INT AUTO_INCREMENT PRIMARY KEY,
            nombre        VARCHAR(255) NOT NULL,
            descripcion   VARCHAR(255),
            estado        ENUM('activo','inactivo') DEFAULT 'inactivo'
        );

        CREATE TABLE productos (
            id_producto    INT AUTO_INCREMENT PRIMARY KEY,
            nombre         VARCHAR(255) NOT NULL,
            precio         DECIMAL(10,2) NOT NULL,
            url_imagen     VARCHAR(255),
            estado         ENUM('activo','inactivo') DEFAULT 'activo',
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
            categoria       ENUM('negocios','usuarios','productos') NOT NULL,
            formatos_validos ENUM('jpg','png','webp','gif') NOT NULL
        );

        -- =========================================
        -- ROLES
        -- =========================================
        INSERT INTO roles (nombre, estado) VALUES
        ('super_admin',      'activo'),
        ('admin_negocio',    'activo'),
        ('operador_negocio', 'activo'),
        ('invitado_reportes','activo');

        -- =========================================
        -- TAGS (módulos o áreas del sistema)
        -- =========================================
        -- 1: usuario, 2: rol, 3: imagen, 4: categoria, 5: negocio, 6: producto, 7: reporte
        INSERT INTO tags (modulos) VALUES
        ('usuario'),
        ('rol'),
        ('imagen'),
        ('categoria'),
        ('negocio_gen'),
        ('negocio_prop'),
        ('producto_gen'),
        ('producto_prop'),
        ('reporte_gen'),
        ('reporte_prop');

        -- =========================================
        -- PERMISOS (tipo de operación)
        -- =========================================
        INSERT INTO permisos (nombre, CRUD) VALUES
        ('crear',      'CREATE'),
        ('visualizar', 'READ'),
        ('editar',     'UPDATE'),
        ('eliminar',   'DELETE');

        -- =========================================
        -- USUARIOS
        -- =========================================
        INSERT INTO usuarios (nombre, correo, identificacion, telefono, password_hash, estado) VALUES
        ('Angelo_gen',       'angel@gmail.com',        '00000001', '999999999', 'perro123',    'activo'),
        ('Admin Negocios',   'admin_negocio@demo.com', '00000002', '988888888', 'admin123',    'activo'),
        ('Operador Demo',    'operador@demo.com',      '00000003', '977777777', 'oper123',     'activo'),
        ('Invitado Reportes','invitado@demo.com',      '00000004', '966666666', 'invitado123', 'activo');

        -- =========================================
        -- USUARIO_ROL
        -- =========================================
        INSERT INTO usuario_rol (id_usuario, id_rol) VALUES
        (1, 1), -- Angelo -> super_admin
        (2, 2), -- Admin Negocios -> admin_negocio
        (3, 3), -- Operador Demo  -> operador_negocio
        (4, 4); -- Invitado       -> invitado_reportes

        -- =========================================
        -- ROL_TAG_PERMISO
        -- =========================================

        -- SUPER_ADMIN: TODOS los permisos (1..4) para TODOS los tags (1..7)
        INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso) VALUES
        (1,1,1),(1,1,2),(1,1,3),(1,1,4),
        (1,2,1),(1,2,2),(1,2,3),(1,2,4),
        (1,3,1),(1,3,2),(1,3,3),(1,3,4),
        (1,4,1),(1,4,2),(1,4,3),(1,4,4),
        (1,5,1),(1,5,2),(1,5,3),(1,5,4),
        (1,6,1),(1,6,2),(1,6,3),(1,6,4),
        (1,7,1),(1,7,2),(1,7,3),(1,7,4);

        -- ADMIN_NEGOCIO (igual que antes, solo usando id_permiso 1..3)
        INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso) VALUES
        (2, 1, 1),(2, 1, 3),(2, 1, 2), -- usuario   C/U/R
        (2, 4, 1),(2, 4, 3),(2, 4, 2), -- categoria C/U/R
        (2, 5, 1),(2, 5, 3),(2, 5, 2), -- negocio   C/U/R
        (2, 6, 1),(2, 6, 3),(2, 6, 2), -- producto  C/U/R
        (2, 7, 2);                     -- reporte   READ

        -- OPERADOR_NEGOCIO
        INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso) VALUES
        (3, 4, 2), -- categoria READ
        (3, 5, 2), -- negocio   READ
        (3, 6, 1),(3, 6, 3),(3, 6, 2), -- producto C/U/R
        (3, 7, 2); -- reporte READ

        -- INVITADO_REPORTES
        INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso) VALUES
        (4, 7, 2); -- reporte READ


        -- =========================================
        -- NEGOCIOS
        -- =========================================
        INSERT INTO negocios (nombre, descripcion, estado, imagen_logo, hora_apertura, hora_cierre, id_propietario) VALUES
        ('Restaurante Doña Pacha', 'Comida criolla y menú diario', 'activo', NULL, '09:00:00', '16:00:00', 2),
        ('Pollería El Buen Sabor', 'Pollos a la brasa y parrillas', 'activo', NULL, '12:00:00', '23:00:00', 2),
        ('Cevichería El Marino',  'Ceviches y mariscos frescos',   'activo', NULL, '10:00:00', '18:00:00', 3);

        -- =========================================
        -- HORARIOS_NEGOCIO
        -- =========================================
        INSERT INTO horarios_negocio (dia_semana, estado, hora_apertura, hora_cierre) VALUES
        ('lunes',    'activo',   '09:00:00', '16:00:00'),
        ('martes',   'activo',   '09:00:00', '16:00:00'),
        ('miercoles','activo',   '09:00:00', '16:00:00'),
        ('jueves',   'activo',   '09:00:00', '16:00:00'),
        ('viernes',  'activo',   '09:00:00', '16:00:00'),
        ('sabado',   'activo',   '10:00:00', '15:00:00'),
        ('domingo',  'inactivo', '00:00:00', '00:00:00');

        -- =========================================
        -- CATEGORÍAS
        -- =========================================
        INSERT INTO categorias (nombre, descripcion, estado) VALUES
        ('Entradas',         'Platos ligeros para empezar', 'activo'),
        ('Platos de fondo',  'Platos principales',          'activo'),
        ('Bebidas',          'Bebidas frías y calientes',   'activo'),
        ('Postres',          'Dulces y postres',            'activo');

        -- =========================================
        -- PRODUCTOS
        -- =========================================
        INSERT INTO productos (nombre, precio, url_imagen, estado, id_categoria, id_negocio) VALUES
        ('Ceviche clásico',  25.00, NULL, 'activo', 1, 3),
        ('Lomo saltado',     28.50, NULL, 'activo', 2, 1),
        ('Inca Kola 500ml',   5.00, NULL, 'activo', 3, 1),
        ('Mazamorra morada',  6.50, NULL, 'activo', 4, 1);

        -- =========================================
        -- PARÁMETROS DE IMÁGENES
        -- =========================================
        INSERT INTO parametros_imagenes (nombre, etiqueta, alto_px, ancho_px, categoria, formatos_validos) VALUES
        ('Logo Negocio',   'logo_negocio',   300, 300, 'negocios', 'png'),
        ('Foto Producto',  'foto_producto',  600, 600, 'productos','jpg'),
        ('Avatar Usuario', 'avatar_usuario', 200, 200, 'usuarios', 'jpg');

        -- =================================================
        --                    PROCEDIMIENTOS
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
            INSERT INTO productos (nombre, precio, url_imagen, estado, id_categoria, id_negocio)
            VALUES (p_nombre, p_precio, p_url_imagen, 'activo', p_id_categoria, p_id_negocio);
        END $$

        -- REPORTE: PRODUCTOS POR NEGOCIO
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
                p.estado
            FROM productos p
            INNER JOIN negocios n   ON p.id_negocio   = n.id_negocio
            INNER JOIN categorias c ON p.id_categoria = c.id_categoria
            WHERE p.id_negocio = p_id_negocio
            AND p.estado = 'activo';
        END $$

        -- REPORTE: PRODUCTOS POR CATEGORÍA
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
                p.estado
            FROM productos p
            INNER JOIN categorias c ON p.id_categoria = c.id_categoria
            INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
            WHERE p.id_categoria = p_id_categoria
            AND p.estado = 'activo';
        END $$

        -- REPORTE: PRODUCTOS POR RANGO DE PRECIO
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
                p.estado
            FROM productos p
            INNER JOIN categorias c ON p.id_categoria = c.id_categoria
            INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
            WHERE p.precio BETWEEN p_precio_min AND p_precio_max
            AND p.estado = 'activo'
            AND (p_id_negocio = 0 OR p.id_negocio = p_id_negocio);
        END $$

        -- REPORTE: NEGOCIOS POR PROPIETARIO
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
                n.hora_cierre
            FROM negocios n
            INNER JOIN usuarios u ON n.id_propietario = u.id_usuario
            WHERE n.id_propietario = p_id_propietario;
        END $$

        -- OBTENER USUARIO PARA LOGIN
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
        INSERT INTO negocios (nombre, descripcion, estado, imagen_logo, hora_apertura, hora_cierre, id_propietario) VALUES
        ('Café Angelo', 'Cafetería de especialidad y postres', 'activo', NULL, '08:00:00', '18:00:00', 1),
        ('Pizzería Gen', 'Pizzas artesanales y pastas',        'activo', NULL, '12:00:00', '23:00:00', 1),
        ('Mini Market Angelo', 'Tienda de abarrotes y snacks', 'activo', NULL, '09:00:00', '21:00:00', 1);

        INSERT INTO productos (nombre, precio, url_imagen, estado, id_categoria, id_negocio) VALUES
        -- =========================
        -- 1. Restaurante Doña Pacha
        -- =========================
        ('Papa a la huancaína',       10.00, NULL, 'activo', 1, 1),
        ('Ocopa arequipeña',          11.00, NULL, 'activo', 1, 1),
        ('Ají de gallina',            18.50, NULL, 'activo', 2, 1),
        ('Seco de res con frejoles',  22.00, NULL, 'activo', 2, 1),
        ('Chicha morada vaso',         4.00, NULL, 'activo', 3, 1),

        -- =========================
        -- 2. Pollería El Buen Sabor
        -- =========================
        ('1/4 de pollo con papas',    20.00, NULL, 'activo', 2, 2),
        ('1/2 pollo familiar',        36.00, NULL, 'activo', 2, 2),
        ('Pollo broaster (porción)',  18.00, NULL, 'activo', 2, 2),
        ('Ensalada criolla',           7.00, NULL, 'activo', 1, 2),
        ('Gaseosa 1.5L',              10.00, NULL, 'activo', 3, 2),

        -- =========================
        -- 3. Cevichería El Marino
        -- =========================
        ('Ceviche mixto',             30.00, NULL, 'activo', 2, 3),
        ('Ceviche de pota',           22.00, NULL, 'activo', 2, 3),
        ('Jalea marina',              32.00, NULL, 'activo', 2, 3),
        ('Leche de tigre vaso',       12.00, NULL, 'activo', 1, 3),
        ('Limonada jarra',            15.00, NULL, 'activo', 3, 3),

        -- =========================
        -- 4. Café Angelo
        -- =========================
        ('Americano',                  7.00, NULL, 'activo', 3, 4),
        ('Capuccino',                 10.00, NULL, 'activo', 3, 4),
        ('Latte de vainilla',         11.50, NULL, 'activo', 3, 4),
        ('Cheesecake de frutos rojos',14.00, NULL, 'activo', 4, 4),
        ('Brownie con helado',        12.00, NULL, 'activo', 4, 4),

        -- =========================
        -- 5. Pizzería Gen
        -- =========================
        ('Pizza margarita personal',  18.00, NULL, 'activo', 2, 5),
        ('Pizza pepperoni mediana',   32.00, NULL, 'activo', 2, 5),
        ('Pizza cuatro quesos grande',45.00, NULL, 'activo', 2, 5),
        ('Pan de ajo (porción)',       9.00, NULL, 'activo', 1, 5),
        ('Gaseosa personal',           5.00, NULL, 'activo', 3, 5),

        -- =========================
        -- 6. Mini Market Angelo
        -- =========================
        ('Agua sin gas 625ml',         3.50, NULL, 'activo', 3, 6),
        ('Galletas de vainilla',       2.50, NULL, 'activo', 1, 6),
        ('Papas fritas en bolsa',      4.00, NULL, 'activo', 1, 6),
        ('Chocolate de leche barra',   3.00, NULL, 'activo', 4, 6),
        ('Energizante lata',           7.50, NULL, 'activo', 3, 6);

        DELIMITER $$

        CREATE TRIGGER trg_negocios_max2_mismo_nombre
        BEFORE INSERT ON negocios
        FOR EACH ROW
        BEGIN
            DECLARE v_count INT;

            SELECT COUNT(*)
            INTO v_count
            FROM negocios
            WHERE id_propietario = NEW.id_propietario
            AND nombre = NEW.nombre;

            IF v_count >= 2 THEN
                SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Máximo 2 negocios con el mismo nombre por propietario';
            END IF;
        END$$

        DELIMITER ;

        DELIMITER $$

        CREATE TRIGGER trg_negocios_max2_mismo_nombre_upd
        BEFORE UPDATE ON negocios
        FOR EACH ROW
        BEGIN
            DECLARE v_count INT;

            -- Solo validamos si cambia nombre o propietario
            IF NEW.nombre <> OLD.nombre OR NEW.id_propietario <> OLD.id_propietario THEN

                SELECT COUNT(*)
                INTO v_count
                FROM negocios
                WHERE id_propietario = NEW.id_propietario
                AND nombre = NEW.nombre
                AND id_negocio <> OLD.id_negocio;

                IF v_count >= 2 THEN
                    SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Máximo 2 negocios con el mismo nombre por propietario';
                END IF;
            END IF;
        END$$

        DELIMITER ;
        USE db_negocios_2025;

        DROP PROCEDURE IF EXISTS sp_reporte_usuarios_roles;
        DELIMITER $$
        CREATE PROCEDURE sp_reporte_usuarios_roles(
            IN p_id_rol INT,
            IN p_estado VARCHAR(10)  -- 'activo','inactivo','todos'
        )
        BEGIN
            SELECT 
                u.id_usuario,
                u.nombre,
                u.correo,
                u.identificacion,
                u.telefono,
                u.estado,
                r.id_rol,
                r.nombre AS rol
            FROM usuarios u
            LEFT JOIN usuario_rol ur ON ur.id_usuario = u.id_usuario
            LEFT JOIN roles r        ON r.id_rol = ur.id_rol
            WHERE (p_id_rol = 0 OR r.id_rol = p_id_rol)
            AND (p_estado = 'todos' OR u.estado = p_estado)
            ORDER BY u.id_usuario DESC;
        END $$
        DELIMITER ;

        DROP PROCEDURE IF EXISTS sp_reporte_roles_permisos;
        DELIMITER $$
        CREATE PROCEDURE sp_reporte_roles_permisos(
            IN p_id_rol INT,
            IN p_tag VARCHAR(50) -- 'todos' o un tag exacto (ej: 'producto')
        )
        BEGIN
            SELECT
                r.id_rol,
                r.nombre AS rol,
                r.estado AS estado_rol,
                t.modulos AS tag_modulo,
                p.nombre  AS permiso,
                p.CRUD    AS crud
            FROM roles r
            INNER JOIN rol_tag_permiso rtp ON rtp.id_rol = r.id_rol
            INNER JOIN tags t              ON t.id_tag  = rtp.id_tag
            INNER JOIN permisos p          ON p.id_permiso = rtp.id_permiso
            WHERE (p_id_rol = 0 OR r.id_rol = p_id_rol)
            AND (p_tag = 'todos' OR t.modulos = p_tag)
            ORDER BY r.nombre, t.modulos, p.CRUD;
        END $$
        DELIMITER ;

        DROP PROCEDURE IF EXISTS sp_reporte_resumen_tiendas;
        DELIMITER $$
        CREATE PROCEDURE sp_reporte_resumen_tiendas()
        BEGIN
            SELECT
                n.id_negocio,
                n.nombre AS negocio,
                u.nombre AS propietario,
                n.estado,
                n.hora_apertura,
                n.hora_cierre,
                COUNT(p.id_producto) AS total_productos_activos,
                COUNT(DISTINCT p.id_categoria) AS categorias_distintas,
                COALESCE(ROUND(AVG(p.precio), 2), 0) AS precio_promedio,
                COALESCE(MIN(p.precio), 0) AS precio_min,
                COALESCE(MAX(p.precio), 0) AS precio_max
            FROM negocios n
            INNER JOIN usuarios u ON u.id_usuario = n.id_propietario
            LEFT JOIN productos p ON p.id_negocio = n.id_negocio AND p.estado = 'activo'
            GROUP BY
                n.id_negocio, n.nombre, u.nombre, n.estado, n.hora_apertura, n.hora_cierre
            ORDER BY n.id_negocio ASC;
        END $$
        DELIMITER ;
