<?php

// 1. CSP Mejorado: Permite recursos de estilo y script de origen propio ('self').
header("Content-Security-Policy: default-src 'none'; script-src 'self'; style-src 'self'; img-src 'self'; connect-src 'self'; form-action 'self'; frame-ancestors 'none';");

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false, // Idealmente 'true' si usas HTTPS
    'httponly' => true,
    'samesite' => 'Strict',
]);

session_start(); // Asegúrate de iniciar la sesión

// Verificación de autenticación y autorización
// Usamos ?? '' para asegurar que $nan nunca sea null si se usa.
$nan = $_GET['user'] ?? '';
if (!isset($_SESSION['nan']) || $_SESSION['nan'] !== $nan || empty($nan)) {
    header("Location: /login");
    exit();
}

$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

// Conexión a la base de datos
$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    // Error de conexión, no revela detalles sensibles al usuario
    error_log("Database connection error: " . $conn->connect_error);
    die("Error de conexión interno. Inténtalo más tarde.");
}

$user = null;
$message = "";

// --- Lógica de ACTUALIZACIÓN (POST) - SEGURA CONTRA SQLi ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge y sanea los datos (aunque las sentencias preparadas se encargan de la inyección)
    $erabiltzaile = $_POST['Erabiltzaile'] ?? '';
    $nombre = $_POST['Izen_Abizen'] ?? '';
    $telefono = $_POST['Telefonoa'] ?? '';
    $fecha = $_POST['Jaio_Data'] ?? '';
    $email = $_POST['Email'] ?? '';

    // Sentencia preparada para UPDATE
    $stmt = $conn->prepare("UPDATE erabiltzaileak SET Erabiltzaile = ?, Izen_Abizen = ?, Telefonoa = ?, Jaio_Data = ?, Email = ? WHERE NAN = ?");

    if ($stmt) {
        // "ssssss" indica que todos los parámetros son de tipo string
        $stmt->bind_param("ssssss", $erabiltzaile, $nombre, $telefono, $fecha, $email, $nan);

        if ($stmt->execute()) {
            $message = "<p class='success-message'>✅ Aldaketak ondo gorde dira.</p>";
            // No redirigimos inmediatamente para que el usuario pueda ver el mensaje de éxito
        } else {
            $message = "<p class='error-message'>❌ Errore bat gertatu da (Batzuetan NAN-a ezin da aldatu).</p>";
            error_log("SQL Update Error: " . $stmt->error);
        }
        $stmt->close();
    } else {
        $message = "<p class='error-message'>❌ Errore bat gertatu da (Prestaketa huts egin du).</p>";
        error_log("SQL Prepare Error: " . $conn->error);
    }
}

// --- Lógica de SELECCIÓN (GET) - SEGURA CONTRA SQLi ---

// Sentencia preparada para SELECT
$stmt = $conn->prepare("SELECT Erabiltzaile, Izen_Abizen, NAN, Telefonoa, Jaio_Data, Email FROM erabiltzaileak WHERE NAN = ?");

if ($stmt) {
    // "s" indica que el parámetro es de tipo string (el NAN)
    $stmt->bind_param("s", $nan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
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
    <title>Erabiltzailea Aldatu</title>
    <!-- CSS externo: Soluciona el problema de 'style-src inline' en CSP -->
    <link rel="stylesheet" href="php/modify_user/styles.css">
    <!-- JS externo: Mantenemos el archivo vacío por si se necesita lógica futura -->
    <script src="php/modify_user/script.js" defer></script>
</head>
<body>
    <h1>Erabiltzailearen Datuak Aldatu</h1>
    <?= $message ?>

    <?php if ($user): ?>
        <form id="user_modify_form" method="post">

            <label for="Erabiltzaile">Erabiltzailea</label>
            <!-- Uso de htmlspecialchars() para prevenir XSS en la salida de datos -->
            <input type="text" id="Erabiltzaile" name="Erabiltzaile" value="<?= htmlspecialchars($user['Erabiltzaile']) ?>" required>

            <label for="Izen_Abizen">Izen Abizena</label>
            <input type="text" id="Izen_Abizen" name="Izen_Abizen" value="<?= htmlspecialchars($user['Izen_Abizen']) ?>" required>

            <label for="Telefonoa">Telefonoa</label>
            <input type="text" id="Telefonoa" name="Telefonoa" value="<?= htmlspecialchars($user['Telefonoa'] ?? '') ?>">

            <label for="Jaio_Data">Jaiotze Data</label>
            <input type="date" id="Jaio_Data" name="Jaio_Data" value="<?= htmlspecialchars($user['Jaio_Data'] ?? '') ?>">

            <label for="Email">Email</label>
            <input type="email" id="Email" name="Email" value="<?= htmlspecialchars($user['Email'] ?? '') ?>">

            <div class="button-group">
                <button type="submit" id="user_modify_submit">Aldaketak Gorde</button>
                <button type="button" class="modify-btn" id="home-button">Hasierara</button>
            </div>
        </form>
    <?php else: ?>
        <!-- Uso de la clase CSS para el error, evitando style en línea -->
        <p class="error-message user-not-found">❌ Erabiltzailea ez da aurkitu. Ziurtatu NAN-a onargarria dela.</p>
    <?php endif; ?>
</body>
</html>