<?php
session_start(); // Saioa hasi edo existitzen den saioa jarraitu

$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

// Konexioa sortu MySQL datu-basearekin
$conn = new mysqli($hostname, $username, $password, $db);

// Konexioan errorea gertatzen bada, exekuzioa eten eta errore mezua erakutsi
if ($conn->connect_error) {
    die("Konexio errorea: " . $conn->connect_error);
}

// URL bidez 'user' parametroa jaso bada, datu-basean bilatu
if (isset($_GET['user'])) {
    $nan = $_GET['user'];

    $sql = "SELECT Izen_Abizen, NAN, Telefonoa, Jaio_Data, Email FROM erabiltzaileak WHERE NAN = '$nan'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $user = null;
    }
} else {
    $user = null;
}

$conn->close(); // Konexioa itxi datu-basearekin
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Erabiltzailearen Datuak</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f7faff;
            margin: 0;
            padding: 0;
        }
        h1, h2, h3 {
            color: #2366a8;
            margin-top: 30px;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 30px auto 10px auto;
            background: #fff;
            box-shadow: 0 2px 8px rgba(35,102,168,0.08);
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            border: none;
            padding: 12px 16px;
            text-align: left;
        }
        th {
            background-color: #e3f0fc;
            color: #2366a8;
        }
        tr:nth-child(even) {
            background-color: #f2f7fc;
        }
        tr:hover {
            background-color: #d6eaff;
        }
        input, select {
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #bcd0e6;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        button, input[type="submit"] {
            background: linear-gradient(90deg, #2366a8 60%, #4fa3e3 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 1px 4px rgba(35,102,168,0.10);
            transition: background 0.2s;
        }
        button:hover, input[type="submit"]:hover {
            background: linear-gradient(90deg, #4fa3e3 60%, #2366a8 100%);
        }
        form {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(35,102,168,0.08);
            padding: 24px;
            margin: 30px auto;
            width: 90%;
            max-width: 500px;
        }
        .message {
            text-align: center;
            margin: 20px auto;
            font-size: 18px;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Erabiltzailearen Informazioa</h2>

    <?php if ($user): ?>
         <!-- Erabiltzailea aurkitu bada, bere datuak erakusten dira taulan -->
        <table>
            <tr><th>Datua</th><th>Balorea</th></tr>
            <tr><td>Izen Abizena</td><td><?= htmlspecialchars($user['Izen_Abizen']) ?></td></tr>
            <tr><td>NAN</td><td><?= htmlspecialchars($user['NAN']) ?></td></tr>
            <tr><td>Telefonoa</td><td><?= htmlspecialchars($user['Telefonoa']) ?></td></tr>
            <tr><td>Jaiotze Data</td><td><?= htmlspecialchars($user['Jaio_Data']) ?></td></tr>
            <tr><td>Email</td><td><?= htmlspecialchars($user['Email']) ?></td></tr>
        </table>
        <?php if (isset($_SESSION['nan'])): ?>
            <form action="/modify_user" method="get" style="display: flex; gap: 12px; justify-content: center;">
                <input type="hidden" name="user" value="<?= htmlspecialchars($_SESSION['nan']) ?>">
                <button type="submit" class="modify-btn">Aldatu Nire Datuak</button>
                <button type="button" class="modify-btn" onclick="window.location.href='/'">Hasierara</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <p style="color:red;">❌ Erabiltzailea ez da aurkitu. Ziurtatu NAN-a onargarria dela.</p>
    <?php endif; ?>
</body>
</html>
