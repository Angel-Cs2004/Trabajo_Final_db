-- =========================================================
--  DB: db_negocios_2025
-- =========================================================

DROP DATABASE IF EXISTS db_negocios_2025;
CREATE DATABASE IF NOT EXISTS db_negocios_2025
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_negocios_2025;

-- =========================================================
--  TABLAS: Seguridad (usuarios / roles / permisos / tags)
-- =========================================================

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

-- =========================================================
--  TABLAS: Negocio / Productos
-- =========================================================

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

-- =========================================================
--  INSERTS: ROLES
-- =========================================================

INSERT INTO roles (nombre, estado) VALUES
('super_admin',      'activo'),
('admin_negocio',    'activo'),
('operador_negocio', 'activo'),
('invitado_reportes','activo');

-- ROLES EXTRA (solo los que pediste, sin tocar tags ni permisos)
-- Nota: No importa mayúsculas/minúsculas, pero acá van exactamente como los pediste.
INSERT INTO roles (nombre, estado) VALUES
('admin_usuarios',    'activo'),
('admin_roles',       'activo'),
('admin_parametros',  'activo'),
('Admin_negocios',    'activo'),
('Mis_negocios',      'activo'),
('Mis_Productos',     'activo'),
('Admin_Productos',   'activo'),
('Admin_categorias',  'activo'),
('Admin_reportes',    'activo'),
('Mis_reportes',      'activo');

-- =========================================================
--  INSERTS: TAGS (NO TOCAR)
-- =========================================================

-- 1: usuario, 2: rol, 3: imagen, 4: categoria, 5: negocio_gen, 6: negocio_prop, 7: producto_gen, 8: producto_prop, 9: reporte_gen, 10: reporte_prop
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

-- =========================================================
--  INSERTS: PERMISOS (NO TOCAR)
-- =========================================================

INSERT INTO permisos (nombre, CRUD) VALUES
('crear',      'CREATE'),
('visualizar', 'READ'),
('editar',     'UPDATE'),
('eliminar',   'DELETE');

-- =========================================================
--  INSERTS: USUARIOS (BASE + COMPLEMENTO HASTA ~50)
-- =========================================================

-- BASE (tuyos, sin quitar)
INSERT INTO usuarios (nombre, correo, identificacion, telefono, password_hash, estado) VALUES
('Angelo_gen',       'angel@gmail.com',        '00000001', '999999999', 'perro123',    'activo'),
('Admin Negocios',   'admin_negocio@demo.com', '00000002', '988888888', 'admin123',    'activo'),
('Operador Demo',    'operador@demo.com',      '00000003', '977777777', 'oper123',     'activo'),
('Invitado Reportes','invitado@demo.com',      '00000004', '966666666', 'invitado123', 'activo');

-- COMPLEMENTO (46 más = total 50)
INSERT INTO usuarios (nombre, correo, identificacion, telefono, password_hash, estado) VALUES
('Valeria Quispe',      'valeria.quispe@correo.pe',     '72384111', '987321450', 'Vq!2025#Lima',     'activo'),
('Diego Huamán',        'diego.huaman@correo.pe',       '70458213', '989110234', 'Dh*Arequipa22',    'activo'),
('Mariana Salazar',     'mariana.salazar@correo.pe',    '74821099', '980443211', 'Ms$Cafe_09',       'activo'),
('Renzo Paredes',       'renzo.paredes@correo.pe',      '73999012', '979332118', 'Rp_88!pollo',      'activo'),
('Camila Rojas',        'camila.rojas@correo.pe',       '75110034', '992120676', 'Cr#Mkt_777',       'activo'),
('Javier Luna',         'javier.luna@correo.pe',        '73622019', '985667210', 'Jl@negocio_1',     'activo'),
('Lucía Arias',         'lucia.arias@correo.pe',        '70844320', '981331005', 'La%prod_2025',     'activo'),
('Sebastián Ortiz',     'sebastian.ortiz@correo.pe',    '70129987', '997110802', 'So!report_88',     'activo'),
('Daniela Chávez',      'daniela.chavez@correo.pe',     '71003455', '986990120', 'Dc^roles_12',      'activo'),
('Fabricio Molina',     'fabricio.molina@correo.pe',    '73440566', '976540311', 'Fm&cat_321',       'activo'),

