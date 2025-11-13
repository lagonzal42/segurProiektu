<?php
// SEGURTASUN GOIBURUAK
header("Content-Security-Policy: default-src 'none'; script-src 'self'; style-src 'self'; img-src 'self'; connect-src 'self'; form-action 'self'; frame-ancestors 'none';");

session_start();
header_remove("X-Powered-By");
header("Server: SegurServer");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// DATU-BASEAREN KONEXIOA
$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

$conn = mysqli_connect($hostname, $username, $password, $db);
if (!$conn) {
    // Ekoizpen ingurunean, errore generiko bat erakutsi behar da
    die("Database connection failed: " . mysqli_connect_error());
}

$mezua = "";

// CSRF TOKEN SORTU EDO BERRIZTU
if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_time']) || ($_SESSION['csrf_time'] + 3600) < time()) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_time'] = time();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // CSRF BALIDAZIOA
    $posted_token = $_POST['csrf_token'] ?? '';
    if (empty($posted_token) || !hash_equals($_SESSION['csrf_token'], $posted_token)) {
        http_response_code(403);
        $mezua = "<span style='color: red;'>CSRF token ez egokia edo falta da.</span>";
        // Ez dugu exit() deitzen HTML-a kargatu ahal izateko errore mezua ikusteko
    } else {
        // DATUAK JASO ETA SANITIZATU

        // Pasahitzak gordetzeko, beti erabili behar dira hash funtzioak (adibidez: password_hash())
        // Kode honetan ez da inplementatzen, baina segurtasun hobea lortzeko pauso bat da.

        $user = $_POST["user"];
        $iz_abz = $_POST['iz_abz'];
        $nan = $_POST['nan'];
        // Telefonoa string gisa kudeatzen dugu datu-basearen tipologia zehatza jakin ezean.
        // Jatorrizko kodeak (int) erabili du, baina hobe da string bezala kudeatzea (Telefonoa string gisa deklaratuko dugu 's').
        $tlnf = $_POST['tlnf'];
        $jaiodata = $_POST['jaiodata'];
        $mail = $_POST['mail'];
        $pas = $_POST['pas'];
        
        // SQL INJECTION KONPONBIDEA: Prestatutako sententziak erabiliz
        $sql = "INSERT INTO erabiltzaileak (Erabiltzaile, Izen_Abizen, NAN, Telefonoa, Jaio_Data, Email, Pasahitza)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Prepare, bind, and execute
        if ($stmt = $conn->prepare($sql)) {
            // 'sssisss' -> String, String, String, Integer, String, String, String (tlnf int gisa suposatuz)
            // Jatorrizkoa Telefonoa (int) bezala hartzen zuenez, 'i' erabiliko dugu $tlnf-rako
            $tlnf_int = (int) $tlnf;

            $stmt->bind_param("sssisss", $user, $iz_abz, $nan, $tlnf_int, $jaiodata, $mail, $pas);

            if ($stmt->execute()) {
                $mezua = "<span class='success-message'>Erregistroa ondo gorde da!</span>";

                // Token berria sortu erregistroa ondo gorde bada
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $_SESSION['csrf_time'] = time();
            } else {
                if ($stmt->errno == 1062) {
                    $mezua = "<span class='error-message'>Errorea: NAN-a dagoeneko existitzen da.</span>";
                } else {
                    $mezua = "<span class='error-message'>Errorea: " . $stmt->error . "</span>";
                }
            }
            $stmt->close();
        } else {
             $mezua = "<span class='error-message'>Errorea: Ezin izan da sententzia prestatu.</span>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erregistroa</title>
    <!-- CSS Fitxategia kanpoko lotura -->
    <link rel="stylesheet" href="php/register/styles.css">
</head>
<body>

    <h1>Erabiltzaileen erregistroa</h1>
    
    <form id="register_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        
        <!-- CSRF tokena -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">

        <label for="user">ERABILTZAILEA:</label> 
        <input type="text" id="user" name="user" placeholder="Erabiltzailea" required maxlength="50">

        <label for="iz_abz">IZEN ABIZEN:</label> 
        <input type="text" id="iz_abz" name="iz_abz" placeholder="Izen Abizen" required maxlength="50">
        
        <label for="nan">NAN:</label> 
        <input type="text" id="nan" name="nan" placeholder="12345678Z" required maxlength="9">
        
        <label for="tlnf">TELEFONOA:</label> 
        <input type="text" id="tlnf" name="tlnf" placeholder="111111111" required maxlength="9">
        
        <label for="jaiodata">JAIOTZE DATA:</label> 
        <input type="text" id="jaiodata" name="jaiodata" placeholder="uuuu-hh-ee" required maxlength="10">
        
        <label for="mail">EMAIL:</label> 
        <input type="email" id="mail" name="mail" placeholder="adibidea@adibidez.eus" required maxlength="50">
        
        <label for="pas">PASAHITZA:</label> 
        <input type="password" id="pas" name="pas" placeholder="Pasahitza" required maxlength="40">
        
        <div class="button-container">
            <!-- CSP konponbidea: 'onclick' kendu da eta JS-an kudeatuko da -->
            <button id="register_submit" type="button">Sartu</button>
            <button id="register_ezabatu" type="reset">Ezabatu</button>
            <button type="button" class="modify-btn" id="home-button">Hasierara</button>
        </div>
    </form>
    
    <!-- JS balidazio-mezua erakusteko elementua (alert-ak ordezkatuz) -->
    <div id="js-mezua" class="message-box" style="display: none;"></div>

    <div class="message-box">
        <?php echo $mezua; ?>
    </div>

    <!-- JS Fitxategia kanpoko lotura -->
    <script src="php/register/register.js" defer></script>
</body>
</html>
<?php
// Konexioa itxi
mysqli_close($conn);
?>