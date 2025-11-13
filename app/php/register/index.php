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
    $user = $_POST["user"];
    $iz_abz = $_POST['iz_abz'];
    $nan = $_POST['nan'];
    $tlnf = (int) $_POST['tlnf'];
    $jaiodata = $_POST['jaiodata'];
    $mail = $_POST['mail'];
    $pas = $_POST['pas'];
    
    // Query insegura (vulnerable a SQL Injection)
    $sql = "INSERT INTO erabiltzaileak (Erabiltzaile, Izen_Abizen, NAN, Telefonoa, Jaio_Data, Email, Pasahitza)
            VALUES ('$user', '$iz_abz', '$nan', $tlnf, '$jaiodata', '$mail', '$pas')";


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
  <form id="register_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    
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
    <?php echo $mezua; ?>
  </div>

</body>
</html>

<?php
mysqli_close($conn);
?>