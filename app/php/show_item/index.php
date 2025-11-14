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
    die("Error de conexión con la base de datos.");
}

$user = null;
$message = "";

$id_raw = $_GET['id'] ?? '';
if (filter_var($id_raw, FILTER_VALIDATE_INT) === false || preg_match('/\D/', $id_raw)) {
    http_response_code(400);
    die('ID no válido.');
}
$id = (int)$id_raw;

// ======== Consulta segura con prepared statement ========
$stmt = $conn->prepare("
    SELECT id, Izena, Jatorria, Kolorea, Egozketa_denb_min
    FROM babarrunak
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result_user = $stmt->get_result();
if ($result_user && $result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
}
$stmt->close();

// Consulta general (no depende de input, ok)
$result = $conn->query("SELECT id, Izena FROM babarrunak ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Babarrunak Ikusi</title>
    <style>
        /* Tus estilos aquí */
    </style>
</head>
<body>
    <h1>Babarrunak</h1>

    <div class="button-container">
        <button type="button" onclick="window.location.href='/'">Hasierara</button>
    </div>

    <?= $message ?>

    <?php if ($user): ?>
        <hr>
        <h3>Babarruna: ID #<?= htmlspecialchars($user['id']) ?></h3>
        <form>
            <label>Izena:</label>
            <input type="text" value="<?= htmlspecialchars($user['Izena']) ?>" readonly>
            <label>Jatorria:</label>
            <input type="text" value="<?= htmlspecialchars($user['Jatorria']) ?>" readonly>
            <label>Kolorea:</label>
            <input type="text" value="<?= htmlspecialchars($user['Kolorea']) ?>" readonly>
            <label>Egozketa denbora (min):</label>
            <input type="number" value="<?= htmlspecialchars($user['Egozketa_denb_min']) ?>" readonly>
        </form>
        <hr>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Izena</th>
            <th>Ekintza</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['Izena']) ?></td>
                    <td>
                        <a href="?id=<?= htmlspecialchars($row['id']) ?>" class="icon-button" title="Ikusi xehetasunak">👁️</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="3">Ez dago produkturik.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
