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
    <h1>Erabiltzailearen Datuak Aldatu</h1>
    <?= $message ?>

    <?php if ($user): ?>
        <form id="user_modify_form" method="post">
            <label for="Izen_Abizen">Izen Abizena</label>
            <input type="text" id="Izen_Abizen" name="Izen_Abizen" value="<?= htmlspecialchars($user['Izen_Abizen']) ?>" required>

            <label for="Telefonoa">Telefonoa</label>
            <input type="text" id="Telefonoa" name="Telefonoa" value="<?= htmlspecialchars($user['Telefonoa']) ?>">

            <label for="Jaio_Data">Jaiotze Data</label>
            <input type="text" id="Jaio_Data" name="Jaio_Data" value="<?= htmlspecialchars($user['Jaio_Data']) ?>">

            <label for="Email">Email</label>
            <input type="email" id="Email" name="Email" value="<?= htmlspecialchars($user['Email']) ?>">

            <button type="submit" id="user_modify_submit">Aldaketak Gorde</button>
            <button type="button" class="modify-btn" onclick="window.location.href='/'">Hasierara</button>
        </form>
    <?php else: ?>
        <p style="color:red;">❌ Erabiltzailea ez da aurkitu. Ziurtatu NAN-a onargarria dela.</p>
    <?php endif; ?>
</body>
</html>
