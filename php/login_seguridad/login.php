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

// Verificar token CSRF
if(!isset($_POST['csrf']) || !verify_csrf($_POST['csrf'])){
    echo json_encode(['success'=>false,'message'=>'Token CSRF inválido']); 
    exit;
}

$email = limpiar_input($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validar entrada
if(!validar_email($email) || empty($password)){
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
        registrar_intento_login($email, false);
        echo json_encode(['success'=>false,'message'=>'Contraseña incorrecta']);
    }
} else {
    // Usuario no encontrado
    registrar_intento_login($email, false);
    echo json_encode(['success'=>false,'message'=>'Usuario no encontrado']);
}

$stmt->close();
$mysqli->close();
