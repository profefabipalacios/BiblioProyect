<?php
require_once "includes/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $dni = trim($_POST['dni']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipo_socio = trim($_POST['tipo_socio']);
    $carrera = trim($_POST['carrera']);

    $stmt = $conn->prepare("UPDATE socio SET dni=?, nombre=?, apellido=?, tipo_socio=?, carrera=? WHERE id_socio=?");
    $stmt->bind_param("sssssi", $dni, $nombre, $apellido, $tipo_socio, $carrera, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Socio actualizado correctamente.'); window.location.href='dashboard.php?page=socios';</script>";
    } else {
        echo "<script>alert('Error al actualizar socio.'); window.location.href='dashboard.php?page=socios';</script>";
    }
}
?>