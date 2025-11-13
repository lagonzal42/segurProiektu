<?php
/**
 * babarrunak.php
 * Script PHP principal con lógica de BD y estructura HTML.
 * Soluciona Inyección SQL usando consultas preparadas.
 */

// 1. Encabezados de Seguridad
header("Content-Security-Policy: default-src 'none'; script-src 'self'; style-src 'self'; img-src 'self'; connect-src 'self'; form-action 'self'; frame-ancestors 'none';");
header_remove("X-Powered-By");
header("Server: SegurServer");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// 2. Configuración de la base de datos
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

// 3. Lógica de lectura con consulta preparada (Corrección de SQL Injection)
if (isset($_GET['id'])) {
    // Validar que 'id' es un entero para mayor seguridad
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($id !== false) {
        // Consulta segura con sentencia preparada
        $sql = "SELECT id, Izena, Jatorria, Kolorea, Egozketa_denb_min FROM babarrunak WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        
        // 'i' indica que el parámetro es un entero
        $stmt->bind_param("i", $id); 
        $stmt->execute();
        
        $result_user = $stmt->get_result();
        
        if ($result_user && $result_user->num_rows > 0) {
            $user = $result_user->fetch_assoc();
        }
        $stmt->close();
    }
}

// 4. Obtener todos los datos para la tabla (se mantiene)
$sql = "SELECT * FROM babarrunak ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Babarrunak Ikusi</title>
    <link rel="stylesheet" href="php/show_item/styles.css"> 
    <script src="php/show_item/script.js" defer></script>

    </head>
    <button type="button" class="modify-btn" id="home-button">Hasierara</button>
<body>
    <h1>Babarrunak</h1> 
   

    <?= $message ?>
    
    <?php if ($user): ?>
        <hr>
        <h3>Babarruna: ID #<?= htmlspecialchars($user['id']) ?></h3>
        
        <form>
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

    <table>
        <tr>
            <th>ID</th>
            <th>Izena</th>
            <th>Ekintza</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['Izena']) ?></td>
                    <td>
                        <a href="?id=<?= $row['id'] ?>" class="icon-button" title="Ikusi xehetasunak">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 144a110.5 110.5 0 0 0 -3.4 220.1C260 384 274.6 384 288 384c70.7 0 128-57.3 128-128s-57.3-128-128-128zm0 224a96 96 0 1 1 0-192 96 96 0 1 1 0 192zM288 0C134.5 0 8 119.5 8 256s126.5 256 280 256 272-119.5 272-256S441.5 0 288 0zm0 464c-119.1 0-216-96.9-216-216S168.9 40 288 40s216 96.9 216 216-96.9 216-216 216z"/></svg>
                        </a>
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
// Cerrar conexión
$conn->close();
?>