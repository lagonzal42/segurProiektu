<?php
// ===============================================
// SECURITY HEADERS & SESSION MANAGEMENT
// ===============================================

// Content Security Policy: Only allows resources from the same origin.
// This is respected by linking external CSS/JS files instead of using inline code.
header("Content-Security-Policy: default-src 'none'; script-src 'self'; style-src 'self'; img-src 'self'; connect-src 'self'; form-action 'self'; frame-ancestors 'none';");


// Session cookies security
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => true, // Should be true in production with HTTPS
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start(); // Start the session

// Remove common identifying headers
header_remove("X-Powered-By");
header("Server: SegurServer");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// ===============================================
// DATABASE CONNECTION (Configuration remains the same)
// ===============================================
$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

$conn = mysqli_connect($hostname, $username, $password, $db);
if (!$conn) {
    // In a real application, logging this error is better than exposing details
    die("Database connection failed: " . mysqli_connect_error());
}

// ===============================================
// CSRF TOKEN GENERATION AND VALIDATION
// ===============================================

// Regenerate token if it's missing or expired (1 hour lifetime)
if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_time']) || ($_SESSION['csrf_time'] + 3600) < time()) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_time'] = time();
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $posted_token = $_POST['csrf_token'] ?? '';
    $user = $_POST['user'] ?? '';
    $pas = $_POST['pas'] ?? '';

    // 1. CSRF Check (using hash_equals for timing attack prevention)
    if(empty($posted_token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $posted_token)) {
        $error_message = "CSRF token-a ez da baliozkoa.";
    } else {
        // 2. SQL Injection FIX: Use prepared statements
        $stmt = mysqli_prepare($conn, "SELECT NAN, Erabiltzaile FROM erabiltzaileak WHERE Erabiltzaile = ? AND Pasahitza = ?");
        
        // Bind parameters 'ss' means two string parameters
        mysqli_stmt_bind_param($stmt, "ss", $user, $pas); 
        
        // Execute the statement
        mysqli_stmt_execute($stmt);
        
        // Get the result
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) > 0) {
            $row = mysqli_fetch_assoc($resultado);

            // Authentication success
            session_regenerate_id(true); // Session fixation prevention
            $_SESSION['nan'] = $row['NAN'];
            $_SESSION['user'] = $row['Erabiltzaile'];

            // Regenerate CSRF token after successful login
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_time'] = time();

            // Redirect (always exit after header redirect)
            header("Location: /show_user?user=" . urlencode($row['NAN']));
            exit();
        } else {
            $error_message = "Datu okerrak.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identifikazioa</title>
    <!-- External CSS link for CSP compliance -->
    <link rel="stylesheet" href="php/login/styles.css"> 
        <script src="php/login/script.js"></script>
</head>
<body>
    <h1>Erabiltzaileen identifikazioa</h1>

    <?php 
    // Display error message using a CSS class instead of inline style
    if (!empty($error_message)) {
        echo '<p class="error-message">' . htmlspecialchars($error_message) . '</p>';
    }
    ?>

    <form id="login_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        
        <!-- CSRF Token (always use htmlspecialchars for output) -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">

        <label for="user">ERABILTZAILEA:</label>
        <input type="text" id="user" name="user" placeholder="Erabiltzaile" required>

        <label for="pas">PASAHITZA:</label>
        <input type="password" id="pas" name="pas" placeholder="Pasahitza" required>
        
        <div class="button-container">
            <!-- Removed inline JS (onclick) for CSP compliance. Logic moved to login.js -->
            <button id="login_submit" type="submit">Sartu</button>
            <button id="login_ezabatu" type="reset">Ezabatu</button>
            <button type="button" id="hasiera_btn" class="modify-btn">Hasierara</button>
        </div>
    </form>

    <!-- External JS link for CSP compliance -->
    <script src="php/login/login.js" defer></script>
</body>
</html>