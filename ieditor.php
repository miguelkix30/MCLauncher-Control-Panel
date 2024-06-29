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
    $fileToDelete = "../launcher/images-launcher/images/" . $_POST['delete'];

    if (file_exists($fileToDelete)) {
        unlink($fileToDelete);
        $message = "El archivo " . $_POST['delete'] . " ha sido eliminado.";
    } else {
        $message = "El archivo no existe.";
    }
}

// Manejar la subida de archivos
if (isset($_POST['submit'])) {
    $targetDir = "../launcher/images-launcher/images/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);

    // Verificar si el archivo ya existe
    if (file_exists($targetFile)) {
        $message = "El archivo " . basename($_FILES["file"]["name"]) . " ya existe.";
    } else {
        // Verificar el tamaño del archivo
        if ($_FILES["file"]["size"] > 5000000) {
            $message = "El archivo es demasiado grande. El tamaño máximo es de 5MB.";
        } else {
            // Permitir ciertos formatos de archivo
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = array("jpg", "jpeg", "png", "gif");
            if (!in_array($imageFileType, $allowedTypes)) {
                $message = "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
            } else {
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                    $message = "El archivo " . basename($_FILES["file"]["name"]) . " ha sido subido.";
                } else {
                    // Mostrar detalles del error
                    $uploadError = $_FILES['file']['error'];
                    switch ($uploadError) {
                        case UPLOAD_ERR_INI_SIZE:
                            $message = "El archivo excede el tamaño permitido por la directiva upload_max_filesize en php.ini.";
                            break;
                        case UPLOAD_ERR_FORM_SIZE:
                            $message = "El archivo excede el tamaño permitido por la directiva MAX_FILE_SIZE especificada en el formulario HTML.";
                            break;
                        case UPLOAD_ERR_PARTIAL:
                            $message = "El archivo se ha subido parcialmente.";
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            $message = "No se ha subido ningún archivo.";
                            break;
                        case UPLOAD_ERR_NO_TMP_DIR:
                            $message = "Falta una carpeta temporal.";
                            break;
                        case UPLOAD_ERR_CANT_WRITE:
                            $message = "No se pudo escribir el archivo en el disco.";
                            break;
                        case UPLOAD_ERR_EXTENSION:
                            $message = "Una extensión de PHP detuvo la subida del archivo.";
                            break;
                        default:
                            $message = "Hubo un error desconocido al subir el archivo.";
                            break;
                    }
                }
            }
        }
    }
}

// Obtener la lista de fondos
$backgrounds = glob('../launcher/images-launcher/images/*.*');
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
    <h1>Administrar imágenes de carga</h1>

    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <h2>Imágenes existentes</h2>
    <div class="container" style="display: block;">
        <table>
            <tr>
                <th>Nombre de la imagen</th>
                <th>Vista previa</th>
                <th>Acción</th>
            </tr>
            <?php foreach ($backgrounds as $background) : ?>
                <tr>
                    <td><?php echo basename($background); ?></td>
                    <td><img src="<?php echo $background; ?>" alt="<?php echo basename($background); ?>" style="max-width: 200px;"></td>
                    <td>
                        <form method="post">
                            <button type="submit" name="delete" value="<?php echo basename($background); ?>" style="background-color: #f44336; color: white; border: none; cursor: pointer; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 14px; border-radius: 12px;">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Subir una nueva imagen</h2>
        <form action="ieditor.php" method="post" enctype="multipart/form-data">
            <label for="file">Selecciona un archivo para subir:</label>
            <input type="file" id="file" name="file" accept=".jpg, .jpeg, .png, .gif"><br><br>
            <input type="submit" id="save_button" value="Subir" name="submit">
        </form>
    </div>
</body>

</html>
