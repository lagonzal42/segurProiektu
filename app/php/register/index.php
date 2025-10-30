<?php
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "segurproiektua";

  $conn = mysqli_connect($hostname, $username, $password, $db);
  if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
  }

  $mezua = ""; # Errore mezua gordetzeko aldagaia, bertan erregistroaren emaitza erakutsiko da.
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {   # Formularioa bidali ondoren exekutatuko da, horretarako POST metodoa erabiltzen da.
    $iz_abz = $_POST['iz_abz'];                 # Formularioan jarritako izen abizena jasotzen du.
    $nan = $_POST['nan'];                       # Formularioan jarritako NAN-a jasotzen du.
    $tlnf = (int) $_POST['tlnf'];               # Formularioan jarritako telefonoa zenbakia jasotzen du, integer-era bihurtuta.
    $jaiodata = $_POST['jaiodata'];             # Formularioan jarritako jaiotze data jasotzen du.
    $mail = $_POST['mail'];                     # Formularioan jarritako email-a jasotzen du.
    $pas = $_POST['pas'];                       # Formularioan jarritako pasahitza jasotzen du.
    
    $sql = "INSERT INTO erabiltzaileak (Izen_Abizen, NAN, Telefonoa, Jaio_Data, Email, Pasahitza)
            VALUES ('$iz_abz', '$nan', $tlnf, '$jaiodata', '$mail', '$pas')";      #SQL INSERT agindua gordeko duen aldagaia da, erabiltzaile berri bat gehitzeko erabiliko dena.


    if ($conn->query($sql) === TRUE) {                                      # SQL-ko datubasearekin konexioa egiten du eta agindua exekutatzen du, erabiltzaile berri bat gehituz.
      $mezua = "<span style='color: green;'>Erregistroa ondo gorde da!</span>";    # Errorerik egon ez bada, erregistroa ondo gorde dela adieraziko duen mezua.
    } else {
      if ($conn->errno == 1062) {                                                  # 1062 errore kodea ematen bada, formularioan sartutako NAN-a datubasean dagoela adieraziko du.
        $mezua = "<span style='color: red;'>Errorea: NAN-a dagoeneko existitzen da.</span>"; 
      } else {
        $mezua = "<span style='color: red;'>Errorea: " . $conn->error . "</span>"; # Aurreko errorerik eman ez bada, beste errore bat egon dela adieraziko da.
      }
    }

  }
?>

<!DOCTYPE html>
<html lang="eu">
<head>
  <meta charset="UTF-8">
  <title>Erregistroa</title>
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


<body>

  <script src="/php/register/register.js"></script> <!-- JavaScript fitxategiarekin konexioa egiteko lerroa, JavaScript artxiboaren kokapena zehazten du. -->

  <h1>Erabiltzaileen erregistroa</h1>
  <form id="register_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">  <!-- Formularioaren hasiera, datuak bidaltzeko metodoa POST da eta action atributuak orri honetara bidaltzen du, bertan definitu baitugu php-zer egingo duen sartutako datuekin. -->
    IZEN ABIZEN: <input type="text" name="iz_abz" placeholder="Izen Abizen" required><br>                         <!-- Izen abizena sartzeko eremua. -->
    NAN: <input type="text" name="nan" placeholder="12345678Z" required><br>                                      <!-- NAN-a sartzeko eremua. -->
    TELEFONOA: <input type="tel" name="tlnf" placeholder="111111111" required><br>                                <!-- Telefono zenbakia sartzeko eremua. -->
    JAIOTZE DATA: <input type="text" name="jaiodata" placeholder="uuuu-hh-ee" required><br>                       <!-- Jaiotze data sartzeko eremua. -->
    EMAIL: <input type="email" name="mail" placeholder="adibidea@adibidez.eus" required><br>                      <!-- Email-a sartzeko eremua. -->
    PASAHITZA: <input type="password" name="pas" placeholder="Pasahitza" required><br>                            <!-- Pasahitza sartzeko eremua. -->
    <button id="register_submit" type="button" onclick="datuakegiaztatu()">Sartu</button>                         <!-- Sartzeko botoia, JavaScript fitxategiari deia egingo dio, sartutako datuak konprobatzeko, ez du "submit" egiten. -->
    <button id="register_ezabatu" type="reset">Ezabatu</button>                                                   <!-- Ezabatzeko botoia, formularioaren edukia ezabatzen du. -->
    <button type="button" class="modify-btn" onclick="window.location.href='/'">Hasierara</button>                <!-- Hasierarako botoia, hasierako orrira, alegia home orrira, eramaten du. -->
  <?php echo $mezua; ?>                                                                                           <!-- Gordetako mezua erakusten du, erregistroaren emaitza jakiteko. -->
</body>
</html> 