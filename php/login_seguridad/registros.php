<?php
// register.php
require_once 'seguridad.php';
header('Content-Type: application/json; charset=utf-8');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "proyecto";

session_start();

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
  echo json_encode(['success'=>false,'message'=>'Método no permitido']); exit;
}

// CSRF token (opcional frontend: si lo usas, añade un hidden input con csrf token)
if(isset($_POST['csrf']) && !verify_csrf($_POST['csrf'])){
  echo json_encode(['success'=>false,'message'=>'Token CSRF inválido']); exit;
}

// inputs
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$imei = trim($_POST['imei'] ?? '');

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
  echo json_encode(['success'=>false,'message'=>'Email inválido']); exit;
}
if(strlen($username) < 2){
  echo json_encode(['success'=>false,'message'=>'Nombre de usuario demasiado corto']); exit;
}
if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)){
  echo json_encode(['success'=>false,'message'=>'Contraseña insegura']); exit;
}

$mysqli = new mysqli($host, $user, $pass, $dbname);
if($mysqli->connect_errno){ echo json_encode(['success'=>false,'message'=>'DB error']); exit; }
$mysqli->set_charset('utf8mb4');

// check email unique
$stmt = $mysqli->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$stmt->bind_param("s",$email);
$stmt->execute();
$stmt->store_result();
if($stmt->num_rows > 0){
  echo json_encode(['success'=>false,'message'=>'Este email ya está registrado']); exit;
}
$stmt->close();

// insert
$hash = password_hash($password, PASSWORD_DEFAULT);
$insert = $mysqli->prepare("INSERT INTO users (username,email,password,imei) VALUES (?,?,?,?)");
if(!$insert){
  echo json_encode(['success'=>false,'message'=>'Error en la consulta']); exit;
}
$insert->bind_param("ssss",$username,$email,$hash,$imei);
if($insert->execute()){
  echo json_encode(['success'=>true,'message'=>'Cuenta creada correctamente']);
} else {
  echo json_encode(['success'=>false,'message'=>'Error al crear cuenta']);
}
$insert->close();
$mysqli->close();
