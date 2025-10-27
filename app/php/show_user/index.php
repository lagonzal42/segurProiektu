<?php
$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

// Conexión a la base de datos
$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se ha pasado el parámetro 'user'
if (isset($_GET['user'])) {
    $nan = $_GET['user'];

    // Preparar la consulta segura
    $stmt = $conn->prepare("SELECT Izen_Abizen, NAN, Telefonoa, Jaio_Data, Email FROM erabiltzaileak WHERE NAN = ?");
    $stmt->bind_param("s", $nan);
    $stmt->execute();
    $result = $stmt->get_result();

    // Obtener datos del usuario
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $user = null;
    }

    $stmt->close();
} else {
    $user = null;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Datos del usuario</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 50%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Información del usuario</h2>

    <?php if ($user): ?>
        <table>
            <tr><th>Campo</th><th>Valor</th></tr>
            <tr><td>Nombre completo</td><td><?= htmlspecialchars($user['Izen_Abizen']) ?></td></tr>
            <tr><td>NAN</td><td><?= htmlspecialchars($user['NAN']) ?></td></tr>
            <tr><td>Teléfono</td><td><?= htmlspecialchars($user['Telefonoa']) ?></td></tr>
            <tr><td>Fecha de nacimiento</td><td><?= htmlspecialchars($user['Jaio_Data']) ?></td></tr>
            <tr><td>Email</td><td><?= htmlspecialchars($user['Email']) ?></td></tr>
        </table>
    <?php else: ?>
        <p style="color:red;">❌ Usuario no encontrado. Verifica el NAN proporcionado.</p>
    <?php endif; ?>
</body>
</html>
