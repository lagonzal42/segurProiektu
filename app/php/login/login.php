<?php
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "segurproiektua";

  $conn = mysqli_connect($hostname, $username, $password, $db);
  if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
  } else {
    echo "<script>console.log('Database connected successfully');</script>";
  }


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $iz_abz = $_POST['iz_abz'];
    $pas = $_POST['pas'];

    // 🚨 Consulta NO segura (susceptible a inyección SQL)
    $sql = "SELECT * FROM erabiltzaileak WHERE Izen_Abizen = '$iz_abz' AND Pasahitza = '$pas'";
    $resultado = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        echo "✅ Los datos son exactamente iguales.";
    } else {
        echo "❌ Datos incorrectos.";
    }
}

?>


<!DOCTYPE html>
<html lang="eu">
<head>
  <meta charset="UTF-8">
  <title>Identifikazioa</title>
</head>
<body>
  <h1>Erabiltzaileen identifikazioa</h1>
  <form id="register_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    IZEN ABIZEN: <input type="text" name="iz_abz" placeholder="Izen Abizen" required><br>
    PASAHITZA: <input type="password" name="pas" placeholder="Pasahitza" required><br>
    <button id="register_submit" type="submit">Sartu</button>
    <button id="register_ezabatu" type="reset">Ezabatu</button>
  </form>
</body>
</html>

