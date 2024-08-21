<?php
session_start();

if ($_SESSION['role'] != 'cliente') {
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

// Obtener la lista de licores
$sql = "SELECT * FROM Licores";
$stmt = sqlsrv_query($conn, $sql);

$selectedLicores = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedLicores = $_POST['licores'];
    $total = 0;
    foreach ($selectedLicores as $licor_id) {
        $sql = "SELECT * FROM Licores WHERE id = ?";
        $params = array($licor_id);
        $licorStmt = sqlsrv_query($conn, $sql, $params);
        $licor = sqlsrv_fetch_array($licorStmt, SQLSRV_FETCH_ASSOC);
        $total += $licor['precio'];
    }

    echo "<h3>Recibo</h3>";
    echo "<ul>";
    foreach ($selectedLicores as $licor_id) {
        $sql = "SELECT * FROM Licores WHERE id = ?";
        $params = array($licor_id);
        $licorStmt = sqlsrv_query($conn, $sql, $params);
        $licor = sqlsrv_fetch_array($licorStmt, SQLSRV_FETCH_ASSOC);
        echo "<li>" . $licor['nombre'] . " - $" . $licor['precio'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . $total . "</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente - Lista de Licores</title>
</head>
<body>

<h2>Selecciona Licores</h2>
<form action="" method="POST">
    <ul>
        <?php while ($licor = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) : ?>
            <li>
                <input type="checkbox" name="licores[]" value="<?php echo $licor['id']; ?>">
                <?php echo $licor['nombre'] . " - $" . $licor['precio']; ?>
            </li>
        <?php endwhile; ?>
    </ul>
    <button type="submit">Generar Recibo</button>
</form>

</body>
</html>

<?php
// Cerrar la conexión
sqlsrv_close($conn);
?>
