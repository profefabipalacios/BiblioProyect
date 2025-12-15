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
    <h2 class="titulo-seccion">Listado de Socios</h2>

    <!-- BUSCADOR -->
    <div class="busqueda-socios">
    <input 
        type="text"
        id="buscarSocio"
        class="input-busqueda input-busqueda-lg"
        placeholder="Buscar por DNI, nombre o apellido"
    >
    <button id="btnBuscarSocio" class="btn-buscar btn-buscar-sm">
        <i class="fas fa-search"></i>
    </button>
</div>

    <!-- TABLA -->
    <div class="tabla-wrapper">
        <table class="tabla-socios-listado tabla-elegante" id="tablaSociosListado">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Carrera</th>
                    <th>Alta</th>
                    <th class="col-acciones">Acciones</th>
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
                            <td><?= $row['carrera'] ?: "Sin carrera" ?></td>
                            <td><?= $row['fecha_alta'] ?></td>
                            <td class="acciones">
                                <button class="btn-icon btn-editar"
                                    title="Editar"
                                    onclick="window.location.href='dashboard.php?page=editar_socio&dni=<?= $row['dni'] ?>'">
                                    <i class="fas fa-pen"></i>
                                </button>

                                <button class="btn-icon btn-eliminar"
                                    title="Eliminar"
                                    onclick="desactivarSocio('<?= $row['dni'] ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center;">
                            No hay socios registrados
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Buscador en tiempo real
document.getElementById("buscarSocio").addEventListener("keyup", function () {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll("#tablaSociosListado tbody tr");

    filas.forEach(fila => {
        fila.style.display = fila.innerText.toLowerCase().includes(filtro)
            ? ""
            : "none";
    });
});

document.getElementById("btnBuscarSocio").addEventListener("click", () => {
    document.getElementById("buscarSocio").dispatchEvent(new Event("keyup"));
});

// Desactivar socio
function desactivarSocio(dni) {
    if (!confirm("¿Desactivar este socio?")) return;

    fetch("eliminar_socio.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "dni=" + encodeURIComponent(dni)
    })
    .then(r => r.json())
    .then(data => {
        alert(data.mensaje);
        if (data.ok) location.reload();
    });
}
</script>