<?php
// 1. Configuración de la Base de Datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

// Conexión a la base de datos
$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$user = null;
$message = "";

// 2. Lógica de Lectura, Edición y Actualización
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener datos actuales del usuario para mostrarlos en el formulario
    $stmt = $conn->prepare("SELECT id, Izena, Jatorria, Kolorea, Egozketa_denb_min FROM babarrunak WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }
    $stmt->close();
}

// Obtener todos los datos para la tabla (se ejecuta siempre)
$sql = "SELECT * FROM babarrunak ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Babarrunak Ikusi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 60%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { color: #007bff; text-decoration: none; }
        a.delete { color: red; }
        a:hover { text-decoration: underline; }
        input[type="text"], input[type="number"] { padding: 5px; margin-top: 5px; width: 100%; box-sizing: border-box; }
        form { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px; width: 50%; }
        button { padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Babarrunak</h2>
    
    <?= $message ?>
    
    <?php if ($user): ?>
        <hr>
        <h3>Babarruna: ID #<?= htmlspecialchars($user['id']) ?></h3>
        
        <form method="POST" action="?id=<?= htmlspecialchars($user['id']) ?>">
            <div>
                <label for="Izena">Izena:</label>
                <input type="text" id="Izena" name="Izena" value="<?= htmlspecialchars($user['Izena']) ?>" readonly>
            </div>
            <div>
                <label for="Jatorria">Jatorria:</label>
                <input type="text" id="Jatorria" name="Jatorria" value="<?= htmlspecialchars($user['Jatorria']) ?>" readonly>
            </div>
            <div>
                <label for="Kolorea">Kolorea:</label>
                <input type="text" id="Kolorea" name="Kolorea" value="<?= htmlspecialchars($user['Kolorea']) ?>" readonly>
            </div>
            <div>
                <label for="Egozketa_denb_min">Egozketa denbora (min):</label>
                <input type="number" id="Egozketa_denb_min" name="Egozketa_denb_min" value="<?= htmlspecialchars($user['Egozketa_denb_min']) ?>" readonly>
            </div>
        </form>
        <hr>
    <?php endif; ?>

    <h3>Babarrun Zerrenda</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Izena</th>
            <th>  </th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['Izena']) ?></td>
                    <td>
                        <a href="?id=<?= $row['id'] ?>">
                           Ikusi
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Ez dago produkturik.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>