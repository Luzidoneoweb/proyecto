<?php
// login.php
ob_start(); // Iniciar el búfer de salida
require_once 'seguridad.php';
header('Content-Type: application/json; charset=utf-8');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "proyecto";

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(['success'=>false,'message'=>'Método no permitido']); 
    exit;
}

$identifier = limpiar_input($_POST['identifier'] ?? '');
$password = limpiar_input($_POST['password'] ?? ''); // Aplicar limpiar_input a la contraseña

error_log("Intento de login - Identificador: " . $identifier);
error_log("Intento de login - Password (sin hash): " . $password);

// Validar entrada
if(empty($identifier) || empty($password)){
    error_log("Validación de credenciales fallida para identificador: " . $identifier);
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Credenciales inválidas']); 
    exit;
}

// Verificar intentos fallidos recientes
// Nota: La variable $email no está definida aquí, debería ser $identifier
if(verificar_intentos_fallidos($identifier)){ // Usar $identifier en lugar de $email
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Demasiados intentos fallidos. Intenta más tarde.']); 
    exit;
}

$mysqli = new mysqli($host, $user, $pass, $dbname);
if($mysqli->connect_errno){ 
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Error de conexión a la base de datos']); 
    exit; 
}
$mysqli->set_charset('utf8mb4');

$stmt = $mysqli->prepare("SELECT id, password, username, email FROM users WHERE username=? OR email=? LIMIT 1");
$stmt->bind_param("ss", $identifier, $identifier);
$stmt->execute();
$res = $stmt->get_result();

if($row = $res->fetch_assoc()){
    error_log("Usuario encontrado en DB: " . $row['username'] . " (ID: " . $row['id'] . ", Email: " . $row['email'] . ")");
    error_log("Hash de contraseña en DB: " . $row['password']);
    if(verificar_password($password, $row['password'])){
        // Login exitoso
        session_regenerate_id(true);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email']; // Usar el email de la DB
        
        // Registrar intento exitoso
        registrar_intento_login($identifier, true);
        
        ob_clean(); // Limpiar el búfer antes de enviar JSON
        echo json_encode([
            'success' => true,
            'message' => 'Login correcto',
            'username' => $row['username']
        ]);
    } else {
        // Contraseña incorrecta
        error_log("Contraseña incorrecta para identificador: " . $identifier);
        registrar_intento_login($identifier, false);
        ob_clean(); // Limpiar el búfer antes de enviar JSON
        echo json_encode(['success'=>false,'message'=>'Contraseña incorrecta']);
    }
} else {
    // Usuario no encontrado
    error_log("Usuario no encontrado para identificador: " . $identifier);
    registrar_intento_login($identifier, false);
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Usuario no encontrado']);
}

$stmt->close();
$mysqli->close();
