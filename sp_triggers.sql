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

-- =========================================================
--  TRIGGERS (tuyos, sin cambiar)
-- =========================================================
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

-- =========================================================
--  PROCEDIMIENTOS EXTRA (tuyos, sin cambiar)
-- =========================================================
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

-- =========================================================
-- REPORTES MODELS NEGOCIOS (con disponibilidad)
-- =========================================================
DELIMITER $$

CREATE PROCEDURE sp_negocios_listar(
    IN p_id_propietario INT,          -- 0 = todos
    IN p_estado VARCHAR(10),           -- 'activo','inactivo','todos'
    IN p_disponibilidad VARCHAR(10),   -- 'abierto','cerrado','todos'
    IN p_busqueda VARCHAR(255),        -- '' = sin filtro
    IN p_orden VARCHAR(10)             -- 'ASC' / 'DESC'
)
BEGIN
    SELECT
        n.*,
        u.nombre AS propietario,
        CASE
            WHEN n.estado = 'activo'
             AND n.hora_apertura <= CURTIME()
             AND n.hora_cierre  > CURTIME()
            THEN 'abierto'
            ELSE 'cerrado'
        END AS estado_disponibilidad
    FROM negocios n
    INNER JOIN usuarios u ON u.id_usuario = n.id_propietario
    WHERE
        (p_id_propietario = 0 OR n.id_propietario = p_id_propietario)
        AND (p_estado = 'todos' OR n.estado = p_estado)
        AND (
            p_busqueda = '' OR
            n.nombre LIKE CONCAT('%', p_busqueda, '%') OR
            n.descripcion LIKE CONCAT('%', p_busqueda, '%') OR
            u.nombre LIKE CONCAT('%', p_busqueda, '%')
        )
        AND (
            p_disponibilidad = 'todos'
            OR (
                p_disponibilidad = 'abierto'
                AND n.estado = 'activo'
                AND n.hora_apertura <= CURTIME()
                AND n.hora_cierre  > CURTIME()
            )
            OR (
                p_disponibilidad = 'cerrado'
                AND NOT (
                    n.estado = 'activo'
                    AND n.hora_apertura <= CURTIME()
                    AND n.hora_cierre  > CURTIME()
                )
            )
        )
    ORDER BY
        CASE WHEN p_orden = 'ASC'  THEN n.nombre END ASC,
        CASE WHEN p_orden = 'DESC' THEN n.nombre END DESC;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS sp_negocios_obtener_por_id;
DELIMITER $$

CREATE PROCEDURE sp_negocios_obtener_por_id(
    IN p_id_negocio INT
)
BEGIN
    SELECT
        n.*,
        u.nombre AS propietario,
        CASE
            WHEN n.estado = 'activo'
             AND n.hora_apertura <= CURTIME()
             AND n.hora_cierre  > CURTIME()
            THEN 'abierto'
            ELSE 'cerrado'
        END AS estado_disponibilidad
    FROM negocios n
    INNER JOIN usuarios u ON u.id_usuario = n.id_propietario
    WHERE n.id_negocio = p_id_negocio
    LIMIT 1;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_negocios_crear;
DELIMITER $$

CREATE PROCEDURE sp_negocios_crear(
    IN p_nombre VARCHAR(255),
    IN p_descripcion VARCHAR(255),
    IN p_estado VARCHAR(10),       -- 'activo' / 'inactivo'
    IN p_imagen_logo VARCHAR(255), -- puede ser NULL
    IN p_hora_apertura TIME,
    IN p_hora_cierre TIME,
    IN p_id_propietario INT
)
BEGIN
    INSERT INTO negocios
        (nombre, descripcion, estado, imagen_logo, hora_apertura, hora_cierre, id_propietario)
    VALUES
        (p_nombre, p_descripcion, p_estado, p_imagen_logo, p_hora_apertura, p_hora_cierre, p_id_propietario);

    SELECT LAST_INSERT_ID() AS id_negocio;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_negocios_actualizar;
DELIMITER $$

CREATE PROCEDURE sp_negocios_actualizar(
    IN p_id_negocio INT,
    IN p_nombre VARCHAR(255),
    IN p_descripcion VARCHAR(255),
    IN p_estado VARCHAR(10),
    IN p_imagen_logo VARCHAR(255),
    IN p_hora_apertura TIME,
    IN p_hora_cierre TIME,
    IN p_id_propietario INT   -- 0 = no cambiar propietario
)
BEGIN
    IF p_id_propietario = 0 THEN
        UPDATE negocios
        SET nombre = p_nombre,
            descripcion = p_descripcion,
            estado = p_estado,
            imagen_logo = p_imagen_logo,
            hora_apertura = p_hora_apertura,
            hora_cierre = p_hora_cierre
        WHERE id_negocio = p_id_negocio;
    ELSE
        UPDATE negocios
        SET nombre = p_nombre,
            descripcion = p_descripcion,
            estado = p_estado,
            imagen_logo = p_imagen_logo,
            hora_apertura = p_hora_apertura,
            hora_cierre = p_hora_cierre,
            id_propietario = p_id_propietario
        WHERE id_negocio = p_id_negocio;
    END IF;

    SELECT ROW_COUNT() AS filas_afectadas;
END $$

DELIMITER ;
-- =========================================================
--  REPORTES_MODELS_CATEGORIAS
-- =========================================================
DELIMITER $$

