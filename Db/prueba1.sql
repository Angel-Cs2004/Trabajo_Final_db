========================================
//PRUEBA 1
========================================
	SELECT 
    u.id_usuario,
    u.nombre      AS usuario,
    r.nombre      AS rol
FROM usuarios u
JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
JOIN roles r        ON ur.id_rol = r.id_rol;

=======================================
//PRUEBA 2
=======================================
SELECT 
    n.id_negocio,
    n.nombre       AS negocio,
    u.id_usuario,
    u.nombre       AS propietario
FROM negocios n
JOIN usuarios u ON n.id_propietario = u.id_usuario
ORDER BY u.id_usuario, n.id_negocio;

SELECT * FROM db_negocios_2025.negocios;

=======================================
//PRUEBA 3
=======================================

SELECT 
    n.nombre        AS negocio,
    h.dia_semana,
    h.hora_apertura,
    h.hora_cierre,
    h.cerrado
FROM horarios_negocio h
JOIN negocios n ON h.id_negocio = n.id_negocio
WHERE n.id_negocio = 1
ORDER BY FIELD(h.dia_semana,
    'lunes','martes','miercoles','jueves','viernes','sabado','domingo');

=======================================
//PRUEBA 4
=======================================

INSERT INTO categorias (nombre, descripcion) VALUES
('Entradas', 'Platos ligeros para empezar'),
('Platos de fondo', 'Platos principales'),
('Bebidas', 'Bebidas frías y calientes'),
('Postres', 'Dulces y postres');

SELECT * FROM categorias;

INSERT INTO productos (nombre, codigo, precio, url_imagen, id_categoria, id_negocio) VALUES
('Ceviche clásico',      'PROD-001', 25.00, NULL, 1, 1),  -- Entrada
('Lomo saltado',         'PROD-002', 28.50, NULL, 2, 1),  -- Plato de fondo
('Inca Kola 500ml',      'PROD-003',  5.00, NULL, 3, 1),  -- Bebida
('Mazamorra morada',     'PROD-004',  6.50, NULL, 4, 1);  -- Postre

SELECT
    p.id_producto,
    p.nombre       AS producto,
    p.codigo,
    p.precio,
    c.nombre       AS categoria,
    n.nombre       AS negocio
FROM productos p
JOIN categorias c ON p.id_categoria = c.id_categoria
JOIN negocios   n ON p.id_negocio   = n.id_negocio
ORDER BY p.id_producto;

=======================================
//PRUEBA 5
=======================================

INSERT INTO parametros_imagenes 
(etiqueta, tipo, alto_px, ancho_px, categoria_admin, formatos_validos)
VALUES
('logo_negocio',  'logo',      300, 300, 'negocios', 'jpg,png'),
('foto_producto', 'producto',  600, 600, 'productos', 'jpg,png,webp'),
('avatar_usuario','perfil',    200, 200, 'usuarios',  'jpg,png');

SELECT * FROM parametros_imagenes;

INSERT INTO cargas_masivas 
(id_usuario, nombre_archivo, total_registros, registros_exitosos, registros_fallidos, estado, mensaje_error)
VALUES
(2, 'productos_enero.xlsx',   50, 48,  2, 'completado', NULL),
(2, 'productos_febrero.xlsx', 40, 40,  0, 'completado', NULL),
(3, 'productos_marzo.xlsx',   60,  0, 60, 'error', 'Formato de columnas incorrecto');

SELECT
    u.id_usuario,
    u.nombre        AS usuario,
    cm.id_carga,
    cm.nombre_archivo,
    cm.total_registros,
    cm.registros_exitosos,
    cm.registros_fallidos,
    cm.estado
FROM cargas_masivas cm
JOIN usuarios u ON cm.id_usuario = u.id_usuario
ORDER BY u.id_usuario, cm.id_carga;
