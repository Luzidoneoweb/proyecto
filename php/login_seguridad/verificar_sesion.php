<?php
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> login
// verificar_sesion.php
session_start();
header('Content-Type: application/json; charset=utf-8');

$response = [
    'logged_in' => false,
    'username' => null,
    'user_id' => null
];

if (isset($_SESSION['user_id'])) {
    $response['logged_in'] = true;
    $response['user_id'] = $_SESSION['user_id'];
    
    // Obtener el nombre de usuario de la base de datos
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "proyecto";
    
    $mysqli = new mysqli($host, $user, $pass, $dbname);
    if (!$mysqli->connect_errno) {
        $mysqli->set_charset('utf8mb4');
        $stmt = $mysqli->prepare("SELECT username FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $response['username'] = $row['username'];
        }
        $stmt->close();
    }
    $mysqli->close();
}

echo json_encode($response);
<<<<<<< HEAD
=======
header('Content-Type: application/json');
echo json_encode(['logged_in' => false]);
>>>>>>> pestana
=======
>>>>>>> login
?>
