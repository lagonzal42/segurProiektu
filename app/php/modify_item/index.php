<?php
// 1. Datu-basearen konfigurazioa 
$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

// Datu-basearen konexioa
$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    // Jardunbide egokia da ekoizpen-errorearen xehetasunik ez adieraztea 
    die("Error de conexión: " . $conn->connect_error);
}

$user = null;
$message = "";

// 2. Irakurketaren, edizioaren eta eguneratzearen logika 
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Formularioa (POST) bidali bada, eguneratu 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $izena = $_POST['Izena'] ?? '';
        $jatorria = $_POST['Jatorria'] ?? '';
        $kolorea = $_POST['Kolorea'] ?? '';
        $denbora = $_POST['Egozketa_denb_min'] ?? '';

        // Query ez-segurua (SQL Injectionekiko kaltebera) 
        $sql = "UPDATE babarrunak SET Izena = '$izena', Jatorria = '$jatorria', Kolorea = '$kolorea', Egozketa_denb_min = '$denbora' WHERE id = $id";
        if ($conn->query($sql)) {
            $message = "<p style='color:green;'>✅ Datuak eguneratu dira.</p>";
        } else {
            $message = "<p style='color:red;'>❌ Errore bat gertatu da: " . htmlspecialchars($conn->error) . "</p>";
        }
    }

    // Babarrunen egungo datuak lortzea, inprimakian erakusteko (ez da segurua) 
    $sql = "SELECT id, Izena, Jatorria, Kolorea, Egozketa_denb_min FROM babarrunak WHERE id = $id";
    $result_user = $conn->query($sql);
    if ($result_user && $result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
    }
}

// Taularako datu guztiak eskuratu (beti exekutatzen da) 
$sql = "SELECT * FROM babarrunak ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Babarrunak Kudeatu</title>
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
    <h1>Babarrunak Kudeaketa</h1>
    <button type="button" class="modify-btn" onclick="window.location.href='/'">Hasierara</button>
    <?= $message ?>
    
    <?php if ($user): ?>
        <hr>
        <h3>Aldatu Babarruna: ID #<?= htmlspecialchars($user['id']) ?></h3>
        
        <form method="POST" action="?id=<?= htmlspecialchars($user['id']) ?>">
            <div>
                <label for="Izena">Izena:</label>
                <input type="text" id="Izena" name="Izena" value="<?= htmlspecialchars($user['Izena']) ?>" required>
            </div>
            <div>
                <label for="Jatorria">Jatorria:</label>
                <input type="text" id="Jatorria" name="Jatorria" value="<?= htmlspecialchars($user['Jatorria']) ?>" required>
            </div>
            <div>
                <label for="Kolorea">Kolorea:</label>
                <input type="text" id="Kolorea" name="Kolorea" value="<?= htmlspecialchars($user['Kolorea']) ?>" required>
            </div>
            <div>
                <label for="Egozketa_denb_min">Egozketa denbora (min):</label>
                <input type="text" id="Egozketa_denb_min" name="Egozketa_denb_min" value="<?= htmlspecialchars($user['Egozketa_denb_min']) ?>" required>
            </div>
            <br>
            <button type="submit">Gorde Aldaketak</button>
        </form>
        <hr>
    <?php endif; ?>

    <h3>Babarrun Zerrenda</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Izena</th>
            <th>Jatorria</th>
            <th>Kolorea</th>
            <th>Egozketa denbora</th>
            <th>Aldatu</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['Izena']) ?></td>
                    <td><?= htmlspecialchars($row['Jatorria']) ?></td>
                    <td><?= htmlspecialchars($row['Kolorea']) ?></td>
                    <td><?= htmlspecialchars($row['Egozketa_denb_min']) ?></td>
                    <td>
                        <a href="?id=<?= $row['id'] ?>">
                           Aldatu
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Ez dago produkturik.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php
// 5. Konexioa itxi
$conn->close();
?>