CREATE PROCEDURE sp_categorias_listar()
BEGIN
    SELECT id_categoria, nombre, descripcion, estado
    FROM categorias
    ORDER BY nombre ASC;
END $$

DELIMITER ;


DELIMITER ;

DROP PROCEDURE IF EXISTS sp_categorias_listar_activas;
DELIMITER $$

CREATE PROCEDURE sp_categorias_listar_activas()
BEGIN
    SELECT id_categoria, nombre, descripcion, estado
    FROM categorias
    WHERE estado = 'activo'
    ORDER BY nombre ASC;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_categorias_obtener_por_id;
DELIMITER $$

CREATE PROCEDURE sp_categorias_obtener_por_id(
    IN p_id_categoria INT
)
BEGIN
    SELECT id_categoria, nombre, descripcion, estado
    FROM categorias
    WHERE id_categoria = p_id_categoria
    LIMIT 1;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_categorias_crear;
DELIMITER $$

CREATE PROCEDURE sp_categorias_crear(
    IN p_nombre VARCHAR(255),
    IN p_descripcion VARCHAR(255),
    IN p_estado VARCHAR(10) -- 'activo'/'inactivo'
)
BEGIN
    INSERT INTO categorias (nombre, descripcion, estado)
    VALUES (p_nombre, p_descripcion, p_estado);

    SELECT LAST_INSERT_ID() AS id_categoria;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_categorias_editar;
DELIMITER $$

CREATE PROCEDURE sp_categorias_editar(
    IN p_id_categoria INT,
    IN p_nombre VARCHAR(255),
    IN p_descripcion VARCHAR(255),
    IN p_estado VARCHAR(10)
)
BEGIN
    UPDATE categorias
    SET nombre = p_nombre,
        descripcion = p_descripcion,
        estado = p_estado
    WHERE id_categoria = p_id_categoria;

    SELECT ROW_COUNT() AS filas_afectadas;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_categorias_desactivar;
DELIMITER $$

CREATE PROCEDURE sp_categorias_desactivar(
    IN p_id_categoria INT
)
BEGIN
    UPDATE categorias
    SET estado = 'inactivo'
    WHERE id_categoria = p_id_categoria;

    SELECT ROW_COUNT() AS filas_afectadas;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_categorias_eliminar;
DELIMITER $$

CREATE PROCEDURE sp_categorias_eliminar(
    IN p_id_categoria INT
)
BEGIN
    DELETE FROM categorias
    WHERE id_categoria = p_id_categoria;

    SELECT ROW_COUNT() AS filas_afectadas;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_categorias_buscar_por_nombre;
DELIMITER $$

CREATE PROCEDURE sp_categorias_buscar_por_nombre(
    IN p_nombre VARCHAR(255)
)
BEGIN
    SELECT *
    FROM categorias
    WHERE nombre = p_nombre
    LIMIT 1;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_categorias_crear_rapida;
DELIMITER $$

CREATE PROCEDURE sp_categorias_crear_rapida(
    IN p_nombre VARCHAR(255)
)
BEGIN
    INSERT INTO categorias (nombre, estado)
    VALUES (p_nombre, 'activo');

    SELECT LAST_INSERT_ID() AS id_categoria;
END $$

DELIMITER ;








-- =========================================================
--  MODELS_REPORTES_PARAMETROS_IMAGENES
-- =========================================================
DELIMITER $$

CREATE PROCEDURE sp_parametros_imagenes_listar()
BEGIN
    SELECT *
    FROM parametros_imagenes
    ORDER BY nombre ASC;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_parametros_imagenes_obtener_por_id;
DELIMITER $$

CREATE PROCEDURE sp_parametros_imagenes_obtener_por_id(
    IN p_id_parametro_imagen INT
)
BEGIN
    SELECT *
    FROM parametros_imagenes
    WHERE id_parametro_imagen = p_id_parametro_imagen
    LIMIT 1;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_parametros_imagenes_crear;
DELIMITER $$

CREATE PROCEDURE sp_parametros_imagenes_crear(
    IN p_nombre VARCHAR(255),
    IN p_etiqueta VARCHAR(100),
    IN p_ancho_px INT,
    IN p_alto_px INT,
    IN p_categoria VARCHAR(50),
    IN p_formatos_validos VARCHAR(20)
)
BEGIN
    INSERT INTO parametros_imagenes
        (nombre, etiqueta, ancho_px, alto_px, categoria, formatos_validos)
    VALUES
        (p_nombre, p_etiqueta, p_ancho_px, p_alto_px, p_categoria, p_formatos_validos);

    SELECT LAST_INSERT_ID() AS id_parametro_imagen;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_parametros_imagenes_actualizar;
DELIMITER $$

CREATE PROCEDURE sp_parametros_imagenes_actualizar(
    IN p_id_parametro_imagen INT,
    IN p_nombre VARCHAR(255),
    IN p_etiqueta VARCHAR(100),
    IN p_ancho_px INT,
    IN p_alto_px INT,
    IN p_categoria VARCHAR(50),
    IN p_formatos_validos VARCHAR(20)
)
BEGIN
    UPDATE parametros_imagenes
    SET nombre = p_nombre,
        etiqueta = p_etiqueta,
        ancho_px = p_ancho_px,
        alto_px = p_alto_px,
        categoria = p_categoria,
        formatos_validos = p_formatos_validos
    WHERE id_parametro_imagen = p_id_parametro_imagen;

    SELECT ROW_COUNT() AS filas_afectadas;
END $$

DELIMITER ;