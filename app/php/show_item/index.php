<?php
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
header_remove("X-Powered-By");
header("Server: SegurServer");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión con la base de datos.");
}

$user = null;
$message = "";

$id_raw = $_GET['id'] ?? '';
if (filter_var($id_raw, FILTER_VALIDATE_INT) === false || preg_match('/\D/', $id_raw)) {
    http_response_code(400);
    die('ID no válido.');
}
$id = (int)$id_raw;

// ======== Consulta segura con prepared statement ========
$stmt = $conn->prepare("
    SELECT id, Izena, Jatorria, Kolorea, Egozketa_denb_min
    FROM babarrunak
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result_user = $stmt->get_result();
if ($result_user && $result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
}
$stmt->close();

// Consulta general (no depende de input, ok)
$result = $conn->query("SELECT id, Izena FROM babarrunak ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Babarrunak Ikusi</title>
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
            <label>Izena:</label>
            <input type="text" value="<?= htmlspecialchars($user['Izena']) ?>" readonly>
            <label>Jatorria:</label>
            <input type="text" value="<?= htmlspecialchars($user['Jatorria']) ?>" readonly>
            <label>Kolorea:</label>
            <input type="text" value="<?= htmlspecialchars($user['Kolorea']) ?>" readonly>
            <label>Egozketa denbora (min):</label>
            <input type="number" value="<?= htmlspecialchars($user['Egozketa_denb_min']) ?>" readonly>
        </form>
        <hr>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Izena</th>
            <th>Ekintza</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['Izena']) ?></td>
                    <td>
                        <a href="?id=<?= htmlspecialchars($row['id']) ?>" class="icon-button" title="Ikusi xehetasunak">👁️</a>
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
$conn->close();
?>
