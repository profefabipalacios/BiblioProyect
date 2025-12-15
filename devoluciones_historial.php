<?php
require_once "includes/conexion.php";
?>

<div class="prestamos-container">
    <h2>Historial de Devoluciones</h2>
    <div class="resumen-historial">
        <div class="resumen-item">
            Total de devoluciones: <span id="totalDevoluciones">0</span>
        </div>

        <div class="exportar">
            <a href="exportar_historial_excel.php" class="btn-exportar excel">Excel</a>
            <a href="exportar_historial_pdf.php" class="btn-exportar pdf">PDF</a>
        </div>
    </div>

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
        const totalSpan = document.getElementById("totalDevoluciones");

        tbody.innerHTML = "";
        totalSpan.textContent = data.total;

        if (data.registros.length === 0) {
            tbody.innerHTML = "<tr><td colspan='7' style='text-align:center;'>No hay registros</td></tr>";
            return;
        }

        data.registros.forEach(p => {
            const fila = `
                <tr>
                    <td>${p.id_prestamo}</td>
                    <td>${p.nombre_item}</td>
                    <td>${p.dni_socio}</td>
                    <td>${p.nombre_bibliotecaria}</td>
                    <td>${p.fecha_prestamo}</td>
                    <td>${p.fecha_devolucion}</td>
                    <td style="color:green;font-weight:bold;">Devuelto</td>
                </tr>
            `;
            tbody.insertAdjacentHTML("beforeend", fila);
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

<style>
.resumen-historial {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding: 10px;
    background: #f5f7fa;
    border-radius: 8px;
}

.resumen-item {
    font-size: 16px;
    font-weight: bold;
}

.exportar {
    display: flex;
    gap: 10px;
}

.btn-exportar {
    padding: 6px 12px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    font-weight: bold;
}

.btn-exportar.excel {
    background-color: #1d6f42;
}

.btn-exportar.pdf {
    background-color: #c62828;
}

.btn-exportar:hover {
    opacity: 0.85;
}
</style>