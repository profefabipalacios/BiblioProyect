<?php
require_once "includes/conexion.php";
?>

<div class="prestamos-container">
    <h2>Historial de Devoluciones</h2>

    <div class="busqueda">
        <input type="text" id="buscarHistorial" placeholder="Buscar por DNI o ítem">
        <button id="btnBuscarHistorial"><i class="fas fa-search"></i></button>
    </div>

    <div class="tabla-prestamos">
        <table id="tablaHistorial">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ítem</th>
                    <th>DNI Socio</th>
                    <th>Bibliotecaria</th>
                    <th>Fecha Préstamo</th>
                    <th>Fecha Devolución</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="7" style="text-align:center;">Cargando historial...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function cargarHistorial(buscar = "") {
    fetch("fetch_historial_devoluciones.php?buscar=" + encodeURIComponent(buscar))
    .then(resp => resp.json())
    .then(data => {
        const tbody = document.querySelector("#tablaHistorial tbody");
        tbody.innerHTML = "";

        if (data.length === 0) {
            tbody.innerHTML = "<tr><td colspan='7' style='text-align:center;'>No hay registros</td></tr>";
            return;
        }

        data.forEach(p => {
            const fila = `
                <tr>
                    <td>${p.id_prestamo}</td>
                    <td>${p.nombre_item}</td>
                    <td>${p.dni_socio}</td>
                    <td>${p.nombre_bibliotecaria}</td>
                    <td>${p.fecha_prestamo}</td>
                    <td>${p.fecha_devolucion}</td>
                    <td style="color:green; font-weight:bold;">Devuelto</td>
                </tr>
            `;
            tbody.innerHTML += fila;
        });
    });
}

document.getElementById("btnBuscarHistorial").addEventListener("click", () => {
    cargarHistorial(document.getElementById("buscarHistorial").value);
});

document.getElementById("buscarHistorial").addEventListener("keyup", e => {
    if (e.key === "Enter") {
        cargarHistorial(e.target.value);
    }
});

window.onload = () => cargarHistorial();
</script>