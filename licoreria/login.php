<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
</head>
<body>

<div class="container">
    <h2>Iniciar Sesión</h2>
    <form action="" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Iniciar Sesión</button>
    </form>
</div>

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Configuración de la base de datos
    $serverName = "localhost";
    $connectionOptions = array(
        "Database" => "nombre_base_de_datos",
        "Uid" => "tu_usuario",
        "PWD" => "tu_contraseña"
    );

    // Conectar a la base de datos
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Verificar los datos del usuario
    $sql = "SELECT * FROM Usuarios WHERE email = ? AND password = ?";
    $params = array($email, $password);

    $stmt = sqlsrv_query($conn, $sql, $params);
    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($user) {
        $_SESSION['role'] = $user['role'];
        if ($user['role'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: cliente.php");
        }
        exit();
    } else {
        echo "<p>Credenciales incorrectas.</p>";
    }

    // Cerrar la conexión
    sqlsrv_close($conn);
}
?>

</body>
</html>