('Ana María Ríos',      'ana.rios@correo.pe',           '72999110', '981555412', 'Ar@cafe_11',       'activo'),
('José Valdivia',       'jose.valdivia@correo.pe',      '71234098', '982111509', 'Jv#piza_66',       'activo'),
('Brenda Cárdenas',     'brenda.cardenas@correo.pe',    '74888812', '983012450', 'Bc$mini_70',       'activo'),
('Hugo Peña',           'hugo.pena@correo.pe',          '70221033', '984115223', 'Hp!mkt_202',       'activo'),
('Paola Medina',        'paola.medina@correo.pe',       '71933010', '985220119', 'Pm*neg_303',       'activo'),
('Santiago Flores',     'santiago.flores@correo.pe',    '74512087', '986332144', 'Sf_444#prod',      'activo'),
('Ximena Cabrera',      'ximena.cabrera@correo.pe',     '70911345', '987220981', 'Xc@img_900',       'activo'),
('Álvaro Torres',       'alvaro.torres@correo.pe',      '73655127', '988553211', 'At%admin_07',      'activo'),
('Fiorella Vargas',     'fiorella.vargas@correo.pe',    '70400981', '989663321', 'Fv^roles_55',      'activo'),
('Marco Gutiérrez',     'marco.gutierrez@correo.pe',    '70011223', '990112009', 'Mg!report_10',     'activo'),

('Carolina Soto',       'carolina.soto@correo.pe',      '71199002', '991022334', 'Cs#neg_88',        'activo'),
('Luis Alberto Núñez',  'luis.nunez@correo.pe',         '73322110', '992788110', 'Ln$cat_19',        'activo'),
('Gabriela Herrera',    'gabriela.herrera@correo.pe',   '72234019', '993554200', 'Gh*prod_77',       'activo'),
('Iván Muñoz',          'ivan.munoz@correo.pe',         '71678033', '994112901', 'Im@usr_2025',      'activo'),
('Karla Pineda',        'karla.pineda@correo.pe',       '74400912', '995009812', 'Kp!misNeg_12',     'activo'),
('Fernando Palomino',   'fernando.palomino@correo.pe',  '70322099', '996120334', 'Fp#admNeg_23',     'activo'),
('Diana Lozano',        'diana.lozano@correo.pe',       '73001900', '997443112', 'Dl$misProd_14',    'activo'),
('César Aguilar',       'cesar.aguilar@correo.pe',      '72888101', '998221120', 'Ca%admProd_02',    'activo'),
('Ruth Navarro',        'ruth.navarro@correo.pe',       '70555123', '979101201', 'Rn^admRep_09',     'activo'),
('Kevin Soto',          'kevin.soto@correo.pe',         '70100998', '978331105', 'Ks*misRep_33',     'activo'),

('Mónica Cabrera',      'monica.cabrera@correo.pe',     '74011244', '977222110', 'Mc@param_70',      'activo'),
('Joel Bustamante',     'joel.bustamante@correo.pe',    '70666001', '976991223', 'Jb!roles_40',      'activo'),
('Patricia Mendoza',    'patricia.mendoza@correo.pe',   '74222119', '975110876', 'Pm#usr_71',        'activo'),
('Rodrigo Barrera',     'rodrigo.barrera@correo.pe',    '71422091', '974992300', 'Rb$negGen_01',     'activo'),
('Mayra Silva',         'mayra.silva@correo.pe',        '73551009', '973450112', 'Ms*cat_202',       'activo'),
('Tomás Delgado',       'tomas.delgado@correo.pe',      '71833077', '972330991', 'Td@prod_908',      'activo'),
('Andrea Figueroa',     'andrea.figueroa@correo.pe',    '74722001', '971220662', 'Af!img_45',        'activo'),
('Óscar Roldán',        'oscar.roldan@correo.pe',       '70711999', '970119980', 'Or#admUsr_10',     'activo'),
('Katherine León',      'katherine.leon@correo.pe',     '72000912', '969001122', 'Kl$misNeg_88',     'activo'),
('Bruno Poma',          'bruno.poma@correo.pe',         '74666012', '968990221', 'Bp%misProd_19',    'activo'),

