<?php
session_start();

if (!isset($_SESSION["username"])) {
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/mode-php.js"></script>
    <style>
        #editor {
            height: 650px;
            width: 100%;
        }
        .editor-submit {
        margin-bottom: 0; /* Eliminar el margen inferior del botón de enviar */
    }
    </style>
</head>

<body>
    <nav>
        <a href="config.php">Configuración del launcher</a>
        <a href="bans.php">Administrar bloqueos de HWID</a>
        <a href="ceditor.php">Clientes</a>
        <a href="logout.php">Cerrar sesión</a>
    </nav><br><br>
    <h1>Editor de clientes</h1>
    <form class="editor-form" action="" method="post">
        <div id="editor"><?php echo htmlspecialchars($text) ?></div><br>
        <input type="hidden" id="text" name="text">
        <input class="editor-submit" type="submit" value="Guardar"/>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var editor = ace.edit("editor");
            editor.setTheme("ace/theme/tomorrow_night");
            editor.session.setMode("ace/mode/php");
            editor.setOptions({
            fontSize: "12pt",
            animatedScroll: true,
            printMargin: false,
            scrollbarStyle: "light"
        });
            document.querySelector('form').addEventListener('submit', function(e) {
                document.getElementById('text').value = editor.getValue();
            });
        });
    </script>
</body>