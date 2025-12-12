<?php
require_once "includes/conexion.php";

$buscar = trim($_GET['buscar'] ?? '');
$estadoFiltro = $_GET['estado'] ?? 'activo';

// Estados permitidos
if ($estadoFiltro === "activo") {
    $condEstado = "p.estado IN ('Prestado','Retrasado')";
} else if ($estadoFiltro === "devuelto") {
    $condEstado = "p.estado = 'Devuelto'";
} else {
    $condEstado = "p.estado IN ('Prestado','Retrasado')";
}

$query = "
    SELECT 
        p.id_prestamo,
        i.nombre_titulo AS nombre_item,
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
    WHERE $condEstado
";

$params = [];
$types = "";

if ($buscar !== "") {
    $query .= " AND (p.dni_socio LIKE ? OR i.nombre_titulo LIKE ?)";
    $buscarLike = "%$buscar%";
    $params = [$buscarLike, $buscarLike];
    $types = "ss";
}

$query .= " ORDER BY p.fecha_prestamo DESC, p.hora_prestamo DESC";

$stmt = $conn->prepare($query);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$prestamos = [];
while ($row = $result->fetch_assoc()) {
    $prestamos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($prestamos, JSON_UNESCAPED_UNICODE);