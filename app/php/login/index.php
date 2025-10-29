<?php
  session_start(); // Inicia la sesión

  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "segurproiektua";

  $conn = mysqli_connect($hostname, $username, $password, $db);
  if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $iz_abz = $_POST['iz_abz'];
    $pas = $_POST['pas'];

    $sql = "SELECT * FROM erabiltzaileak WHERE Izen_Abizen = '$iz_abz' AND Pasahitza = '$pas'";
    $resultado = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        $row = mysqli_fetch_assoc($resultado);
        $_SESSION['nan'] = $row['NAN'];
        $_SESSION['iz_abz'] = $row['Izen_Abizen'];

        // Redirige a modify_user con el parámetro user
        header("Location: /show_user?user=" . urlencode($row['NAN']));
        exit();
    } else {
        echo "Datu okerrak.";
    }
  }
?>


<!DOCTYPE html>
<html lang="eu">
<head>
  <meta charset="UTF-8">
  <title>Identifikazioa</title>
</head>

<script type="text/javascript" src="/php/login/login.js"></script>

<body>
  <h1>Erabiltzaileen identifikazioa</h1>
  <form id="login_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    IZEN ABIZEN: <input type="text" name="iz_abz" placeholder="Izen Abizen" required><br>
    PASAHITZA: <input type="password" name="pas" placeholder="Pasahitza" required><br>
    <button id="login_submit" type="submit" onclick="datuakegiaztatu()">Sartu</button>
    <button id="login_ezabatu" type="reset">Ezabatu</button>
  </form>
</body>
</html>