('Rafael Castañeda',    'rafael.castaneda@correo.pe',   '73122018', '967880112', 'Rc^admProd_55',    'activo'),
('Silvana Tapia',       'silvana.tapia@correo.pe',      '71344001', '966771100', 'St*admCat_11',     'activo'),
('Elena Quispe',        'elena.quispe@correo.pe',       '72555110', '965662299', 'Eq@admRep_77',     'activo'),
('Miguel Ángel Cano',   'miguel.cano@correo.pe',        '70988012', '964553112', 'Mc!misRep_20',     'activo'),
('Piero Zamora',        'piero.zamora@correo.pe',       '74100987', '963441190', 'Pz#admNeg_99',     'activo'),
('Nadia Rojas',         'nadia.rojas@correo.pe',        '71500771', '962330221', 'Nr$usr_120',       'activo');

-- =========================================================
--  INSERTS: USUARIO_ROL (BASE + COMPLEMENTO HETEROGÉNEO)
-- =========================================================

-- BASE (tuyos, sin quitar)
INSERT INTO usuario_rol (id_usuario, id_rol) VALUES
(1, 1), -- Angelo -> super_admin
(2, 2), -- Admin Negocios -> admin_negocio
(3, 3), -- Operador Demo  -> operador_negocio
(4, 4); -- Invitado       -> invitado_reportes

-- COMPLEMENTO (asignaciones variadas usando SELECT por nombre de rol)
-- (5..50)
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 5,  id_rol FROM roles WHERE nombre='Mis_negocios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 6,  id_rol FROM roles WHERE nombre='Mis_Productos';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 7,  id_rol FROM roles WHERE nombre='Admin_Productos';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 8,  id_rol FROM roles WHERE nombre='Mis_reportes';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 9,  id_rol FROM roles WHERE nombre='admin_usuarios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 10, id_rol FROM roles WHERE nombre='Admin_categorias';

INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 11, id_rol FROM roles WHERE nombre='Admin_negocios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 12, id_rol FROM roles WHERE nombre='admin_roles';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 13, id_rol FROM roles WHERE nombre='admin_parametros';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 14, id_rol FROM roles WHERE nombre='Admin_reportes';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 15, id_rol FROM roles WHERE nombre='Mis_negocios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 16, id_rol FROM roles WHERE nombre='Mis_Productos';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 17, id_rol FROM roles WHERE nombre='operador_negocio';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 18, id_rol FROM roles WHERE nombre='admin_negocio';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 19, id_rol FROM roles WHERE nombre='invitado_reportes';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 20, id_rol FROM roles WHERE nombre='Admin_Productos';

INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 21, id_rol FROM roles WHERE nombre='Admin_categorias';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 22, id_rol FROM roles WHERE nombre='Mis_reportes';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 23, id_rol FROM roles WHERE nombre='admin_usuarios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 24, id_rol FROM roles WHERE nombre='Mis_negocios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 25, id_rol FROM roles WHERE nombre='Mis_Productos';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 26, id_rol FROM roles WHERE nombre='Admin_negocios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 27, id_rol FROM roles WHERE nombre='Admin_Productos';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 28, id_rol FROM roles WHERE nombre='Admin_reportes';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 29, id_rol FROM roles WHERE nombre='Mis_reportes';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 30, id_rol FROM roles WHERE nombre='admin_parametros';

INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 31, id_rol FROM roles WHERE nombre='admin_roles';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 32, id_rol FROM roles WHERE nombre='admin_usuarios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 33, id_rol FROM roles WHERE nombre='Mis_negocios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 34, id_rol FROM roles WHERE nombre='Admin_negocios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 35, id_rol FROM roles WHERE nombre='Admin_categorias';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 36, id_rol FROM roles WHERE nombre='Admin_Productos';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 37, id_rol FROM roles WHERE nombre='admin_parametros';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 38, id_rol FROM roles WHERE nombre='admin_usuarios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 39, id_rol FROM roles WHERE nombre='Mis_negocios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 40, id_rol FROM roles WHERE nombre='Mis_Productos';

INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 41, id_rol FROM roles WHERE nombre='Admin_Productos';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 42, id_rol FROM roles WHERE nombre='Admin_categorias';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 43, id_rol FROM roles WHERE nombre='Admin_reportes';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 44, id_rol FROM roles WHERE nombre='Mis_reportes';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 45, id_rol FROM roles WHERE nombre='Admin_negocios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 46, id_rol FROM roles WHERE nombre='admin_usuarios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 47, id_rol FROM roles WHERE nombre='Mis_negocios';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 48, id_rol FROM roles WHERE nombre='Mis_Productos';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 49, id_rol FROM roles WHERE nombre='Admin_reportes';
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT 50, id_rol FROM roles WHERE nombre='Mis_reportes';

-- =========================================================
--  INSERTS: ROL_TAG_PERMISO (BASE + EXTENSIÓN + ROLES NUEVOS)
-- =========================================================

-- BASE (tuyo, sin quitar)
-- SUPER_ADMIN: TODOS los permisos (1..4) para TODOS los tags (1..7)
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso) VALUES
(1,1,1),(1,1,2),(1,1,3),(1,1,4),
(1,2,1),(1,2,2),(1,2,3),(1,2,4),
(1,3,1),(1,3,2),(1,3,3),(1,3,4),
(1,4,1),(1,4,2),(1,4,3),(1,4,4),
(1,5,1),(1,5,2),(1,5,3),(1,5,4),
(1,6,1),(1,6,2),(1,6,3),(1,6,4),
(1,7,1),(1,7,2),(1,7,3),(1,7,4);

-- EXTENSIÓN: SUPER_ADMIN también para tags 8..10 (sin borrar lo anterior)
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso) VALUES
(1,8,1),(1,8,2),(1,8,3),(1,8,4),
(1,9,1),(1,9,2),(1,9,3),(1,9,4),
(1,10,1),(1,10,2),(1,10,3),(1,10,4);

-- ADMIN_NEGOCIO (tuyo, sin quitar)
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso) VALUES
(2, 1, 1),(2, 1, 3),(2, 1, 2), -- usuario   C/U/R
(2, 4, 1),(2, 4, 3),(2, 4, 2), -- categoria C/U/R
(2, 5, 1),(2, 5, 3),(2, 5, 2), -- negocio   C/U/R
(2, 6, 1),(2, 6, 3),(2, 6, 2), -- producto  C/U/R
(2, 7, 2);                     -- reporte   READ

-- OPERADOR_NEGOCIO (tuyo, sin quitar)
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso) VALUES
(3, 4, 2), -- categoria READ
(3, 5, 2), -- negocio   READ
(3, 6, 1),(3, 6, 3),(3, 6, 2), -- producto C/U/R
(3, 7, 2); -- reporte READ

-- INVITADO_REPORTES (tuyo, sin quitar)
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso) VALUES
(4, 7, 2); -- reporte READ

-- ROLES NUEVOS: permisos variados (sin crear tags, usando los existentes)
-- Para evitar depender de id_rol fijo, insertamos con SELECT (id_rol) por nombre.

-- admin_usuarios: usuario CRUD, rol READ
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 1, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='admin_usuarios' AND p.id_permiso IN (1,2,3,4);
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 2, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='admin_usuarios' AND p.id_permiso IN (2);

-- admin_roles: rol CRUD, usuario READ
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 2, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='admin_roles' AND p.id_permiso IN (1,2,3,4);
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 1, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='admin_roles' AND p.id_permiso IN (2);

