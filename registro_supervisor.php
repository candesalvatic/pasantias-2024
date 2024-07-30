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

// Manejo del formulario de registro
if (isset($_POST['registrar'])) {
    $email = $_POST['email'];
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $confirmar_contraseña = $_POST['confirmar_contraseña'];

    if ($contraseña !== $confirmar_contraseña) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO registro_supervisor (email, usuario_super, contraseña_super) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $usuario, $contraseña_hash);

        if ($stmt->execute()) {
            // Registro exitoso, redirigir al inicio de sesión
            header("Location: logeo_supervisor.php?registro=exitoso");
            exit();
        } else {
            $error = "Error al registrar el usuario: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Supervisor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(-45deg, #a8d5ba, #6cbf8c, #a5d6a7, #9ccc65);
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
            color: #388e3c;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #388e3c;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #388e3c;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #2e7d32;
        }
        .error {
            color: #f44336;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registro de Supervisor</h2>
        <form method="post" action="">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required><br>
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required><br>
            <label for="confirmar_contraseña">Confirmar Contraseña:</label>
            <input type="password" id="confirmar_contraseña" name="confirmar_contraseña" required><br>
            <input type="submit" name="registrar" value="Registrar">
        </form>

        <?php
        // Mostrar el mensaje de error si existe
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>
    </div>
</body>
</html>

