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
    <h1>Babarrunak</h1>

    <!-- IDs ezabatzeko formularioa -->
    <form method="get" action="">
        <label for="id">Ezabatzeko ID-a:</label>
        <input type="text" name="id" id="id" min="1" required>
        <button type="submit" id="item_delete_submit">Ezabatu</button>
        <button type="button" class="modify-btn" onclick="window.location.href='/'">Hasierara</button>
    </form>
    <!-- balioak erakusteko taula -->
    <table>
        <tr>
            <!-- Taularen leheengo errenkada -->
            <th>ID</th>
            <th>Izena</th>
        </tr>
        <!-- Informazio errenkadak -->
        <?php
        // errenkadak badaude
        if ($result->num_rows > 0) 
        {
            //errenkada bakoitzeko
            while ($row = $result->fetch_assoc())
            {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Izena']) . "</td>";
                echo "</tr>";
            }
        } 
        else // ez badago errenkadarik
            echo "<tr><td colspan='2'>Ez dago produkturik.</td></tr>";
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
