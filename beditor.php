<?php
// Inicia la sesión
session_start();

if (!isset($_SESSION["username"])) {
    // El usuario no ha iniciado sesión, redirigir a la página de inicio de sesión
    header("Location: index.php");
    exit;
}

// Manejar la eliminación de archivos
if (isset($_POST['delete'])) {
    $fileToDelete = "../launcher/media-launcher/" . $_POST['delete'] . ".mp4";

    if (file_exists($fileToDelete)) {
        unlink($fileToDelete);
        $message = "El archivo " . $_POST['delete'] . " ha sido eliminado.";
    } else {
        $message = "El archivo no existe.";
    }
}

// Manejar la subida de archivos
if (isset($_POST['submit'])) {
    $targetDir = "../launcher/media-launcher/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        $message = "El archivo " . basename($_FILES["file"]["name"]) . " ha sido subido.";
    } else {
        $message = "Hubo un error al subir el archivo.";
    }
}

// Obtener la lista de fondos
$backgrounds = glob('../launcher/media-launcher/*.mp4');
?>

<!DOCTYPE html>
<html>

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
    <h1>Administrar fondos personalizados</h1>

    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <h2>Fondos existentes</h2>
    <div class="container" style="display: block;">
        <table>
            <tr>
                <th>Nombre del fondo</th>
                <th>Acción</th>
            </tr>
            <?php foreach ($backgrounds as $background) : ?>
                <tr>
                    <td><?php echo basename($background); ?></td>
                    <td>
                        <form method="post">
                            <button type="submit" name="delete" value="<?php echo pathinfo($background, PATHINFO_FILENAME); ?>" style="background-color: #f44336; color: white; border: none; cursor: pointer; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 14px; border-radius: 12px;">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>


        <h2>Subir un nuevo fondo</h2>
        <form action="beditor.php" method="post" enctype="multipart/form-data">
            <input type="file" id="file" name="file" accept=".mp4"><br><br>
            <input type="submit" id="save_button" value="Subir" name="submit">
        </form>
    </div>
</body>

</html>