-- admin_parametros: imagen CREATE/READ/UPDATE (sin DELETE)
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 3, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='admin_parametros' AND p.id_permiso IN (1,2,3);

-- Admin_negocios: negocio_gen CRUD, categoria READ, producto_gen READ, reportes_gen READ
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 5, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='Admin_negocios' AND p.id_permiso IN (1,2,3,4);
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 4, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='Admin_negocios' AND p.id_permiso IN (2);
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 7, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='Admin_negocios' AND p.id_permiso IN (2);
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 9, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='Admin_negocios' AND p.id_permiso IN (2);

-- Mis_negocios: negocio_prop CREATE/READ/UPDATE (sin DELETE), producto_prop READ
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 6, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='Mis_negocios' AND p.id_permiso IN (1,2,3);
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 8, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='Mis_negocios' AND p.id_permiso IN (2);

-- Mis_Productos: producto_prop CREATE/READ/UPDATE (sin DELETE)
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 8, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='Mis_Productos' AND p.id_permiso IN (1,2,3);

-- Admin_Productos: producto_gen CRUD, categoria READ
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 7, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='Admin_Productos' AND p.id_permiso IN (1,2,3,4);
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 4, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='Admin_Productos' AND p.id_permiso IN (2);

-- Admin_categorias: categoria CRUD
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 4, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='Admin_categorias' AND p.id_permiso IN (1,2,3,4);

-- Admin_reportes: reporte_gen READ + reporte_prop READ
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 9, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='Admin_reportes' AND p.id_permiso IN (2);
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 10, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='Admin_reportes' AND p.id_permiso IN (2);

-- Mis_reportes: reporte_prop READ
INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
SELECT r.id_rol, 10, p.id_permiso FROM roles r JOIN permisos p
WHERE r.nombre='Mis_reportes' AND p.id_permiso IN (2);

-- =========================================================
--  INSERTS: NEGOCIOS (BASE + COMPLEMENTO HASTA ~50)
-- =========================================================

-- BASE (tuyos, sin quitar)
INSERT INTO negocios (nombre, descripcion, estado, imagen_logo, hora_apertura, hora_cierre, id_propietario) VALUES
('Restaurante Doña Pacha', 'Comida criolla y menú diario', 'activo', NULL, '09:00:00', '16:00:00', 2),
('Pollería El Buen Sabor', 'Pollos a la brasa y parrillas', 'activo', NULL, '12:00:00', '23:00:00', 2),
('Cevichería El Marino',  'Ceviches y mariscos frescos',   'activo', NULL, '10:00:00', '18:00:00', 3);

-- BASE (tuyos, sin quitar) - los 3 adicionales
INSERT INTO negocios (nombre, descripcion, estado, imagen_logo, hora_apertura, hora_cierre, id_propietario) VALUES
('Café Angelo', 'Cafetería de especialidad y postres', 'activo', NULL, '08:00:00', '18:00:00', 1),
('Pizzería Gen', 'Pizzas artesanales y pastas',        'activo', NULL, '12:00:00', '23:00:00', 1),
('Mini Market Angelo', 'Tienda de abarrotes y snacks', 'activo', NULL, '09:00:00', '21:00:00', 1);

