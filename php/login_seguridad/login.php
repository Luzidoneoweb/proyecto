<?php
// login.php
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

$email = limpiar_input($_POST['email'] ?? '');
$password = limpiar_input($_POST['password'] ?? ''); // Aplicar limpiar_input a la contraseña

error_log("Intento de login - Email: " . $email);
error_log("Intento de login - Password (sin hash): " . $password);

// Validar entrada
if(!validar_email($email) || empty($password)){
    error_log("Validación de credenciales fallida para email: " . $email);
    echo json_encode(['success'=>false,'message'=>'Credenciales inválidas']); 
    exit;
}

// Verificar intentos fallidos recientes
if(verificar_intentos_fallidos($email)){
    echo json_encode(['success'=>false,'message'=>'Demasiados intentos fallidos. Intenta más tarde.']); 
    exit;
}

$mysqli = new mysqli($host, $user, $pass, $dbname);
if($mysqli->connect_errno){ 
    echo json_encode(['success'=>false,'message'=>'Error de conexión a la base de datos']); 
    exit; 
}
$mysqli->set_charset('utf8mb4');

$stmt = $mysqli->prepare("SELECT id, password, username FROM users WHERE email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if($row = $res->fetch_assoc()){
    error_log("Usuario encontrado en DB: " . $row['username'] . " (ID: " . $row['id'] . ")");
    error_log("Hash de contraseña en DB: " . $row['password']);
    if(verificar_password($password, $row['password'])){
        // Login exitoso
        session_regenerate_id(true);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $email;
        
        // Registrar intento exitoso
        registrar_intento_login($email, true);
        
        echo json_encode([
            'success' => true,
            'message' => 'Login correcto',
            'username' => $row['username']
        ]);
    } else {
        // Contraseña incorrecta
        error_log("Contraseña incorrecta para email: " . $email);
        registrar_intento_login($email, false);
        echo json_encode(['success'=>false,'message'=>'Contraseña incorrecta']);
    }
} else {
    // Usuario no encontrado
    error_log("Usuario no encontrado para email: " . $email);
    registrar_intento_login($email, false);
    echo json_encode(['success'=>false,'message'=>'Usuario no encontrado']);
}

$stmt->close();
$mysqli->close();
