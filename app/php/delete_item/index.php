<?php

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
    die("Error de conexión: " . $conn->connect_error);
}

if (isset($_GET['item'])) {
    $item_raw = $_GET['item'];
    $item = $conn->real_escape_string($item_raw);

    if ($item === '') {
        echo "<p style='color:red;'>❌ Izena hutsik.</p>";
    } else {
        $check_sql = "SELECT COUNT(*) AS cnt FROM babarrunak WHERE Izena = '$item'";
        $check_res = $conn->query($check_sql);
        if ($check_res) {
            $row = $check_res->fetch_assoc();
            $check_res->free();

            if ((int)$row['cnt'] === 0) {
                echo "<p style='color:orange;'>ℹ️ \"" . htmlspecialchars($item_raw) . "\" ez da existitzen.</p>";
            } else {
                $del_sql = "DELETE FROM babarrunak WHERE Izena = '$item' LIMIT 1";
                if ($conn->query($del_sql)) {
                    if ($conn->affected_rows > 0) {
                        echo "<p style='color:green;'>✅ \"" . htmlspecialchars($item_raw) . "\" babarruna borratu da.</p>";
                    } else {
                        echo "<p style='color:orange;'>ℹ️ Ez da ezer ezabatu.</p>";
                    }
                } else {
                    echo "<p style='color:red;'>❌ Errore bat gertatu da. </p>";
                }
            }
        } else {
            echo "<p style='color:red;'>❌ Errore bat comprobando existencia. </p>";
        }
    }
}

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
        h1 {
            color: #2c3e50;
            text-align: center;
            padding: 40px 0 10px 0;
            font-weight: 300;
            font-size: 2.2em;
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
        }

        .back-container {
            text-align: center;
            margin: 10px 0 40px 0;
        }
        .back-btn {
            background: #95a5a6;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: background 0.2s, transform 0.2s;
        }
        .back-btn:hover {
            background: #7f8c8d;
            transform: translateY(-1px);
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #eee;
        }
        th, td {
            border: none;
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f4f4f4;
            vertical-align: middle;
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

        table form {
            display: inline-block;
            margin: 0;
            padding: 0;
            border: none;
            background: none;
        }

        table button {
            background: #2c3e50;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 7px 12px;
            margin: 0;
            font-size: 0.95em;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: background 0.2s, transform 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table button:hover {
            background: #34495e;
            transform: translateY(-1px);
        }
        table button:focus {
            outline: none;
        }
    </style>
</head>
<body>

    <h1>Babarrunak</h1>

    <div class="back-container">
        <button type="button" class="back-btn" onclick="window.location.href='/'">Hasierara</button>
    </div>

    <table>
        <tr>
            <th>Izena</th>
            <th>Ekintza</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Izena']) . "</td>";
                echo "<td>
                    <form method='get' action='/delete_item'>
                        <input type='hidden' name='item' value='" . htmlspecialchars($row['Izena'], ENT_QUOTES) . "'>
                        <button type='submit'>Ezabatu</button>
                    </form>
                </td>";
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
