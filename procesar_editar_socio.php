<?php
require_once "includes/conexion.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php?page=socios");
    exit;
}

$dni = $_POST['dni'];
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$tipo_socio = trim($_POST['tipo_socio']);
$id_carrera = !empty($_POST['id_carrera']) ? intval($_POST['id_carrera']) : null;

// Validar
if ($nombre === "" || $apellido === "" || $tipo_socio === "") {
    echo "<script>
            alert('Todos los campos obligatorios deben completarse.');
            window.location.href='dashboard.php?page=editar_socio&dni={$dni}';
          </script>";
    exit;
}

// Preparar UPDATE
$stmt = $conn->prepare("
    UPDATE socio 
    SET nombre = ?, apellido = ?, tipo_socio = ?, id_carrera = ?
    WHERE dni = ?
");

$stmt->bind_param(
    "sssis",
    $nombre,
    $apellido,
    $tipo_socio,
    $id_carrera,
    $dni
);

if ($stmt->execute()) {
    echo "<script>
        alert('Datos actualizados correctamente.');
        window.location.href='dashboard.php?page=socios';
    </script>";
} else {
    echo "<script>
        alert('Error al actualizar: " . addslashes($conn->error) . "');
        window.location.href='dashboard.php?page=editar_socio&dni={$dni}';
    </script>";
}