<?php
$host = "localhost";   
$usuario = "root";     
$clave = "";           
$bd = "biblioteca";

$conn = new mysqli($host, $usuario, $clave, $bd);

// Verifica la conexión, muestra error en caso de no conectarse.
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>