<?php
require_once "includes/conexion.php";
?>

<div class="prestamos-container">
    <h2>Gestión de Devoluciones</h2>

    <!-- Buscador -->
    <div class="busqueda">
        <input type="text" id="buscar" placeholder="Buscar por DNI o nombre del item">
        <button id="btnBuscar"><i class="fas fa-search"></i></button>
    </div>

    <!-- Tabla de préstamos -->
    <div class="tabla-prestamos">
        <table id="tablaPrestamos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item</th>
                    <th>DNI Socio</th>
                    <th>Bibliotecaria</th>
                    <th>Fecha Préstamo</th>
                    <th>Hora Préstamo</th>
                    <th>Fecha Devolución</th>
                    <th>Hora Devolución</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="10" style="text-align:center;">Realice una búsqueda para ver los préstamos</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
// Función para cargar la tabla de préstamos
function cargarPrestamos(busqueda = '') {
    fetch('fetch_devoluciones.php?buscar=' + encodeURIComponent(busqueda))
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#tablaPrestamos tbody");
            tbody.innerHTML = "";

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="10" style="text-align:center;">No se encontraron préstamos</td></tr>';
                return;
            }

            data.forEach(item => {
                let iconoEstado = '';
                let accion = '';

                if (item.estado === "Prestado" || item.estado === "Retrasado") {
                    iconoEstado = `<span style="color:${item.estado === "Retrasado" ? 'red' : 'orange'};">
                        ${item.estado === "Retrasado" ? '⏳ Retrasado' : '🔴 Pendiente'}
                    </span>`;
                    accion = `<button onclick="registrarDevolucion(${item.id_prestamo})" class="btn-devolucion">
                                Registrar Devolución
                              </button>`;
                } else if (item.estado === "Devuelto") {
                    iconoEstado = '<span style="color:green;">✅ Devuelto</span>';
                    accion = '-';
                }

                const fila = `
                    <tr>
                        <td>${item.id_prestamo}</td>
                        <td>${item.nombre_item}</td>
                        <td>${item.dni_socio}</td>
                        <td>${item.nombre_bibliotecaria}</td>
                        <td>${item.fecha_prestamo}</td>
                        <td>${item.hora_prestamo}</td>
                        <td>${item.fecha_devolucion ?? '-'}</td>
                        <td>${item.hora_devolucion ?? '-'}</td>
                        <td>${iconoEstado}</td>
                        <td>${accion}</td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });
        });
}

// Función para registrar devolución
function registrarDevolucion(idPrestamo) {
    if (!confirm("¿Confirmar devolución del ítem?")) return;

    fetch('registrar_devolucion.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_prestamo=' + idPrestamo
    })
    .then(response => response.json())
    .then(data => {
        alert(data.mensaje);
        cargarPrestamos(document.getElementById('buscar').value);
    });
}

// Buscar con el botón
document.getElementById('btnBuscar').addEventListener('click', () => {
    const query = document.getElementById('buscar').value;
    cargarPrestamos(query);
});

// Buscar al presionar Enter
document.getElementById('buscar').addEventListener('keyup', function(e) {
    if (e.key === "Enter") {
        const query = this.value;
        cargarPrestamos(query);
    }
});

// Cargar todos los préstamos al abrir la página
window.onload = () => cargarPrestamos();
</script>

<style>
.btn-devolucion {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 5px 8px;
    cursor: pointer;
    border-radius: 5px;
}
.btn-devolucion:hover {
    background-color: #45a049;
}
</style>