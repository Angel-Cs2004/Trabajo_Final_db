# Sistema de Negocios 2025 – PHP + MySQL

Este proyecto es un **sistema web de gestión de negocios y productos** desarrollado con **PHP (sin framework)** y **MySQL**, pensado como trabajo final del curso de Base de Datos y a la vez alineado con una propuesta de tesis sobre administración de restaurantes/negocios.

Incluye:

- Módulo de usuarios con roles (super_admin, admin, proveedor).
- Gestión de negocios, horarios y productos.
- Administración de cargas masivas.
- Reportes construidos mediante **Procedimientos Almacenados (SP)**.
- Estructura básica tipo MVC (casero) para poder crecer el proyecto.

---

## 🧰 Tecnologías

- **PHP** 8.x (modo Apache / módulo de XAMPP)
- **MySQL** 5.7+ o 8.x
- Servidor web: **Apache** (XAMPP)
- Sistema operativo: Windows (desarrollo local)

---

## 📁 Estructura del proyecto

Ejemplo de estructura dentro de `htdocs`:

```bash
C:\xampp\htdocs\Trabajo_Final_db/
  app/
    config/
      db.php               # Configuración de conexión a MySQL
    controllers/           # (Futuro) controladores PHP
    models/                # (Futuro) modelos / acceso a datos
    views/                 # (Futuro) vistas
  public/
    index.php              # Pantalla de inicio (login)
    menu_principal.php     # Menú después del login
    reporte_productos_negocio.php  # Ejemplo de reporte con SP
    # ... otros reportes y pantallas
  sql/
    db_negocios_2025.sql   # Script con tablas + SP + datos de ejemplo (nombre de ejemplo)
