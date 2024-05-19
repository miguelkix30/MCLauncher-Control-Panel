<?php
// Inicia la sesión
session_start();

// Destruye la sesión
session_destroy();

// Redirige al usuario a index.php
header("Location: index.php");
exit;
?>