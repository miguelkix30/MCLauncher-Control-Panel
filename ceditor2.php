<?php
// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicializar la variable $instance
$instance = [];

// Incluir el archivo de instancias
if (file_exists('../files/php/instances.php')) {
    include '../files/php/instances.php';
}

// Si $instance no es un array, inicializarlo como array vacío
if (!is_array($instance)) {
    $instance = [];
}

// Función para guardar el array $instance en el archivo instances.php
function saveInstances($instances) {
    $content = "<?php\n\$instance = " . var_export($instances, true) . ";\n?>";
    file_put_contents('../files/php/instances.php', $content);
}

// Manejar las solicitudes de creación, edición y eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $key = $_POST['key'] ?? '';

    switch ($action) {
        case 'create':
            if (!empty($key)) {
                $instance[$key] = [
                    'loadder' => [
                        'minecraft_version' => $_POST['minecraft_version'],
                        'loadder_type' => $_POST['loadder_type'],
                        'loadder_version' => $_POST['loadder_version'],
                    ],
                    'verify' => isset($_POST['verify']),
                    'ignored' => explode(',', $_POST['ignored']),
                    'whitelist' => explode(',', $_POST['whitelist']),
                    'whitelistActive' => isset($_POST['whitelistActive']),
                    'status' => [
                        'nameServer' => $_POST['nameServer'],
                        'ip' => $_POST['ip'],
                        'port' => $_POST['port'],
                    ],
                    'optionalMods' => json_decode($_POST['optionalMods'], true),
                    'background' => $_POST['background'],
                    'mkid' => isset($_POST['mkid']),
                    'maintenance' => isset($_POST['maintenance']),
                    'maintenancemsg' => $_POST['maintenancemsg'],
                ];
                saveInstances($instance);
            }
            break;

        case 'edit':
            if (!empty($key) && isset($instance[$key])) {
                $instance[$key]['loadder']['minecraft_version'] = $_POST['minecraft_version'];
                $instance[$key]['loadder']['loadder_type'] = $_POST['loadder_type'];
                $instance[$key]['loadder']['loadder_version'] = $_POST['loadder_version'];
                $instance[$key]['verify'] = isset($_POST['verify']);
                $instance[$key]['ignored'] = explode(',', $_POST['ignored']);
                $instance[$key]['whitelist'] = explode(',', $_POST['whitelist']);
                $instance[$key]['whitelistActive'] = isset($_POST['whitelistActive']);
                $instance[$key]['status']['nameServer'] = $_POST['nameServer'];
                $instance[$key]['status']['ip'] = $_POST['ip'];
                $instance[$key]['status']['port'] = $_POST['port'];
                $instance[$key]['optionalMods'] = json_decode($_POST['optionalMods'], true);
                $instance[$key]['background'] = $_POST['background'];
                $instance[$key]['mkid'] = isset($_POST['mkid']);
                $instance[$key]['maintenance'] = isset($_POST['maintenance']);
                $instance[$key]['maintenancemsg'] = $_POST['maintenancemsg'];
                saveInstances($instance);
            }
            break;

        case 'delete':
            if (!empty($key) && isset($instance[$key])) {
                unset($instance[$key]);
                saveInstances($instance);
            }
            break;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$editingInstance = null;
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['edit'])) {
    $editKey = $_GET['edit'];
    if (isset($instance[$editKey])) {
        $editingInstance = $instance[$editKey];
        $editingInstance['key'] = $editKey;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Instancias</title>
</head>
<body>
    <h1>Gestión de Instancias</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Clave</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($instance as $key => $value): ?>
                <tr>
                    <td><?php echo htmlspecialchars($key); ?></td>
                    <td>
                        <form action="" method="get" style="display:inline;">
                            <input type="hidden" name="edit" value="<?php echo htmlspecialchars($key); ?>">
                            <button type="submit">Editar</button>
                        </form>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="key" value="<?php echo htmlspecialchars($key); ?>">
                            <button type="submit" onclick="return confirm('¿Estás seguro de que quieres eliminar esta instancia?');">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2><?php echo $editingInstance ? 'Editar Instancia' : 'Crear Nueva Instancia'; ?></h2>
    <form action="" method="post">
        <input type="hidden" name="action" value="<?php echo $editingInstance ? 'edit' : 'create'; ?>">
        <input type="hidden" name="key" value="<?php echo htmlspecialchars($editingInstance['key'] ?? ''); ?>">
        
        <label for="minecraft_version">Versión de Minecraft:</label>
        <input type="text" id="minecraft_version" name="minecraft_version" value="<?php echo htmlspecialchars($editingInstance['loadder']['minecraft_version'] ?? ''); ?>"><br>

        <label for="loadder_type">Tipo de Loader:</label>
        <input type="text" id="loadder_type" name="loadder_type" value="<?php echo htmlspecialchars($editingInstance['loadder']['loadder_type'] ?? ''); ?>"><br>

        <label for="loadder_version">Versión de Loader:</label>
        <input type="text" id="loadder_version" name="loadder_version" value="<?php echo htmlspecialchars($editingInstance['loadder']['loadder_version'] ?? ''); ?>"><br>

        <label for="verify">Verificar:</label>
        <input type="checkbox" id="verify" name="verify" <?php echo $editingInstance && $editingInstance['verify'] ? 'checked' : ''; ?>><br>

        <label for="ignored">Archivos Ignorados (separados por comas):</label>
        <input type="text" id="ignored" name="ignored" value="<?php echo htmlspecialchars(implode(',', $editingInstance['ignored'] ?? [])); ?>"><br>

        <label for="whitelist">Whitelist (separados por comas):</label>
        <input type="text" id="whitelist" name="whitelist" value="<?php echo htmlspecialchars(implode(',', $editingInstance['whitelist'] ?? [])); ?>"><br>

        <label for="whitelistActive">Whitelist Activa:</label>
        <input type="checkbox" id="whitelistActive" name="whitelistActive" <?php echo $editingInstance && $editingInstance['whitelistActive'] ? 'checked' : ''; ?>><br>

        <label for="nameServer">Nombre del Servidor:</label>
        <input type="text" id="nameServer" name="nameServer" value="<?php echo htmlspecialchars($editingInstance['status']['nameServer'] ?? ''); ?>"><br>

        <label for="ip">IP:</label>
        <input type="text" id="ip" name="ip" value="<?php echo htmlspecialchars($editingInstance['status']['ip'] ?? ''); ?>"><br>

        <label for="port">Puerto:</label>
        <input type="text" id="port" name="port" value="<?php echo htmlspecialchars($editingInstance['status']['port'] ?? ''); ?>"><br>

        <label for="optionalMods">Mods Opcionales (formato JSON):</label>
        <textarea id="optionalMods" name="optionalMods"><?php echo htmlspecialchars(json_encode($editingInstance['optionalMods'] ?? [])); ?></textarea><br>

        <label for="background">Fondo:</label>
        <input type="text" id="background" name="background" value="<?php echo htmlspecialchars($editingInstance['background'] ?? ''); ?>"><br>

        <label for="mkid">MKID:</label>
        <input type="checkbox" id="mkid" name="mkid" <?php echo $editingInstance && $editingInstance['mkid'] ? 'checked' : ''; ?>><br>

        <label for="maintenance">Mantenimiento:</label>
        <input type="checkbox" id="maintenance" name="maintenance" <?php echo $editingInstance && $editingInstance['maintenance'] ? 'checked' : ''; ?>><br>

        <label for="maintenancemsg">Mensaje de Mantenimiento:</label>
        <input type="text" id="maintenancemsg" name="maintenancemsg" value="<?php echo htmlspecialchars($editingInstance['maintenancemsg'] ?? ''); ?>"><br>

        <button type="submit"><?php echo $editingInstance ? 'Actualizar Instancia' : 'Crear Instancia'; ?></button>
    </form>
</body>
</html>