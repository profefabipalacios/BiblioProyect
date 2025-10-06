<?php
require_once "includes/conexion.php";
?>

<?php if (isset($_SESSION['socio_guardado']) && $_SESSION['socio_guardado'] === true): ?>
    <script>
        alert("✅ Socio cargado correctamente.");
    </script>
    <?php unset($_SESSION['socio_guardado']); ?>
<?php endif; ?>

<h2>Gestión de Socios</h2>

<form id="formSocios" method="POST" action="procesar_socio.php">
    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni" required>
    <button type="button" id="btnVerificar">Verificar</button>

    <div id="mensaje-verificacion" style="margin-top: 10px;"></div>

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" disabled required>

    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="apellido" disabled required>

    <label for="tipo">Seleccione tipo de socio:</label>
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

    <button type="submit" id="btnGuardar" disabled>Guardar</button>
</form>

<script>
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
            } else {
                mensajeDiv.innerHTML = "<p style='color:green;'>El socio no existe ❌. Complete los datos para registrarlo.</p>";
                habilitarCampos();
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
</script>