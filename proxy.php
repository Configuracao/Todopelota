<?php
if (!isset($_GET['url'])) {
    http_response_code(400);
    die("Error: URL no proporcionada");
}

$url = $_GET['url'];

// Configurar cabeceras para permitir reproducci?n en el navegador
header("Access-Control-Allow-Origin: *");
header("Content-Type: video/MP2T");
header("Accept-Ranges: bytes");

$opts = ["http" => ["header" => "User-Agent: Ranger/4.5.2-f8cfa536\r\n"]];
$context = stream_context_create($opts);

$stream = fopen($url, 'rb', false, $context);

if (!$stream) {
    http_response_code(502);
    die("Error: No se pudo obtener el segmento.");
}

// Leer y enviar los datos al reproductor sin interrupciones
fpassthru($stream);
fclose($stream);
?>