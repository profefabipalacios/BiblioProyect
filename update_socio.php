<?php
require_once "includes/conexion.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["id_socio"]);
    $dni = trim($_POST["dni"]);
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $tipo = trim($_POST["tipo_socio"]);
    $carrera = $_POST["carrera"] ?: null;

    $sql = "UPDATE socio SET dni=?, nombre=?, apellido=?, tipo_socio=?, carrera=? WHERE id_socio=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $dni, $nombre, $apellido, $tipo, $carrera, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Cambios guardados correctamente.'); window.location.href='dashboard.php?page=socios';</script>";
    } else {
        echo "<script>alert('Error al actualizar socio.'); window.location.href='dashboard.php?page=socios';</script>";
    }
}
?>