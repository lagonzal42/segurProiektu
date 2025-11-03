<?php
//Sesioen informazioa kargatu
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
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
        h1 {
            text-align: center;
        }
        .button-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 24px;
            max-width: 500px;
            margin: 40px auto 0 auto;
            justify-items: center;
        }
        .button-grid a {
            width: 100%;
            text-align: center;
        }
        .button-grid button {
            width: 90%;
            background: linear-gradient(90deg, #2366a8 60%, #4fa3e3 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 1px 4px rgba(35,102,168,0.10);
            transition: background 0.8s;
        }
        .button-grid button:hover {
            background: linear-gradient(90deg, #4fa3e3 60%, #2366a8 100%);
        }
    </style>
</head>
<body>
    <h1>Hasiera</h1>
    <!-- Hasiera botoien kuadrikula -->
    <div class="button-grid">
        <!-- Hasiera botoiak -->
        <a href="add_items"><button type="button">Gehitu item bat</button></a>
        <a href="delete_item"><button type="button">Borratu item bat</button></a>
        <a href="items"><button type="button">Item-ak</button></a>
        <a href="login"><button type="button">Login</button></a>
        <a href="modify_item"><button type="button">Aldatu itemak</button></a>
        <a href="register"><button type="button">Erregistratu</button></a>
        <a href="show_item"><button type="button">Itemak ikusi detaileekin</button></a>
        <!-- Sesio bat hasi bada botoi berri bat jarri -->
        <?php
        if (isset($_SESSION['nan'])) {
            echo '<a href="show_user?user=' . urlencode($_SESSION['nan']) . '"><button type="button">Nire datuak</button></a>';
        }
        ?>
    </div>
</body>
</html>
