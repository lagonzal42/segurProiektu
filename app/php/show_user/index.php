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

$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Konexio errorea: " . $conn->connect_error);
}

if (isset($_GET['user'])) {
    $nan = $_GET['user'];

    // Query insegura (vulnerable a SQL Injection)
    $sql = "SELECT Erabiltzaile, Izen_Abizen, NAN, Telefonoa, Jaio_Data, Email FROM erabiltzaileak WHERE NAN = '$nan'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $user = null;
    }
} else {
    $user = null;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Erabiltzailearen Datuak</title>
    <style nonce="<?= htmlspecialchars($csp_nonce, ENT_QUOTES) ?>">
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #fcfcfc; 
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            padding: 40px 0 20px 0;
            font-weight: 300;
            font-size: 2.2em;
            border-bottom: 1px solid #eee;
            margin-bottom: 40px;
        }

        table {
            border-collapse: collapse;
            width: 90%;
            max-width: 500px;
            margin: 30px auto;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #eee;
        }
        th, td {
            border: none;
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f4f4f4;
        }
        th {
            background-color: #f8f8f8;
            color: #2c3e50;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9em;
            width: 35%;
        }
        td {
            width: 65%; 
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }

        form {
            background: none; 
            box-shadow: none;
            padding: 0;
            margin: 20px auto 40px auto;
            width: 90%;
            max-width: 500px;
            border: none;
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        button, .modify-btn {
            background: #2c3e50; 
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            font-size: 0.95em;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background 0.2s, transform 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            flex-shrink: 0; 
        }
        button:hover, .modify-btn:hover {
            background: #34495e;
            transform: translateY(-1px);
        }
        
        button[onclick*='window.location.href'] {
            background: #95a5a6; 
        }
        button[onclick*='window.location.href']:hover {
            background: #7f8c8d;
        }

    
        p[style*='color:red'] {
            background-color: #ffe6e6;
            border: 1px solid #cc3333;
            color: #661a1a !important;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            margin: 30px auto;
            max-width: 450px;
        }
    </style>
</head>
<body>
    <h2>Erabiltzailearen Informazioa</h2>

    <?php if ($user): ?>
        <table>
            <tr><th>Datua</th><td>Balorea</td></tr>
            <tr><th>Erabiltzaile</th><td><?= htmlspecialchars($user['Erabiltzaile']) ?></td></tr></td></tr>
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
                <button type="button" onclick="window.location.href='/'">Hasierara</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <p style="color:red;">❌ Erabiltzailea ez da aurkitu. Ziurtatu NAN-a onargarria dela.</p>
    <?php endif; ?>
</body>
</html>