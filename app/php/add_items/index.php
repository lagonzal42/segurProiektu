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
        $stmt = $conn->prepare("INSERT INTO babarrunak (Izena, Jatorria, Kolorea, Egozketa_denb_min) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            die("Error en prepare(): " . $conn->error);
        }
        $stmt->bind_param("sssi", $izena, $jatorria, $kolorea, $denbora);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>✅ Babarruna ondo gehitu da!</p>";
        } else {
            echo "<p style='color:red;'>❌ Errore bat gertatu da: " . htmlspecialchars($stmt->error) . "</p>";
        }

        $stmt->close();
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
        body { font-family: Arial, sans-serif; margin: 30px; }
        label { display: block; margin-top: 10px; }
        input[type="text"] { width: 300px; padding: 6px; }
        button { margin-top: 15px; padding: 8px 12px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #45a049; }
        a { display: inline-block; margin-top: 15px; }
    </style>
</head>
<body>
    <h2>Babarruna gehitu</h2>

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
    </form>

<?php
$conn->close();
?>
    