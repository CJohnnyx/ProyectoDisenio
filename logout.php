<?php
// logout.php
require 'config.php';

// Cerrar sesión
session_unset();
session_destroy();

// Redirigir a la página de login
header('Location: index.php');
exit;
