<?php
session_start();
require_once "includes/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") exit;

// VALIDACIÓN
if (!isset($_SESSION["id_bibliotecaria"])) {
    echo "<script>alert('Error de sesión'); window.location='login.php';</script>";
    exit;
}

$tipo_item = $_POST["tipo_item"];
$nombre    = $_POST["nombre_titulo"];
$autor     = $_POST["autor_marca"];
$stock     = intval($_POST["stock_total"]);
$id_biblio = $_SESSION["id_bibliotecaria"];

if ($tipo_item === "Libro") {
    $isbn     = $_POST["ISBN"];
    $edicion  = $_POST["edicion"];
    $editorial= $_POST["editorial"];
    $anio     = $_POST["anio_pub"];

    $sql = "INSERT INTO inventario
            (nombre_titulo, tipo_item, autor_marca, ISBN, edicion, editorial, anio_pub,
             stock_total, stock_disponible, id_bibliotecaria_registro)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssiiii",
        $nombre, $tipo_item, $autor, $isbn, $edicion, $editorial, $anio,
        $stock, $stock, $id_biblio
    );
}

else if ($tipo_item === "Insumo Tecnologico") {

    $sql = "INSERT INTO inventario
            (nombre_titulo, tipo_item, autor_marca,
             stock_total, stock_disponible, id_bibliotecaria_registro)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssiii",
        $nombre, $tipo_item, $autor, $stock, $stock, $id_biblio
    );
}

if ($stmt->execute()) {
    echo "<script>alert('Insumo registrado correctamente'); 
    window.location='dashboard.php?page=insumos';</script>";
} else {
    echo "<script>alert('Error al guardar: " . addslashes($stmt->error) . "');
    window.location='dashboard.php?page=alta_insumo';</script>";
}
?>