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

    $conn = new mysqli($hostname, $username, $password, $db);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }


    if (isset($_GET['item'])) {
        $item_raw = $_GET['item'];
        $item = $conn->real_escape_string($item_raw);

        if ($item === '') {
            echo "<p style='color:red;'>❌ Nombre vacío.</p>";
        } else {
            // comprobar existencia
            $check_sql = "SELECT COUNT(*) AS cnt FROM babarrunak WHERE Izena = '$item'";
            $check_res = $conn->query($check_sql);
            if ($check_res) {
                $row = $check_res->fetch_assoc();
                $check_res->free();

                if ((int)$row['cnt'] === 0) {
                    echo "<p style='color:orange;'>ℹ️ \"" . htmlspecialchars($item_raw) . "\" ez da existitzen.</p>";
                } else {
                    // eliminar (limitar a 1 por si hay duplicados)
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

    // Obtener todos los registros
    $sql = "SELECT * FROM babarrunak ORDER BY id DESC";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Babarrunak ezabatu</title>
    <style nonce="<?= htmlspecialchars($csp_nonce, ENT_QUOTES) ?>">
        
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

    <h1>Babarrunak</h1>

    <!-- IDs ezabatzeko formularioa (ahora por nombre) -->
    <form method="get" action="/delete_item">
        <label for="item">Ezabatzeko izena:</label>
        <input type="text" name="item" id="item" required>
        <button type="submit" id="item_delete_submit">Ezabatu</button>
        <button type="button" class="modify-btn" onclick="window.location.href='/'">Hasierara</button>
    </form>
    <!-- balioak erakusteko taula -->
    <table>
        <tr>
            <!-- Taularen leheengo errenkada -->
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
