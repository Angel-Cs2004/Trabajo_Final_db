<?php
function can(string $modulo, string $accion): bool
{
    $modulo = strtoupper(trim($modulo));
    $accion = strtoupper(trim($accion));

    $permisosModulo = $_SESSION['usuario_auth']['permisosPorModulo'] ?? [];

    return !empty($permisosModulo[$modulo][$accion]);
}