-- COMPLEMENTO (44 más = total 50). Propietarios distribuidos (1..20 y más)
INSERT INTO negocios (nombre, descripcion, estado, imagen_logo, hora_apertura, hora_cierre, id_propietario) VALUES
('Anticuchos La Feria',     'Anticuchos y parrilla nocturna',      'activo', NULL, '17:00:00', '23:30:00', 5),
('Juguería Vitaminazo',     'Jugos naturales y sánguches',         'activo', NULL, '07:00:00', '14:00:00', 6),
('Pastelería Dulce Norte',  'Tortas, empanadas y café',            'activo', NULL, '09:00:00', '20:00:00', 7),
('Sanguchería El Buen Pan', 'Sánguches calientes y café',          'activo', NULL, '08:00:00', '16:00:00', 8),
('Chifa Dragón Rojo',       'Chifa tradicional y combos',          'activo', NULL, '12:00:00', '22:00:00', 9),
('Arepas Caribe',           'Arepas y bebidas',                    'activo', NULL, '11:00:00', '21:00:00', 10),
('Hamburguesas La 13',      'Hamburguesas artesanales',            'activo', NULL, '16:00:00', '23:00:00', 11),
('Tacos Don Pepe',          'Tacos, nachos y salsas',              'activo', NULL, '13:00:00', '22:30:00', 12),
('Panadería Santa Rosa',    'Pan del día y postres',               'activo', NULL, '06:30:00', '13:30:00', 13),
('Bodega El Ahorro',        'Abarrotes y bebidas',                 'activo', NULL, '08:00:00', '21:30:00', 14),

('Café Mirador',            'Café y brunch',                       'activo', NULL, '08:00:00', '19:00:00', 15),
('Poke & Bowl',             'Bowls saludables y bebidas',          'activo', NULL, '11:00:00', '20:00:00', 16),
('Heladería Polar',         'Helados y cremoladas',                'activo', NULL, '12:00:00', '21:00:00', 17),
('Parrillas El Carbón',     'Parrillas y guarniciones',            'activo', NULL, '12:30:00', '23:30:00', 18),
('Ceviches La Ola',         'Ceviche y causas',                    'activo', NULL, '10:00:00', '17:00:00', 19),
('Comedor Don Lucho',       'Menú casero',                         'activo', NULL, '09:00:00', '15:00:00', 20),
('Pizza Napoli Express',    'Pizzas al paso',                      'activo', NULL, '12:00:00', '23:00:00', 21),
('Market San Martín',       'Minimarket y limpieza',               'activo', NULL, '09:00:00', '22:00:00', 22),
('Café Central',            'Café de especialidad',                'activo', NULL, '07:30:00', '18:30:00', 23),
('Dulcería La Abuela',      'Postres tradicionales',               'activo', NULL, '10:00:00', '19:30:00', 24),

('Pollo Dorado',            'Pollo a la brasa y parrillas',        'activo', NULL, '12:00:00', '23:00:00', 25),
('Chifa Jade',              'Chifa y sopas',                        'activo', NULL, '12:00:00', '22:00:00', 26),
('Cafetería Aurora',        'Café, sánguches y postres',           'activo', NULL, '08:00:00', '20:00:00', 27),
('La Barra Cevichera',      'Mariscos frescos',                    'activo', NULL, '11:00:00', '18:30:00', 28),
('Pizzería La Esquina',     'Pizzas familiares',                    'activo', NULL, '12:00:00', '23:30:00', 29),
('Tienda Express 24',       'Snacks, bebidas y abarrotes',         'activo', NULL, '09:00:00', '23:00:00', 30),
('Veggie Green',            'Comida vegetariana',                  'activo', NULL, '11:00:00', '20:00:00', 31),
('Súper Snacks',            'Dulces, snacks y bebidas',            'activo', NULL, '10:00:00', '22:00:00', 32),
('Ramen House',             'Ramen y entradas',                    'activo', NULL, '12:00:00', '22:00:00', 33),
('Café Andino',             'Café y panadería',                    'activo', NULL, '07:00:00', '17:00:00', 34),

('La Sazón de Casa',        'Menú y platos criollos',              'activo', NULL, '09:00:00', '16:00:00', 35),
('Empanadas & Más',         'Empanadas al horno',                  'activo', NULL, '09:00:00', '18:00:00', 36),
('Marisquería Puerto Azul', 'Mariscos y pescados',                 'activo', NULL, '10:00:00', '19:00:00', 37),
('Cafetería La Estación',   'Café, jugos y postres',               'activo', NULL, '08:00:00', '19:00:00', 38),
('Bodega El Barrio',        'Abarrotes diarios',                   'activo', NULL, '08:00:00', '22:00:00', 39),
('Parrillas La Casona',     'Parrillas y platos fuertes',          'activo', NULL, '12:00:00', '23:30:00', 40),
('Sandwich Club',           'Sándwiches gourmet',                  'activo', NULL, '09:00:00', '17:30:00', 41),
('Tacos La Noche',          'Tacos nocturnos',                     'activo', NULL, '17:00:00', '23:45:00', 42),
('Pastelería San José',     'Postres y tortas',                    'activo', NULL, '09:00:00', '20:00:00', 43),
('Chicha & Tradición',      'Bebidas tradicionales',               'activo', NULL, '10:00:00', '18:00:00', 44),

