<?php

    // --------------------------------------------------------
    // SEGURTASUN GOIBURUAK ETA CSP
    // --------------------------------------------------------
    // Oharra: "default-src 'self'"-ek kanpoko fitxategiak (CSS eta JS barne) onartzen ditu
    // domeinu berean baldin badaude. Lineako estiloak CSS fitxategira eraman dira.
    header("Content-Security-Policy: default-src 'none'; script-src 'self'; style-src 'self'; img-src 'self'; connect-src 'self'; form-action 'self'; frame-ancestors 'none';");

    session_start();

    // Goiburu sentikorrak kendu/ezarri
    header_remove("X-Powered-By");
    header("Server: SegurServer");
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: DENY");
    header("X-XSS-Protection: 1; mode=block");

    // --------------------------------------------------------
    // DATU-BASEAREN KONFIGURAZIOA
    // --------------------------------------------------------
    $hostname = "db";
    $username = "admin";
    $password = "test";
    $db = "segurproiektua";

    // Datu-basearen konexioa
    $conn = new mysqli($hostname, $username, $password, $db);
    if ($conn->connect_error) {
        die("Error de conexión con la base de datos.");
    }

    $user = null;
    $message = "";

    // --------------------------------------------------------
    // CSRF TOKEN KUDEAKETA
    // --------------------------------------------------------
    // Token berria sortu ordu 1eko iraungitzearekin
    if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_time']) || ($_SESSION['csrf_time'] + 3600) < time()) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_time'] = time();
    }

    $current_csrf_token = $_SESSION['csrf_token'];


    // --------------------------------------------------------
    // DATU-BASEAREN LOGIKA ETA SEGURTASUN HOBEKUNTZAK
    // --------------------------------------------------------

    if (isset($_GET['id'])) {
        // ID balioa zenbakizkoa dela ziurtatu
        $id = intval($_GET['id']);
        
        // Formularioa (POST) bidali bada, eguneratu
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $posted_token = $_POST['csrf_token'] ?? '';
            if (empty($posted_token) || !hash_equals($current_csrf_token, $posted_token)) {
                http_response_code(403);
                $message = "<p class='error-message'>CSRF token falta da edo ez da egokia.</p>";
            } else {

                // 1. POST datuak sanitizatu eta baliozkotu
                $izena = trim($_POST['Izena'] ?? '');
                $jatorria = trim($_POST['Jatorria'] ?? '');
                $kolorea = trim($_POST['Kolorea'] ?? '');
                // Zenbakizko balioa dela ziurtatu
                $denbora = filter_input(INPUT_POST, 'Egozketa_denb_min', FILTER_VALIDATE_INT);

                if ($denbora === false) {
                    $message = "<p class='error-message'>Egozketa denbora zenbaki osoa izan behar da.</p>";
                } else {
                    // 2. Prestatutako adierazpen segurua erabili SQL Injection ekiditeko
                    $stmt_update = $conn->prepare("UPDATE babarrunak SET Izena = ?, Jatorria = ?, Kolorea = ?, Egozketa_denb_min = ? WHERE id = ?");
                    
                    // 'sssi' --> String, String, String, Integer
                    $stmt_update->bind_param("sssii", $izena, $jatorria, $kolorea, $denbora, $id); 
                    
                    if ($stmt_update->execute()) {
                        $message = "<p class='success-message'>Datuak ondo eguneratu dira.</p>";
                        
                        // Token berria sortu eguneratze arrakastatsuaren ondoren
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                        $_SESSION['csrf_time'] = time();
                        $current_csrf_token = $_SESSION['csrf_token']; // Eguneratu uneko tokena
                    } else {
                        // Errorearen xehetasunak garapen-ingurunean bakarrik erakutsi
                        $message = "<p class='error-message'>Errore bat gertatu da: " . htmlspecialchars($stmt_update->error) . "</p>";
                    }
                    $stmt_update->close();
                }
            }
        }

        // Babarrunen egungo datuak lortzea, inprimakian erakusteko (ID bidez)
        // Prestatutako adierazpen segurua erabili
        $stmt_select_one = $conn->prepare("SELECT id, Izena, Jatorria, Kolorea, Egozketa_denb_min FROM babarrunak WHERE id = ?");
        $stmt_select_one->bind_param("i", $id); // 'i' --> Integer
        $stmt_select_one->execute();
        $result_user = $stmt_select_one->get_result();

        if ($result_user && $result_user->num_rows > 0) {
            $user = $result_user->fetch_assoc();
        } else {
            // ID okerra edo existitzen ez den kasua
            $message .= "<p class='error-message'>Ez da ID horrekin bat datorren babarrunik aurkitu.</p>";
        }
        $stmt_select_one->close();
    }

    // Taularako datu guztiak eskuratu (beti exekutatzen da)
    $sql_all = "SELECT * FROM babarrunak ORDER BY id DESC";
    $result = $conn->query($sql_all);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Babarrunak Kudeatu</title>
    <!-- Estilo guztiak kanpoko fitxategira eraman dira CSP arazoak ekiditeko -->
    <link rel="stylesheet" href="php/modify_item/styles.css">
</head>
<body>
    <h1>Babarrunak Kudeaketa</h1>
    
    <div class="button-container">
        <!-- JS kanpoko fitxategira eraman da -->
        <button type="button" class="modify-btn" id="home-button">Hasierara</button>
    </div>
    
    <?= $message ?>
    
    <?php if ($user): ?>
        <hr>
        <h3>Aldatu Babarruna: ID #<?= htmlspecialchars($user['id']) ?></h3>
        
        <form method="POST" action="?id=<?= htmlspecialchars($user['id']) ?>">

            <!-- CSRF token segurua -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($current_csrf_token, ENT_QUOTES) ?>">

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
            <th>Ekintza</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['Izena']) ?></td>
                    <td><?= htmlspecialchars($row['Jatorria']) ?></td>
                    <td><?= htmlspecialchars($row['Kolorea']) ?></td>
                    <td><?= htmlspecialchars($row['Egozketa_denb_min']) ?></td>
                    <td>
                        <a href="?id=<?= $row['id'] ?>" class="button action-button">
                            Aldatu
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Ez dago produkturik.</td></tr>
        <?php endif; ?>
    </table>
    
    <!-- JS fitxategia amaieran kargatu -->
    <script src="php/modify_item/script.js"></script>
</body>
</html>

<?php
// Konexioa itxi
if (isset($conn)) {
    $conn->close();
}
?>