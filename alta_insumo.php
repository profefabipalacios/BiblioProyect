<?php
session_start();
require_once "includes/conexion.php";
?>

<div class="alta-insumo-container">
    <h2>Nuevo Insumo</h2>
    <form method="POST" action="procesar_insumo.php">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="tipo_item">Tipo:</label>
        <select name="tipo_item" id="tipo_item" required>
            <option value="">-- Seleccione --</option>
            <option value="Libro">Libro</option>
            <option value="Insumo Tecnologico">Insumo Tecnológico</option>
        </select>

        <label for="autor_marca">Autor/Marca:</label>
        <input type="text" name="autor_marca" id="autor_marca">

        <label for="stock_total">Stock Total:</label>
        <input type="number" name="stock_total" id="stock_total" min="1" required>

        <button type="submit">Guardar</button>
        <a href="dashboard.php?page=insumos" class="btn-volver">Volver</a>
    </form>
</div>