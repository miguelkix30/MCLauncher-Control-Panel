<?php
// Iniciar la sesión
session_start();

// Comprobar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Leer el archivo de usuarios
    $users = json_decode(file_get_contents("users.json"), true);

    // Comprobar si las credenciales son correctas
    foreach ($users as $user) {
        if ($user["username"] == $username && $user["password"] == $password) {
            // Iniciar la sesión
            $_SESSION["username"] = $username;

            // Redirigir al usuario a la URL almacenada o a una página predeterminada
            $redirect_to = isset($_SESSION["redirect_to"]) ? $_SESSION["redirect_to"] : "index.php";
            unset($_SESSION["redirect_to"]);

            header("Location: $redirect_to");
            exit;
        }
    }

    // Si las credenciales son incorrectas, mostrar un mensaje de error
    $error = "Nombre de usuario o contraseña incorrectos";
}

?>

<!-- Formulario de inicio de sesión -->
<head>
    <title>MC Launcher Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<form class="login-form" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <label for="username">Usuario:</label>
    <input type="text" id="username" name="username" required>
    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required>
    <input type="submit" value="Iniciar sesión">
</form>
</body>
<?php
if (isset($error)) {
    echo "<p>$error</p>";
}
?>