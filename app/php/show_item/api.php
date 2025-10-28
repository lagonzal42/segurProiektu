<?php

// Datu basearekin konexioaren parametroak
// Zure docker-compose.yml fitxategitik zuzenean hartuta:
$db_host = 'db'; // 'db' da zerbitzuaren izena docker-compose-n
$db_user = 'admin';
$db_pass = 'test';
$db_name = 'segurproiektua';

// Saia zaitez konektatzen MySQLi erabiliz (zure Dockerfile-n instalatuta dago)
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Konexioa egiaztatu
if ($conn->connect_error) {
    header('Content-Type: application/json');
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Konexio errorea: ' . $conn->connect_error]);
    exit;
}

// Karaktere-jokoa UTF-8 gisa ezarri
$conn->set_charset('utf8mb4');

// Eskaera mota aztertu (GET edo POST)
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // --- GET ESKAERA: Lortu babarrun guztiak ---

    $sql = "SELECT id, Izena, Jatorria, Kolorea, Egozketa_denb_min FROM babarrunak";
    $result = $conn->query($sql);

    $babarrunak = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Ziurtatu zenbakiak zenbaki gisa doazela (eta ez string gisa)
            $row['id'] = (int)$row['id'];
            $row['Egozketa_denb_min'] = $row['Egozketa_denb_min'] ? (int)$row['Egozketa_denb_min'] : null;
            $babarrunak[] = $row;
        }
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Errorea kontsultan: ' . $conn->error]);
        exit;
    }

    // Bidali emaitzak JSON formatuan
    header('Content-Type: application/json');
    echo json_encode($babarrunak);

} elseif ($method === 'POST') {
    // --- POST ESKAERA: Eguneratu babarrun bat ---

    // Lortu bidalitako JSON datuak
    $data = json_decode(file_get_contents('php://input'), true);

    // Egiaztatu datuak jaso ditugula
    if (empty($data) || !isset($data['id'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Datu okerrak edo ID falta da']);
        exit;
    }

    // Datuak prestatu (null balioak onartu)
    $id = (int)$data['id'];
    $izena = $data['Izena'];
    $jatorria = !empty($data['Jatorria']) ? $data['Jatorria'] : null;
    $kolorea = !empty($data['Kolorea']) ? $data['Kolorea'] : null;
    $denbora = !empty($data['Egozketa_denb_min']) ? (int)$data['Egozketa_denb_min'] : null;

    // --- SEGURTASUNA: Sententzia prestatuak erabili SQL Injekzioa saihesteko ---
    $sql = "UPDATE babarrunak 
            SET Izena = ?, Jatorria = ?, Kolorea = ?, Egozketa_denb_min = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    
    // Parametro motak: s = string, i = integer
    // Izena (s), Jatorria (s), Kolorea (s), Egozketa_denb_min (i), id (i)
    $stmt->bind_param('sssii', $izena, $jatorria, $kolorea, $denbora, $id);

    if ($stmt->execute()) {
        // Ondo joan da
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Ondo eguneratua']);
    } else {
        // Errorea egon da
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Errorea eguneratzean: ' . $stmt->error]);
    }

    $stmt->close();

} else {
    // Beste metodo bat (PUT, DELETE...) ez da onartzen
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Metodo ez onartua']);
}

// Itxi konexioa
$conn->close();

?>