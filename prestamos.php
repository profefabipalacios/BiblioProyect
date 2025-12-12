<?php
require_once "includes/conexion.php";
session_start();
?>
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

            <div style="display:flex; align-items:center; gap:8px;">
                <input type="text" id="dni" name="dni" required style="width:150px;">

                <button type="button" id="btnVerificarDNI"
                    style="padding:5px 8px; background:#007bff; color:white; border:none; border-radius:4px; cursor:pointer;">
                    Verificar
                </button>

                <span id="estadoDNI" style="font-size:20px;"></span>
            </div>

            <small id="mensajeDNI"></small>

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
// ===============================================================
//                   BÚSQUEDA DE INSUMOS
// ===============================================================
document.getElementById('btnBuscarInsumo').addEventListener('click', buscarInsumo);
document.getElementById('buscarInsumo').addEventListener('keyup', e => { if (e.key === 'Enter') buscarInsumo(); });

function buscarInsumo() {
    const termino = document.getElementById('buscarInsumo').value.trim();
    const cuerpo = document.getElementById('resultadoBusqueda');

    if (termino === "") {
        cuerpo.innerHTML = "<tr><td colspan='6' style='text-align:center;'>Ingrese un término de búsqueda</td></tr>";
        return;
    }

    fetch("buscar_inventario.php?busqueda=" + encodeURIComponent(termino))
        .then(resp => resp.json())
        .then(data => {
            cuerpo.innerHTML = "";

            if (data.length === 0) {
                cuerpo.innerHTML = "<tr><td colspan='6' style='text-align:center;'>No se encontraron resultados</td></tr>";
                return;
            }

            data.forEach(item => {
                cuerpo.innerHTML += `
                    <tr>
                        <td>${item.id_item}</td>
                        <td>${item.nombre_titulo}</td>
                        <td>${item.tipo_item}</td>
                        <td>${item.autor_marca}</td>
                        <td>${item.stock_disponible}</td>
                        <td>
                            <button type="button"
                                onclick='agregarInsumo(${item.id_item}, "${item.nombre_titulo}", ${item.stock_disponible})'
                                ${item.stock_disponible > 0 ? "" : "disabled"}>
                                Agregar
                            </button>
                        </td>
                    </tr>
                `;
            });
        });
}

// ===============================================================
//     AUTOAGREGAR INSUMO SI VIENE DESDE insumos.php
// ===============================================================
window.onload = function () {
    const params = new URLSearchParams(window.location.search);
    const id_item = params.get('id_item');

    if (id_item) {
        fetch("buscar_inventario.php?id_item=" + id_item)
            .then(resp => resp.json())
            .then(item => {
                if (item && item.id_item) {
                    agregarInsumo(item.id_item, item.nombre_titulo, item.stock_disponible);
                }
            });
    }
};

// ===============================================================
//               AGREGAR INSUMO A TABLA DE PRÉSTAMO
// ===============================================================
function agregarInsumo(id, nombre, stock) {
    const tbody = document.querySelector("#tablaSeleccionados tbody");

    if (tbody.querySelector(`tr[data-id='${id}']`)) {
        alert("Este insumo ya está agregado.");
        return;
    }

    tbody.innerHTML += `
        <tr data-id="${id}">
            <td><input type="hidden" name="id_item[]" value="${id}">${id}</td>
            <td>${nombre}</td>
            <td><input type="number" name="cantidad[]" value="1" min="1" max="${stock}" required></td>
            <td><button type="button" onclick="quitarInsumo(this)">🗑️</button></td>
        </tr>
    `;

    document.getElementById("buscarInsumo").value = "";
}

function quitarInsumo(btn) {
    btn.closest("tr").remove();
}

// ===============================================================
//               VERIFICACIÓN DE SOCIO POR DNI
// ===============================================================
let socioValido = false;

document.getElementById("btnVerificarDNI").addEventListener("click", verificarSocio);

function verificarSocio() {
    const dni = document.getElementById("dni").value.trim();
    const estado = document.getElementById("estadoDNI");
    const mensaje = document.getElementById("mensajeDNI");

    estado.textContent = "";
    mensaje.textContent = "";
    socioValido = false;

    if (dni === "") {
        mensaje.textContent = "Ingrese un DNI válido.";
        mensaje.style.color = "red";
        return;
    }

    fetch("verificar_socio.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "dni=" + encodeURIComponent(dni)
    })
    .then(res => res.json())
    .then(data => {
        if (data.existe) {
            socioValido = true;
            estado.textContent = "✔";
            estado.style.color = "green";
            mensaje.textContent = "El socio está registrado.";
            mensaje.style.color = "green";
        } else {
            socioValido = false;
            estado.textContent = "✘";
            estado.style.color = "red";
            mensaje.textContent = "El DNI no pertenece a un socio registrado.";
            mensaje.style.color = "red";

            setTimeout(() => {
                if (confirm("Este DNI no pertenece a un socio. ¿Desea registrarlo ahora?")) {
                    window.location.href = "dashboard.php?page=socios&dni=" + dni;
                }
            }, 300);
        }
    });
}

// ===============================================================
//    IMPEDIR REGISTRAR PRÉSTAMO SI EL DNI NO ES VÁLIDO
// ===============================================================
document.getElementById("formPrestamo").addEventListener("submit", function(e) {
    if (!socioValido) {
        e.preventDefault();
        alert("❌ El DNI no corresponde a un socio registrado.");
    }
});
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
.btn-guardar:hover { background-color:#0056b3; }
</style>