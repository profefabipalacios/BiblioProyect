<?php
require_once "includes/conexion.php";

$tipo = $_GET['tipo'] ?? '';
$buscar = $_GET['buscar'] ?? '';

// Nueva consulta acorde a la estructura ACTUALIZADA de inventario
$query = "SELECT * FROM inventario WHERE 1";
$params = [];
$types = "";

// Filtrar por tipo
if ($tipo !== '') {
    $query .= " AND tipo_item = ?";
    $params[] = $tipo;
    $types .= "s";
}

// Filtro de búsqueda
if ($buscar !== '') {

    // Importante: nombre → nombre_titulo
    $query .= " AND (
        id_item LIKE ? OR 
        nombre_titulo LIKE ? OR
        autor_marca LIKE ?
    )";

    $buscarLike = "%$buscar%";
    $params = array_merge($params, [$buscarLike, $buscarLike, $buscarLike]);
    $types .= "sss";
}

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

header('Content-Type: application/json');
echo json_encode($insumos);
?>