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

    $stmt = $conn->prepare("SELECT Izena, Jatorria FROM babarrunak ORDER BY id DESC");
    if (!$stmt) {
        die("Errorea kontsultaren sorreran ");
    }
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
  
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Babarrunak</title>
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

        table {
            border-collapse: collapse;
            width: 90%;
            max-width: 800px;
            margin: 30px auto;
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
        
        .button-container {
            max-width: 800px;
            margin: 20px auto 40px auto;
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 0 20px;
        }
        .button-container a, .button-container button {
            text-decoration: none;
            display: block;
        }
        button {
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
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap; 
        }
        button:hover {
            background: #34495e;
            transform: translateY(-1px);
        }
        .modify-btn {
            background: #95a5a6; 
        }
        .modify-btn:hover {
            background: #7f8c8d;
        }

        form, input, select, .message {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Babarrunak</h1>
    <table>
        <tr>
            <th>Izena</th>
            <th>Jatorria</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Izena']) ?></td>
                    <td><?= htmlspecialchars($row['Jatorria']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="3">Ez dago produkturik.</td></tr>
        <?php endif; ?>
    </table>
    
    <div class="button-container">
        <a href="add_items">
            <button>Gehitu babarrunak</button>
        </a>
        <a href="/", style="text-decoration:none;">
            <button type="button" class="modify-btn">Hasierara</button>
        </a>
    </div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>