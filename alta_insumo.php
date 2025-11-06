<?php
session_start();
require_once "includes/conexion.php";

// Verificar bibliotecario logueado
if (!isset($_SESSION['id_bibliotecaria'])) {
    echo "<script>alert('Error: No se ha identificado al bibliotecario.'); 
          window.location='dashboard.php?page=insumos';</script>";
    exit;
}
?>

<div class="alta-insumo-container">
    <h2>Registrar Nuevo Insumo</h2>

    <form method="POST" action="procesar_insumo.php" class="form-insumo">

        <label for="nombre">Nombre del insumo:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="tipo_item">Tipo de Insumo:</label>
        <select name="tipo_item" id="tipo_item" required>
            <option value="">-- Seleccione --</option>
            <option value="Libro">Libro</option>
            <option value="Insumo Tecnologico">Insumo Tecnológico</option>
        </select>

        <label for="autor_marca">Autor / Marca:</label>
        <input type="text" name="autor_marca" id="autor_marca" placeholder="Opcional">

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
    max-width: 600px;
    margin: auto;
    padding: 25px;
    background: #ffffff;
    border-radius: 10px;
    border: 1px solid #ddd;
}

.alta-insumo-container h2 {
    text-align: center;
    margin-bottom: 20px;
}

.form-insumo {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.form-insumo label {
    font-weight: bold;
}

.form-insumo input,
.form-insumo select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 8px;
}

.botones {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
}

.btn-guardar {
    background-color: #28a745;
    padding: 8px 15px;
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 8px;
    font-weight: bold;
}

.btn-volver {
    background-color: #6c757d;
    padding: 8px 15px;
    text-decoration: none;
    color: white;
    border-radius: 8px;
    font-weight: bold;
}

.btn-guardar:hover,
.btn-volver:hover {
    opacity: 0.85;
}
</style>