<?php
require_once "includes/conexion.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_socio = intval($_POST['id_socio']);
    $dni = trim($_POST['dni']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipo_socio = trim($_POST['tipo_socio']);
    $id_carrera = !empty($_POST['id_carrera']) ? intval($_POST['id_carrera']) : null;

    // Validación básica
    if ($dni === "" || $nombre === "" || $apellido === "") {
        echo "<script>alert('Todos los campos obligatorios deben estar completos.'); 
              window.location.href='dashboard.php?page=socios';</script>";
        exit;
    }

    $sql = "UPDATE socio 
            SET dni = ?, nombre = ?, apellido = ?, tipo_socio = ?, id_carrera = ?
            WHERE id_socio = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $dni, $nombre, $apellido, $tipo_socio, $id_carrera, $id_socio);

    if ($stmt->execute()) {
        echo "<script>alert('Datos actualizados correctamente.');
              window.location.href='dashboard.php?page=socios';</script>";
    } else {
        echo "<script>alert('Error al actualizar: " . addslashes($conn->error) . "');
              window.location.href='dashboard.php?page=socios';</script>";
    }
}
?>