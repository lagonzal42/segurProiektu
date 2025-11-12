<?php
session_start();
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

if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_time']) || ($_SESSION['csrf_time'] + 3600) < time()) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_time'] = time();
}

// 2. Irakurketaren, edizioaren eta eguneratzearen logika 
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Formularioa (POST) bidali bada, eguneratu 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $posted_token = $_POST['csrf_token'] ?? '';
        if (empty($posted_token) || !hash_equals($_SESSION['csrf_token'], $posted_token)) {
            http_response_code(403);
            echo "<p style='color:red;'>CSRF token falta da edo ez da egokia.</p>";
            exit();
        }

        $izena = $_POST['Izena'] ?? '';
        $jatorria = $_POST['Jatorria'] ?? '';
        $kolorea = $_POST['Kolorea'] ?? '';
        $denbora = $_POST['Egozketa_denb_min'] ?? '';

        // Query ez-segurua (SQL Injectionekiko kaltebera) 
        $sql = "UPDATE babarrunak SET Izena = '$izena', Jatorria = '$jatorria', Kolorea = '$kolorea', Egozketa_denb_min = '$denbora' WHERE id = $id";
        if ($conn->query($sql)) {
            $message = "<p style='color:green;'>Datuak eguneratu dira.</p>";
            
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_time'] = time();
        } else {
            $message = "<p style='color:red;'>Errore bat gertatu da: " . htmlspecialchars($conn->error) . "</p>";
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
        h3 {
            color: #2c3e50;
            margin: 30px auto 15px auto;
            max-width: 800px;
            padding: 0 20px;
            font-weight: 500;
            font-size: 1.4em;
        }
        hr {
            border: 0;
            border-top: 1px solid #eee;
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
        }

        form {
            background: #ffffff;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin: 20px auto;
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
        input[type="text"], input[type="number"] {
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid #bdc3c7;
            margin-bottom: 0;
            width: 100%;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.2s;
        }
        input:focus {
            border-color: #2c3e50;
            outline: none;
        }

        .button-container {
            max-width: 800px;
            margin: 20px auto 40px auto;
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 0 20px;
        }
        button, a.button {
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
            text-decoration: none; 
            display: inline-block;
            text-align: center;
            white-space: nowrap;
            margin-top: 5px; 
        }
        button:hover, a.button:hover {
            background: #34495e;
            transform: translateY(-1px);
        }
        .modify-btn {
            background: #95a5a6; 
        }
        .modify-btn:hover {
            background: #7f8c8d;
        }
        
        .action-button {
            background: #3498db; 
            padding: 5px 10px;
            font-size: 0.85em;
            text-transform: none;
            letter-spacing: normal;
        }
        .action-button:hover {
            background: #2980b9;
        }

        p[style*='color:green'], p[style*='color:red'] {
            text-align: center;
            margin: 20px auto;
            font-size: 1.1em;
            padding: 10px 20px;
            border-radius: 4px;
            max-width: 450px;
        }
        p[style*='color:green'] {
            background-color: #e6ffee;
            border: 1px solid #33cc33;
            color: #1a661a !important;
        }
        p[style*='color:red'] {
            background-color: #ffe6e6;
            border: 1px solid #cc3333;
            color: #661a1a !important;
        }

        table {
            border-collapse: collapse;
            width: 90%;
            max-width: 800px;
            margin: 30px auto 40px auto;
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
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        tr:hover {
            background-color: #f0f4f7;
        }
    </style>
</head>
<body>
    <h1>Babarrunak Kudeaketa</h1>
    
    <div class="button-container">
        <button type="button" class="modify-btn" onclick="window.location.href='/'">Hasierara</button>
    </div>
    
    <?= $message ?>
    
    <?php if ($user): ?>
        <hr>
        <h3>Aldatu Babarruna: ID #<?= htmlspecialchars($user['id']) ?></h3>
        
        <form method="POST" action="?id=<?= htmlspecialchars($user['id']) ?>">

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES) ?>">

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
                <input type="number" id="Egozketa_denb_min" name="Egozketa_denb_min" value="<?= htmlspecialchars($user['Egozketa_denb_min']) ?>" required>
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
            <th>Ekintza</th>
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
                        <a href="?id=<?= $row['id'] ?>" class="button action-button">
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