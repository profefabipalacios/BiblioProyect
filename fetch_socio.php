<?php
require_once "includes/conexion.php";

$query = "SELECT * FROM socio";
if (isset($_GET['dni']) && $_GET['dni'] !== "") {
    $dni = $_GET['dni'];
    $stmt = $conn->prepare("SELECT * FROM socio WHERE dni = ?");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}

if ($result->num_rows > 0) {
    echo "<table class='tabla-socios'>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Tipo</th>
                <th>Carrera</th>
                <th>Fecha Alta</th>
                <th>Acciones</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['dni']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['apellido']}</td>
                <td>{$row['tipo_socio']}</td>
                <td>{$row['carrera']}</td>
                <td>{$row['fecha_alta']}</td>
                <td>
                    <button class='btn-editar' onclick=\"window.location.href='dashboard.php?page=editar_socio&id={$row['id_socio']}'\">Editar</button>
                    <button class='btn-eliminar' onclick='eliminarSocio({$row['id_socio']})'>Eliminar</button>
                </td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hay socios registrados.</p>";
}
?>