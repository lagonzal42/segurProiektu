<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();

$csp_nonce = base64_encode(random_bytes(16));

header_remove("X-Powered-By");
header("Server: SegurServer");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'nonce-$csp_nonce'; img-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self';");


// Conexión a la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Generar CSRF token si no existe o expiró
if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_time']) || ($_SESSION['csrf_time'] + 3600) < time()) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_time'] = time();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $posted_token = $_POST['csrf_token'] ?? '';

    if (empty($posted_token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $posted_token)) {
        http_response_code(403);
        die("<p style='color:red;'>CSRF token-a ez da baliozkoa.</p>");
    }

    $user = trim($_POST['user'] ?? '');
    $pas = trim($_POST['pas'] ?? '');

    if ($user === '' || $pas === '') {
        $message = "<p style='color:red;'>Eremu guztiak bete behar dira.</p>";
    } else {
        // --- CONSULTA SEGURA PREPARADA ---
        $stmt = $conn->prepare("SELECT NAN, Erabiltzaile, Pasahitza FROM erabiltzaileak WHERE Erabiltzaile = ?");
        if ($stmt) {
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                // --- Verificación segura de contraseña ---
                if (password_verify($pas, $row['Pasahitza'])) {
                    session_regenerate_id(true);
                    $_SESSION['nan'] = $row['NAN'];
                    $_SESSION['user'] = $row['Erabiltzaile'];

                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    $_SESSION['csrf_time'] = time();

                    header("Location: /show_user?user=" . urlencode($row['NAN']));
                    exit();
                } else {
                    $message = "<p style='color:red;'>Datu okerrak.</p>";
                }
            } else {
                $message = "<p style='color:red;'>Datu okerrak.</p>";
            }
            $stmt->close();
        } else {
            $message = "<p style='color:red;'>Errorea kontsulta prestatzean: " . htmlspecialchars($conn->error) . "</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="eu">
<head>
  <meta charset="UTF-8">
  <title>Identifikazioa</title>
  <style nonce="<?= htmlspecialchars($csp_nonce, ENT_QUOTES) ?>">
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #fcfcfc; 
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        h1 {
            color: #2c3e50; 
            text-align: center;
            padding: 40px 0 20px 0;
            font-weight: 300;
            font-size: 2.2em;
            border-bottom: 1px solid #eee; 
            margin-bottom: 40px;
        }
        form {
            background: #ffffff;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin: 30px auto;
            width: 90%;
            max-width: 400px;
            border: 1px solid #eee;
        }
        input[type="text"], input[type="password"] {
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid #bdc3c7; 
            margin-bottom: 20px;
            width: 100%;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.2s;
            display: block; 
        }
        input:focus {
            border-color: #2c3e50; 
            outline: none;
        }
        .button-container {
            display: flex;
            justify-content: flex-start; 
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        button {
            background: #2c3e50; 
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            font-size: 0.95em;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background 0.2s, transform 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: auto; 
            margin-bottom: 5px; 
        }
        button:hover {
            background: #34495e;
            transform: translateY(-1px);
        }
        #login_ezabatu {
            background: #95a5a6; 
        }
        #login_ezabatu:hover {
            background: #7f8c8d;
        }
        .modify-btn {
            background: #3498db; 
        }
        .modify-btn:hover {
            background: #2980b9;
        }
        p[style*='color:red'] {
            background-color: #ffe6e6;
            border: 1px solid #cc3333;
            color: #661a1a !important;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            margin-top: 15px;
        }
        table, th, td, tr {
            display: none;
        }
    </style>
</head>

<script type="text/javascript" src="/php/login/login.js"></script>

<body>
  <h1>Erabiltzaileen identifikazioa</h1>

  <?= $message ?>

  <form id="login_form" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES) ?>">

    <label for="user">ERABILTZAILEA:</label>
    <input type="text" id="user" name="user" placeholder="Erabiltzaile" required>

    <label for="pas">PASAHITZA:</label>
    <input type="password" id="pas" name="pas" placeholder="Pasahitza" required>
    
    <div class="button-container">
        <button id="login_submit" type="submit" onclick="datuakegiaztatu()">Sartu</button>
        <button id="login_ezabatu" type="reset">Ezabatu</button>
        <button type="button" class="modify-btn" onclick="window.location.href='/'">Hasierara</button>
    </div>
  </form>
</body>
</html>
