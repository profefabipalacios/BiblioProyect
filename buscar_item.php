<?php
require_once "includes/conexion.php";

if (isset($_POST['id_item'])) {
    $id_item = intval($_POST['id_item']);

    $stmt = $conn->prepare("SELECT id_item, nombre, categoria FROM inventario WHERE id_item = ?");
    $stmt->bind_param("i", $id_item);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        echo json_encode([
            "existe" => true,
            "nombre" => $fila['nombre'],
            "categoria" => $fila['categoria']
        ]);
    } else {
        echo json_encode(["existe" => false]);
    }

    $stmt->close();
    exit;
}
echo json_encode(["error" => "No se recibió id_item"]);