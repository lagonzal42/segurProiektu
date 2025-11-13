<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();

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
    die("Database connection failed.");
}

$mezua = "";

// CSRF token berria sortu behar bada
if (
    empty($_SESSION['csrf_token']) || 
    empty($_SESSION['csrf_time']) || 
    ($_SESSION['csrf_time'] + 3600) < time()
) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_time'] = time();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $posted_token = $_POST['csrf_token'] ?? '';
    if (empty($posted_token) || !hash_equals($_SESSION['csrf_token'], $posted_token)) {
        http_response_code(403);
        echo "<p style='color:red;'>CSRF token ez egokia edo falta da.</p>";
        exit();
    }

    // Datuak jaso eta garbitu
    $user = trim($_POST["user"] ?? '');
    $iz_abz = trim($_POST['iz_abz'] ?? '');
    $nan = trim($_POST['nan'] ?? '');
    $tlnf = trim($_POST['tlnf'] ?? '');
    $jaiodata = trim($_POST['jaiodata'] ?? '');
    $mail = trim($_POST['mail'] ?? '');
    $pas = $_POST['pas'] ?? '';

    // Pasahitza hashatu (segurtasuna handitzeko)
    $pas_hash = password_hash($pas, PASSWORD_BCRYPT);

    // ✅ Prepared Statement segurua
    $stmt = $conn->prepare("
        INSERT INTO erabiltzaileak (Erabiltzaile, Izen_Abizen, NAN, Telefonoa, Jaio_Data, Email, Pasahitza)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    if ($stmt) {
        $stmt->bind_param("sssssss", $user, $iz_abz, $nan, $tlnf, $jaiodata, $mail, $pas_hash);

        if ($stmt->execute()) {
            $mezua = "<span style='color: green;'>Erregistroa ondo gorde da!</span>";

            // CSRF token berria sortu ondoren
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_time'] = time();
        } else {
            if ($stmt->errno == 1062) {
                $mezua = "<span style='color: red;'>Errorea: NAN-a dagoeneko existitzen da.</span>";
            } else {
                $mezua = "<span style='color: red;'>Errorea: " . htmlspecialchars($stmt->error) . "</span>";
            }
        }

        $stmt->close();
    } else {
        $mezua = "<span style='color: red;'>Errorea: ezin izan da adierazpena prestatu.</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="eu">
<head>
  <meta charset="UTF-8">
  <title>Erregistroa</title>
  <style>
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
    form {
      background: #ffffff;
      border-radius: 6px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      padding: 30px;
      margin: 30px auto;
      width: 90%;
      max-width: 450px;
      border: 1px solid #eee;
    }
    label {
      display: block;
      margin-top: 15px;
      margin-bottom: 5px;
      font-weight: 500;
      color: #555;
      font-size: 0.95em;
    }
    input[type="text"], input[type="tel"], input[type="email"], input[type="password"], input[type="submit"] {
      padding: 10px 12px;
      border-radius: 4px;
      border: 1px solid #bdc3c7; 
      margin-bottom: 10px;
      width: 100%;
      box-sizing: border-box;
      font-size: 1em;
      transition: border-color 0.2s;
      display: block;
    }
    input:focus {
      border-color: #2c3e50; 
      outline: none;
    }
    .button-container {
      display: flex;
      justify-content: flex-start; 
      gap: 10px;
      margin-top: 20px;
      flex-wrap: wrap;
    }
    button {
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
      margin-bottom: 5px;
    }
    button:hover {
      background: #34495e;
      transform: translateY(-1px);
    }
    #register_ezabatu {
      background: #95a5a6; 
    }
    #register_ezabatu:hover {
      background: #7f8c8d;
    }
    .modify-btn {
      background: #3498db; 
    }
    .modify-btn:hover {
      background: #2980b9;
    }
    .message-box {
      text-align: center;
      margin: 20px auto;
      max-width: 450px;
    }
    .message-box span {
      display: inline-block;
      font-size: 1.1em;
      padding: 10px 20px;
      border-radius: 4px;
    }
    .message-box span[style*='color: green'] {
      background-color: #e6ffee;
      border: 1px solid #33cc33;
      color: #1a661a !important;
    }
    .message-box span[style*='color: red'] {
      background-color: #ffe6e6;
      border: 1px solid #cc3333;
      color: #661a1a !important;
    }
    table, th, td, tr {
      display: none;
    }
  </style>
</head>
<body>
  <script src="/php/register/register.js"></script>
  <h1>Erabiltzaileen erregistroa</h1>
  
  <form id="register_form" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES); ?>">

    <label for="user">ERABILTZAILEA:</label> 
    <input type="text" id="user" name="user" placeholder="Erabiltzailea" required>

    <label for="iz_abz">IZEN ABIZEN:</label> 
    <input type="text" id="iz_abz" name="iz_abz" placeholder="Izen Abizen" required>

    <label for="nan">NAN:</label> 
    <input type="text" id="nan" name="nan" placeholder="12345678Z" required>

    <label for="tlnf">TELEFONOA:</label> 
    <input type="tel" id="tlnf" name="tlnf" placeholder="111111111" required>

    <label for="jaiodata">JAIOTZE DATA:</label> 
    <input type="text" id="jaiodata" name="jaiodata" placeholder="uuuu-hh-ee" required>

    <label for="mail">EMAIL:</label> 
    <input type="email" id="mail" name="mail" placeholder="adibidea@adibidez.eus" required>

    <label for="pas">PASAHITZA:</label> 
    <input type="password" id="pas" name="pas" placeholder="Pasahitza" required>

    <div class="button-container">
        <button id="register_submit" type="button" onclick="datuakegiaztatu()">Sartu</button>
        <button id="register_ezabatu" type="reset">Ezabatu</button>
        <button type="button" class="modify-btn" onclick="window.location.href='/'">Hasierara</button>
    </div>
  </form>

  <div class="message-box">
    <?= $mezua; ?>
  </div>
</body>
</html>

<?php
$conn->close();
?>
