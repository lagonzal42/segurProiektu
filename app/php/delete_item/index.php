<?php
$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query insegurua
    $sql = "DELETE FROM babarrunak WHERE id = $id";
    if ($conn->query($sql)) {
        echo "<p style='color:green;'>✅ $id babarruna borratu da.</p>";
    } else {
        echo "<p style='color:red;'>❌ Errore bat gertatu da babarruna borratzean: " . htmlspecialchars($conn->error) . "</p>";
    }
}

// Obtener todos los registros
$sql = "SELECT * FROM babarrunak ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Babarrunak ezabatu</title>
    <style>
        
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #fcfcfc; 
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        h1, h2 {
            color: #2c3e50; 
            text-align: center;
            padding: 40px 0 20px 0;
            font-weight: 300;
            font-size: 2.2em;
            border-bottom: 1px solid #eee; 
            margin-bottom: 40px;
        }
        h2 {
            font-size: 1.8em;
            padding: 20px 0 10px 0;
            margin-bottom: 20px;
            border-bottom: none;
        }

        form {
            background: #ffffff;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin: 30px auto;
            width: 90%;
            max-width: 450px;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap; 
        }
        label {
            font-weight: 500;
            color: #555;
            white-space: nowrap;
        }
        input[type="number"] {
            flex-grow: 1; 
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid #bdc3c7;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.2s;
            margin-bottom: 0;
        }
        input:focus {
            border-color: #2c3e50;
            outline: none;
        }

        
        .button-container {
            width: 100%;
            display: flex;
            justify-content: flex-end; 
            margin-top: 15px;
        }

        button, input[type="submit"] {
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
            width: auto;
            margin: 0; 
        }
        button:hover, input[type="submit"]:hover {
            background: #34495e;
            transform: translateY(-1px);
        }
        .modify-btn {
            background: #95a5a6;
        }
        .modify-btn:hover {
            background: #7f8c8d;
        }

        p {
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
    <h2>Babarrunak</h2>

    <form method="get" action="">
        <label for="id">Ezabatzeko ID-a:</label>
        <input type="number" name="id" id="id" min="1" required>
        
        <div class="button-container">
            <button type="submit" id="item_delete_submit">Ezabatu</button>
            <button type="button" class="modify-btn" onclick="window.location.href='/'">Hasierara</button>
        </div>
    </form>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Izena</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Izena']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Ez dago produkturik.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>