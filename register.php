<?php
// register.php
require 'config.php';

// Si ya está logueado, enviarlo a home
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Encriptar contraseña
    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insertar en la BD
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)");
        $stmt->execute([
            'nombre' => $nombre,
            'email' => $email,
            'password' => $hash
        ]);
        $mensaje = "Usuario registrado con éxito. <a href='index.php'>Iniciar sesión</a>";
    } catch (PDOException $e) {
        // Puede ser que el email ya exista u otro error
        $mensaje = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
</head>
<body>
    <h1>Registro de Usuario</h1>
    <?php if ($mensaje): ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Registrar</button>
    </form>

    <p>¿Ya tienes cuenta? <a href="index.php">Inicia sesión</a></p>
</body>
</html>
