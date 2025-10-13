<?php
// seguridad.php - Funciones de seguridad para la aplicación
session_start();

// Función para limpiar datos de entrada
function limpiar_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para validar email
function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Función para validar contraseña segura
function validar_password($password) {
    // Mínimo 8 caracteres, al menos una mayúscula, una minúscula, un número y un carácter especial
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}

// Función para generar hash de contraseña
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Función para verificar contraseña
function verificar_password($password, $hash) {
    return password_verify($password, $hash);
}

// Función para prevenir ataques de fuerza bruta
function registrar_intento_login($email, $exitoso = false) {
    $archivo = 'logs/intentos_login.txt';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $estado = $exitoso ? 'EXITOSO' : 'FALLIDO';
    
    $log_entry = "$timestamp - IP: $ip - Email: $email - Estado: $estado\n";
    
    // Crear directorio si no existe
    $dir = dirname($archivo);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents($archivo, $log_entry, FILE_APPEND | LOCK_EX);
}

// Función para verificar si hay demasiados intentos fallidos
function verificar_intentos_fallidos($email, $limite = 5, $tiempo_limite = 300) {
    $archivo = 'logs/intentos_login.txt';
    if (!file_exists($archivo)) {
        return false;
    }
    
    $contenido = file_get_contents($archivo);
    $lineas = explode("\n", $contenido);
    $intentos_recientes = 0;
    $tiempo_actual = time();
    
    foreach (array_reverse($lineas) as $linea) {
        if (empty($linea)) continue;
        
        if (strpos($linea, $email) !== false && strpos($linea, 'FALLIDO') !== false) {
            // Extraer timestamp de la línea
            preg_match('/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $linea, $matches);
            if (isset($matches[1])) {
                $timestamp_linea = strtotime($matches[1]);
                if (($tiempo_actual - $timestamp_linea) <= $tiempo_limite) {
                    $intentos_recientes++;
                } else {
                    break; // Los intentos más antiguos no cuentan
                }
            }
        }
    }
    
    return $intentos_recientes >= $limite;
}
?>
