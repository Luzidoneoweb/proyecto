<?php
// registros.php
ob_start(); // Iniciar el búfer de salida
require_once 'seguridad.php';
header('Content-Type: application/json; charset=utf-8');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "proyecto";

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Método no permitido']); 
    exit;
}

// Limpiar y validar inputs
$username = limpiar_input($_POST['username'] ?? '');
$email = limpiar_input($_POST['email'] ?? '');
$password = limpiar_input($_POST['password'] ?? ''); // Aplicar limpiar_input
$password_confirm = limpiar_input($_POST['password_confirm'] ?? ''); // Aplicar limpiar_input


// Validaciones
if(!validar_email($email)){
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Email inválido']); 
    exit;
}

if(strlen($username) < 2){
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Nombre de usuario demasiado corto (mínimo 2 caracteres)']); 
    exit;
}

if(strlen($username) > 50){
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Nombre de usuario demasiado largo (máximo 50 caracteres)']); 
    exit;
}

if(!validar_password($password)){
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Contraseña insegura. Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial']); 
    exit;
}

if($password !== $password_confirm){
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Las contraseñas no coinciden']); 
    exit;
}

$mysqli = new mysqli($host, $user, $pass, $dbname);
if($mysqli->connect_errno){ 
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Error de conexión a la base de datos']); 
    exit; 
}
$mysqli->set_charset('utf8mb4');

// Verificar si el email ya existe
$stmt = $mysqli->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if($stmt->num_rows > 0){
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Este email ya está registrado']); 
    exit;
}
$stmt->close();

// Verificar si el nombre de usuario ya existe
$stmt = $mysqli->prepare("SELECT id FROM users WHERE username=? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if($stmt->num_rows > 0){
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Este nombre de usuario ya está en uso']); 
    exit;
}
$stmt->close();

// Insertar nuevo usuario
$hash = hash_password($password);
$insert = $mysqli->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
if(!$insert){
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Error en la consulta de inserción']); 
    exit;
}

$insert->bind_param("sss", $username, $email, $hash);
if($insert->execute()){
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>true,'message'=>'Cuenta creada correctamente']);
} else {
    ob_clean(); // Limpiar el búfer antes de enviar JSON
    echo json_encode(['success'=>false,'message'=>'Error al crear cuenta']);
}
$insert->close();
$mysqli->close();
