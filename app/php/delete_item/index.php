<?php

// 1. Mejora de la seguridad: Configuración de cabeceras HTTP
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
    // Es mejor no revelar detalles de la DB en un entorno de producción.
    // Aquí usamos die() solo para un ejemplo de desarrollo/error crítico.
    die("Error de conexión a la base de datos.");
}

$status_message = '';

if (isset($_GET['item'])) {
    // Sanitize and validate input
    $item_raw = trim($_GET['item']);
    
    if ($item_raw === '') {
        $status_message = "<p class='status-error'>❌ Izena hutsik. (El nombre está vacío)</p>";
    } else {
        // ** SOLUCIÓN A SQL INJECTION: USO DE PREPARED STATEMENTS **
        $item_clean = $item_raw;

        // --- Paso 1: Comprobar existencia (SELECT con Prepared Statement) ---
        // Usamos Prepared Statements para garantizar que la variable $item_clean
        // nunca se interprete como código SQL, solo como dato.
        $stmt_check = $conn->prepare("SELECT COUNT(*) AS cnt FROM babarrunak WHERE Izena = ?");
        
        if ($stmt_check) {
            $stmt_check->bind_param("s", $item_clean); // 's' indica que el parámetro es un string
            $stmt_check->execute();
            $check_res = $stmt_check->get_result();
            $row = $check_res->fetch_assoc();
            $stmt_check->close();

            if ((int)$row['cnt'] === 0) {
                // El nombre se escapa para evitar XSS al mostrarlo en el HTML
                $status_message = "<p class='status-warning'>ℹ️ \"" . htmlspecialchars($item_raw) . "\" ez da existitzen. (No existe)</p>";
            } else {
                // --- Paso 2: Ejecutar borrado (DELETE con Prepared Statement) ---
                $stmt_del = $conn->prepare("DELETE FROM babarrunak WHERE Izena = ? LIMIT 1");
                
                if ($stmt_del) {
                    $stmt_del->bind_param("s", $item_clean); // 's' indica que el parámetro es un string
                    
                    if ($stmt_del->execute()) {
                        if ($stmt_del->affected_rows > 0) {
                            $status_message = "<p class='status-success'>✅ \"" . htmlspecialchars($item_raw) . "\" babarruna borratu da. (Borrado con éxito)</p>";
                        } else {
                            $status_message = "<p class='status-warning'>ℹ️ Ez da ezer ezabatu. (No se borró nada)</p>";
                        }
                    } else {
                         // Error en la ejecución del DELETE
                        $status_message = "<p class='status-error'>❌ Errore bat gertatu da borratzean. (Error al borrar)</p>";
                    }
                    $stmt_del->close();
                } else {
                    // Error en la preparación del DELETE
                    $status_message = "<p class='status-error'>❌ Errore bat gertatu da. (Error en la preparación)</p>";
                }
            }
        } else {
            // Error en la preparación del SELECT
            $status_message = "<p class='status-error'>❌ Errore bat comprobando existencia. (Error al comprobar existencia)</p>";
        }
    }
}

// 3. Obtener la lista de elementos para mostrar
$sql_select_all = "SELECT * FROM babarrunak ORDER BY id DESC";
$result_all = $conn->query($sql_select_all);

?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Babarrunak ezabatu - Segurua</title>
    <!-- CSS y JS externos para CSP (Content Security Policy) -->
    <link rel="stylesheet" href="php/delete_item/styles.css">
    <script src="php/delete_item/script.js"></script>
</head>
<body>

    <h1>Babarrunak (Borrar Elementos)</h1>

    <div class="back-container">
        <!-- El botón ahora usa un ID y la lógica está en script.js -->
        <button type="button" class="back-btn" id="back-to-home">Hasierara</button>
    </div>

    <!-- Mostrar mensaje de estado (ya no usa style inline) -->
    <?php echo $status_message; ?>

    <table>
        <tr>
            <th>Izena</th>
            <th>Ekintza</th>
        </tr>
        <?php
        if ($result_all && $result_all->num_rows > 0) {
            while ($row = $result_all->fetch_assoc()) {
                echo "<tr>";
                // Escapar el nombre para prevenir XSS en la tabla
                echo "<td>" . htmlspecialchars($row['Izena']) . "</td>";
                echo "<td>
                    <form method='get' action='delete_item.php'>
                        <!-- El valor también se escapa, usando ENT_QUOTES para manejar comillas correctamente -->
                        <input type='hidden' name='item' value='" . htmlspecialchars($row['Izena'], ENT_QUOTES) . "'>
                        <button type='submit'>Ezabatu</button>
                    </form>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Ez dago produkturik. (No hay productos)</td></tr>";
        }
        ?>
    </table>

</body>
</html>

<?php
// Cerrar la conexión al finalizar
if ($result_all) {
    $result_all->free();
}
$conn->close();
?>