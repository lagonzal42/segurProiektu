<?php
// 1. Datu-basearen konfigurazioa 
$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

// Datu-basearen konexioa
$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$user = null;
$message = "";

// 2. Irakurketaren, edizioaren eta eguneratzearen logika 
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query ez-segurua (SQL Injectionekiko kaltebera) 
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
    <title>Babarrunak Ikusi</title>
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
            max-width: 450px; 
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
            background-color: #f7f7f7; 
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-bottom: 0;
            width: 100%;
            box-sizing: border-box;
            font-size: 1em;
            color: #333;
        }
        
        .button-container {
            max-width: 800px;
            margin: 0 auto 40px auto;
            padding: 0 20px;
            text-align: center;
        }
        button {
            background: #95a5a6;
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
            white-space: nowrap;
        }
        button:hover {
            background: #7f8c8d;
            transform: translateY(-1px);
        }
        a.icon-button {
            display: inline-flex; 
            align-items: center;
            justify-content: center;
            width: 30px; 
            height: 30px;
            border-radius: 50%; 
            background-color: #3498db; 
            color: #fff;
            text-decoration: none;
            transition: background-color 0.2s, transform 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            font-size: 1.1em; 
        }
        a.icon-button:hover {
            background-color: #2980b9;
            transform: scale(1.05); 
        }
        .icon-button svg {
            width: 16px;
            height: 16px;
            fill: currentColor; 
            vertical-align: middle;
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
        
        .modify-btn { display: none; }
        .message { display: none; }
    </style>
</head>
<body>
    <h1>Babarrunak</h1>

    <div class="button-container">
        <button type="button" onclick="window.location.href='/'">Hasierara</button>
    </div>

    <?= $message ?>
    
    <?php if ($user): ?>
        <hr>
        <h3>Babarruna: ID #<?= htmlspecialchars($user['id']) ?></h3>
        
        <form>
            <div>
                <label for="Izena">Izena:</label>
                <input type="text" id="Izena" name="Izena" value="<?= htmlspecialchars($user['Izena']) ?>" readonly>
            </div>
            <div>
                <label for="Jatorria">Jatorria:</label>
                <input type="text" id="Jatorria" name="Jatorria" value="<?= htmlspecialchars($user['Jatorria']) ?>" readonly>
            </div>
            <div>
                <label for="Kolorea">Kolorea:</label>
                <input type="text" id="Kolorea" name="Kolorea" value="<?= htmlspecialchars($user['Kolorea']) ?>" readonly>
            </div>
            <div>
                <label for="Egozketa_denb_min">Egozketa denbora (min):</label>
                <input type="number" id="Egozketa_denb_min" name="Egozketa_denb_min" value="<?= htmlspecialchars($user['Egozketa_denb_min']) ?>" readonly>
            </div>
        </form>
        <hr>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Izena</th>
            <th>Ekintza</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['Izena']) ?></td>
                    <td>
                        <a href="?id=<?= $row['id'] ?>" class="icon-button" title="Ikusi xehetasunak">
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 144a110.5 110.5 0 0 0 -3.4 220.1C260 384 274.6 384 288 384c70.7 0 128-57.3 128-128s-57.3-128-128-128zm0 224a96 96 0 1 1 0-192 96 96 0 1 1 0 192zM288 0C134.5 0 8 119.5 8 256s126.5 256 280 256 272-119.5 272-256S441.5 0 288 0zm0 464c-119.1 0-216-96.9-216-216S168.9 40 288 40s216 96.9 216 216-96.9 216-216 216z"/></svg>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="3">Ez dago produkturik.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php
// Konexioa itxi
$conn->close();
?>