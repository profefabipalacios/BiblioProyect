<?php
require_once "includes/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_prestamo = intval($_POST["id_prestamo"]);

    // Fecha y hora actual
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    $fecha_devolucion = date("Y-m-d");
    $hora_devolucion = date("H:i:s");

    $sql = "UPDATE prestamo 
            SET fecha_devolucion = ?, hora_devolucion = ?, estado = 'Devuelto'
            WHERE id_prestamo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $fecha_devolucion, $hora_devolucion, $id_prestamo);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Devolución registrada correctamente."]);
    } else {
        echo json_encode(["mensaje" => "Error al registrar la devolución."]);
    }
}
?>