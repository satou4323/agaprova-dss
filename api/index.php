<?php
/**
 * API REST - Endpoints para consultas AJAX
 */

require_once dirname(dirname(__FILE__)) . '/config/config.php';

ErrorHandler::register();
\App\Session::init();

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

try {
    // Verificar autenticación
    if (!\App\Session::get('user_id')) {
        throw new Exception('No autenticado', 401);
    }

    switch ($action) {
        case 'get_precios':
            $precios = \App\Models\Precio::all();
            $response['success'] = true;
            $response['data'] = $precios;
            break;

        case 'get_rutas':
            $rutas = \App\Models\Ruta::all();
            $response['success'] = true;
            $response['data'] = $rutas;
            break;

        case 'get_estaciones':
            $estaciones = \App\Models\Estacion::all();
            $response['success'] = true;
            $response['data'] = $estaciones;
            break;

        case 'get_clima':
            $clima = \App\Models\Clima::getActivo();
            $response['success'] = true;
            $response['data'] = $clima ? $clima->toArray() : null;
            break;

        case 'validar_bloqueo':
            if ($method !== 'POST') throw new Exception('Método no permitido', 405);
            
            $data = json_decode(file_get_contents('php://input'), true);
            $ruta_id = $data['ruta_id'] ?? null;
            $fecha = $data['fecha'] ?? null;

            if (!$ruta_id || !$fecha) {
                throw new Exception('Parámetros inválidos');
            }

            $bloqueado = \App\Models\Bloqueo::estasBloqueada($ruta_id);
            $response['success'] = true;
            $response['data'] = ['puede_enviar' => !$bloqueado];
            break;

        case 'calcular_flete':
            if ($method !== 'POST') throw new Exception('Método no permitido', 405);
            
            $data = json_decode(file_get_contents('php://input'), true);
            $ruta_id = $data['ruta_id'] ?? null;

            if (!$ruta_id) {
                throw new Exception('Parámetros inválidos');
            }

            $costo = \App\Models\CostoFlete::getCostoPorRuta($ruta_id);
            $response['success'] = true;
            $response['data'] = ['costo' => $costo];
            break;

        case 'listar_simulaciones':
            $simulaciones = \App\Models\EscenarioSimulacion::getEscenarios();
            $response['success'] = true;
            $response['data'] = $simulaciones;
            break;

        case 'obtener_simulacion':
            $id = $_GET['id'] ?? null;
            if (!$id) throw new Exception('ID requerido');
            
            $simulacion = \App\Models\EscenarioSimulacion::find($id);
            if (!$simulacion) throw new Exception('Simulación no encontrada', 404);
            
            $response['success'] = true;
            $response['data'] = $simulacion->toArray();
            break;

        default:
            throw new Exception('Acción no soportada: ' . $action);
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    http_response_code($e->getCode() ?: 400);
}

echo json_encode($response);
