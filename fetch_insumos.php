<?php
require_once "includes/conexion.php";

$tipo   = $_GET["tipo"] ?? "";
$buscar = $_GET["buscar"] ?? "";

$sql = "SELECT * FROM inventario WHERE 1=1";
$params = [];
$types  = "";

// FILTRO DE TIPO
if ($tipo !== "") {
    $sql .= " AND tipo_item = ?";
    $params[] = $tipo;
    $types .= "s";
}

// FILTRO BÚSQUEDA
if ($buscar !== "") {
    $sql .= " AND (
        id_item LIKE ?
        OR nombre_titulo LIKE ?
        OR autor_marca LIKE ?
        OR ISBN LIKE ?
        OR editorial LIKE ?
    )";

    $like = "%$buscar%";
    $params = array_merge($params, [$like, $like, $like, $like, $like]);
    $types .= "sssss";
}

$stmt = $conn->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$datos = [];

while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}

header("Content-Type: application/json");
echo json_encode($datos);