<?php
// Inicia la sesión
session_start();

if (!isset($_SESSION["username"])) {
    // El usuario no ha iniciado sesión, redirigir a la página de inicio de sesión
    header("Location: index.php");
    exit;
}

// Lee el archivo bans.json
$bans = json_decode(file_get_contents('../launcher/config-launcher/bans.json'), true);

// Comprueba si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Comprueba si se ha enviado una nueva HWID
    if (!empty($_POST['new_hwid'])) {
        // Añade la nueva HWID a la lista de bans
        $bans[] = $_POST['new_hwid'];
    }

    // Comprueba si se ha enviado una HWID para eliminar
    if (!empty($_POST['remove_hwid'])) {
        // Busca la HWID en la lista de bans y la elimina
        if (($key = array_search($_POST['remove_hwid'], $bans)) !== false) {
            unset($bans[$key]);
        }
    }

    // Reindexa el array y guarda la lista de bans en el archivo bans.json
    $bans = array_values($bans);
    file_put_contents('../launcher/config-launcher/bans.json', json_encode($bans));
}
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
    <h1>Administrar bloqueos de HWID</h1>
    <div class="container" style="display: block;">
        <h2>Lista de HWID Baneadas</h2>
        <hr>
        <table>
            <tr>
                <th>HWID</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($bans as $hwid): ?>
                <tr>
                    <td><?php echo $hwid; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="remove_hwid" value="<?php echo $hwid; ?>">
                            <input type="submit" value="Eliminar" style="background-color: #f44336; color: white; border: none; cursor: pointer; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 14px; border-radius: 12px;">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <h2>Añadir HWID a la Lista de Baneos</h2>
        <hr>
        <form method="post">
            <label for="new_hwid">Añadir HWID:</label>
            <input type="text" id="new_hwid" name="new_hwid">
            <input type="submit" value="Añadir" style="background-color: #4CAF50; color: white; border: none; cursor: pointer; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 14px; border-radius: 12px;">
        </form>
    </div>
</body>
</html>