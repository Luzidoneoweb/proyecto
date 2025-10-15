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

$identifier = limpiar_input($_POST['identifier'] ?? '');
$password = limpiar_input($_POST['password'] ?? ''); // Aplicar limpiar_input a la contraseña

// Validar entrada
if(empty($identifier) || empty($password)){
    echo json_encode(['success'=>false,'message'=>'Credenciales inválidas']); 
    exit;
}

// Verificar intentos fallidos recientes
if(verificar_intentos_fallidos($identifier)){
    echo json_encode(['success'=>false,'message'=>'Demasiados intentos fallidos. Intenta más tarde.']); 
    exit;
}

$mysqli = new mysqli($host, $user, $pass, $dbname);
if($mysqli->connect_errno){ 
    echo json_encode(['success'=>false,'message'=>'Error de conexión a la base de datos']); 
    exit; 
}
$mysqli->set_charset('utf8mb4');

$stmt = $mysqli->prepare("SELECT id, password, username, email FROM users WHERE username=? OR email=? LIMIT 1");
$stmt->bind_param("ss", $identifier, $identifier);
$stmt->execute();
$res = $stmt->get_result();

if($row = $res->fetch_assoc()){
    if(verificar_password($password, $row['password'])){
        // Login exitoso
        session_regenerate_id(true);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email']; // Usar el email de la DB
        
        // Registrar intento exitoso
        registrar_intento_login($identifier, true);
        
        echo json_encode([
            'success' => true,
            'message' => 'Login correcto',
            'username' => $row['username']
        ]);
    } else {
        // Contraseña incorrecta
        registrar_intento_login($identifier, false);
        echo json_encode(['success'=>false,'message'=>'Contraseña incorrecta']);
    }
} else {
    // Usuario no encontrado
    registrar_intento_login($identifier, false);
    echo json_encode(['success'=>false,'message'=>'Usuario no encontrado']);
}

$stmt->close();
$mysqli->close();
