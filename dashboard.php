<?php

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Biblioteca</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1>Panel de Gestión de Biblioteca</h1>
        <div class="usuario">
            <span>👤 <?php echo $_SESSION['usuario']; ?></span>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </header>

        <nav>
    <ul>
        <li class="submenu">
            <a href="#" onclick="toggleSubmenu(event)">
                <i class="fas fa-users"></i> Gestión de Socios ▾
            </a>
            <ul class="submenu-opciones" style="display:none;">
                <li><a href="dashboard.php?page=socios">Agregar nuevo socio</a></li>
                <li><a href="dashboard.php?page=socios_listado">Modificar y eliminar socios</a></li>
            </ul>
        </li>

        <li><a href="dashboard.php?page=insumos"><i class="fas fa-boxes"></i> Gestión de Insumos</a></li>
        <li><a href="dashboard.php?page=prestamos"><i class="fas fa-book-reader"></i> Gestión de Préstamos</a></li>
        <li><a href="dashboard.php?page=devoluciones"><i class="fas fa-undo-alt"></i> Gestión de Devoluciones</a></li>
    </ul>
</nav>

<script>
function toggleSubmenu(event) {
    event.preventDefault();
    const submenu = event.target.parentNode.querySelector(".submenu-opciones");
    submenu.style.display = submenu.style.display === "none" ? "block" : "none";
}
</script>

    <script>
    document.getElementById("toggleSocios").addEventListener("click", function(event) {
        event.preventDefault();
        const menu = document.getElementById("menuSocios");

        // Mostrar / ocultar
        if (menu.style.display === "block") {
            menu.style.display = "none";
        } else {
            menu.style.display = "block";
        }
    });
    </script>

    <main>
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            switch ($page) {
                case 'socios':                 // -> agregar socio
                    include("socios.php");
                    break;

                case 'socios_listado':
                    include("socios_listado.php");
                    break;

                case 'insumos':
                    include("insumos.php");
                    break;

                case 'prestamos':
                    include("prestamos.php");
                    break;

                case 'devoluciones':
                    include("devoluciones.php");
                    break;

                case 'editar_socio':
                    include("editar_socio.php");
                    break;

                case 'alta_insumo':
                    include("alta_insumo.php");
                    break;

                default:
                echo "<p>Seleccione una opción del menú.</p>";
                }
        } else {
            echo "<p>Bienvenido al panel de la biblioteca. Seleccione una opción del menú.</p>";
        }
        ?>
    </main>
</body>
</html>