<?php
/**
 * config.php
 * Configuración de la BD y sesión.
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión antes de cualquier salida
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$dbname = "centro_odontologico";
$user = "root";
$pass = ""; // Contraseña por defecto en XAMPP suele ser vacía

try {
    // Conexión con PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Habilitar excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>
