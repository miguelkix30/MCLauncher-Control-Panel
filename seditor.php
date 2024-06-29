<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}

$splashesFile = '../launcher/config-launcher/splashes.json';

if (!file_exists($splashesFile)) {
    file_put_contents($splashesFile, json_encode([]));
}

$splashes = json_decode(file_get_contents($splashesFile), true);

// Manejar la adición de splashes
if (isset($_POST['add'])) {
    $newSplash = [
        "message" => $_POST['message'],
        "author" => $_POST['author']
    ];
    $splashes[] = $newSplash;
    file_put_contents($splashesFile, json_encode($splashes, JSON_PRETTY_PRINT));
    $message = "El splash ha sido añadido.";
}

// Manejar la eliminación de splashes
if (isset($_POST['delete'])) {
    $indexToDelete = $_POST['delete'];
    if (isset($splashes[$indexToDelete])) {
        array_splice($splashes, $indexToDelete, 1);
        file_put_contents($splashesFile, json_encode($splashes, JSON_PRETTY_PRINT));
        $message = "El splash ha sido eliminado.";
    } else {
        $message = "El splash no existe.";
    }
}

// Manejar la edición de splashes
if (isset($_POST['edit'])) {
    $indexToEdit = $_POST['edit'];
    if (isset($splashes[$indexToEdit])) {
        $splashes[$indexToEdit]['message'] = $_POST['message'];
        $splashes[$indexToEdit]['author'] = $_POST['author'];
        file_put_contents($splashesFile, json_encode($splashes, JSON_PRETTY_PRINT));
        $message = "El splash ha sido editado.";
    } else {
        $message = "El splash no existe.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>MC Launcher Admin Panel - Editor de Splashes</title>
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
    <h1>Administrar Splashes</h1>

    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <div class="container" style="display: block;">
        <table>
            <tr>
                <th>Mensaje</th>
                <th>Autor</th>
                <th>Acción</th>
            </tr>
            <?php foreach ($splashes as $index => $splash) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($splash['message']); ?></td>
                    <td><?php echo htmlspecialchars($splash['author']); ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <button type="submit" name="delete" value="<?php echo $index; ?>" style="background-color: #f44336; color: white; border: none; cursor: pointer; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 14px; border-radius: 12px;">Eliminar</button>
                        </form>
                        <button onclick="document.getElementById('edit-form-<?php echo $index; ?>').style.display='block'" style="background-color: #4CAF50; color: white; border: none; cursor: pointer; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 14px; border-radius: 12px;">Editar</button>
                        <div id="edit-form-<?php echo $index; ?>" style="display:none;">
                            <form method="post">
                                <input type="hidden" name="edit" value="<?php echo $index; ?>">
                                <input type="text" name="message" value="<?php echo htmlspecialchars($splash['message']); ?>" required>
                                <input type="text" name="author" value="<?php echo htmlspecialchars($splash['author']); ?>" required>
                                <button type="submit">Guardar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Agregar un nuevo splash</h2>
        <form action="seditor.php" method="post">
            <label for="message">Mensaje:</label>
            <input type="text" id="message" name="message" required><br><br>
            <label for="author">Autor:</label>
            <input type="text" id="author" name="author" required><br><br>
            <input type="submit" id="save_button" value="Agregar" name="add">
        </form>
    </div>
</body>
</html>
