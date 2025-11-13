<?php

// 1. Mejora de la seguridad: Configuración de cabeceras HTTP
// CSP actualizado para permitir la carga de scripts y estilos desde el mismo origen ('self')
header("Content-Security-Policy: default-src 'none'; script-src 'self'; style-src 'self'; img-src 'self'; connect-src 'self'; form-action 'self'; frame-ancestors 'none';");
header_remove("X-Powered-By");
header("Server: SegurServer");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// 2. Configuración de la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "segurproiektua";

// Conexión a la base de datos
$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    // Manejo de error de conexión.
    die("Error de conexión a la base de datos.");
}

// 3. Obtener la lista de elementos para mostrar
// ** NOTA DE SEGURIDAD **: Esta consulta no toma entradas del usuario, por lo que es segura.
$sql = "SELECT Izena, Jatorria FROM babarrunak ORDER BY id DESC";
$result = $conn->query($sql);
?>
  
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Babarrunak Listado</title>
    <!-- CSS y JS externos para CSP (Content Security Policy) -->
    <link rel="stylesheet" href="php/items/styles.css">
    <script src="php/items/script.js"></script>
</head>
<body>
    <h1>Babarrunak (Listado)</h1>
    <table>
        <tr>
            <th>Izena</th>
            <th>Jatorria</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <!-- Prevenir XSS: Escapar los datos antes de mostrarlos -->
                    <td><?= htmlspecialchars($row['Izena']) ?></td>
                    <td><?= htmlspecialchars($row['Jatorria']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="2">Ez dago produkturik. (No hay productos)</td></tr>
        <?php endif; ?>
    </table>
    
    <div class="button-container">
        <!-- El botón "Gehitu babarrunak" sigue siendo un enlace -->
        <a href="add_items" class="add-link">
            <button class="add-btn">Gehitu babarrunak</button>
        </a>
        <!-- El botón "Hasierara" ahora usa un ID para JS externo -->
        <button type="button" id="hasiera_btn" class="modify-btn">Hasierara</button>
    </div>

</body>
</html>

<?php
// Cerrar la conexión al finalizar
if ($result) {
    $result->free();
}
$conn->close();
?>