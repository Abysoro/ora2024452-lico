<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
</head>
<body>

<div class="container">
    <h2>Registro</h2>
    <form action="" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        
        <label for="role">Selecciona tu rol:</label>
        <select name="role" id="role" required>
            <option value="admin">Administrador</option>
            <option value="cliente">Cliente</option>
        </select>
        
        <button type="submit">Registrar</button>
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

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

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO Usuarios (email, password, role) VALUES (?, ?, ?)";
    $params = array($email, $password, $role);

    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "<p>Usuario registrado exitosamente.</p>";
    }

    // Cerrar la conexión
    sqlsrv_close($conn);
}
?>

</body>
</html>
