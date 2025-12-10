<?php
session_start();
require_once "includes/conexion.php";
?>

<div class="insumos-container">
    <h2>Gestión de Insumos</h2>

    <!-- Botones tipo de insumo + nuevo insumo -->
    <div class="tipo-insumo-buttons">
        <button id="btnLibros" class="tipo-btn"><i class="fas fa-book"></i> Libros</button>
        <button id="btnTecnologico" class="tipo-btn"><i class="fas fa-desktop"></i> Insumos Tecnológicos</button>
        <a href="dashboard.php?page=alta_insumo" class="tipo-btn" id="btnNuevoInsumo">
            <i class="fas fa-plus"></i> Nuevo Insumo
        </a>
    </div>

    <!-- Buscador -->
    <div class="busqueda">
        <input type="text" id="buscar" placeholder="Buscar por ID, nombre, autor/marca">
        <button id="btnBuscar"><i class="fas fa-search"></i></button>
    </div>

    <!-- Tabla dinámica -->
    <div class="tabla-insumos">
        <table id="tablaInsumos">
            <thead id="theadInsumos"></thead>
            <tbody id="tbodyInsumos">
                <tr><td style='text-align:center;'>Seleccione un tipo para ver los insumos</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
let tipoActual = '';

function cargarTabla(tipo, busqueda = '') {
    tipoActual = tipo;

    fetch("fetch_insumos.php?tipo=" + encodeURIComponent(tipo) + "&buscar=" + encodeURIComponent(busqueda))
        .then(resp => resp.json())
        .then(data => {
            const thead = document.getElementById("theadInsumos");
            const tbody = document.getElementById("tbodyInsumos");

            tbody.innerHTML = "";

            // ================================
            // TABLA PARA LIBROS
            // ================================
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
                        <th>Stock Total</th>
                        <th>Disponible</th>
                        <th>Acción</th>
                    </tr>
                `;

                if (data.length === 0) {
                    tbody.innerHTML = "<tr><td colspan='10' style='text-align:center;'>No se encontraron libros.</td></tr>";
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
                                <button class="btn-prestar" onclick="prestar(${item.id_item})" ${disabled}>
                                    Prestar
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            // ================================
            // TABLA PARA INSUMOS TECNOLÓGICOS
            // ================================
            if (tipo === "Insumo Tecnologico") {
                thead.innerHTML = `
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Stock Total</th>
                        <th>Disponible</th>
                        <th>Acción</th>
                    </tr>
                `;

                if (data.length === 0) {
                    tbody.innerHTML = "<tr><td colspan='6' style='text-align:center;'>No se encontraron insumos tecnológicos.</td></tr>";
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
                                <button class="btn-prestar" onclick="prestar(${item.id_item})" ${disabled}>
                                    Prestar
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(() => alert("Error al cargar los datos."));
}

// Redirige al préstamo con el ID seleccionado
function prestar(id) {
    window.location.href = "dashboard.php?page=prestamos&id_item=" + id;
}

// Eventos de botones
document.getElementById("btnLibros").addEventListener("click", () => cargarTabla("Libro"));
document.getElementById("btnTecnologico").addEventListener("click", () => cargarTabla("Insumo Tecnologico"));

document.getElementById("btnBuscar").addEventListener("click", () => {
    if (!tipoActual) return alert("Seleccione un tipo de insumo.");
    cargarTabla(tipoActual, document.getElementById("buscar").value);
});

document.getElementById("buscar").addEventListener("keyup", e => {
    if (e.key === "Enter" && tipoActual) {
        cargarTabla(tipoActual, e.target.value);
    }
});
</script>

<style>
.btn-prestar {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 6px;
    cursor: pointer;
}
.btn-prestar:disabled {
    background-color: #aaa;
    cursor: not-allowed;
}
.btn-prestar:hover:not(:disabled) {
    opacity: 0.85;
}
</style>