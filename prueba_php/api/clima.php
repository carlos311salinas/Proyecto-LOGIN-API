<?php

header('Content-Type: application/json');

session_start();

require_once '../app/config/database.php';

require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(
    dirname(__DIR__)
);

$dotenv->load();


// OBTENER CIUDAD

$ciudad = $_POST['ciudad'] ?? '';

//Obtiene el usuario actual
$usuarioId = $_SESSION['user']['id'];


// VALIDAR

if (empty($ciudad)) {

    echo json_encode([
        'success' => false,
        'message' => 'Debe ingresar una ciudad'
    ]);

    exit;
}


// BUSCAR CACHE

$stmt = $pdo->prepare("

    SELECT *
    FROM consultas_clima

    WHERE ciudad = ?

    AND fecha >= NOW() - INTERVAL 5 MINUTE

    ORDER BY id DESC

    LIMIT 1

");

$stmt->execute([$ciudad]);

$cache = $stmt->fetch(PDO::FETCH_ASSOC);


// SI EXISTE CACHE

if ($cache) {

    // GUARDAR CONSULTA

    $stmt = $pdo->prepare("

                    INSERT INTO consultas_clima (

                        usuario_id,
                        ciudad,
                        temperatura,
                        descripcion,
                        humedad,
                        viento
                    )
                    VALUES (?, ?, ?, ?, ?, ?)
                    ");

    $stmt->execute([

        $usuarioId,

        $data['name'],

        $data['main']['temp'],

        $data['weather'][0]['description'],

        $data['main']['humidity'],

        $data['wind']['speed']
    ]);

    echo json_encode([

        'success' => true,

        'ciudad' => $cache['ciudad'],

        'temperatura' => $cache['temperatura'],

        'descripcion' => $cache['descripcion'],

        'humedad' => $cache['humedad'],

        'viento' => $cache['viento'],

        'cache' => true

    ]);

    exit;
}


// API KEY

$apiKey = $_ENV['WEATHER_API_KEY'];


// URL API

$url = "https://api.openweathermap.org/data/2.5/weather?q={$ciudad}&appid={$apiKey}&units=metric&lang=es";


// CONSUMIR API

$response = @file_get_contents($url);


// VALIDAR RESPUESTA
//convertir el json
$data = json_decode($response, true);

if ($response === false) {

    echo json_encode([
        'success' => false,
        'message' => 'CIUDAD NO ENCONTRADA'
    ]);

    exit;
}




// VALIDAR CIUDAD

if (
    isset($data['cod'])
    &&
    $data['cod'] != 200
) {

    echo json_encode([
        'success' => false,
        'message' => 'Ciudad no encontrada'
    ]);

    exit;
}


// RESPUESTA FINAL

echo json_encode([

    'success' => true,

    'ciudad' => $data['name'],

    'temperatura' =>
        $data['main']['temp'],

    'descripcion' =>
        $data['weather'][0]['description'],

    'humedad' =>
        $data['main']['humidity'],

    'viento' =>
        $data['wind']['speed']

]);