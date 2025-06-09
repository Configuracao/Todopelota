<?php 
$filelink='https://supervideo.cc/e/'.$_GET['v'].'';

if (strpos($filelink,"supervideo.") !== false) {
    require_once("JavaScriptUnpacker.php");

    $ua = "Mozilla/5.0 (Windows NT 10.0; rv:81.0) Gecko/20100101 Firefox/81.0";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $filelink);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_TIMEOUT, 25);
    $h = curl_exec($ch);
    curl_close($ch);

    $out = "";
    if (preg_match("/eval\(function\(p,a,c,k,e,[r|d]?/",$h)) {
        $jsu = new JavaScriptUnpacker();
        $out = $jsu->Unpack($h);
    }

    if (preg_match('/sources\:\[\{file\:\"([^\"]+)\"/',$out,$m)) {
        $link = $m[1];
    }
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8"/>
<title>Supervideo</title>
<meta name="robots" content="noindex" />
<META NAME="GOOGLEBOT" CONTENT="NOINDEX" />
<meta name="referrer" content="never">
<meta http-equiv="X-UA-Compatible" content="IE=11" />
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" id="viewport" name="viewport">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link href="https://cdn.rawgit.com/ufilestorage/a/master/skins/jw-logo-bar.css" rel="stylesheet" type="text/css" />
<link href="https://bundlecode.com/demo/ok/netflix.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://cdn.rawgit.com/ufilestorage/a/master/jquery-2.2.3.min.js"></script>
<script src="https://ssl.p.jwpcdn.com/player/v/8.13.0/jwplayer.js"></script>
<script>jwplayer.key="64HPbvSQorQcd52B8XFuhMtEoitbvY/EXJmMBfKcXZQU2Rnn";</script>
<style type="text/css">body,html{margin:0;padding:0}#uplay_player{position:absolute;width:100%important!;height:100%important!;border:none;overflow:hidden;}</style>
</head>
<body>
<div id="uplay_player"></div>
<script type="text/javascript">
var videoPlayer = jwplayer("uplay_player");
videoPlayer.setup({
sources: [{'file':'<?php echo $link; ?>','type':'video/mp4'}],
cast: {},
width: "100%",
height: "100%",
controls: true,
displaytitle: false,
flashplayer: "https://p.jwpcdn.com/player/v/7.12.8/jwplayer.flash.swf",
fullscreen: "true",
primary: "html5",
autostart: false,
image:'<?php echo $cover; ?>',
advertising: {
                                client: "vast",
                                tag: ""
                            },
tracks: [{
			file: "<?php echo $sub; ?>",
			label: "Subs",
			kind:  "captions",
			default: "true",
			skin: "netflix",
			}],
			captions: {
			color: "#FFFF00",
			fontSize: 14,
			edgeStyle: "uniform",
			backgroundOpacity: 0,
			},
 logo: {
			file: "",
			logoBar: "",
			position: "top-left",
			link: ""
		},
			aboutlink:"",
			abouttext:"",
sharing: {
		//code: encodeURI("<iframe width=\"640\" height=\"380\" src=\"empty-url\" frameborder=\"0\" scrolling=\"no\"></iframe>"),
	},
});
videoPlayer.on("ready",function() {
		jwLogoBar.addLogo(videoPlayer);
	});
</script>
</body>
</html>