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
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 50%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .modify-btn { margin-top: 20px; padding: 10px 15px; font-size: 16px; cursor: pointer; }
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
            <form action="/modify_user" method="get">
                <input type="hidden" name="user" value="<?= htmlspecialchars($_SESSION['nan']) ?>">
                <button type="submit" class="modify-btn">Aldatu Nire Datuak</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <p style="color:red;">❌ Erabiltzailea ez da aurkitu. Ziurtatu NAN-a onargarria dela.</p>
    <?php endif; ?>
</body>
</html>
