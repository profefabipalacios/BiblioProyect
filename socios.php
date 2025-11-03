<?php
require_once "includes/conexion.php";
session_start();
?>

<h2>Gestión de Socios</h2>

<form id="formSocios" method="POST" action="procesar_socio.php" class="formulario-socio">
    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni" required>
    <button type="button" id="btnVerificar" class="btn-verificar">Verificar</button>
    <div id="mensaje-verificacion" class="mensaje"></div>

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" disabled required>

    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="apellido" disabled required>

    <label for="tipo">Tipo de socio:</label>
    <select name="tipo" id="tipo" disabled required>
        <option value="">-- Seleccione --</option>
        <option value="Alumno">Alumno</option>
        <option value="Docente">Docente</option>
    </select>

    <label for="carrera">Carrera (opcional):</label>
    <select name="carrera" id="carrera" disabled>
        <option value="">-- Sin carrera --</option>
        <option value="PROF. DE INGLÉS">PROF. DE INGLÉS</option>
        <option value="PROF. DE EDUCACIÓN SECUNDARIA EN MATEMÁTICA">PROF. DE EDUCACIÓN SECUNDARIA EN MATEMÁTICA</option>
        <option value="PROF. DE EDUCACIÓN SECUNDARIA EN INFORMÁTICA">PROF. DE EDUCACIÓN SECUNDARIA EN INFORMÁTICA</option>
        <option value="PROF. DE EDUCACIÓN SECUNDARIA EN CIENCIAS DE LA ADMINISTRACIÓN">PROF. DE EDUCACIÓN SECUNDARIA EN CIENCIAS DE LA ADMINISTRACIÓN</option>
        <option value="TEC. EN CIENCIA DE DATOS E INTELIGENCIA ARTIFICIAL">TEC. EN CIENCIA DE DATOS E INTELIGENCIA ARTIFICIAL</option>
        <option value="TEC. SUPERIOR EN TECNOLOGÍAS DE LOS ALIMENTOS">TEC. SUPERIOR EN TECNOLOGÍAS DE LOS ALIMENTOS</option>
    </select>

    <button type="submit" id="btnGuardar" class="btn-guardar" disabled>Guardar</button>
</form>

<hr>

<h3>Listado de Socios</h3>
<div id="tablaSocios"></div>

<style>
.formulario-socio {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    max-width: 700px;
    margin-bottom: 25px;
}
.formulario-socio label {
    font-weight: bold;
    margin-top: 6px;
}
.formulario-socio input, .formulario-socio select {
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 6px;
}
.btn-verificar, .btn-guardar {
    grid-column: span 2;
    padding: 8px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}
.btn-verificar {
    background-color: #4a90e2;
    color: white;
}
.btn-guardar {
    background-color: #5cb85c;
    color: white;
}
.mensaje {
    grid-column: span 2;
    font-size: 14px;
}
.tabla-socios {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
.tabla-socios th, .tabla-socios td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}
.tabla-socios th {
    background-color: #f2f2f2;
}
.btn-editar {
    background-color: #ffc107;
    color: #333;
    padding: 5px 10px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
.btn-eliminar {
    background-color: #d9534f;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
.btn-editar:hover, .btn-eliminar:hover {
    opacity: 0.8;
}
</style>

<script>
// Al cargar la página, mostrar todos los socios
window.onload = function() {
    cargarSocios();
};

document.getElementById('btnVerificar').addEventListener('click', function () {
    let dni = document.getElementById('dni').value;
    let mensajeDiv = document.getElementById('mensaje-verificacion');

    if (dni.trim() === "") {
        mensajeDiv.innerHTML = "<p style='color:red;'>Ingrese un DNI válido.</p>";
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "verificar_socio.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let respuesta = JSON.parse(xhr.responseText);

            if (respuesta.existe) {
                mensajeDiv.innerHTML = "<p style='color:red;'>El socio ya existe ✅.</p>";
                deshabilitarCampos();
                cargarSocioPorDNI(dni);
            } else {
                mensajeDiv.innerHTML = "<p style='color:green;'>El socio no existe ❌. Complete los datos para registrarlo.</p>";
                habilitarCampos();
                cargarSocios();
            }
        }
    };
    xhr.send("dni=" + encodeURIComponent(dni));
});

function habilitarCampos() {
    document.getElementById('nombre').disabled = false;
    document.getElementById('apellido').disabled = false;
    document.getElementById('tipo').disabled = false;
    document.getElementById('carrera').disabled = false;
    document.getElementById('btnGuardar').disabled = false;
}
function deshabilitarCampos() {
    document.getElementById('nombre').disabled = true;
    document.getElementById('apellido').disabled = true;
    document.getElementById('tipo').disabled = true;
    document.getElementById('carrera').disabled = true;
    document.getElementById('btnGuardar').disabled = true;
}

function cargarSocios() {
    fetch('fetch_socio.php')
        .then(response => response.text())
        .then(data => document.getElementById('tablaSocios').innerHTML = data);
}

function cargarSocioPorDNI(dni) {
    fetch('fetch_socio.php?dni=' + encodeURIComponent(dni))
        .then(response => response.text())
        .then(data => document.getElementById('tablaSocios').innerHTML = data);
}

function eliminarSocio(id) {
    if (confirm("¿Está seguro de eliminar este socio?")) {
        fetch('eliminar_socio.php?id=' + id)
            .then(response => response.text())
            .then(() => cargarSocios());
    }
}
</script>