('Cevichería Costa Viva',   'Ceviches y jaleas',                   'activo', NULL, '10:30:00', '18:30:00', 45),
('Pizzería Trattoria Uno',  'Pastas y pizzas',                     'activo', NULL, '12:00:00', '23:00:00', 46),
('Mini Market La Ruta',     'Abarrotes, bebidas y snacks',         'activo', NULL, '09:00:00', '22:00:00', 47),
('Café Bruma',              'Café, postres y desayuno',            'activo', NULL, '07:30:00', '19:30:00', 48);

-- =========================================================
--  INSERTS: HORARIOS_NEGOCIO (tuyo, sin quitar)
-- =========================================================

INSERT INTO horarios_negocio (dia_semana, estado, hora_apertura, hora_cierre) VALUES
('lunes',    'activo',   '09:00:00', '16:00:00'),
('martes',   'activo',   '09:00:00', '16:00:00'),
('miercoles','activo',   '09:00:00', '16:00:00'),
('jueves',   'activo',   '09:00:00', '16:00:00'),
('viernes',  'activo',   '09:00:00', '16:00:00'),
('sabado',   'activo',   '10:00:00', '15:00:00'),
('domingo',  'inactivo', '00:00:00', '00:00:00');

-- =========================================================
--  INSERTS: CATEGORIAS (tuyo, sin quitar)
-- =========================================================

INSERT INTO categorias (nombre, descripcion, estado) VALUES
('Entradas',         'Platos ligeros para empezar', 'activo'),
('Platos de fondo',  'Platos principales',          'activo'),
('Bebidas',          'Bebidas frías y calientes',   'activo'),
('Postres',          'Dulces y postres',            'activo');

-- =========================================================
--  INSERTS: PRODUCTOS (BASE + COMPLEMENTO HASTA ~50)
-- =========================================================

-- BASE (tuyos, sin quitar)
INSERT INTO productos (nombre, precio, url_imagen, estado, id_categoria, id_negocio) VALUES
('Ceviche clásico',  25.00, NULL, 'activo', 1, 3),
('Lomo saltado',     28.50, NULL, 'activo', 2, 1),
('Inca Kola 500ml',   5.00, NULL, 'activo', 3, 1),
('Mazamorra morada',  6.50, NULL, 'activo', 4, 1);

-- BASE (tuyos, sin quitar) - lista grande
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

-- COMPLEMENTO (16 más para llegar a 50 total aprox). Distribuido en negocios variados (7..50)
INSERT INTO productos (nombre, precio, url_imagen, estado, id_categoria, id_negocio) VALUES
('Anticucho clásico',          16.00, NULL, 'activo', 2, 7),
('Choclo con queso',            8.00, NULL, 'activo', 1, 7),
('Jugo de naranja',             7.50, NULL, 'activo', 3, 8),
('Sándwich de pollo',          12.00, NULL, 'activo', 2, 9),
('Torta de chocolate',         14.50, NULL, 'activo', 4, 9),
('Chaufa especial',            24.00, NULL, 'activo', 2, 11),
('Wantán frito',               10.00, NULL, 'activo', 1, 11),
('Hamburguesa clásica',        18.00, NULL, 'activo', 2, 12),
('Papas nativas',               9.00, NULL, 'activo', 1, 12),
('Taco al pastor',             13.00, NULL, 'activo', 2, 13),
('Nachos con queso',           15.00, NULL, 'activo', 1, 13),
('Helado doble',                9.50, NULL, 'activo', 4, 16),
('Ceviche tradicional',        29.00, NULL, 'activo', 2, 19),
('Limonada frozen',            11.00, NULL, 'activo', 3, 19),
('Empanada de pollo',           7.00, NULL, 'activo', 1, 36),
('Menú del día',               17.50, NULL, 'activo', 2, 35);

