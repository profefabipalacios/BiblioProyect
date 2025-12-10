<?php
require_once "includes/conexion.php";
session_start();
?>
<!--   -->
<div class="prestamos-container">
    <h2>Gestión de Préstamos</h2>

    <!-- Filtro de búsqueda -->
    <div class="busqueda">
        <input type="text" id="buscarInsumo" placeholder="Buscar por nombre, autor o marca">
        <button id="btnBuscarInsumo" style="width:80%;"><i class="fas fa-search"></i></button>
    </div>

    <!-- Tabla de resultados de búsqueda -->
    <div class="tabla-busqueda" style="margin-top:15px;">
        <table id="tablaResultado" style="width:100%; border-collapse:collapse; border:1px solid #ccc;">
            <thead style="background-color:#f2f2f2;">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Autor/Marca</th>
                    <th>Stock Disponible</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="resultadoBusqueda">
                <tr><td colspan="6" style="text-align:center;">Ingrese un término de búsqueda</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Formulario de préstamo -->
    <div class="formulario-prestamo" style="margin-top:25px;">
        <h3>Registrar Préstamo</h3>
        <form id="formPrestamo" method="POST" action="procesar_prestamo.php">
            <label for="dni">DNI del Socio:</label>
            <input type="text" id="dni" name="dni" required>
            
            <table id="tablaSeleccionados" style="width:100%; border-collapse:collapse; border:1px solid #ccc; margin-top:15px;">
                <thead style="background-color:#f2f2f2;">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Quitar</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div class="botones-prestamo" style="margin-top:20px;">
                <button type="submit" class="btn-guardar">Registrar Préstamo</button>
            </div>
        </form>
    </div>
</div>

<script>
// --- Búsqueda en inventario ---
document.getElementById('btnBuscarInsumo').addEventListener('click', buscarInsumo);
document.getElementById('buscarInsumo').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') buscarInsumo();
});

function buscarInsumo() {
    const termino = document.getElementById('buscarInsumo').value.trim();

    if (termino === "") {
        document.getElementById('resultadoBusqueda').innerHTML = "<tr><td colspan='6' style='text-align:center;'>Ingrese un término de búsqueda</td></tr>";
        return;
    }

    fetch("buscar_inventario.php?busqueda=" + encodeURIComponent(termino))
    .then(resp => resp.json())
    .then(data => {
        const cuerpo = document.getElementById('resultadoBusqueda');
        cuerpo.innerHTML = "";

        if (data.length > 0) {
            data.forEach(item => {
                const fila = `
                    <tr>
                        <td>${item.id_item}</td>
                        <td>${item.nombre}</td>
                        <td>${item.tipo_item}</td>
                        <td>${item.autor_marca}</td>
                        <td>${item.stock_disponible}</td>
                        <td><button type="button" onclick='agregarInsumo(${item.id_item}, "${item.nombre}", ${item.stock_disponible})' ${item.stock_disponible > 0 ? "" : "disabled"}>Agregar</button></td>
                    </tr>
                `;
                cuerpo.insertAdjacentHTML('beforeend', fila);
            });
        } else {
            cuerpo.innerHTML = "<tr><td colspan='6' style='text-align:center;'>No se encontraron resultados</td></tr>";
        }
    });
}

// --- Agrega automáticamente el insumo si viene desde insumos.php ---
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    const id_item = urlParams.get('id_item');

    if (id_item) {
        fetch("buscar_inventario.php?id_item=" + encodeURIComponent(id_item))
            .then(resp => resp.json())
            .then(item => {
                if (item && item.id_item) {
                    agregarInsumo(item.id_item, item.nombre, item.stock_disponible);
                }
            });
    }
};
// --- Agregar insumo a tabla de selección ---
function agregarInsumo(id, nombre, stock) {
    const tabla = document.querySelector("#tablaSeleccionados tbody");

    const existente = tabla.querySelector(`tr[data-id='${id}']`);
    if (existente) {
        alert("Este insumo ya fue agregado.");
        return;
    }

    const fila = document.createElement("tr");
    fila.setAttribute("data-id", id);
    fila.innerHTML = `
        <td><input type="hidden" name="id_item[]" value="${id}">${id}</td>
        <td>${nombre}</td>
        <td><input type="number" name="cantidad[]" min="1" max="${stock}" value="1" required></td>
        <td><button type="button" onclick="quitarInsumo(this)">🗑️</button></td>
    `;
    tabla.appendChild(fila);

    // --- Colocar foco, seleccionar texto y limpiar el input ---
    const buscador = document.getElementById('buscarInsumo');
    buscador.focus();
    buscador.select();
    buscador.value = "";
}

function quitarInsumo(btn) {
    btn.closest("tr").remove();
}
</script>

<style>
.btn-guardar {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 8px 15px;
    cursor: pointer;
    border-radius: 5px;
}
.btn-guardar:hover {
    background-color: #0056b3;
}
</style>