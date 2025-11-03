<?php
require_once "includes/conexion.php";

// Obtener término de búsqueda (si existe)
$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

// Consulta base
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

// Si hay búsqueda, agregar condición dinámica
$params = [];
$types = "";

if (!empty($buscar)) {
    $query .= " AND (p.dni_socio LIKE ? OR i.nombre LIKE ?)";
    $buscarParam = "%$buscar%";
    $params = [$buscarParam, $buscarParam];
    $types = "ss";
}

$query .= " ORDER BY p.fecha_prestamo DESC, p.hora_prestamo DESC";

$stmt = $conn->prepare($query);

// Enlazar parámetros si existen
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$prestamos = [];
while ($row = $result->fetch_assoc()) {
    // Normalización del campo estado (en caso de valores nulos o inconsistentes)
    if (empty($row['estado'])) {
        $row['estado'] = "Prestado";
    }
    $prestamos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($prestamos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);