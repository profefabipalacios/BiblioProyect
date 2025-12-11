<?php
session_start();
require_once "includes/conexion.php";

if (!isset($_SESSION["id_bibliotecaria"])) {
    header("Location: login.php");
    exit;
}
?>

<h2>Registrar Nuevo Insumo</h2>

<div class="selector-tipo">
    <button onclick="mostrarFormulario('libro')">📚 Registrar Libro</button>
    <button onclick="mostrarFormulario('tecno')">💻 Registrar Insumo Tecnológico</button>
</div>

<!-- FORMULARIO LIBRO -->
<form id="formLibro" method="POST" action="procesar_insumo.php" style="display:none;">
    <h3>Nuevo Libro</h3>

    <input type="hidden" name="tipo_item" value="Libro">

    <label>Título:</label>
    <input type="text" name="nombre_titulo" required>

    <label>Autor:</label>
    <input type="text" name="autor_marca" required>

    <label>ISBN:</label>
    <input type="text" name="ISBN" required>

    <label>Edición:</label>
    <input type="text" name="edicion" required>

    <label>Editorial:</label>
    <input type="text" name="editorial" required>

    <label>Año Publicación:</label>
    <input type="number" name="anio_pub" required>

    <label>Stock Total:</label>
    <input type="number" name="stock_total" min="1" required>

    <button type="submit" class="btn">Guardar Libro</button>
</form>

<!-- FORMULARIO INSUMO TECNOLÓGICO -->
<form id="formTecno" method="POST" action="procesar_insumo.php" style="display:none;">
    <h3>Nuevo Insumo Tecnológico</h3>

    <input type="hidden" name="tipo_item" value="Insumo Tecnologico">

    <label>Nombre del insumo:</label>
    <input type="text" name="nombre_titulo" required>

    <label>Marca / Autor:</label>
    <input type="text" name="autor_marca" required>

    <label>Stock Total:</label>
    <input type="number" name="stock_total" min="1" required>

    <button type="submit" class="btn">Guardar Insumo</button>
</form>

<script>
function mostrarFormulario(tipo) {
    document.getElementById("formLibro").style.display = "none";
    document.getElementById("formTecno").style.display = "none";

    if (tipo === "libro") {
        document.getElementById("formLibro").style.display = "block";
    } else {
        document.getElementById("formTecno").style.display = "block";
    }
}
</script>

<style>
.selector-tipo button {
    padding: 10px;
    margin-right: 10px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
form {
    background: white;
    padding: 15px;
    margin-top: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 6px #0003;
}
label { font-weight: bold; margin-top: 8px; display: block; }
input { width: 100%; padding: 6px; margin-bottom: 10px; }
.btn {
    padding: 8px 12px;
    background: green;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
</style>