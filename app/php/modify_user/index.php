<?php
session_start();

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
    die("Error de conexión: " . $conn->connect_error);
}

$user = null;
$message = "";

if (isset($_GET['user'])) {
    $nan = $_GET['user'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['Izen_Abizen'] ?? '';
        $telefono = $_POST['Telefonoa'] ?? '';
        $fecha = $_POST['Jaio_Data'] ?? '';
        $email = $_POST['Email'] ?? '';

        $sql = "UPDATE erabiltzaileak SET Izen_Abizen = '$nombre', Telefonoa = '$telefono', Jaio_Data = '$fecha', Email = '$email' WHERE NAN = '$nan'";
        if ($conn->query($sql)) {
            header("Location: /show_user?user=" . urlencode($nan));
            exit();
        } else {
            $message = "<p style='color:red;'>❌ Errore bat gertatu da: " . htmlspecialchars($conn->error) . "</p>";
        }
    }

    $sql = "SELECT Izen_Abizen, NAN, Telefonoa, Jaio_Data, Email FROM erabiltzaileak WHERE NAN = '$nan'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Erabiltzailea Aldatu</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        form { width: 50%; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input { width: 100%; padding: 8px; margin-top: 4px; }
        button { margin-top: 15px; padding: 10px 15px; font-size: 16px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Erabiltzailearen Datuak Aldatu</h2>
    <?= $message ?>

    <?php if ($user): ?>
        <form id="user_modify_form" method="post">
            <label for="Izen_Abizen">Izen Abizena</label>
            <input type="text" id="Izen_Abizen" name="Izen_Abizen" value="<?= htmlspecialchars($user['Izen_Abizen']) ?>" required>

            <label for="Telefonoa">Telefonoa</label>
            <input type="text" id="Telefonoa" name="Telefonoa" value="<?= htmlspecialchars($user['Telefonoa']) ?>">

            <label for="Jaio_Data">Jaiotze Data</label>
            <input type="date" id="Jaio_Data" name="Jaio_Data" value="<?= htmlspecialchars($user['Jaio_Data']) ?>">

            <label for="Email">Email</label>
            <input type="email" id="Email" name="Email" value="<?= htmlspecialchars($user['Email']) ?>">

            <button type="submit" id="user_modify_submit">Aldaketak Gorde</button>
        </form>
    <?php else: ?>
        <p style="color:red;">❌ Erabiltzailea ez da aurkitu. Ziurtatu NAN-a onargarria dela.</p>
    <?php endif; ?>
</body>
</html>
