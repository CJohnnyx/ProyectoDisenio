<?php
// home.php
require 'config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inicio</title>
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <p>Has iniciado sesión correctamente.</p>

    <ul>
        <li><a href="productos.php">Gestionar Productos</a></li>
        <li><a href="citas.php">Agendar/Listar Citas</a></li>
        <li><a href="logout.php">Cerrar Sesión</a></li>
    </ul>
</body>
</html>
