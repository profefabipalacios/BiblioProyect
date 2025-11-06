<?php
require_once "includes/conexion.php";
session_start();

// Validar que venga un DNI
if (!isset($_GET['dni']) || empty($_GET['dni'])) {
    echo "<script>alert('DNI no recibido.'); window.location='dashboard.php?page=socios';</script>";
    exit;
}

$dni = $_GET['dni'];

// Cargar socio
$stmt = $conn->prepare("
    SELECT dni, nombre, apellido, tipo_socio, id_carrera 
    FROM socio 
    WHERE dni = ?
");
$stmt->bind_param("s", $dni);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('El socio no existe.'); window.location='dashboard.php?page=socios';</script>";
    exit;
}

$socio = $result->fetch_assoc();

// Cargar todas las carreras
$carreras = [];
$carRes = $conn->query("SELECT id_carrera, nombre FROM carrera ORDER BY nombre ASC");
while ($row = $carRes->fetch_assoc()) {
    $carreras[] = $row;
}
?>

<h2>Editar Socio</h2>

<form method="POST" action="procesar_editar_socio.php" class="formulario-editar">
    
    <label>DNI:</label>
    <input type="text" value="<?= $socio['dni'] ?>" disabled>
    <input type="hidden" name="dni" value="<?= $socio['dni'] ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($socio['nombre']) ?>" required>

    <label>Apellido:</label>
    <input type="text" name="apellido" value="<?= htmlspecialchars($socio['apellido']) ?>" required>

    <label>Tipo de socio:</label>
    <select name="tipo_socio" required>
        <option value="Alumno" <?= $socio['tipo_socio'] == "Alumno" ? "selected" : "" ?>>Alumno</option>
        <option value="Docente" <?= $socio['tipo_socio'] == "Docente" ? "selected" : "" ?>>Docente</option>
    </select>

    <label>Carrera:</label>
    <select name="id_carrera">
        <option value="">-- Sin carrera --</option>
        <?php foreach ($carreras as $c): ?>
            <option value="<?= $c['id_carrera'] ?>"
                <?= $socio['id_carrera'] == $c['id_carrera'] ? "selected" : "" ?>>
                <?= htmlspecialchars($c['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <div class="botones">
        <button type="submit" class="btn-guardar">Guardar Cambios</button>
        <button type="button" class="btn-volver" onclick="window.location.href='dashboard.php?page=socios'">Volver</button>
    </div>
</form>

<style>
.formulario-editar {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    max-width: 650px;
}
.formulario-editar label {
    font-weight: bold;
}
.formulario-editar input, .formulario-editar select {
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 6px;
}
.botones {
    grid-column: span 2;
    display: flex;
    gap: 10px;
    margin-top: 15px;
}
.btn-guardar {
    padding: 8px 12px;
    background-color: #28a745;
    border: none;
    border-radius: 6px;
    color: white;
    cursor: pointer;
}
.btn-volver {
    padding: 8px 12px;
    background-color: #6c757d;
    border: none;
    border-radius: 6px;
    color: white;
    cursor: pointer;
}
.btn-guardar:hover, .btn-volver:hover {
    opacity: 0.85;
}
</style>