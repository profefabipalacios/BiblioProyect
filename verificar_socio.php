<?php
require_once "includes/conexion.php";

if (isset($_POST['dni'])) {
    $dni = trim($_POST['dni']);

    $stmt = $conn->prepare("SELECT dni FROM socio WHERE dni = ?");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $stmt->store_result();

    $existe = $stmt->num_rows > 0;
    $stmt->close();

    echo json_encode(["existe" => $existe]);
    exit;
}

echo json_encode(["error" => "No se recibió DNI"]);