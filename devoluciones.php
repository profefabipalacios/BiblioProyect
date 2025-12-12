<?php
require_once "includes/conexion.php";
?>

<div class="prestamos-container">
    <h2>Gestión de Devoluciones</h2>

    <!-- Botones -->
    <div style="margin-bottom:15px; display:flex; gap:10px;">
        <button onclick="cargarPrestamos()" style="padding:8px 12px; background:#1976D2; color:white; border:none; border-radius:5px;">
            Préstamos Activos
        </button>

        <button onclick="window.location.href='dashboard.php?page=devoluciones_historial'"
            style="padding:8px 12px; background:#555; color:white; border:none; border-radius:5px;">
            Historial de Devoluciones
        </button>
    </div>

    <!-- Buscador -->
    <div class="busqueda">
        <input type="text" id="buscar" placeholder="Buscar por DNI o nombre del ítem">
        <button id="btnBuscar"><i class="fas fa-search"></i></button>
    </div>

    <!-- Tabla -->
    <div class="tabla-prestamos">
        <table id="tablaPrestamos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ítem</th>
                    <th>DNI Socio</th>
                    <th>Bibliotecaria</th>
                    <th>Fecha Préstamo</th>
                    <th>Hora Préstamo</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="8" style="text-align:center;">Cargando préstamos activos...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
// --------------------------------------
// CARGAR PRÉSTAMOS ACTIVOS
// --------------------------------------
function cargarPrestamos(busqueda = "") {
    fetch("fetch_devoluciones.php?estado=activo&buscar=" + encodeURIComponent(busqueda))
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector("#tablaPrestamos tbody");
            tbody.innerHTML = "";

            if (data.length === 0) {
                tbody.innerHTML = "<tr><td colspan='8' style='text-align:center;'>No hay préstamos activos</td></tr>";
                return;
            }

            data.forEach(p => {
                const retrasado = (p.estado === "Retrasado");

                tbody.innerHTML += `
                    <tr>
                        <td>${p.id_prestamo}</td>
                        <td>${p.nombre_item}</td>
                        <td>${p.dni_socio}</td>
                        <td>${p.nombre_bibliotecaria}</td>
                        <td>${p.fecha_prestamo}</td>
                        <td>${p.hora_prestamo}</td>
                        <td style="color:${retrasado?'red':'orange'}; font-weight:bold;">
                            ${retrasado ? "⏳ Retrasado" : "🔴 Pendiente"}
                        </td>
                        <td>
                            <button onclick="registrarDevolucion(${p.id_prestamo})"
                                class="btn-devolucion">Registrar Devolución</button>
                        </td>
                    </tr>
                `;
            });
        });
}

document.getElementById("btnBuscar").onclick = () =>
    cargarPrestamos(document.getElementById("buscar").value);

document.getElementById("buscar").addEventListener("keyup", e => {
    if (e.key === "Enter") cargarPrestamos(e.target.value);
});

// Inicial
window.onload = () => cargarPrestamos();

// --------------------------------------
// REGISTRAR DEVOLUCIÓN
// --------------------------------------
function registrarDevolucion(id) {
    if (!confirm("¿Confirmar la devolución?")) return;

    fetch("registrar_devolucion.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "id_prestamo=" + id
    })
    .then(res => res.json())
    .then(data => {
        alert(data.mensaje);
        cargarPrestamos(document.getElementById("buscar").value);
    });
}
</script>