-- =========================================================
--  INSERTS: PARÁMETROS DE IMÁGENES (tuyo, sin quitar)
-- =========================================================

INSERT INTO parametros_imagenes (nombre, etiqueta, alto_px, ancho_px, categoria, formatos_validos) VALUES
('Logo Negocio',   'logo_negocio',   300, 300, 'negocios', 'png'),
('Foto Producto',  'foto_producto',  600, 600, 'productos','jpg'),
('Avatar Usuario', 'avatar_usuario', 200, 200, 'usuarios', 'jpg');

-- =========================================================
--  PROCEDIMIENTOS
-- =========================================================
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

-- =========================================================
--  TRIGGERS 
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
-- =============================
-- TRIGGERS: CATEGORIAS => Evitar duplicados con los nombres o al editar
-- =============================
DELIMITER $$

DROP TRIGGER IF EXISTS trg_categorias_nombre_unico_ins $$
CREATE TRIGGER trg_categorias_nombre_unico_ins
BEFORE INSERT ON categorias
FOR EACH ROW
BEGIN
    DECLARE v_count INT;

    SELECT COUNT(*)
    INTO v_count
    FROM categorias
    WHERE nombre = NEW.nombre;

    IF v_count > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se puede crear la categoría: ya existe una con ese nombre';
    END IF;
END $$

DELIMITER ;
DELIMITER $$

DROP TRIGGER IF EXISTS trg_categorias_nombre_unico_upd $$
CREATE TRIGGER trg_categorias_nombre_unico_upd
BEFORE UPDATE ON categorias
FOR EACH ROW
BEGIN
    DECLARE v_count INT;

    -- Solo valida si realmente cambia el nombre
    IF NEW.nombre <> OLD.nombre THEN
        SELECT COUNT(*)
        INTO v_count
        FROM categorias
        WHERE nombre = NEW.nombre
          AND id_categoria <> OLD.id_categoria;

        IF v_count > 0 THEN
            SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'No se puede cambiar el nombre: ya existe otra categoría con ese nombre';
        END IF;
    END IF;
END $$

DELIMITER ;

-- =============================
-- TRIGGERS: PRODUCTOS => Evitar duplicados con los nombres o al editar
-- =============================

DELIMITER $$

DROP TRIGGER IF EXISTS trg_productos_nombre_unico_por_negocio_ins $$
CREATE TRIGGER trg_productos_nombre_unico_por_negocio_ins
BEFORE INSERT ON productos
FOR EACH ROW
BEGIN
    DECLARE v_count INT;

    SELECT COUNT(*)
    INTO v_count
    FROM productos
    WHERE id_negocio = NEW.id_negocio
      AND TRIM(nombre) = TRIM(NEW.nombre);

    IF v_count > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se puede crear el producto: ya existe otro con ese nombre en esta tienda';
    END IF;
END $$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS trg_productos_nombre_unico_por_negocio_upd $$
CREATE TRIGGER trg_productos_nombre_unico_por_negocio_upd
BEFORE UPDATE ON productos
FOR EACH ROW
BEGIN
    DECLARE v_count INT;

    IF TRIM(NEW.nombre) <> TRIM(OLD.nombre)
       OR NEW.id_negocio <> OLD.id_negocio THEN

        SELECT COUNT(*)
        INTO v_count
        FROM productos
        WHERE id_negocio = NEW.id_negocio
          AND TRIM(nombre) = TRIM(NEW.nombre)
          AND id_producto <> OLD.id_producto;

        IF v_count > 0 THEN
            SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'No se puede actualizar el producto: ya existe otro con ese nombre en esta tienda';
        END IF;
    END IF;
END $$

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