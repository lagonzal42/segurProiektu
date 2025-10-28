<?php
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "segurproiektua";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT * FROM babarrunak ORDER BY id DESC";
$result = $conn->query($sql);
?>
  
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Babarrunak</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 60%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { color: red; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Babarrunak</h2>
    <table>
        <tr>
            <th>Izena</th>
            <th>Jatorria</th>
            <th>Kolorea</th>
            <th>Egozketa denbora</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Izena']) ?></td>
                    <td><?= htmlspecialchars($row['Jatorria']) ?></td>
                    <td><?= htmlspecialchars($row['Kolorea']) ?></td>
                    <td><?= htmlspecialchars($row['Egozketa_denb_min']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="3">Ez dago produkturik.</td></tr>
        <?php endif; ?>
    </table>
    <a href="add_items">
        <button>Gehitu babarrunak</button>
    </a>

</body>
</html>

<?php
$conn->close();
?>