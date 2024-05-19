<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>MC Launcher Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="icon" type="image/x-icon" href="../favicon.png">
</head>
<body>
    <?php
    if (isset($_SESSION["username"])) {
        // The user is authenticated, show the navigation bar and the page content
        ?>
        <nav>
            <a href="config.php">Configuración del launcher</a>
            <a href="bans.php">Administrar bloqueos de HWID</a>
            <a href="ceditor.php">Clientes</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
        <h2>Bienvenido, <?php echo $_SESSION["username"]; ?></h2>
        <?php
        // Here goes the rest of the page content
    } else {
        // The user is not authenticated, show the login form
        include "login.php";
    }
    ?>
</body>
</html>