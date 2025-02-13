<?php
error_reporting(E_ERROR | E_PARSE);

// Recibe la URL completa desde el parámetro 'v'
$filelink = htmlspecialchars($_GET['url'], ENT_QUOTES, 'UTF-8');
$link = '';

// Verifica si la URL pertenece a filemoon.sx, streamwish.to o playerwish.com
if (!empty($filelink) && (strpos($filelink, "dhtpre.com") !== false || 
                          strpos($filelink, "streamwish.to") !== false || 
                          strpos($filelink, "fastbrisk.com") !== false || 
                          strpos($filelink, "swishsrv.com") !== false || 
                          strpos($filelink, "playerwish.com") !== false || 
                          strpos($filelink, "filemooon.link") !== false || 
                          strpos($filelink, "filegram.to") !== false || 
                          strpos($filelink, "listeamed.net") !== false || 
                          strpos($filelink, "iplayerhls.com") !== false || 
                          strpos($filelink, "jwplayerhls.com") !== false)) {

    require_once("JavaScriptUnpacker.php");
    require_once("tear.php");

    $ua = "Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Mobile Safari/537.36";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $filelink);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_TIMEOUT, 25);
    
    $h = curl_exec($ch);
    
    if (curl_errno($ch)) {
        error_log('cURL Error: ' . curl_error($ch));
    }
    
    curl_close($ch);

    $out = "";
    if (preg_match("/eval\(function\(p,a,c,k,e,[r|d]?/",$h)) {
        $jsu = new JavaScriptUnpacker();
        $out = $jsu->Unpack($h);
    }

    if (preg_match("/sources\:\[\{file\:\"([^\"]+)\"/",$out,$m)) {
        $link = $m[1];
    }
}

header('Content-Type: application/json');
echo json_encode(['link' => $link]);