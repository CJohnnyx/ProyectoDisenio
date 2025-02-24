<?php
// productos.php
require 'config.php';

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Acción crear
if (isset($_POST['action']) && $_POST['action'] === 'crear') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $cantidad = (int)($_POST['cantidad'] ?? 0);

    $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, cantidad) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $descripcion, $cantidad]);
}

// Acción editar
if (isset($_POST['action']) && $_POST['action'] === 'editar') {
    $id = (int)$_POST['id'];
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $cantidad = (int)($_POST['cantidad'] ?? 0);

    $stmt = $pdo->prepare("UPDATE productos SET nombre=?, descripcion=?, cantidad=? WHERE id=?");
    $stmt->execute([$nombre, $descripcion, $cantidad, $id]);
}

// Acción eliminar
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id=?");
    $stmt->execute([$id]);
}

// Obtener lista de productos
$stmt = $pdo->query("SELECT * FROM productos");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Para editar, si se recibe ?edit=ID
$productoAEditar = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmtEdit = $pdo->prepare("SELECT * FROM productos WHERE id=?");
    $stmtEdit->execute([$id]);
    $productoAEditar = $stmtEdit->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Productos</title>
</head>
<body>
    <h1>Gestión de Productos</h1>
    <p><a href="home.php">Volver al inicio</a></p>

    <?php if ($productoAEditar): ?>
        <!-- Formulario para editar producto -->
        <h2>Editar Producto</h2>
        <form method="POST">
            <input type="hidden" name="action" value="editar">
            <input type="hidden" name="id" value="<?php echo $productoAEditar['id']; ?>">

            <label>Nombre:</label><br>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($productoAEditar['nombre']); ?>" required><br><br>

            <label>Descripción:</label><br>
            <textarea name="descripcion" required><?php echo htmlspecialchars($productoAEditar['descripcion']); ?></textarea><br><br>

            <label>Cantidad:</label><br>
            <input type="number" name="cantidad" value="<?php echo $productoAEditar['cantidad']; ?>"><br><br>

            <button type="submit">Actualizar</button>
        </form>
    <?php else: ?>
        <!-- Formulario para crear producto -->
        <h2>Crear Producto</h2>
        <form method="POST">
            <input type="hidden" name="action" value="crear">

            <label>Nombre:</label><br>
            <input type="text" name="nombre" required><br><br>

            <label>Descripción:</label><br>
            <textarea name="descripcion" required></textarea><br><br>

            <label>Cantidad:</label><br>
            <input type="number" name="cantidad" value="0"><br><br>

            <button type="submit">Crear</button>
        </form>
    <?php endif; ?>

    <hr>
    <h2>Lista de Productos</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($productos as $prod): ?>
        <tr>
            <td><?php echo $prod['id']; ?></td>
            <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
            <td><?php echo htmlspecialchars($prod['descripcion']); ?></td>
            <td><?php echo $prod['cantidad']; ?></td>
            <td>
                <a href="?edit=<?php echo $prod['id']; ?>">Editar</a> |
                <a href="?delete=<?php echo $prod['id']; ?>" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
