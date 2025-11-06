<?php
require_once "includes/conexion.php";

if (!isset($_GET['dni'])) {
    echo "Error: DNI no recibido.";
    exit;
}

$dni = $_GET['dni'];

// Verificar si tiene préstamos activos
$check = $conn->prepare("SELECT * FROM prestamo WHERE dni_socio = ? AND estado = 'Prestado'");
$check->bind_param("s", $dni);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    echo "No se puede eliminar: el socio tiene préstamos activos.";
    exit;
}

$stmt = $conn->prepare("DELETE FROM socio WHERE dni = ?");
$stmt->bind_param("s", $dni);

if ($stmt->execute()) {
    echo "Socio eliminado correctamente.";
} else {
    echo "Error al eliminar el socio.";
}
?>