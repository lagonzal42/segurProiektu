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


// Comprobación de sesión y usuario
if (!isset($_SESSION['nan']) || $_SESSION['nan'] !== ($_GET['user'] ?? null)) {
    header("Location: /login");
    exit();
}

$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión.");
}

$user = null;
$message = "";

if (isset($_GET['user'])) {
    $nan = $_GET['user'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $erabiltzaile = $_POST['Erabiltzaile'] ?? '';
        $nombre = $_POST['Izen_Abizen'] ?? '';
        $telefono = $_POST['Telefonoa'] ?? '';
        $fecha = $_POST['Jaio_Data'] ?? '';
        $email = $_POST['Email'] ?? '';

        // ✅ Consulta preparada segura para UPDATE
        $stmt = $conn->prepare("
            UPDATE erabiltzaileak 
            SET Erabiltzaile = ?, Izen_Abizen = ?, Telefonoa = ?, Jaio_Data = ?, Email = ? 
            WHERE NAN = ?
        ");
        if ($stmt) {
            $stmt->bind_param("ssssss", $erabiltzaile, $nombre, $telefono, $fecha, $email, $nan);
            if ($stmt->execute()) {
                // Redirigir tras actualizar con éxito
                header("Location: /show_user?user=" . urlencode($nan));
                exit();
            } else {
                $message = "<p style='color:red;'>❌ Errore bat gertatu da eguneratzean.</p>";
            }
            $stmt->close();
        } else {
            $message = "<p style='color:red;'>❌ Ezin izan da adierazpena prestatu.</p>";
        }
    }

    // ✅ Consulta preparada segura para SELECT
    $stmt = $conn->prepare("
        SELECT Erabiltzaile, Izen_Abizen, NAN, Telefonoa, Jaio_Data, Email 
        FROM erabiltzaileak 
        WHERE NAN = ?
    ");
    if ($stmt) {
        $stmt->bind_param("s", $nan);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Erabiltzailea Aldatu</title>
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
            max-width: 450px;
            border: 1px solid #eee;
        }
        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
            font-size: 0.95em;
        }
        input[type="text"], input[type="email"], input[type="date"], input[type="submit"] {
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid #bdc3c7;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.2s;
        }
        input:focus {
            border-color: #2c3e50;
            outline: none;
        }

        .button-group {
            display: flex;
            justify-content: space-between; 
            gap: 10px;
            margin-top: 20px;
        }
        button {
            background: #2c3e50; 
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 12px 15px;
            font-size: 0.95em;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background 0.2s, transform 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            flex-grow: 1; 
        }
        button:hover {
            background: #34495e;
            transform: translateY(-1px);
        }
        .modify-btn {
            background: #95a5a6; 
            flex-grow: 0; 
        }
        .modify-btn:hover {
            background: #7f8c8d;
        }
        
        p[style*='color:green'], p[style*='color:red'] {
            text-align: center;
            margin: 20px auto;
            font-size: 1.1em;
            padding: 10px 20px;
            border-radius: 4px;
            max-width: 450px;
        }
        p[style*='color:red'] {
            background-color: #ffe6e6;
            border: 1px solid #cc3333;
            color: #661a1a !important;
        }
        
        table, th, td, tr {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Erabiltzailearen Datuak Aldatu</h1>
    <?= $message ?>

    <?php if ($user): ?>
        <form id="user_modify_form" method="post">
            <label for="Erabiltzaile">Erabiltzailea</label>
            <input type="text" id="Erabiltzaile" name="Erabiltzaile" value="<?= htmlspecialchars($user['Erabiltzaile']) ?>" required>

            <label for="Izen_Abizen">Izen Abizena</label>
            <input type="text" id="Izen_Abizen" name="Izen_Abizen" value="<?= htmlspecialchars($user['Izen_Abizen']) ?>" required>

            <label for="Telefonoa">Telefonoa</label>
            <input type="text" id="Telefonoa" name="Telefonoa" value="<?= htmlspecialchars($user['Telefonoa']) ?>">

            <label for="Jaio_Data">Jaiotze Data</label>
            <input type="date" id="Jaio_Data" name="Jaio_Data" value="<?= htmlspecialchars($user['Jaio_Data']) ?>">

            <label for="Email">Email</label>
            <input type="email" id="Email" name="Email" value="<?= htmlspecialchars($user['Email']) ?>">

            <div class="button-group">
                <button type="submit" id="user_modify_submit">Aldaketak Gorde</button>
                <a href="/", style="text-decoration:none;">
                    <button type="button" class="modify-btn">Hasierara</button>
                </a>
            </div>
        </form>
    <?php else: ?>
        <p style="color:red;">Erabiltzailea ez da aurkitu. Ziurtatu NAN-a onargarria dela.</p>
    <?php endif; ?>
</body>
</html>
