<?php
require_once "includes/conexion.php";

$data = [];

// --- SI VIENE UN id_item DESDE insumos.php ---
if (isset($_GET["id_item"])) {
    $id = intval($_GET["id_item"]);
    $stmt = $conn->prepare("SELECT * FROM inventario WHERE id_item = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    echo json_encode($res->fetch_assoc());
    exit;
}

// --- BÚSQUEDA NORMAL ---
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

if ($busqueda !== "") {
    $like = "%$busqueda%";

    $stmt = $conn->prepare("
        SELECT * FROM inventario
        WHERE nombre_titulo LIKE ?
        OR autor_marca LIKE ?
        ORDER BY nombre_titulo ASC
    ");

    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
}

header("Content-Type: application/json");
echo json_encode($data);