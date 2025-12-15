<?php
require_once "includes/conexion.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=historial_devoluciones.xlsx");

echo "ID\tItem\tDNI\tBibliotecaria\tFecha Prestamo\tFecha Devolucion\n";

$sql = "
    SELECT
        p.id_prestamo,
        i.nombre_titulo,
        p.dni_socio,
        CONCAT(b.nombre,' ',b.apellido) AS bibliotecaria,
        p.fecha_prestamo,
        p.fecha_devolucion
    FROM prestamo p
    INNER JOIN inventario i ON p.id_item = i.id_item
    INNER JOIN bibliotecaria b ON p.id_bibliotecaria = b.id_bibliotecaria
    WHERE p.estado = 'Devuelto'
    ORDER BY p.fecha_devolucion DESC
";

$res = $conn->query($sql);

while ($row = $res->fetch_assoc()) {
    echo "{$row['id_prestamo']}\t{$row['nombre_titulo']}\t{$row['dni_socio']}\t{$row['bibliotecaria']}\t{$row['fecha_prestamo']}\t{$row['fecha_devolucion']}\n";
}