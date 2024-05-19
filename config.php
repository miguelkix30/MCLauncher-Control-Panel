<?php
session_start();

if (!isset($_SESSION["username"])) {
    // El usuario no ha iniciado sesión, redirigir a la página de inicio de sesión
    header("Location: index.php");
    exit;
}

// Leer el archivo de configuración
$config = json_decode(file_get_contents("../launcher/config-launcher/config.json"), true);

// Comprobar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Actualizar los parámetros de configuración
    $config["maintenance"] = isset($_POST["maintenance"]) ? true : false;
    $config["maintenance_message"] = isset($_POST["maintenance_message"]) ? $_POST["maintenance_message"] : $config["maintenance_message"];
    if ($_POST["online"] == "custom") {
        $config["online"] = $_POST["online_input"];
    } else {
        $config["online"] = $_POST["online"] === 'true' ? true : false;
    }
    if ($_POST["custom_background"] == "custom") {
        $config["custom_background"] = $_POST["custom_background_input"];
    } else {
        $config["custom_background"] = $_POST["custom_background"];
    }
    $config["client_id"] = isset($_POST["client_id"]) ? $_POST["client_id"] : $config["client_id"];
    $config["dataDirectory"] = isset($_POST["dataDirectory"]) ? $_POST["dataDirectory"] : $config["dataDirectory"];
    $config["rss"] = isset($_POST["rss"]) ? $_POST["rss"] : $config["rss"];
    $config["notification"]["enabled"] = isset($_POST["notification_enabled"]) && $_POST["notification_enabled"] == "1" ? true : false;
    $config["notification"]["color"] = isset($_POST["notification_color"]) ? $_POST["notification_color"] : $config["notification"]["color"];
    if ($_POST["notification_icon"] == "custom") {
        $config["notification"]["icon"] = $_POST["custom_icon"];
    } else {
        $config["notification"]["icon"] = $_POST["notification_icon"];
    }
    $config["notification"]["title"] = isset($_POST["notification_title"]) ? $_POST["notification_title"] : $config["notification"]["title"];
    $config["notification"]["content"] = isset($_POST["notification_content"]) ? $_POST["notification_content"] : $config["notification"]["content"];
    $config["modsBeta"] = isset($_POST["modsBeta"]) && $_POST["modsBeta"] == "1" ? true : false;
    // Guardar el archivo de configuración
    file_put_contents("../launcher/config-launcher/config.json", json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

// Mostrar la barra de navegación y el formulario
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
    <form method="post">
        <h2>General</h2>
        <div class="container">
            <div>
                <label>
                    <span>Mantenimiento:</span>
                    <div class="switch">
                        <input type="checkbox" id="maintenance" name="maintenance" <?php echo $config["maintenance"] ? 'checked' : ''; ?>>
                        <span class="slider round"></span>
                    </div>
                </label>
                <label for="maintenance_message">Mensaje de Mantenimiento:</label>
                <textarea id="maintenance_message" name="maintenance_message" rows="4" cols="50"><?php echo $config["maintenance_message"]; ?></textarea>
                <?php
                $predefinedBackgrounds = [
                    'Por defecto' => 'none',
                    'Autumn' => 'http://node1.miguelkinetwork.fun:25565/launcher/media-launcher/autumn.mp4',
                    'Permalanda' => 'http://node1.miguelkinetwork.fun:25565/launcher/media-launcher/permalanda.mp4',
                    'Spring' => 'http://node1.miguelkinetwork.fun:25565/launcher/media-launcher/spring.mp4',
                    'Summer' => 'http://node1.miguelkinetwork.fun:25565/launcher/media-launcher/summer.mp4',
                    'Winter' => 'http://node1.miguelkinetwork.fun:25565/launcher/media-launcher/winter.mp4'
                ];
                $isCustomBackground = !in_array($config["custom_background"], $predefinedBackgrounds);
                $customBackgroundValue = $isCustomBackground ? $config["custom_background"] : '';
                ?>

                <label for="custom_background">Fondo Personalizado:</label>
                <select id="custom_background" name="custom_background" onchange="toggleCustomBackgroundInput(this)">
                    <?php
                    foreach ($predefinedBackgrounds as $name => $url) {
                        $selected = $config["custom_background"] == $url ? 'selected' : '';
                        echo "<option value=\"$url\" $selected>$name</option>";
                    }
                    ?>
                    <option value="custom" <?php echo $isCustomBackground ? 'selected' : ''; ?>>Otro (Enlace custom)</option>
                </select>
                <input type="text" id="custom_background_input" name="custom_background_input" style="<?php echo $isCustomBackground ? 'display: block;' : 'display: none;'; ?>" value="<?php echo $customBackgroundValue; ?>">
            </div>
            <div>
                <?php
                $isCustomOnline = !in_array($config["online"], ['true', 'false']);
                $customOnlineValue = $isCustomOnline ? $config["online"] : '';
                ?>

                <label for="online">Online:</label>
                <select id="online" name="online" onchange="toggleCustomOnlineInput(this)">
                    <option value="true" <?php echo $config["online"] === 'true' ? 'selected' : ''; ?>>True</option>
                    <option value="false" <?php echo $config["online"] === 'false' ? 'selected' : ''; ?>>False</option>
                    <option value="custom" <?php echo $isCustomOnline ? 'selected' : ''; ?>>Otro (Enlace custom)</option>
                </select>
                <input type="text" id="online_input" name="online_input" style="<?php echo $isCustomOnline ? 'display: block;' : 'display: none;'; ?>" value="<?php echo $customOnlineValue; ?>">
                <label for="client_id">ID del Cliente:</label>
                <input type="text" id="client_id" name="client_id" value="<?php echo $config["client_id"]; ?>">
                <label for="dataDirectory">Directorio de Datos:</label>
                <input type="text" id="dataDirectory" name="dataDirectory" value="<?php echo $config["dataDirectory"]; ?>">
            </div>
        </div>
        <h2>Notificaciones</h2>
        <div class="container">
            <div>
                <label>
                    <span>Notificación Habilitada:</span>
                    <input type="hidden" id="notification_enabled" name="notification_enabled" value="0">
                    <div class="switch">
                        <input type="checkbox" id="notification_enabled" name="notification_enabled" value="1" <?php echo $config["notification"]["enabled"] ? 'checked' : ''; ?>>
                        <span class="slider round"></span>
                    </div>
                </label>
                <label for="notification_color">Color de Notificación:</label>
                <select id="notification_color" name="notification_color">
                    <option value="red" <?php echo $config["notification"]["color"] == 'red' ? 'selected' : ''; ?>>Rojo</option>
                    <option value="green" <?php echo $config["notification"]["color"] == 'green' ? 'selected' : ''; ?>>Verde</option>
                    <option value="blue" <?php echo $config["notification"]["color"] == 'blue' ? 'selected' : ''; ?>>Azul</option>
                    <option value="yellow" <?php echo $config["notification"]["color"] == 'yellow' ? 'selected' : ''; ?>>Amarillo</option>
                </select>
                <?php
                $predefinedValues = ['info', 'warning', 'error', 'exclamation'];
                $isCustomIcon = !in_array($config["notification"]["icon"], $predefinedValues);
                $customIconValue = $isCustomIcon ? $config["notification"]["icon"] : '';
                ?>

                <label for="notification_icon">Icono de Notificación:</label>
                <select id="notification_icon" name="notification_icon" onchange="toggleCustomIconInput(this)">
                    <option value="info" <?php echo $config["notification"]["icon"] == 'info' ? 'selected' : ''; ?>>Info</option>
                    <option value="warning" <?php echo $config["notification"]["icon"] == 'warning' ? 'selected' : ''; ?>>Advertencia</option>
                    <option value="error" <?php echo $config["notification"]["icon"] == 'error' ? 'selected' : ''; ?>>Error</option>
                    <option value="exclamation" <?php echo $config["notification"]["icon"] == 'exclamation' ? 'selected' : ''; ?>>Exclamación</option>
                    <option value="custom" <?php echo $isCustomIcon ? 'selected' : ''; ?>>Otro (Enlace custom)</option>
                </select>
                <input type="text" id="custom_icon" name="custom_icon" style="<?php echo $isCustomIcon ? 'display: block;' : 'display: none;'; ?>" value="<?php echo $customIconValue; ?>" oninput="updateCustomIconValue(this)">
            </div>
            <div>
                <label for="notification_title">Título de Notificación:</label>
                <input type="text" id="notification_title" name="notification_title" value="<?php echo $config["notification"]["title"]; ?>">
                <label for="notification_content">Contenido de Notificación:</label>
                <textarea id="notification_content" name="notification_content" rows="4" cols="50"><?php echo $config["notification"]["content"]; ?></textarea>
            </div>
        </div>
        <h2>Experimental</h2>
        <div class="container">
            <div>
                <label>
                    <span>Mods Beta:</span>
                    <div class="switch">
                        <input type="checkbox" id="modsBeta" name="modsBeta" value="1" <?php echo $config["modsBeta"] ? 'checked' : ''; ?>>
                        <span class="slider round"></span>
                    </div>
                </label>
            </div>
        </div>
        <input type="submit" id="save_button" value="Guardar">
    </form>
</body>
<script>
    function toggleCustomIconInput(select) {
        var customIconInput = document.getElementById('custom_icon');
        customIconInput.style.display = select.value === 'custom' ? 'block' : 'none';
    }

    function updateCustomIconValue(input) {
        var select = document.getElementById('notification_icon');
        select.value = 'custom';
    }

    function toggleCustomBackgroundInput(selectElement) {
        var customBackgroundInput = document.getElementById('custom_background_input');
        if (selectElement.value == 'custom') {
            customBackgroundInput.style.display = 'block';
        } else {
            customBackgroundInput.style.display = 'none';
            customBackgroundInput.value = '';
        }
    }
    function toggleCustomOnlineInput(selectElement) {
    var customOnlineInput = document.getElementById('online_input');
    if (selectElement.value == 'custom') {
        customOnlineInput.style.display = 'block';
    } else {
        customOnlineInput.style.display = 'none';
        customOnlineInput.value = '';
    }
}
</script>