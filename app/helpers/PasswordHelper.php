<?php
/**
 * Utilidad para manejo seguro de contraseñas
 * Soporta migración gradual de contraseñas planas a hasheadas
 */

class PasswordHelper
{
    /**
     * Hash seguro de contraseñas usando Argon2ID
     * 
     * @param string $password Contraseña en texto plano
     * @return string Hash de la contraseña
     */
    public static function hash($password)
    {
        if (defined('PASSWORD_ARGON2ID')) {
            return password_hash($password, PASSWORD_ARGON2ID);
        }
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verifica una contraseña contra un hash
     * COMPATIBLE con contraseñas en texto plano (migración gradual)
     * 
     * @param string $password Contraseña ingresada
     * @param string $hash Hash almacenado en BD
     * @return bool True si la contraseña es correcta
     */
    public static function verify($password, $hash)
    {
        // Si el hash comienza con $, es un hash real (bcrypt, argon2, etc)
        if (strpos($hash, '$') === 0) {
            return password_verify($password, $hash);
        }
        
        // Si no, es contraseña en texto plano (legacy)
        // Comparación directa para compatibilidad
        return $password === $hash;
    }

    /**
     * Verifica si un hash necesita ser actualizado
     * 
     * @param string $hash Hash actual
     * @return bool True si necesita rehashing
     */
    public static function needsRehash($hash)
    {
        // Si no es un hash válido (texto plano), necesita rehash
        if (strpos($hash, '$') !== 0) {
            return true;
        }

        // Verificar si necesita actualización a algoritmo más seguro
        if (defined('PASSWORD_ARGON2ID')) {
            return password_needs_rehash($hash, PASSWORD_ARGON2ID);
        }
        return password_needs_rehash($hash, PASSWORD_BCRYPT);
    }

    /**
     * Valida la fortaleza de una contraseña
     * 
     * @param string $password Contraseña a validar
     * @return array Array vacío si es válida, o array con errores
     */
    public static function validate($password)
    {
        $errores = [];

        if (strlen($password) < 8) {
            $errores[] = "La contraseña debe tener al menos 8 caracteres";
        }
        return $errores;
    }

    /**
     * Genera una contraseña aleatoria segura
     * 
     * @param int $length Longitud de la contraseña
     * @return string Contraseña generada
     */
    public static function generate($length = 12)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        
        return $password;
    }
}
?>
