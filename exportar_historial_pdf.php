<?php
require_once "includes/conexion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Historial de Devoluciones</title>
<style>
body { font-family: Arial; font-size: 12px; }
table { width:100%; border-collapse: collapse; }
th, td { border:1px solid #000; padding:5px; }
th { background:#eee; }
</style>
</head>
<body>

<h2>INSTITUTO DE FORMACION PROFESIONAL Nº6 - PERICO</h2>
<h2>Historial de Devoluciones</h2>

<table>
<tr>
    <th>ID</th>
    <th>Item</th>
    <th>DNI</th>
    <th>Bibliotecaria</th>
    <th>Préstamo</th>
    <th>Devolución</th>
</tr>

<?php
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
";

$res = $conn->query($sql);
while ($r = $res->fetch_assoc()):
?>
<tr>
    <td><?= $r['id_prestamo'] ?></td>
    <td><?= $r['nombre_titulo'] ?></td>
    <td><?= $r['dni_socio'] ?></td>
    <td><?= $r['bibliotecaria'] ?></td>
    <td><?= $r['fecha_prestamo'] ?></td>
    <td><?= $r['fecha_devolucion'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<script>
window.print();
</script>

</body>
</html>