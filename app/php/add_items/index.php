<?php
$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $izena = trim($_POST["izena"] ?? '');
    $jatorria = trim($_POST["jatorria"] ?? '');
    $kolorea = trim($_POST["kolorea"] ?? '');
    $denbora = trim($_POST["denbora"] ?? '');

    if ($izena !== '' && $jatorria !== '' && $kolorea !== '' && $denbora !== '') {
        // Query insegura (vulnerable a SQL Injection)
        $sql = "INSERT INTO babarrunak (Izena, Jatorria, Kolorea, Egozketa_denb_min) VALUES ('$izena', '$jatorria', '$kolorea', $denbora)";
        if ($conn->query($sql)) {
            echo "<p style='color:green;'>✅ Babarruna ondo gehitu da!</p>";
        } else {
            echo "<p style='color:red;'>❌ Errore bat gertatu da: " . htmlspecialchars($conn->error) . "</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ Datu guztiak bete behar dira.</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Babarruna Gehitu</title>
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
    <h1>Babarruna gehitu</h1>

    <form id="item_add_form" method="POST" action="add_items">
        <label for="izena">Izena:</label>
        <input type="text" id="izena" name="izena" required>

        <label for="jatorria">Jatorria:</label>
        <input type="text" id="jatorria" name="jatorria" required>

        <label for="kolorea">Kolorea:</label>
        <input type="text" id="kolorea" name="kolorea" required>

        <label for="denbora">Denbora:</label>
        <input type="number" min="0" step="1" id="denbora" name="denbora" required>

        <button type="submit" id="item_add_submit">Gehitu</button>
        <button type="button" class="modify-btn" onclick="window.location.href='/'">Hasierara</button>
    </form>

<?php
$conn->close();
?>
