<?php
require_once "includes/conexion.php";

$buscar = trim($_GET["buscar"] ?? "");

// ================================
// CONSULTA BASE (solo DEVUELTOS)
// ================================
$sql = "
    SELECT
        p.id_prestamo,
        i.nombre_titulo AS nombre_item,
        p.dni_socio,
        CONCAT(b.nombre, ' ', b.apellido) AS nombre_bibliotecaria,
        p.fecha_prestamo,
        p.fecha_devolucion
    FROM prestamo p
    INNER JOIN inventario i ON p.id_item = i.id_item
    INNER JOIN bibliotecaria b ON p.id_bibliotecaria = b.id_bibliotecaria
    WHERE p.estado = 'Devuelto'
";

$params = [];
$types = "";

// Filtro de búsqueda
if ($buscar !== "") {
    $sql .= " AND (p.dni_socio LIKE ? OR i.nombre_titulo LIKE ?)";
    $like = "%$buscar%";
    $params[] = $like;
    $params[] = $like;
    $types .= "ss";
}

$sql .= " ORDER BY p.fecha_devolucion DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$datos = [];
while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}

// ================================
// TOTAL DE DEVOLUCIONES
// ================================
$totalRes = $conn->query("SELECT COUNT(*) AS total FROM prestamo WHERE estado = 'Devuelto'");
$total = $totalRes->fetch_assoc()["total"];

header("Content-Type: application/json");
echo json_encode([
    "total" => $total,
    "registros" => $datos
], JSON_UNESCAPED_UNICODE);