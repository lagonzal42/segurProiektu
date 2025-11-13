<?php

// 1. Configuración de Seguridad y Sesión
// Se mantiene la política CSP y la configuración de sesión
header("Content-Security-Policy: default-src 'none'; script-src 'self'; style-src 'self'; img-src 'self'; connect-src 'self'; form-action 'self'; frame-ancestors 'none';");

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => true, // **RECOMENDADO:** Usar 'true' si el sitio usa HTTPS. Lo mantengo 'false' si está en HTTP para desarrollo local.
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();

// 2. Conexión a la Base de Datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    // Es mejor no mostrar errores detallados al usuario final, solo en entornos de desarrollo
    error_log("Konexio errorea: " . $conn->connect_error);
    die("Konexio errorea gertatu da.");
}

$user = null;

// 3. Consulta de Datos (Segurizado contra SQL Injection)
if (isset($_GET['user'])) {
    $nan = $_GET['user'];

    // Consulta Segura: Uso de Consultas Preparadas
    // 3.1. Preparar la sentencia
    $sql = "SELECT Erabiltzaile, Izen_Abizen, NAN, Telefonoa, Jaio_Data, Email FROM erabiltzaileak WHERE NAN = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("Errorea sententzia prestatzean: " . $conn->error);
    } else {
        // 3.2. Ligar el parámetro (s = string)
        $stmt->bind_param("s", $nan);

        // 3.3. Ejecutar la sentencia
        $stmt->execute();

        // 3.4. Obtener el resultado
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
        }

        // 3.5. Cerrar la sentencia
        $stmt->close();
    }
}

$conn->close();

// 4. Incluir la vista HTML
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Erabiltzailearen Datuak</title>
    <link rel="stylesheet" href="php/show_user/styles.css"> 
</head>
<body>
    <h2>Erabiltzailearen Informazioa</h2>

    <?php if ($user): ?>
        <table>
            <tr><th>Datua</th><td>Balorea</td></tr>
            <tr><th>Erabiltzaile</th><td><?= htmlspecialchars($user['Erabiltzaile']) ?></td></tr>
            <tr><th>Izen Abizena</th><td><?= htmlspecialchars($user['Izen_Abizen']) ?></td></tr>
            <tr><th>NAN</th><td><?= htmlspecialchars($user['NAN']) ?></td></tr>
            <tr><th>Telefonoa</th><td><?= htmlspecialchars($user['Telefonoa']) ?></td></tr>
            <tr><th>Jaiotze Data</th><td><?= htmlspecialchars($user['Jaio_Data']) ?></td></tr>
            <tr><th>Email</th><td><?= htmlspecialchars($user['Email']) ?></td></tr>
        </table>
        
        <?php if (isset($_SESSION['nan'])): ?>
            <form action="/modify_user" method="get">
                <input type="hidden" name="user" value="<?= htmlspecialchars($_SESSION['nan']) ?>">
                <button type="submit" class="modify-btn">Aldatu Nire Datuak</button>
                <button type="button" class="modify-btn" id="home-button">Hasierara</button>

        <?php endif; ?>
    <?php else: ?>
        <p class="error-message">❌ Erabiltzailea ez da aurkitu. Ziurtatu NAN-a onargarria dela.</p>
    <?php endif; ?>

    <script src="php/show_user/script.js"></script>
</body>
</html>