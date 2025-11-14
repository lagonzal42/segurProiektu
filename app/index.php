<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();

session_regenerate_id(true);

$csp_nonce = base64_encode(random_bytes(16));

header_remove("X-Powered-By");
header("Server: SegurServer");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'nonce-$csp_nonce'; img-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self';");
    

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <style nonce="<?= htmlspecialchars($csp_nonce, ENT_QUOTES) ?>">
        /* Estilos Simples, Minimalistas y Sobrios */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif; /* Fuente moderna y limpia */
            background: #fcfcfc; /* Fondo blanco casi puro */
            color: #333; /* Texto oscuro y legible */
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        h1 {
            color: #2c3e50; /* Tono azul-gris sobrio para el título */
            text-align: center;
            padding: 40px 0 20px 0;
            font-weight: 300; /* Ligero */
            font-size: 2.2em;
            border-bottom: 1px solid #eee; /* Separador sutil */
            margin-bottom: 40px;
        }
        .button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Adaptable y limpio */
            gap: 15px; /* Espacio uniforme */
            max-width: 800px; /* Ancho máximo centrado */
            margin: 0 auto;
            padding: 0 20px;
        }
        .button-grid a {
            text-decoration: none; /* Quitar subrayado del enlace */
        }
        .button-grid button {
            width: 100%;
            height: 60px; /* Altura cómoda */
            background: #ffffff; /* Fondo del botón blanco */
            color: #2c3e50; /* Texto del botón oscuro y sobrio */
            border: 1px solid #bdc3c7; /* Borde muy sutil */
            border-radius: 4px;
            padding: 0 15px;
            font-size: 1em;
            font-weight: 500; /* Peso medio */
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); /* Sombra muy ligera */
            transition: all 0.2s ease-in-out; /* Transición suave */
            text-transform: uppercase; /* Minimalista y profesional */
            letter-spacing: 0.5px;
        }
        .button-grid button:hover {
            background: #ecf0f1; /* Gris muy claro al pasar el ratón */
            border-color: #95a5a6; /* Borde ligeramente más oscuro */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px); /* Efecto 3D sutil */
        }
    </style>
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