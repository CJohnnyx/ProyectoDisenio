<?php
// citas.php
require 'config.php';

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Crear cita
if (isset($_POST['action']) && $_POST['action'] === 'crear') {
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $descripcion = trim($_POST['descripcion'] ?? '');
    $usuario_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO citas (usuario_id, fecha, hora, descripcion) VALUES (?, ?, ?, ?)");
    $stmt->execute([$usuario_id, $fecha, $hora, $descripcion]);
}

// Eliminar cita
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Solo puede eliminar la cita si pertenece al usuario actual
    $stmt = $pdo->prepare("DELETE FROM citas WHERE id=? AND usuario_id=?");
    $stmt->execute([$id, $_SESSION['user_id']]);
}

// Listar citas del usuario
$stmt = $pdo->prepare("SELECT * FROM citas WHERE usuario_id=? ORDER BY fecha, hora");
$stmt->execute([$_SESSION['user_id']]);
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mis Citas</title>
</head>
<body>
    <h1>Agendar / Listar Citas</h1>
    <p><a href="home.php">Volver al inicio</a></p>

    <h2>Agendar Nueva Cita</h2>
    <form method="POST">
        <input type="hidden" name="action" value="crear">

        <label>Fecha:</label><br>
        <input type="date" name="fecha" required><br><br>

        <label>Hora:</label><br>
        <input type="time" name="hora" required><br><br>

        <label>Descripción / Motivo:</label><br>
        <textarea name="descripcion"></textarea><br><br>

        <button type="submit">Agendar</button>
    </form>

    <hr>
    <h2>Mis Citas</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($citas as $cita): ?>
        <tr>
            <td><?php echo $cita['id']; ?></td>
            <td><?php echo $cita['fecha']; ?></td>
            <td><?php echo $cita['hora']; ?></td>
            <td><?php echo htmlspecialchars($cita['descripcion']); ?></td>
            <td>
                <a href="?delete=<?php echo $cita['id']; ?>" onclick="return confirm('¿Cancelar cita?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
