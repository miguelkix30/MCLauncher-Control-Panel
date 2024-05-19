<?php
session_start();

if (!isset($_SESSION["username"])) {
    // El usuario no ha iniciado sesión, redirigir a la página de inicio de sesión
    header("Location: index.php");
    exit;
}
?>
<head>
    <title>MC Launcher Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="icon" type="image/x-icon" href="../favicon.png">
</head>

<body>
    <nav>
        <a href="config.php">Configuración del launcher</a>
        <a href="bans.php">Administrar bloqueos de HWID</a>
        <a href="instances.php">Clientes</a>
        <a href="logout.php">Cerrar sesión</a>
    </nav><br><br>

    <body>
    <h1>Configurador de Clientes</h1>
    <h2>TODO: hacer el Configurador de Clientes</h>
</body>
</body>