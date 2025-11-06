<?php
session_start();
require_once "includes/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validación de sesión
    if (!isset($_SESSION["id_bibliotecaria"])) {
        echo "<script>
            alert('❌ Error: No se ha identificado al bibliotecario.');
            window.location.href = 'login.php';
        </script>";
        exit;
    }

    // Sanitización y validación de datos
    $nombre        = trim($_POST["nombre"] ?? '');
    $tipo_item     = trim($_POST["tipo_item"] ?? '');
    $autor_marca   = trim($_POST["autor_marca"] ?? '');
    $stock_total   = intval($_POST["stock_total"] ?? 0);

    // Validaciones básicas
    if ($nombre === '' || $tipo_item === '' || $stock_total <= 0) {
        echo "<script>
            alert('⚠️ Complete todos los campos obligatorios correctamente.');
            window.location.href = 'dashboard.php?page=alta_insumo';
        </script>";
        exit;
    }

    $id_bibliotecaria = $_SESSION["id_bibliotecaria"];
    $stock_disponible = $stock_total;

    // Preparar sentencia SQL
    $sql = "INSERT INTO inventario (
                nombre, tipo_item, autor_marca, stock_total, stock_disponible, id_bibliotecaria_registro
            ) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "<script>
            alert('❌ Error al preparar la consulta: " . addslashes($conn->error) . "');
            window.location.href = 'dashboard.php?page=alta_insumo';
        </script>";
        exit;
    }

    $stmt->bind_param("sssiii",
        $nombre,
        $tipo_item,
        $autor_marca,
        $stock_total,
        $stock_disponible,
        $id_bibliotecaria
    );

    // Ejecutar y responder
    if ($stmt->execute()) {
        echo "<script>
            alert('✅ Insumo registrado correctamente.');
            window.location.href = 'dashboard.php?page=insumos';
        </script>";
    } else {
        echo "<script>
            alert('❌ Error al guardar el insumo: " . addslashes($stmt->error) . "');
            window.location.href = 'dashboard.php?page=alta_insumo';
        </script>";
    }

    $stmt->close();
}
?>