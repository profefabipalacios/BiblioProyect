<?php
$host = "localhost";   // Cambia si usas un host diferente
$usuario = "root";     // Usuario de MySQL
$clave = "";           // Contraseña (en XAMPP suele estar vacía)
$bd = "biblioteca";

$conn = new mysqli($host, $usuario, $clave, $bd);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>