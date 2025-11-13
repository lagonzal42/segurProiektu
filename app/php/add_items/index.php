<?php

// Nota: La cabecera X-XSS-Protection es obsoleta y se ha eliminado.
header("Content-Security-Policy: default-src 'none'; script-src 'self'; style-src 'self'; img-src 'self'; connect-src 'self'; form-action 'self'; frame-ancestors 'none';");

session_start();

// Cabeceras de seguridad
header_remove("X-Powered-By");
header("Server: SegurServer");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");

$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

// Crear conexión
$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    // Evitar exponer detalles de error en producción
    error_log("Error de conexión a la DB: " . $conn->connect_error);
    die("Error de conexión. Inténtalo más tarde."); 
}

// Lógica de generación y expiración de token CSRF
$csrf_validity = 3600; // 1 hora
if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_time']) || ($_SESSION['csrf_time'] + $csrf_validity) < time()) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_time'] = time();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Validación de token CSRF
    $posted_token = $_POST['csrf_token'] ?? '';
    if (empty($posted_token) || !hash_equals($_SESSION['csrf_token'], $posted_token)) {
        http_response_code(403);
        echo "<p class='error-message'>CSRF token falta da edo ez da egokia.</p>";
        exit();
    }

    $izena = trim($_POST["izena"] ?? '');
    $jatorria = trim($_POST["jatorria"] ?? '');
    $kolorea = trim($_POST["kolorea"] ?? '');
    $denbora = trim($_POST["denbora"] ?? '');

    if ($izena !== '' && $jatorria !== '' && $kolorea !== '' && $denbora !== '') {
        
        // 2. Sentencia preparada para prevenir SQL Injection
        $sql = "INSERT INTO babarrunak (Izena, Jatorria, Kolorea, Egozketa_denb_min) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            echo "<p class='error-message'>Errore bat gertatu da sententzia prestatzean.</p>";
            error_log("Error al preparar la sentencia: " . $conn->error);
        } else {
            // "sssi" -> string, string, string, integer
            $stmt->bind_param("sssi", $izena, $jatorria, $kolorea, $denbora);
            
            if ($stmt->execute()) {
                echo "<p class='success-message'>Babarruna ondo gehitu da!</p>";
            
                // Regenerar token CSRF tras una acción exitosa
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $_SESSION['csrf_time'] = time();
            } else {
                echo "<p class='error-message'>Errore bat gertatu da: " . htmlspecialchars($stmt->error) . "</p>";
            }
            $stmt->close();
        }
    } else {
        echo "<p class='error-message'>Datu guztiak bete behar dira.</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Babarruna Gehitu</title>
    <link rel="stylesheet" href="php/add_items/styles.css">
    <script src="php/add_items/script.js" defer></script>
</head>
<body>
    <h1>Babarruna gehitu</h1>

    <?php
    echo '<div class="message">';
    ?>

    <form id="item_add_form" method="POST" action="add_items">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES) ?>">
        
        <label for="izena">Izena:</label>
        <input type="text" id="izena" name="izena" required>

        <label for="jatorria">Jatorria:</label>
        <input type="text" id="jatorria" name="jatorria" required>

        <label for="kolorea">Kolorea:</label>
        <input type="text" id="kolorea" name="kolorea" required>

        <label for="denbora">Denbora:</label>
        <input type="number" min="0" step="1" id="denbora" name="denbora" required>

        <button type="submit" id="item_add_submit">Gehitu</button>
        <button type="button" id="hasiera_btn" class="modify-btn">Hasierara</button>
    </form>

<?php
echo '</div>'; // Cierre del contenedor de mensaje
$conn->close();
?>