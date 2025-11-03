<?php
require_once "includes/conexion.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM socio WHERE id_socio = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
?>