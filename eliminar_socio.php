<?php
require_once "includes/conexion.php";

header("Content-Type: application/json");

if (!isset($_POST['dni'])) {
    echo json_encode(["ok" => false, "mensaje" => "DNI no recibido."]);
    exit;
}

$dni = $_POST['dni'];

// Verificar préstamos activos
$check = $conn->prepare("
    SELECT id_prestamo 
    FROM prestamo 
    WHERE dni_socio = ? 
      AND estado IN ('Prestado', 'Retrasado')
");
$check->bind_param("s", $dni);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "No se puede eliminar: el socio tiene préstamos activos."
    ]);
    exit;
}

$del = $conn->prepare("DELETE FROM socio WHERE dni = ?");
$del->bind_param("s", $dni);

if ($del->execute()) {
    echo json_encode([
        "ok" => true,
        "mensaje" => "✅ Socio eliminado correctamente."
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "mensaje" => "❌ Error al eliminar el socio."
    ]);
}