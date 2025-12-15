<?php
require_once "includes/conexion.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$carreras = [];
$result = $conn->query("SELECT id_carrera, nombre FROM carrera ORDER BY nombre ASC");
while ($row = $result->fetch_assoc()) {
    $carreras[] = $row;
}

$accion = $_GET['accion'] ?? '';
?>

<div class="socios-container">

    <h2 class="titulo-socios">Gestión de Socios</h2>

    <?php if ($accion === ''): ?>

        <div class="socios-botones">
            <a href="dashboard.php?page=socios&accion=alta" class="socio-btn socio-btn-alta">
                <div class="socio-btn-icon"><i class="fas fa-user-plus"></i></div>
                <span class="socio-btn-title">Agregar Socio</span>
                <span class="socio-btn-desc">Registrar un nuevo socio</span>
            </a>

            <a href="dashboard.php?page=socios&accion=listar" class="socio-btn socio-btn-listar">
                <div class="socio-btn-icon"><i class="fas fa-users"></i></div>
                <span class="socio-btn-title">Ver Socios</span>
                <span class="socio-btn-desc">Consultar socios</span>
            </a>
        </div>

    <?php elseif ($accion === 'alta'): ?>

        <a href="dashboard.php?page=socios" class="volver-link">← Volver</a>

        <form id="formSocios" method="POST" action="procesar_socio.php" class="formulario-socio">

            <div class="campo-form">
                <label>DNI</label>
                <div class="grupo-dni">
                    <input type="text" id="dni" name="dni" required>
                    <button type="button" id="btnVerificar" class="btn-verificar">✔</button>
                </div>
                <div id="mensaje-verificacion" class="mensaje"></div>
            </div>

            <div class="campo-form">
                <label>Nombre</label>
                <input type="text" id="nombre" name="nombre" disabled required>
            </div>

            <div class="campo-form">
                <label>Apellido</label>
                <input type="text" id="apellido" name="apellido" disabled required>
            </div>

            <div class="campo-form">
                <label>Tipo de socio</label>
                <select id="tipo" name="tipo" disabled required>
                    <option value="">-- Seleccione --</option>
                    <option value="Alumno">Alumno</option>
                    <option value="Docente">Docente</option>
                </select>
            </div>

            <div class="campo-form">
                <label>Carrera</label>
                <select id="id_carrera" name="id_carrera" disabled>
                    <option value="">-- Sin carrera --</option>
                    <?php foreach ($carreras as $c): ?>
                        <option value="<?= $c['id_carrera'] ?>">
                            <?= htmlspecialchars($c['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" id="btnGuardar" class="btn-guardar" disabled>
                Guardar Socio
            </button>
        </form>

    <?php elseif ($accion === 'listar'): ?>

        <a href="dashboard.php?page=socios" class="volver-link">← Volver</a>

        <div class="buscador-socios">
            <input type="text" id="busquedaSocio" placeholder="Buscar por DNI, nombre o apellido">
            <button type="button" id="btnBuscarSocio">Buscar</button>
        </div>

        <div id="tablaSocios"></div>

    <?php endif; ?>

</div>

<script>
const accionActual = "<?= $accion ?>";

document.addEventListener("DOMContentLoaded", () => {
    if (accionActual === "listar") {
        cargarSocios();

        document.getElementById("btnBuscarSocio").onclick = () =>
            cargarSocios(document.getElementById("busquedaSocio").value);

        document.getElementById("busquedaSocio").addEventListener("keyup", e => {
            if (e.key === "Enter") cargarSocios(e.target.value);
        });
    }

    const btnVerificar = document.getElementById("btnVerificar");
    if (btnVerificar) {
        btnVerificar.onclick = () => {
            const dni = document.getElementById("dni").value.trim();
            const msg = document.getElementById("mensaje-verificacion");

            if (!dni) {
                msg.innerHTML = "Ingrese un DNI válido";
                return;
            }

            fetch("verificar_socio.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "dni=" + encodeURIComponent(dni)
            })
            .then(r => r.json())
            .then(d => {
                if (d.existe) {
                    msg.innerHTML = "El socio ya existe";
                    deshabilitarCampos();
                } else {
                    msg.innerHTML = "Socio no registrado. Complete los datos";
                    habilitarCampos();
                }
            });
        };
    }
});

function habilitarCampos() {
    ["nombre","apellido","tipo","id_carrera","btnGuardar"]
        .forEach(id => document.getElementById(id).disabled = false);
}

function deshabilitarCampos() {
    ["nombre","apellido","tipo","id_carrera","btnGuardar"]
        .forEach(id => document.getElementById(id).disabled = true);
}

function cargarSocios(filtro = "") {
    fetch("fetch_socio.php" + (filtro ? "?q=" + encodeURIComponent(filtro) : ""))
        .then(r => r.text())
        .then(html => document.getElementById("tablaSocios").innerHTML = html);
}
</script>

<style>
.formulario-socio {
    max-width: 520px;
    margin: 20px auto;
    padding: 25px 30px;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.campo-form {
    margin-bottom: 18px;
}

.campo-form label {
    display: block;
    margin-bottom: 6px;
    color: #2c3e50;
}

.campo-form input,
.campo-form select {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 15px;
}

.campo-form input:focus,
.campo-form select:focus {
    border-color: #007bff;
    outline: none;
}

.grupo-dni {
    display: flex;
    gap: 10px;
}

.btn-verificar {
    width: 46px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.btn-verificar:hover {
    background-color: #0056b3;
}

.mensaje {
    margin-top: 6px;
    font-size: 14px;
}

.btn-guardar {
    margin-top: 10px;
    padding: 12px;
    background-color: #2ecc71;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
}

.btn-guardar:hover {
    background-color: #27ae60;
}
</style>