<?php
  session_start(); // Saioa hasteko erabiltzen da.

  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "segurproiektua";

  $conn = mysqli_connect($hostname, $username, $password, $db);
  if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {   # Formularioa bidali ondoren exekutatuko da, horretarako POST metodoa erabiltzen da.
    $iz_abz = $_POST['iz_abz'];                 # Formularioan jarritako izen abizena jasotzen du.
    $pas = $_POST['pas'];                       # Formularioan jarritako pasahitza jasotzen du.

    $sql = "SELECT * FROM erabiltzaileak WHERE Izen_Abizen = '$iz_abz' AND Pasahitza = '$pas'";      # SQL SELECT agindua gordeko duen aldagaia da, erabiltzaile bat bilatuko duena emandako datuak erabiliz.
    $resultado = mysqli_query($conn, $sql);                                            # SQL-ko datubasearekin konexioa egiten du eta agindua exekutatzen du, erabiltzailea existitzen bada bere baduak aukeratuz.

    if (mysqli_num_rows($resultado) > 0) {                                                   # Lortutako emaitza konprobatzen du, erabiltzaile existitzen badenentz konprobatzeko.  
        $row = mysqli_fetch_assoc($resultado);                                               # Lortutako emaitzaren lerroa hartzen du, datu guztiekin.
        $_SESSION['nan'] = $row['NAN'];                                                              # Lortutako lerroaren NAN datua eskuratzen du.
        $_SESSION['iz_abz'] = $row['Izen_Abizen'];                                                   # Lortutako lerroaren izen abizena eskuratzen du.

        header("Location: /show_user?user=" . urlencode($row['NAN']));               # Erabiltzailearen informazioa begiratzeko berbideraketa.
        exit();
    } else {                                                                                         # Erabilitako datuak okerrak badira, datu okerrak sartu direla agertuko da.
        echo "Datu okerrak.";
    }
  }
?>


<!DOCTYPE html>
<html lang="eu">
<head>
  <meta charset="UTF-8">
  <title>Identifikazioa</title>
      <style> /* Erabiliko den estiloaren definizioa. */
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

<script type="text/javascript" src="/php/login/login.js"></script> <!-- JavaScript fitxategiarekin konexioa egiteko lerroa, JavaScript artxiboaren kokapena zehazten du. -->

<body>
  <h1>Erabiltzaileen identifikazioa</h1>
  <form id="login_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">       <!-- Formularioaren hasiera, datuak bidaltzeko metodoa POST da eta action atributuak orri honetara bidaltzen du, bertan definitu baitugu php-zer egingo duen sartutako datuekin. -->
    IZEN ABIZEN: <input type="text" name="iz_abz" placeholder="Izen Abizen" required><br>                           <!-- Izen abizena sartzeko eremua. -->
    PASAHITZA: <input type="password" name="pas" placeholder="Pasahitza" required><br>                              <!-- Pasahitza sartzeko eremua. -->
    <button id="login_submit" type="submit" onclick="datuakegiaztatu()">Sartu</button>                              <!-- Sartzeko botoia, JavaScript fitxategiari deia egingo dio, sartutako datuak konprobatzeko, ez du "submit" egiten. -->
    <button id="login_ezabatu" type="reset">Ezabatu</button>                                                        <!-- Ezabatzeko botoia, formularioaren edukia ezabatzen du. -->
    <button type="button" class="modify-btn" onclick="window.location.href='/'">Hasierara</button>                  <!-- Hasierarako botoia, hasierako orrira, alegia home orrira, eramaten du. -->
  </form>
</body>
</html>

