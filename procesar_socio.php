<?php
require_once "includes/conexion.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = trim($_POST['dni']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipo = trim($_POST['tipo']);
    $id_carrera = isset($_POST['id_carrera']) && $_POST['id_carrera'] !== '' ? intval($_POST['id_carrera']) : null;

    if (!isset($_SESSION['id_bibliotecaria'])) {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => 'Error: No se ha identificado al bibliotecario que realiza la carga.'
        ];
        header("Location: dashboard.php?page=socios");
        exit;
    }

    $id_bib_registro = $_SESSION['id_bibliotecaria'];

    if ($dni === '' || $nombre === '' || $apellido === '' || $tipo === '') {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => 'Todos los campos son obligatorios (excepto carrera).'
        ];
        header("Location: dashboard.php?page=socios");
        exit;
    }

    $fecha_alta = date("Y-m-d");

    $insert = $conn->prepare("
    INSERT INTO socio (dni, nombre, apellido, tipo_socio, id_carrera, fecha_alta, id_bib_registro) 
    VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param("ssssisi", $dni, $nombre, $apellido, $tipo, $id_carrera, $fecha_alta, $id_bib_registro);

    if ($insert->execute()) {
        echo "<script>
            alert('Socio cargado correctamente.');
            window.location.href='dashboard.php?page=socios';
        </script>";
    } else {
        echo "<script>
            alert('Error al guardar el socio: " . addslashes($conn->error) . "');
            window.location.href='dashboard.php?page=socios';
        </script>";
    }

    $insert->close();
    exit;
} else {
    header("Location: dashboard.php?page=socios");
    exit;
}
?>