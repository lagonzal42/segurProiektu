<?php
// 1. Configuración de la Base de Datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

// Conexión a la base de datos
$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    // Es buena práctica no revelar detalles del error en producción
    die("Error de conexión: " . $conn->connect_error);
}

$user = null;
$message = "";

// 2. Lógica de Lectura, Edición y Actualización
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Si se envió el formulario (POST), procesar la actualización
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $izena = $_POST['Izena'] ?? '';
        $jatorria = $_POST['Jatorria'] ?? '';
        $kolorea = $_POST['Kolorea'] ?? '';
        $denbora = $_POST['Egozketa_denb_min'] ?? '';

        // Preparar la consulta de UPDATE (Uso de consultas preparadas para seguridad)
        $update_stmt = $conn->prepare("UPDATE babarrunak SET Izena = ?, Jatorria = ?, Kolorea = ?, Egozketa_denb_min = ? WHERE id = ?");
        // Nota: asumo que id es un string 's' o quizás un entero 'i' si la BD lo define así.
        // Lo dejo como 'sssss' como en tu código original, pero podrías cambiar el último a 'i'
        // si id es un entero.
        $update_stmt->bind_param("ssssi", $izena, $jatorria, $kolorea, $denbora, $id);

        if ($update_stmt->execute()) {
            $message = "<p style='color:green;'>✅ Datuak eguneratu dira.</p>";
        } else {
            $message = "<p style='color:red;'>❌ Errore bat gertatu da: " . htmlspecialchars($update_stmt->error) . "</p>";
        }

        $update_stmt->close();
    }

    // Obtener datos actuales del usuario para mostrarlos en el formulario
    $stmt = $conn->prepare("SELECT id, Izena, Jatorria, Kolorea, Egozketa_denb_min FROM babarrunak WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_user = $stmt->get_result();
    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
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
    <title>Babarrunak Kudeatu</title>
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
    <h2>Babarrunak Kudeaketa</h2>
    
    <?= $message ?>
    
    <?php if ($user): ?>
        <hr>
        <h3>Aldatu Babarruna: ID #<?= htmlspecialchars($user['id']) ?></h3>
        
        <form method="POST" action="?id=<?= htmlspecialchars($user['id']) ?>">
            <div>
                <label for="Izena">Izena:</label>
                <input type="text" id="Izena" name="Izena" value="<?= htmlspecialchars($user['Izena']) ?>" required>
            </div>
            <div>
                <label for="Jatorria">Jatorria:</label>
                <input type="text" id="Jatorria" name="Jatorria" value="<?= htmlspecialchars($user['Jatorria']) ?>" required>
            </div>
            <div>
                <label for="Kolorea">Kolorea:</label>
                <input type="text" id="Kolorea" name="Kolorea" value="<?= htmlspecialchars($user['Kolorea']) ?>" required>
            </div>
            <div>
                <label for="Egozketa_denb_min">Egozketa denbora (min):</label>
                <input type="number" id="Egozketa_denb_min" name="Egozketa_denb_min" value="<?= htmlspecialchars($user['Egozketa_denb_min']) ?>" required>
            </div>
            <br>
            <button type="submit">Gorde Aldaketak</button>
            <a href="./" style="margin-left: 10px;">Utzi (Ezeztatu)</a>
        </form>
        <hr>
    <?php endif; ?>

    <h3>Babarrun Zerrenda</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Izena</th>
            <th>Jatorria</th>
            <th>Kolorea</th>
            <th>Egozketa denbora</th>
            <th>Aldatu</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['Izena']) ?></td>
                    <td><?= htmlspecialchars($row['Jatorria']) ?></td>
                    <td><?= htmlspecialchars($row['Kolorea']) ?></td>
                    <td><?= htmlspecialchars($row['Egozketa_denb_min']) ?></td>
                    <td>
                        <a href="?id=<?= $row['id'] ?>">
                           Aldatu
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
// 5. Cerrar Conexión
$conn->close();
?>