<?php
// Configuración de producción
$host = "sql206.infinityfree.com";
$user = "if0_39209868";        // Cambia si tienes otro usuario
$password = "xRe9fa3aAy";        // Cambia si tienes contraseña
$database = "if0_39209868_proyecto";


$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Asegurar codificación correcta para títulos/traducciones
$conn->set_charset('utf8mb4');

// Configuración local (comentada para producción)
// $host = "localhost";
// $user = "root";        // Cambia si tienes otro usuario
// $password = "";        // Cambia si tienes contraseña
// $database = "proyecto";

// $conn = new mysqli($host, $user, $password, $database);

// if ($conn->connect_error) {
//     die("Conexión fallida: " . $conn->connect_error);
// }

// // Asegurar codificación correcta para títulos/traducciones
// $conn->set_charset('utf8mb4');