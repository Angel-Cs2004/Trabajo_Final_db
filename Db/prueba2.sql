USE db_negocios_2025;
CALL sp_insertar_negocio(
    'Angelo y Su Sazon',                  -- p_nombre
    'Comida a lo pendejo',  -- p_descripcion
    NULL,                                -- p_imagen_logo (por ahora sin imagen)
    'abierto',                           -- p_estado_disponibilidad
    1                                    -- p_id_propietario (id_usuario del proveedor)
);
SELECT * FROM negocios;



-- PRUEBA 2

USE db_negocios_2025;

CALL sp_insertar_producto(
    'Arroz con pollo',   -- p_nombre
    'PROD-010',          -- p_codigo
    18.50,               -- p_precio
    NULL,                -- p_url_imagen (por ahora sin imagen)
    2,                   -- p_id_categoria (por ejemplo: 2 = Platos de fondo)
    1                    -- p_id_negocio   (por ejemplo: 1 = Restaurante Doña Pacha)
);

SELECT * FROM productos;


--PRUEBA 3
USE db_negocios_2025;
CALL sp_reporte_productos_por_negocio(1);

--PRUEBA 4
CALL sp_reporte_productos_por_categoria(1);

--PRUEBA 5
CALL sp_reporte_productos_rango_precio(5.00, 30.00, 0);

-- PRUEBA 6
CALL sp_reporte_negocios_por_propietario(2);
CALL sp_reporte_negocios_por_propietario(3);

-- PRUEBA 7
CALL sp_reporte_cargas_masivas(0, 'todos');
CALL sp_reporte_cargas_masivas(2, 'todos');
CALL sp_reporte_cargas_masivas(0, 'error');
CALL sp_reporte_cargas_masivas(2, 'completado');