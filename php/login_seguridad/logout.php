<?php
<<<<<<< HEAD
require_once 'seguridad.php'; // Incluir funciones de seguridad
header('Content-Type: application/json; charset=utf-8');

// Limpiar todas las variables de sesión
$_SESSION = [];

// Destruir la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Eliminar la cookie de "recordarme" si existe
delete_remember_cookie();

// Devolver respuesta JSON
echo json_encode([
    'success' => true,
    'message' => 'Sesión cerrada correctamente'
]);
exit;
=======
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Sesión cerrada correctamente.']);
?>
>>>>>>> pestana
