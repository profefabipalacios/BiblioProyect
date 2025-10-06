<?php
require_once "includes/conexion.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = trim($_POST['dni']);
    $id_items = $_POST['id_item'];
    $cantidades = $_POST['cantidad'];

    if (!isset($_SESSION['id_bibliotecaria'])) {
        echo "<script>alert('Error: No se identificó al bibliotecario.'); window.location='dashboard.php?page=prestamos';</script>";
        exit;
    }

    $id_bib = $_SESSION['id_bibliotecaria'];
    $fecha_prestamo = date("Y-m-d");
    $hora_prestamo = date("H:i:s");

    $conn->begin_transaction();

    try {
        foreach ($id_items as $i => $id_item) {
            $cantidad = (int)$cantidades[$i];

            // Verificar stock disponible
            $sql_stock = "SELECT stock_disponible FROM inventario WHERE id_item = ?";
            $stmt_stock = $conn->prepare($sql_stock);
            $stmt_stock->bind_param("i", $id_item);
            $stmt_stock->execute();
            $result_stock = $stmt_stock->get_result()->fetch_assoc();

            if (!$result_stock || $result_stock['stock_disponible'] < $cantidad) {
                throw new Exception("No hay stock suficiente para el ítem ID $id_item.");
            }

            // Insertar el préstamo con cantidad
            $sql_insert = "INSERT INTO prestamo (id_item, dni_socio, id_bibliotecaria, fecha_prestamo, hora_prestamo, estado, cantidad)
                           VALUES (?, ?, ?, ?, ?, 'Prestado', ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("isissi", $id_item, $dni, $id_bib, $fecha_prestamo, $hora_prestamo, $cantidad);
            $stmt_insert->execute();

            // Actualizar el stock disponible
            $sql_update = "UPDATE inventario SET stock_disponible = stock_disponible - ? WHERE id_item = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ii", $cantidad, $id_item);
            $stmt_update->execute();
        }

        $conn->commit();
        echo "<script>alert('Préstamos registrados correctamente.'); window.location='dashboard.php?page=prestamos';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error: {$e->getMessage()}'); window.location='dashboard.php?page=prestamos';</script>";
    }
}
?>