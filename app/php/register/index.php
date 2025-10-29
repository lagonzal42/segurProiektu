<?php
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "segurproiektua";

  $conn = mysqli_connect($hostname, $username, $password, $db);
  if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
  }

  $mezua = ""; 
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $iz_abz = $_POST['iz_abz'];
    $nan = $_POST['nan'];
    $tlnf = (int) $_POST['tlnf'];
    $jaiodata = $_POST['jaiodata'];
    $mail = $_POST['mail'];
    $pas = $_POST['pas'];
    
    $sql = "INSERT INTO erabiltzaileak (Izen_Abizen, NAN, Telefonoa, Jaio_Data, Email, Pasahitza)
            VALUES ('$iz_abz', '$nan', $tlnf, '$jaiodata', '$mail', '$pas')";


    if ($conn->query($sql) === TRUE) {
      $mezua = "<span style='color: green;'>Erregistroa ondo gorde da!</span>";
    } else {
      if ($conn->errno == 1062) {
        $mezua = "<span style='color: red;'>Errorea: NAN-a dagoeneko existitzen da.</span>";
      } else {
        $mezua = "<span style='color: red;'>Errorea: " . $conn->error . "</span>";
      }
    }

  }
?>

<!DOCTYPE html>
<html lang="eu">
<head>
  <meta charset="UTF-8">
  <title>Erregistroa</title>
</head>


<body>

  <script src="/php/register/register.js"></script>

  <h1>Erabiltzaileen erregistroa</h1>
  <form id="register_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    IZEN ABIZEN: <input type="text" name="iz_abz" placeholder="Izen Abizen" required><br>
    NAN: <input type="text" name="nan" placeholder="NAN" required><br>
    TELEFONOA: <input type="tel" name="tlnf" placeholder="Telefonoa" required><br>
    JAIOTZE DATA: <input type="text" name="jaiodata" placeholder="Jaiotze Data" required><br>
    EMAIL: <input type="email" name="mail" placeholder="Email" required><br>
    PASAHITZA: <input type="password" name="pas" placeholder="Pasahitza" required><br>
    <button id="register_submit" type="button" onclick="datuakegiaztatu()">Sartu</button>
    <button id="register_ezabatu" type="reset">Ezabatu</button>
  </form>
  <?php echo $message; ?>
</body>
</html>

