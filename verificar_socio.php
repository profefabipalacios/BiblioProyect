<?php
require_once "includes/conexion.php";
header('Content-Type: application/json');

if (!isset($_POST['dni'])) {
    echo json_encode(["error" => "No se recibió DNI"]);
    exit;
}

$dni = trim($_POST['dni']);

$stmt = $conn->prepare("
    SELECT dni, nombre, apellido, tipo_socio, estado 
    FROM socio 
    WHERE dni = ?
");
$stmt->bind_param("s", $dni);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["existe" => false]);
    exit;
}

$socio = $result->fetch_assoc();

echo json_encode([
    "existe" => true,
    "datos"  => $socio
]);
exit;