<?php
$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Borrado de registro si se pasa el parámetro id
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM babarrunak WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>✅ $id babarruna borratu da.</p>";
    } else {
        echo "<p style='color:red;'>❌ Errore bat gertatu da babarruna borratzean: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
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
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 60%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        input[type="number"] { padding: 6px; width: 100px; }
        button { padding: 6px 10px; margin-left: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Babarrunak</h2>

    <!-- Formulario para eliminar por ID -->
    <form method="get" action="">
        <label for="id">Ezabatzeko ID-a:</label>
        <input type="number" name="id" id="id" min="1" required>
        <button type="submit" id="item_delete_submit">Ezabatu</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Izena</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['Izena']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="2">Ez dago produkturik.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
