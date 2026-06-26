<?php\
$base = 'C:/Apache24/htdocs/agaprova-dss/';\
$file = $base . 'vendor/tecnickcom/tcpdf/include/tcpdf_static.php';\
$c = file_get_contents($file);\
$lines = explode(chr(10), $c);\
for ($i = 95; $i < 140 && $i < count($lines); $i++) {\
    echo ($i+1) . ': ' . $lines[$i] . chr(10);\
}\
?>
