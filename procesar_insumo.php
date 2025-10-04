<?php
session_start();
require_once "includes/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $tipo_item = $_POST["tipo_item"];
    $autor_marca = $_POST["autor_marca"];
    $stock_total = $_POST["stock_total"];

    // Obtenemos id de bibliotecaria en sesión
    if (!isset($_SESSION["id_bibliotecaria"])) {
        // Si no hay sesión activa, redirigir al login
        header("Location: login.php");
        exit;
    }
    $id_bibliotecaria = $_SESSION["id_bibliotecaria"];

    // Stock disponible al inicio = stock total
    $stock_disponible = $stock_total;

    $sql = "INSERT INTO inventario (nombre, tipo_item, autor_marca, stock_total, stock_disponible, id_bibliotecaria_registro)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiii", $nombre, $tipo_item, $autor_marca, $stock_total, $stock_disponible, $id_bibliotecaria);

    if ($stmt->execute()) {
        echo "<script>
            alert('✅ Insumo registrado correctamente');
            window.location.href = 'dashboard.php?page=insumos';
        </script>";
    } else {
        echo "<script>
            alert('❌ Error al guardar el insumo: " . addslashes($stmt->error) . "');
            window.location.href = 'dashboard.php?page=alta_insumo';
        </script>";
    }
}
?>