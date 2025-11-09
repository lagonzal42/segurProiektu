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
        form {
            background: #ffffff; 
            border-radius: 6px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); 
            padding: 30px;
            margin: 30px auto;
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
        input[type="text"], input[type="number"], select {
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid #bdc3c7; 
            margin-bottom: 15px;
            width: 100%;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.2s;
        }
        input:focus, select:focus {
            border-color: #2c3e50; 
            outline: none;
        }
        button, input[type="submit"] {
            background: #2c3e50; 
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background 0.2s, transform 0.2s;
            margin-right: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        button:last-child {
            margin-right: 0;
        }
        button:hover, input[type="submit"]:hover {
            background: #34495e; 
            transform: translateY(-1px);
        }

        .message p {
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

        table, th, td, tr:nth-child(even), tr:hover {
            display: none;
        }

    </style>
</head>
<body>
    <h1>Babarruna gehitu</h1>

    <?php
    // Las etiquetas <p> de mensaje ahora se imprimirán con el estilo sobrio aplicado
    // Se asegura que los mensajes se vean en un contenedor para aplicar los estilos de fondo
    echo '<div class="message">';
    // El código PHP para imprimir el mensaje se ejecuta aquí:
    // if ($conn->query($sql)) { ... } else { ... }
    // El mensaje de conexión exitosa/error se imprime dentro de este bloque
    // Usé un div 'message' para centrar y dar estilos al feedback.
    ?>

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
echo '</div>'; // Cierre del contenedor de mensaje
$conn->close();
?>