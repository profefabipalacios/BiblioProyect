<?php
session_start();
require_once "includes/conexion.php";
?>

<div class="insumos-container">
    <h2>Gestión de Insumos</h2>

    <!-- Botones tipo de insumo + nuevo insumo -->
    <div class="tipo-insumo-buttons">
        <button id="btnLibros" class="tipo-btn"><i class="fas fa-book"></i> Libros</button>
        <button id="btnTecnologico" class="tipo-btn"><i class="fas fa-desktop"></i> Insumo Tecnológico</button>
        <a href="dashboard.php?page=alta_insumo" class="tipo-btn" id="btnNuevoInsumo">
            <i class="fas fa-plus"></i> Nuevo Insumo
        </a>
    </div>

    <!-- Buscador con botón lupa -->
    <div class="busqueda">
        <input type="text" id="buscar" placeholder="Buscar por código, nombre, tipo o autor/marca">
        <button id="btnBuscar"><i class="fas fa-search"></i></button>
    </div>

    <!-- Tabla de resultados -->
    <div class="tabla-insumos">
        <table id="tablaInsumos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Autor/Marca</th>
                    <th>Stock Total</th>
                    <th>Stock Disponible</th>
                    <th>ID Bibliotecaria Registro</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="7" style="text-align:center;">Seleccione un tipo para ver los insumos</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
let tipoActual = '';

function cargarTabla(tipo, busqueda = '') {
    fetch('fetch_insumos.php?tipo=' + tipo + '&buscar=' + encodeURIComponent(busqueda))
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#tablaInsumos tbody");
            tbody.innerHTML = "";

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No se encontraron registros</td></tr>';
                return;
            }

            data.forEach(item => {
                const fila = `
                    <tr>
                        <td>${item.id_item}</td>
                        <td>${item.nombre}</td>
                        <td>${item.tipo_item}</td>
                        <td>${item.autor_marca}</td>
                        <td>${item.stock_total}</td>
                        <td>${item.stock_disponible}</td>
                        <td>${item.id_bibliotecaria_registro}</td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });
        });
}

document.getElementById('btnLibros').addEventListener('click', () => {
    tipoActual = 'Libro';
    cargarTabla(tipoActual);
});

document.getElementById('btnTecnologico').addEventListener('click', () => {
    tipoActual = 'Insumo Tecnologico';
    cargarTabla(tipoActual);
});

document.getElementById('btnBuscar').addEventListener('click', () => {
    if (!tipoActual) return;
    const query = document.getElementById('buscar').value;
    cargarTabla(tipoActual, query);
});

document.getElementById('buscar').addEventListener('keyup', function(e) {
    if (e.key === "Enter" && tipoActual) {
        const query = this.value;
        cargarTabla(tipoActual, query);
    }
});
</script>