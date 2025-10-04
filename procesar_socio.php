<?php
require_once "includes/conexion.php";
session_start();

// Validar que venga por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = trim($_POST['dni']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipo = trim($_POST['tipo']);

    // Verificar que exista el ID de bibliotecaria en sesión
    if (!isset($_SESSION['id_bibliotecaria'])) {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => 'Error: No se ha identificado al bibliotecario que realiza la carga.'
        ];
        header("Location: dashboard.php?page=socios");
        exit;
    }

    $id_bib_registro = $_SESSION['id_bibliotecaria'];

    // Verificar que no esté vacío
    if ($dni === '' || $nombre === '' || $apellido === '' || $tipo === '') {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => 'Todos los campos son obligatorios.'
        ];
        header("Location: dashboard.php?page=socios");
        exit;
    }

    // Insertar socio
    $fecha_alta = date("Y-m-d");
    $insert = $conn->prepare("INSERT INTO socio (dni, nombre, apellido, tipo_socio, fecha_alta, id_bib_registro) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->bind_param("sssssi", $dni, $nombre, $apellido, $tipo, $fecha_alta, $id_bib_registro);

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