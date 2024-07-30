<?php
// Configuración de la base de datos
$servername = "localhost"; // Cambia esto si tu base de datos no está en localhost
$username = "root"; // Tu usuario de MySQL
$password = ""; // Tu contraseña de MySQL
$dbname = "municipalidad";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejo del formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Preparar la consulta
    $stmt = $conn->prepare("SELECT id_personal FROM login_personal WHERE usuario_per = ? AND contraseña_per = ?");
    $stmt->bind_param("ss", $usuario, $contraseña);

    // Ejecutar la consulta
    $stmt->execute();
    $stmt->store_result();

    // Verificar si hay una fila que coincida
    if ($stmt->num_rows > 0) {
        // Si el usuario y la contraseña son correctos, redirigir a la página principal
        header("Location: paginainicio.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }

    // Cerrar la consulta
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
</head>
<body>
    <h2>Inicio de Sesión</h2>
    <form method="post" action="">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required><br><br>
        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required><br><br>
        <input type="submit" value="Iniciar Sesión">
    </form>

    <?php
    // Mostrar el mensaje de error si existe
    if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
    ?>
</body>
</html>
