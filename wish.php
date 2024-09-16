<?php
error_reporting(E_ERROR | E_PARSE);

// Recibe la URL completa desde el parámetro 'v'
$filelink = htmlspecialchars($_GET['v'], ENT_QUOTES, 'UTF-8');
$link = '';

// Verifica si la URL pertenece a filemoon.sx, streamwish.to o playerwish.com
if (!empty($filelink) && (strpos($filelink, "filemoon.sx") !== false || 
                          strpos($filelink, "streamwish.to") !== false || 
                          strpos($filelink, "playerwish.com") !== false)) {

    require_once("JavaScriptUnpacker.php");
    require_once("tear.php");

    $ua = "Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Mobile Safari/537.36";
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

echo $lin;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JW Player Custom Skin</title>

    <!-- Incluir la hoja de estilo personalizada -->
    <link rel="stylesheet" href="css/hotstar.css">

    <!-- Incluir la librería de JW Player -->
    <script src="https://content.jwplatform.com/libraries/KB5zFt7A.js"></script>
    <style type="text/css">
        body, html {
            margin: 0;
            padding: 0;
        }

        #my-player {
            position: absolute;
            width: 100% !important;
            height: 100% !important;
            border: none;
            overflow: hidden;
        }

        /* Ocultar el botón de retroceso por defecto */
        .jw-icon-rewind {
            display: none !important;
        }

        /* Estilo de los botones personalizados */
        .jw-icon-forward, .jw-icon-rewind-custom {
            width: 40px; /* Ajusta el tamaño del botón */
            height: 40px;
            background-size: contain;
            cursor: pointer;
            display: inline-block;
            margin-right: 10px; /* Espacio entre los botones */
        }

        .jw-icon-forward {
            background: url('https://i.ibb.co/v4KNPs7/forward-10s.png') no-repeat center center;
        }

        .jw-icon-rewind-custom {
            background: url('https://i.ibb.co/hdypSdg/descarga.png') no-repeat center center;
        }
    </style>
</head>
<body>

    <!-- Contenedor para el reproductor -->
    <div id="my-player" class="jwplayer jw-skin-netflix"></div>

    <script>
          function getParameterByName(name) {
              name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
              var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                  results = regex.exec(location.search);
              return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
          }
              var getURL  = getParameterByName('get');
              var getIMG  = getParameterByName('img');
              var getDES  = getParameterByName('description');
              var getTITU  = getParameterByName('titu');
              var getLang = getParameterByName('lang');

        if (getURL == "#") {alert('Vuelve a la pÃƒÆ’Ã‚Â¡gina anterior');}
        if (getIMG == "#") {alert('Vuelve a la pÃƒÆ’Ã‚Â¡gina anterior');}

      </script>
    <script type="text/javascript">
    var videoPlayer = jwplayer("my-player");
    videoPlayer.setup({
        file: '<?= $link ?>',
        image: (getIMG),  // URL de la imagen que se mostrará como poster
        title: 'Reproductor DarkPlus',  // Título que se mostrará
        description: (getDES),
        width: "100%",
        height: "100%",
        controls: true,
        displaytitle: true,  // Mostrar el título
        fullscreen: true,
        primary: "html5",
        autostart: false,
        advertising: {
            client: "vast",
            tag: "#"
        },
        tracks: [{
            file: "<?php echo isset($srt) ? $srt : ''; ?>",
            label: "Subs",
            kind: "captions",
            default: true,
        }],
        captions: {
            color: "#FFFF00",
            fontSize: 14,
            edgeStyle: "uniform",
            backgroundOpacity: 0,
        },
        logo: {
            file: "#",
            logoBar: "",
            position: "top-left",
            link: ""
        },
        skin: {
            name: "hotstar"  // Nombre de la skin
        }
    });

    // Esperar a que el reproductor esté listo antes de agregar los controles personalizados
    videoPlayer.on('ready', function() {
        // Agregar el botón de adelantar 10 segundos
        videoPlayer.addButton(
            'https://i.ibb.co/v4KNPs7/forward-10s.png',  // Icono del botón
            'Adelantar 10 segundos',  // Descripción
            function() {
                var currentPosition = videoPlayer.getPosition();
                videoPlayer.seek(currentPosition + 10);
            },
            'forward'  // ID del botón
        );

        // Agregar el botón de retroceder 10 segundos
        videoPlayer.addButton(
            'https://i.postimg.cc/TYk9BN1y/descarga.png',  // Icono del botón
            'Retroceder 10 segundos',  // Descripción
            function() {
                var currentPosition = videoPlayer.getPosition();
                videoPlayer.seek(currentPosition - 10);
            },
            'rewind-custom',  // ID del botón
            true  // Colocar el botón al inicio de la barra de control
        );
    });
    </script>

</body>
</html>