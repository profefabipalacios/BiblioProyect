<?php
require_once "includes/conexion.php";

$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

$query = "SELECT * FROM inventario 
          WHERE nombre LIKE '%$busqueda%' 
          OR autor_marca LIKE '%$busqueda%' 
          ORDER BY nombre ASC";

$result = $conn->query($query);
$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
?>