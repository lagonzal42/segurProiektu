
  
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Babarrunak</title>
</head>
<body>
    <h1>Babarrunak</h1>
    
<?php
    $hostname = "db";
    $username = "admin";
    $password = "test";
    $db = "segurproiektua";

    $conn = mysqli_connect($hostname, $username, $password, $db);
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $query = mysqli_query($conn, "SELECT * FROM babarrunak")
        or die (mysqli_error($conn));

    while ($row = mysqli_fetch_array($query)) {
        echo "<div class='item'>
                <h3>" . $row['Izena'] . "</h3>
                <p>" . $row['Jatorria'] . "</p>
              </div>";
    }
?>


</body>
</html>