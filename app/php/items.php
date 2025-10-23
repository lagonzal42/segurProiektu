<?php
    $hostname = "localhost";
    $username = "admin";
    $password = "test";
    $db = "database";

    $conn = mysqli_connect($hostname, $username, $password, $db);
    if ($conn -> connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $query = mysqli_query($conn, "SELECT * FROM babarrunak");
        or die (mysqli_error($conn));

    while ($row = mysqli_fetch_array($query)) {
        echo "<div class='item'>
                <h3>" . $row['Izena'] . "</h3>
                <p>" . $row['Jatorria'] . "</p>
              </div>";
    }
?>
  
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Babarrunak</title>
</head>
<body>
    <h1>Babarrunak</h1>
    



</body>
</html>