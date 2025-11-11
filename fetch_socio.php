<?php
require_once "includes/conexion.php";

$filterByDni = isset($_GET['dni']) && $_GET['dni'] !== "";

// Consulta base
$sql = "
    SELECT s.dni, s.nombre, s.apellido, s.tipo_socio, 
           s.fecha_alta, s.estado, 
           c.nombre AS carrera
    FROM socio s
    LEFT JOIN carrera c ON s.id_carrera = c.id_carrera
";

// Si se filtra por DNI (verificar)
if ($filterByDni) {
    $sql .= " WHERE s.dni = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_GET['dni']);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Mostrar solo activos
    $sql .= " WHERE s.estado = 'Activo' ORDER BY s.apellido, s.nombre";
    $result = $conn->query($sql);
}

if ($result->num_rows > 0) {
    echo "<table class='tabla-socios'>
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Tipo</th>
                <th>Carrera</th>
                <th>Fecha Alta</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>";

    while ($row = $result->fetch_assoc()) {

        $carrera = $row['carrera'] ? htmlspecialchars($row['carrera']) : "Sin carrera";
        $estado = $row['estado'];

        echo "<tr>
            <td>{$row['dni']}</td>
            <td>{$row['nombre']}</td>
            <td>{$row['apellido']}</td>
            <td>{$row['tipo_socio']}</td>
            <td>{$carrera}</td>
            <td>{$row['fecha_alta']}</td>
            <td>{$estado}</td>
            <td>
                <div class='acciones'>";

        if ($estado === "Activo") {
            echo "<button class='btn-editar' onclick=\"window.location.href='dashboard.php?page=editar_socio&dni={$row['dni']}'\">Editar</button>
                  <button class='btn-eliminar' onclick='eliminarSocio(\"{$row['dni']}\")'>Eliminar</button>";
        } else {
            echo "<button class='btn-editar' disabled style='opacity:0.4;'>Editar</button>";
            echo "<button class='btn-eliminar' disabled style='opacity:0.4;'>Desactivar</button>";
        }

        echo "</div>
            </td>
        </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p>No hay socios registrados.</p>";
}
?>