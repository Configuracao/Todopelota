<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/vnd.apple.mpegurl");

$streamUrl = "http://cukuyx.dh01ddfddf.xyz/live/cyx_93531158996778016_480p.m3u8";

$headers = [
    "User-Agent: Ranger/4.5.2-f8cfa536",
    "App: com.msandroid.mobile",
    "App-Version: 50710",
    "Content-Auth: spared_addr=http://cukuyx.dh01ddfddf.xyz/v3/youshi/&app_id=magmob&user_id=673818360&media_encrypted=0&client_ip=2803:1800:401f:a48a:7c31:66ff:fef9:9396&dev_id=36b5741b&link=cf&session_id=wSxrjVHqpD2n&sign_type=cfl&auth_id=673818360_magmob__0&app_ver=50710&main_addr=http://cukuyx.dh01ddfddf.xyz/v3/youshi/&expired=1739139206&tag=8B645F&check_play_ip=true&token=0C5A450A84EA9029312ADCC7C581F915&sign2_method=sign_o3&instance=0&start_moment=1739129320790&sign2=e364a3845bb86ef3e81734277a58aea0",
    "Content-License: app_id=magmob&tag=8B645F&scheme=md5-01&media_code=cyx_93531158996778016_480p&expired=1739559007&token=0EEFCB735AC1AD83DFC188B1BFDC3EB3",
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $streamUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    // Reemplazar los enlaces de los segmentos para que pasen por el proxy
    $response = preg_replace_callback('/(http[^\s]+\.ts)/', function ($matches) {
        return "https://pelistart.free.nf/proxy.php?url=" . urlencode($matches[1]);
    }, $response);
}

echo $response;
?>