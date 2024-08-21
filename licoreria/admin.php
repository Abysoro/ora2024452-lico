<?php
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

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

// Agregar licor a la base de datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $licor = $_POST['licor'];
    $precio = $_POST['precio'];

    $sql = "INSERT INTO Licores (nombre, precio) VALUES (?, ?)";
    $params = array($licor, $precio);

    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
}

// Obtener la lista de licores
$sql = "SELECT * FROM Licores";
$stmt = sqlsrv_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Lista de Licores</title>
</head>
<body>

<h2>Lista de Licores</h2>
<ul>
    <?php while ($licor = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) : ?>
        <li><?php echo $licor['nombre'] . " - $" . $licor['precio']; ?></li>
    <?php endwhile; ?>
</ul>

<h3>Agregar Licor</h3>
<form action="" method="POST">
    <input type="text" name="licor" placeholder="Nombre del Licor" required>
    <input type="number" name="precio" placeholder="Precio" required>
    <button type="submit">Agregar</button>
</form>

</body>
</html>

<?php
// Cerrar la conexión
sqlsrv_close($conn);
?>
