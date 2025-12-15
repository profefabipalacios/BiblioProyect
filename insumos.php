<?php
session_start();
require_once "includes/conexion.php";
?>

<div class="insumos-container">

    <h2 class="titulo-socios">Gestión de Insumos</h2>

    <!-- Botones tipo de insumo (reutiliza estilo de socios.php) -->
    <div class="socios-botones">

        <a href="#" id="btnLibros" class="socio-btn socio-btn-listar">
            <div class="socio-btn-icon">
                <i class="fas fa-book"></i>
            </div>
            <span class="socio-btn-title">Libros</span>
            <span class="socio-btn-desc">Material bibliográfico disponible</span>
        </a>

        <a href="#" id="btnTecno" class="socio-btn socio-btn-listar">
            <div class="socio-btn-icon">
                <i class="fas fa-desktop"></i>
            </div>
            <span class="socio-btn-title">Insumos tecnológicos</span>
            <span class="socio-btn-desc">Equipamiento y recursos tecnológicos</span>
        </a>

        <a href="dashboard.php?page=alta_insumo" class="socio-btn socio-btn-alta">
            <div class="socio-btn-icon">
                <i class="fas fa-plus"></i>
            </div>
            <span class="socio-btn-title">Nuevo insumo</span>
            <span class="socio-btn-desc">Registrar un nuevo ítem</span>
        </a>

    </div>

    <!-- Buscador -->
    <div class="busqueda">
        <input type="text" id="buscar" class="input-busqueda" placeholder="Buscar por ID, título, autor o ISBN">
        <button id="btnBuscar" class="btn-buscar"><i class="fas fa-search"></i></button>
    </div>

    <!-- Tabla -->
    <div class="tabla-insumos">
        <table id="tablaInsumos">
            <thead id="theadInsumos"></thead>
            <tbody id="tbodyInsumos">
                <tr>
                    <td style="text-align:center;">Seleccione un tipo de insumo</td>
                </tr>
            </tbody>
        </table>
    </div>

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

            thead.innerHTML = "";
            tbody.innerHTML = "";

            // ================= LIBROS =================
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
                    tbody.innerHTML = "<tr><td colspan='10' style='text-align:center;'>No hay libros cargados</td></tr>";
                    return;
                }

                data.forEach(item => {
                    const disabled = item.stock_disponible > 0 ? "" : "disabled";

                    tbody.innerHTML += `
                        <tr>
                            <td>${item.id_item}</td>
                            <td>${item.nombre_titulo}</td>
                            <td>${item.autor_marca}</td>
                            <td>${item.ISBN ?? "-"}</td>
                            <td>${item.edicion ?? "-"}</td>
                            <td>${item.editorial ?? "-"}</td>
                            <td>${item.anio_pub ?? "-"}</td>
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

            // ============ INSUMOS TECNOLÓGICOS ============
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
                    tbody.innerHTML = "<tr><td colspan='6' style='text-align:center;'>No hay insumos tecnológicos</td></tr>";
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
        .catch(() => {
            document.getElementById("tbodyInsumos").innerHTML =
                "<tr><td style='text-align:center;'>Error al cargar datos</td></tr>";
        });
}

// Redirección a préstamos
function prestar(id) {
    window.location.href = "dashboard.php?page=prestamos&id_item=" + id;
}

// Eventos botones
document.getElementById("btnLibros").addEventListener("click", e => {
    e.preventDefault();
    cargarTabla("Libro");
});

document.getElementById("btnTecno").addEventListener("click", e => {
    e.preventDefault();
    cargarTabla("Insumo Tecnologico");
});

document.getElementById("btnBuscar").addEventListener("click", () => {
    if (!tipoActual) {
        alert("Seleccione un tipo de insumo");
        return;
    }
    cargarTabla(tipoActual, document.getElementById("buscar").value);
});
</script>