<?php
require_once "includes/conexion.php";
session_start();

if (!isset($_GET['id'])) {
    echo "<p>Error: no se proporcionó un ID válido.</p>";
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM socio WHERE id_socio = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$socio = $result->fetch_assoc();

if (!$socio) {
    echo "<p>Socio no encontrado.</p>";
    exit;
}
?>

<h3>Editar Socio</h3>

<form method="POST" action="procesar_editar_socio.php" class="formulario-socio">
    <input type="hidden" name="id" value="<?php echo $socio['id_socio']; ?>">

    <label>DNI:</label>
    <input type="text" name="dni" value="<?php echo $socio['dni']; ?>" required>

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?php echo $socio['nombre']; ?>" required>

    <label>Apellido:</label>
    <input type="text" name="apellido" value="<?php echo $socio['apellido']; ?>" required>

    <label>Tipo de Socio:</label>
    <select name="tipo_socio" required>
        <option value="Alumno" <?php if ($socio['tipo_socio'] == 'Alumno') echo 'selected'; ?>>Alumno</option>
        <option value="Docente" <?php if ($socio['tipo_socio'] == 'Docente') echo 'selected'; ?>>Docente</option>
    </select>

    <label>Carrera:</label>
    <input type="text" name="carrera" value="<?php echo $socio['carrera']; ?>">

    <button type="submit" class="btn-guardar">Guardar Cambios</button>
</form>