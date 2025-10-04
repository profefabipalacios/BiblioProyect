<?php
require_once "includes/conexion.php";

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

$query = "SELECT * FROM inventario WHERE 1";
$params = [];
$types = "";

// Filtrar por tipo si se seleccionó
if ($tipo) {
    $query .= " AND tipo_item = ?";
    $params[] = $tipo;
    $types .= "s";
}

// Filtrar por búsqueda si hay texto
if ($buscar) {
    $query .= " AND (id_item LIKE ? OR nombre LIKE ? OR tipo_item LIKE ? OR autor_marca LIKE ?)";
    $buscarParam = "%$buscar%";
    $params = array_merge($params, [$buscarParam, $buscarParam, $buscarParam, $buscarParam]);
    $types .= str_repeat("s", 4);
}

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$insumos = [];

while ($row = $result->fetch_assoc()) {
    $insumos[] = $row;
}

// Devolver los datos en JSON
header('Content-Type: application/json');
echo json_encode($insumos);
?>