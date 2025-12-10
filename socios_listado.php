<?php
require_once "includes/conexion.php";

// Obtener socios ordenados alfabéticamente
$query = "
    SELECT s.dni, s.nombre, s.apellido, s.tipo_socio, s.fecha_alta, 
           c.nombre AS carrera, s.estado
    FROM socio s
    LEFT JOIN carrera c ON s.id_carrera = c.id_carrera
    ORDER BY s.apellido ASC, s.nombre ASC
";
$result = $conn->query($query);
?>

<div class="contenedor-listado">
    <h2>Listado de Socios 👤</h2>

    <!-- BUSCADOR -->
    <div class="busqueda-socios">
        <input type="text" id="buscarSocio" placeholder="Buscar por DNI, nombre o apellido">
        <button id="btnBuscarSocio"><i class="fas fa-search"></i></button>
    </div>

    <!-- TABLA -->
    <table class="tabla-socios-listado" id="tablaSociosListado">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Carrera</th>
                <!-- <th>Estado</th> -->
                <th>Alta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['dni'] ?></td>
                    <td><?= htmlspecialchars($row['apellido']) ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= $row['tipo_socio'] ?></td>
                    <td><?= $row['carrera'] ? $row['carrera'] : "Sin carrera" ?></td>
                    <!-- <td>
                        <?php if ($row['estado'] == "Activo"): ?>
                            <span class="estado-activo">Activo</span>
                        <?php else: ?>
                            <span class="estado-inactivo">Inactivo</span>
                        <?php endif; ?>
                    </td> -->
                    <td><?= $row['fecha_alta'] ?></td>
                    <td>
                        <button class="btn-editar"
                            onclick="window.location.href='dashboard.php?page=editar_socio&dni=<?= $row['dni'] ?>'">
                            Editar
                        </button>

                        <button class="btn-eliminar"
                            onclick="desactivarSocio('<?= $row['dni'] ?>')">
                            Eliminar
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" style="text-align:center;">No hay socios registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ESTILOS -->
<style>
.contenedor-listado {
    width: 95%;
    margin: auto;
}

.busqueda-socios {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 12px;
    gap: 8px;
}

.busqueda-socios input {
    padding: 7px;
    width: 250px;
    border: 1px solid #bbb;
    border-radius: 6px;
}

.busqueda-socios button {
    background-color: #007bff;
    border: none;
    padding: 7px 12px;
    color: white;
    border-radius: 6px;
    cursor: pointer;
}

.tabla-socios-listado {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 6px #0002;
}

.tabla-socios-listado th {
    background: #f0f0f0;
    padding: 12px;
    text-align: left;
    font-weight: bold;
}

.tabla-socios-listado td {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.tabla-socios-listado tr:hover {
    background: #fafafa;
}

.btn-editar {
    background: #ffc107;
    padding: 6px 10px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.btn-eliminar {
    background: #dc3545;
    padding: 6px 10px;
    border: none;
    border-radius: 6px;
    color: white;
    cursor: pointer;
}

.estado-activo {
    color: green;
    font-weight: bold;
}

.estado-inactivo {
    color: red;
    font-weight: bold;
}
</style>

<script>
// BUSCADOR EN TIEMPO REAL
document.getElementById("buscarSocio").addEventListener("keyup", function() {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll("#tablaSociosListado tbody tr");

    filas.forEach(fila => {
        const texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});

document.getElementById("btnBuscarSocio").addEventListener("click", () => {
    const evento = new Event("keyup");
    document.getElementById("buscarSocio").dispatchEvent(evento);
});

// DESACTIVAR SOCIO
function desactivarSocio(dni) {
    if (!confirm("¿Desactivar este socio? No podrá realizar préstamos.")) return;

    fetch("eliminar_socio.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "dni=" + encodeURIComponent(dni)
    })
    .then(r => r.json())
    .then(data => {
        alert(data.mensaje);
        if (data.ok) location.reload();
    })
    .catch(err => console.error("error", err));
}
</script>