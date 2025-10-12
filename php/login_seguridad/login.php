<?php
// login.php
require_once 'seguridad.php';
header('Content-Type: application/json; charset=utf-8');
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "proyecto";

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
  echo json_encode(['success'=>false,'message'=>'Método no permitido']); exit;
}

// CSRF check if provided
if(isset($_POST['csrf']) && !verify_csrf($_POST['csrf'])){
  echo json_encode(['success'=>false,'message'=>'Token CSRF inválido']); exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if(!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)){
  echo json_encode(['success'=>false,'message'=>'Credenciales inválidas']); exit;
}

$mysqli = new mysqli($host, $user, $pass, $dbname);
if($mysqli->connect_errno){ echo json_encode(['success'=>false,'message'=>'DB error']); exit; }
$mysqli->set_charset('utf8mb4');

$stmt = $mysqli->prepare("SELECT id, password FROM users WHERE email=? LIMIT 1");
$stmt->bind_param("s",$email);
$stmt->execute();
$res = $stmt->get_result();
if($row = $res->fetch_assoc()){
  if(password_verify($password, $row['password'])){
    // login success
    session_regenerate_id(true);
    $_SESSION['user_id'] = $row['id'];
    echo json_encode(['success'=>true,'message'=>'Login correcto']);
  } else {
    echo json_encode(['success'=>false,'message'=>'Contraseña incorrecta']);
  }
} else {
  echo json_encode(['success'=>false,'message'=>'Usuario no encontrado']);
}
$stmt->close();
$mysqli->close();
