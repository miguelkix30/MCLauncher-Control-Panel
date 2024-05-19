<?php
session_start();

if (!isset($_SESSION["username"])) {
    // El usuario no ha iniciado sesión, redirigir a la página de inicio de sesión
    header("Location: index.php");
    exit;
}

$file = '../files/php/instances.php';
if (isset($_POST['text'])) {
    file_put_contents($file, $_POST['text']);
    echo "Archivo guardado.";
}
$text = file_get_contents($file);

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
        <a href="ceditor.php">Clientes</a>
        <a href="logout.php">Cerrar sesión</a>
    </nav><br><br>
    <form class="editor-form" action="" method="post">
    <textarea class="editor-content" name="text" rows="30" cols="80"><?php echo htmlspecialchars($text) ?></textarea><br>
    <input class="editor-submit" type="submit" />
</form>

</body>