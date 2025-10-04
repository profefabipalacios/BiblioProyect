<?php
require_once "includes/conexion.php";

$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

$query = "
    SELECT 
        p.id_prestamo,
        i.nombre AS nombre_item,
        p.dni_socio,
        CONCAT(b.nombre, ' ', b.apellido) AS nombre_bibliotecaria,
        p.fecha_prestamo,
        p.hora_prestamo,
        p.fecha_devolucion,
        p.hora_devolucion,
        p.estado
    FROM prestamo p
    INNER JOIN inventario i ON p.id_item = i.id_item
    INNER JOIN bibliotecaria b ON p.id_bibliotecaria = b.id_bibliotecaria
    WHERE 1
";

$params = [];
$types = "";

if ($buscar) {
    $query .= " AND (p.dni_socio LIKE ? OR i.nombre LIKE ?)";
    $buscarParam = "%$buscar%";
    $params = [$buscarParam, $buscarParam];
    $types = "ss";
}

$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$prestamos = [];

while ($row = $result->fetch_assoc()) {
    $prestamos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($prestamos);