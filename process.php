<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $data = json_decode($_POST['data'], true);
    $action = $_POST['action'];

    $instance = array();
    include '../files/php/instances.php';

    if ($action === 'create') {
        $instance[$name] = array_merge(array(), $data);
    } elseif ($action === 'edit') {
        $instance[$name] = array_merge($instance[$name] ?? array(), $data);
    } elseif ($action === 'delete') {
        unset($instance[$name]);
    }

    $content = "<?php\n\$instance = array();\n";
    foreach ($instance as $inst_name => $inst_data) {
        $content .= "\$instance['$inst_name'] = array_merge(\$instance['$inst_name'] ?? array(), " . var_export($inst_data, true) . ");\n";
    }
    file_put_contents('../files/php/instances.php', $content);
    header('Location: ceditor.php');
    exit;
}
?>
