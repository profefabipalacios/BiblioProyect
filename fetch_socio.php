<?php
require_once "includes/conexion.php";

// Si se envía un DNI, filtramos por ese socio
if (isset($_GET['dni']) && $_GET['dni'] !== "") {
    $dni = $_GET['dni'];

    $stmt = $conn->prepare("
        SELECT s.dni, s.nombre, s.apellido, s.tipo_socio, 
               s.fecha_alta, c.nombre AS carrera
        FROM socio s
        LEFT JOIN carrera c ON s.id_carrera = c.id_carrera
        WHERE s.dni = ?
    ");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $result = $stmt->get_result();

} else {

    // Listar todos los socios
    $query = "
        SELECT s.dni, s.nombre, s.apellido, s.tipo_socio, 
               s.fecha_alta, c.nombre AS carrera
        FROM socio s
        LEFT JOIN carrera c ON s.id_carrera = c.id_carrera
        ORDER BY s.apellido, s.nombre
    ";
    $result = $conn->query($query);
}

if ($result->num_rows > 0) {
    echo "<table class='tabla-socios'>
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Tipo de Socio</th>
                    <th>Carrera</th>
                    <th>Fecha de Alta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = $result->fetch_assoc()) {
        $carrera = $row['carrera'] ? htmlspecialchars($row['carrera']) : "Sin carrera";

        echo "<tr>
                <td>{$row['dni']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['apellido']}</td>
                <td>{$row['tipo_socio']}</td>
                <td>{$carrera}</td>
                <td>{$row['fecha_alta']}</td>
                <td>
                    <div class='acciones'>
                        <button class='btn-editar' onclick=\"window.location.href='dashboard.php?page=editar_socio&dni={$row['dni']}'\">
                            Editar
                        </button>

                        <button class='btn-eliminar' onclick='eliminarSocio(\"{$row['dni']}\")'>
                            Eliminar
                        </button>
                    </div>
                </td>
            </tr>";
    }

    echo "</tbody></table>";

} else {
    echo "<p>No hay socios registrados.</p>";
}
?>