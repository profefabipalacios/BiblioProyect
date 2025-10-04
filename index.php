<?php
session_start();
include("includes/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave   = $_POST["clave"];

    $sql = "SELECT * FROM bibliotecaria WHERE usuario=? AND clave=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $clave);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $fila = $resultado->fetch_assoc(); // ← obtenemos los datos del bibliotecario

        // Guardamos en sesión el usuario y el ID
        $_SESSION["usuario"] = $fila["usuario"];
        $_SESSION["id_bibliotecaria"] = $fila["id_bibliotecaria"]; // ← aquí guardamos el ID

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca IES N°6 - Login</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <div class="background"></div> <!-- Imagen de fondo -->

    <div class="login-container">
        <img src="assets/img/logo.png" alt="Logo Biblioteca" class="logo">
        <h2>Acceso Bibliotecarios</h2>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="clave" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>
</html>