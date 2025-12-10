<?php
require_once "includes/conexion.php";

$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

$query = "SELECT * FROM inventario 
          WHERE nombre LIKE '%$busqueda%' 
          OR autor_marca LIKE '%$busqueda%' 
          ORDER BY nombre ASC";

$result = $conn->query($query);
$data = [];

if (isset($_GET["id_item"])) {
    $id = intval($_GET["id_item"]);
    $stmt = $conn->prepare("SELECT * FROM inventario WHERE id_item = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
    exit;
}

header('Content-Type: application/json');
echo json_encode($data);
?>