<?php
session_start();
require_once "includes/conexion.php";

// Verificar sesión (opcional pero recomendado)
if (!isset($_SESSION["id_bibliotecaria"])) {
    echo "<script>
        alert('❌ Debe iniciar sesión para registrar insumos.');
        window.location.href = 'login.php';
    </script>";
    exit;
}
?>

<div class="alta-insumo-container">
    <h2>Registrar Nuevo Insumo</h2>

    <form method="POST" action="procesar_insumo.php" onsubmit="return validarFormulario();">

        <label for="nombre">Nombre del insumo:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="tipo_item">Tipo de insumo:</label>
        <select name="tipo_item" id="tipo_item" required>
            <option value="">-- Seleccione --</option>
            <option value="Libro">Libro</option>
            <option value="Insumo Tecnologico">Insumo Tecnológico</option>
        </select>

        <label for="autor_marca">Autor o Marca (opcional):</label>
        <input type="text" name="autor_marca" id="autor_marca">

        <label for="stock_total">Stock Total:</label>
        <input type="number" name="stock_total" id="stock_total" min="1" required>

        <div class="botones">
            <button type="submit" class="btn-guardar">Guardar</button>
            <a href="dashboard.php?page=insumos" class="btn-volver">Volver</a>
        </div>

    </form>
</div>

<style>
.alta-insumo-container {
    background: #ffffff;
    padding: 25px;
    border-radius: 12px;
    max-width: 600px;
    margin: auto;
    margin-top: 20px;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
}

.alta-insumo-container h2 {
    margin-bottom: 15px;
    text-align: center;
    color: #333;
}

.alta-insumo-container form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.alta-insumo-container input,
.alta-insumo-container select {
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}

.botones {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}

.btn-guardar {
    background-color: #28a745;
    color: white;
    padding: 8px 18px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.btn-volver {
    background-color: #6c757d;
    color: white;
    padding: 8px 18px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-block;
}

.btn-guardar:hover,
.btn-volver:hover {
    opacity: 0.85;
}
</style>

<script>
function validarFormulario() {
    let nombre = document.getElementById("nombre").value.trim();
    let tipo = document.getElementById("tipo_item").value;
    let stock = parseInt(document.getElementById("stock_total").value);

    if (nombre === "") {
        alert("⚠️ El campo 'Nombre' no puede estar vacío.");
        return false;
    }

    if (tipo === "") {
        alert("⚠️ Debe seleccionar un tipo de insumo.");
        return false;
    }

    if (isNaN(stock) || stock <= 0) {
        alert("⚠️ El stock debe ser un número mayor a cero.");
        return false;
    }

    return true;
}
</script>