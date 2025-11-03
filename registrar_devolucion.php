<?php
require_once "includes/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitizar entrada
    $id_prestamo = intval($_POST["id_prestamo"] ?? 0);

    if ($id_prestamo <= 0) {
        echo json_encode(["mensaje" => "ID de préstamo inválido."]);
        exit;
    }

    // Zona horaria local
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    $fecha_devolucion = date("Y-m-d");
    $hora_devolucion = date("H:i:s");

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // 1. Verificar existencia del préstamo y estado
        $sql_item = "SELECT id_item, cantidad, estado FROM prestamo WHERE id_prestamo = ?";
        $stmt_item = $conn->prepare($sql_item);
        $stmt_item->bind_param("i", $id_prestamo);
        $stmt_item->execute();
        $result = $stmt_item->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("No se encontró el préstamo especificado.");
        }

        $row = $result->fetch_assoc();

        $id_item = intval($row["id_item"]);
        $cantidad = intval($row["cantidad"]);
        $estado_actual = $row["estado"];

        // Si ya fue devuelto, evitar duplicado
        if ($estado_actual === "Devuelto") {
            throw new Exception("El préstamo ya fue marcado como devuelto previamente.");
        }

        // 2. Actualizar préstamo como devuelto
        $sql_update = "UPDATE prestamo 
                       SET fecha_devolucion = ?, hora_devolucion = ?, estado = 'Devuelto'
                       WHERE id_prestamo = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssi", $fecha_devolucion, $hora_devolucion, $id_prestamo);
        $stmt_update->execute();

        // 3. Devolver stock al inventario
        $sql_stock = "UPDATE inventario 
                      SET stock_disponible = stock_disponible + ? 
                      WHERE id_item = ?";
        $stmt_stock = $conn->prepare($sql_stock);
        $stmt_stock->bind_param("ii", $cantidad, $id_item);
        $stmt_stock->execute();

        // Confirmar cambios
        $conn->commit();

        echo json_encode([
            "mensaje" => "Devolución registrada correctamente. Stock actualizado (+{$cantidad}).",
            "exito" => true
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            "mensaje" => "Error al registrar la devolución: " . $e->getMessage(),
            "exito" => false
        ]);
    }
}
?>