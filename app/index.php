<?php

header("Content-Security-Policy: default-src 'none'; script-src 'self'; style-src 'self'; img-src 'self'; connect-src 'self'; form-action 'self'; frame-ancestors 'none';");
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

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Hasiera</h1>
    <div class="button-grid">
        <a href="add_items"><button type="button">Gehitu item bat</button></a>
        <a href="delete_item"><button type="button">Borratu item bat</button></a>
        <a href="items"><button type="button">Item-ak</button></a>
        <a href="login"><button type="button">Login</button></a>
        <a href="modify_item"><button type="button">Aldatu itemak</button></a>
        <a href="register"><button type="button">Erregistratu</button></a>
        <a href="show_item"><button type="button">Itemak ikusi detaileekin</button></a>
        <?php
        if (isset($_SESSION['nan'])) {
            echo '<a href="show_user?user=' . urlencode($_SESSION['nan']) . '"><button type="button">Nire datuak</button></a>';
        }
        ?>
    </div>
</body>
</html>