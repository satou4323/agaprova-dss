<?php\
error_reporting(E_ALL);\
ini_set('display_errors', 1);\
\
$base = 'C:/Apache24/htdocs/agaprova-dss/';\
$results = [];\
\
// ===== FIX 1: BloqueoController.php =====\
$file1 = $base . 'app/Controllers/BloqueoController.php';\
$content1 = file_get_contents($file1);\
if ($content1 === false) {\
    $results[] = 'ERROR: Cannot read BloqueoController.php';\
} else {\
    // Find the guardarAction and fix the redirect after successful save\
    // Change: redirect to bloqueo/index -> redirect to costoflete/index after success\
    $old = "if (\\$this->db->query(\\$sql, [\\$codigo, \\$nombre, \\$origen, \\$destino, \\$tiempo, \\$tipo_via])) {\
            Session::flash('success', 'Ruta registrada correctamente');\
        } else {\
            Session::flash('error', 'Error al registrar la ruta');\
        }\
        \\$this->redirect('/bloqueo/index');";\
    \
    // Better: use str_replace with exact strings from the file\
    $search = "if ($this->db->query($sql, [$codigo, $nombre, $origen, $destino, $tiempo, $tipo_via])) {\
            Session::flash('success', 'Ruta registrada correctamente');\
        } else {\
            Session::flash('error', 'Error al registrar la ruta');\
        }\
        $this->redirect('/bloqueo/index');";\
    \
    $replace = "if ($this->db->query($sql, [$codigo, $nombre, $origen, $destino, $tiempo, $tipo_via])) {\
            Session::flash('success', 'Ruta creada. Ahora configure el costo de flete para esta ruta.');\
            $this->redirect('/costoflete/index');\
        } else {\
            Session::flash('error', 'Error al registrar la ruta');\
            $this->redirect('/bloqueo/index');\
        }";\
    \
    // Count occurrences first\
    $count = substr_count($content1, "Session::flash('success', 'Ruta registrada correctamente')");\
    $results[] = "BloqueoController occurrences of success flash: " . $count;\
    \
    // Show the relevant section\
    $pos = strpos($content1, 'guardarAction');\
    if ($pos !== false) {\
        $results[] = "guardarAction section: " . substr($content1, $pos, 500);\
    }\
}\
\
echo implode(chr(10) . '---' . chr(10), $results);\
?>
