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

        //berbideraketa erabiltzailearen informaziora
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
      <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f7faff;
            margin: 0;
            padding: 0;
        }
        h1, h2, h3 {
            color: #2366a8;
            margin-top: 30px;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 30px auto 10px auto;
            background: #fff;
            box-shadow: 0 2px 8px rgba(35,102,168,0.08);
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            border: none;
            padding: 12px 16px;
            text-align: left;
        }
        th {
            background-color: #e3f0fc;
            color: #2366a8;
        }
        tr:nth-child(even) {
            background-color: #f2f7fc;
        }
        tr:hover {
            background-color: #d6eaff;
        }
        input, select {
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #bcd0e6;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        button, input[type="submit"] {
            background: linear-gradient(90deg, #2366a8 60%, #4fa3e3 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 1px 4px rgba(35,102,168,0.10);
            transition: background 0.2s;
        }
        button:hover, input[type="submit"]:hover {
            background: linear-gradient(90deg, #4fa3e3 60%, #2366a8 100%);
        }
        form {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(35,102,168,0.08);
            padding: 24px;
            margin: 30px auto;
            width: 90%;
            max-width: 500px;
        }
        .message {
            text-align: center;
            margin: 20px auto;
            font-size: 18px;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>

<script type="text/javascript" src="/php/login/login.js"></script>

<body>
  <h1>Erabiltzaileen identifikazioa</h1>
  <form id="login_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    IZEN ABIZEN: <input type="text" name="iz_abz" placeholder="Izen Abizen" required><br>
    PASAHITZA: <input type="password" name="pas" placeholder="Pasahitza" required><br>
    <button id="login_submit" type="submit" onclick="datuakegiaztatu()">Sartu</button>
    <button id="login_ezabatu" type="reset">Ezabatu</button>
    <button type="button" class="modify-btn" onclick="window.location.href='/'">Hasierara</button>
  </form>
</body>
</html>

