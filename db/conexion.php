<?php
$host = "localhost";
$user = "root";        // Cambia si tienes otro usuario
$password = "";        // Cambia si tienes contraseña
$database = "proyecto";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Asegurar codificación correcta para títulos/traducciones
$conn->set_charset('utf8mb4');