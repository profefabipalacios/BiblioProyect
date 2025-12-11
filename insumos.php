<?php
session_start();
require_once "includes/conexion.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
<div class="insumos-container">
    <h2>Gestión de Insumos</h2>

    <!-- Botones tipo de insumo -->
    <div class="tipo-insumo-buttons">
        <button id="btnLibros" class="tipo-btn"><i class="fas fa-book"></i> Libros</button>
        <button id="btnTecno" class="tipo-btn"><i class="fas fa-desktop"></i> Insumos Tecnológicos</button>
        <a href="dashboard.php?page=alta_insumo" class="tipo-btn"><i class="fas fa-plus"></i> Nuevo Insumo</a>
    </div>

    <!-- Buscador -->
    <div class="busqueda">
        <input type="text" id="buscar" placeholder="Buscar por ID, título, autor o ISBN">
        <button id="btnBuscar"><i class="fas fa-search"></i></button>
    </div>

    <!-- Tabla -->
    <table id="tablaInsumos">
        <thead id="theadInsumos"></thead>
        <tbody id="tbodyInsumos">
            <tr><td style="text-align:center;">Seleccione un tipo de insumo</td></tr>
        </tbody>
    </table>
</div>

<script>
let tipoActual = "";

function cargarTabla(tipo, busqueda = "") {

    tipoActual = tipo;

    fetch("fetch_insumos.php?tipo=" + encodeURIComponent(tipo) + "&buscar=" + encodeURIComponent(busqueda))
        .then(resp => resp.json())
        .then(data => {
            const thead = document.getElementById("theadInsumos");
            const tbody = document.getElementById("tbodyInsumos");

            tbody.innerHTML = "";

            // ============================
            // TABLA LIBROS
            // ============================
            if (tipo === "Libro") {
                thead.innerHTML = `
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Edición</th>
                        <th>Editorial</th>
                        <th>Año</th>
                        <th>Total</th>
                        <th>Disponible</th>
                        <th>Acción</th>
                    </tr>
                `;

                if (data.length === 0) {
                    tbody.innerHTML = "<tr><td colspan='10' style='text-align:center;'>No hay libros.</td></tr>";
                    return;
                }

                data.forEach(item => {
                    const disabled = item.stock_disponible > 0 ? "" : "disabled";

                    tbody.innerHTML += `
                        <tr>
                            <td>${item.id_item}</td>
                            <td>${item.nombre_titulo}</td>
                            <td>${item.autor_marca}</td>
                            <td>${item.ISBN}</td>
                            <td>${item.edicion}</td>
                            <td>${item.editorial}</td>
                            <td>${item.anio_pub}</td>
                            <td>${item.stock_total}</td>
                            <td>${item.stock_disponible}</td>
                            <td>
                                <button class="btn-prestar" onclick="prestar(${item.id_item})" ${disabled}>Prestar</button>
                            </td>
                        </tr>
                    `;
                });
            }

            // ============================
            // TABLA INSUMOS TECNOLÓGICOS
            // ============================
            if (tipo === "Insumo Tecnologico") {

                thead.innerHTML = `
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Total</th>
                        <th>Disponible</th>
                        <th>Acción</th>
                    </tr>
                `;

                if (data.length === 0) {
                    tbody.innerHTML = "<tr><td colspan='6' style='text-align:center;'>No hay insumos tecnológicos.</td></tr>";
                    return;
                }

                data.forEach(item => {
                    const disabled = item.stock_disponible > 0 ? "" : "disabled";

                    tbody.innerHTML += `
                        <tr>
                            <td>${item.id_item}</td>
                            <td>${item.nombre_titulo}</td>
                            <td>${item.autor_marca}</td>
                            <td>${item.stock_total}</td>
                            <td>${item.stock_disponible}</td>
                            <td>
                                <button class="btn-prestar" onclick="prestar(${item.id_item})" ${disabled}>Prestar</button>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(err => alert("Error cargando datos"));
}

// Redirige al préstamo
function prestar(id) {
    window.location.href = "dashboard.php?page=prestamos&id_item=" + id;
}

// Eventos
document.getElementById("btnLibros").onclick = () => cargarTabla("Libro");
document.getElementById("btnTecno").onclick = () => cargarTabla("Insumo Tecnologico");

document.getElementById("btnBuscar").onclick = () => {
    if (!tipoActual) return alert("Seleccione un tipo.");
    cargarTabla(tipoActual, document.getElementById("buscar").value);
};
</script>

<style>
#tablaInsumos {
    width: 100%;
    margin-top: 15px;
    border-collapse: collapse;
    background: white;
}
#tablaInsumos th, #tablaInsumos td {
    border: 1px solid #ddd;
    padding: 8px;
}
.btn-prestar {
    background: green;
    color: white;
    padding: 5px 12px;
    border: none;
    border-radius: 6px;
}
</style>