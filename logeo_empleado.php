<?php
// Configuración de la base de datos
$servername = "localhost"; // Cambia esto si tu base de datos no está en localhost
$username = "root"; // Tu usuario de MySQL
$password = ""; // Tu contraseña de MySQL
$dbname = "Municipalidad";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejo del formulario de inicio de sesión
if (isset($_POST['iniciar_sesion'])) {
    $usuario = $_POST['usuario_login'];
    $contraseña = $_POST['contraseña_login'];

    $stmt = $conn->prepare("SELECT contraseña_per FROM registro_personal WHERE usuario_per = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($contraseña_hash);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if (password_verify($contraseña, $contraseña_hash)) {
            // Contraseña correcta, redirigir a la página de inicio
            header("Location: paginainicio.php");
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            overflow: hidden;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(-45deg, #87CEFA, #4682b4, #b0e0e6, #87CEEB);
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
        }
        @keyframes gradientAnimation {
            0% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0% 0%; }
        }
        .container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            z-index: 1;
        }
        h2 {
            color: #4682b4;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #4682b4;
        }
        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4682b4;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #4169e1;
        }
        .error {
            color: #ff6347;
            margin-top: 10px;
        }
        .mensaje {
            color: #32cd32;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Inicio de Sesión</h2>
        <form method="post" action="">
            <label for="usuario_login">Usuario:</label>
            <input type="text" id="usuario_login" name="usuario_login" required><br>
            <label for="contraseña_login">Contraseña:</label>
            <input type="password" id="contraseña_login" name="contraseña_login" required><br>
            <input type="submit" name="iniciar_sesion" value="Iniciar Sesión">
        </form>

        <?php
        // Mostrar el mensaje de error si existe
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }

        // Mostrar mensaje de registro exitoso si está presente en la URL
        if (isset($_GET['registro']) && $_GET['registro'] == 'exitoso') {
            echo "<p class='mensaje'>Registro exitoso. Puedes iniciar sesión.</p>";
        }
        ?>
    </div>
</body>
</html>

