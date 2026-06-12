<?php
/**
 * API REST - Endpoints para consultas AJAX
 */

require_once dirname(dirname(__FILE__)) . '/config/config.php';

ErrorHandler::register();
Session::start();

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
    if (!Session::get('usuario_id')) {
        throw new Exception('No autenticado', 401);
    }

    switch ($action) {
        case 'get_precios':
            $precios = Precio::getAll();
            $response['success'] = true;
            $response['data'] = $precios;
            break;

        case 'get_rutas':
            $rutas = Ruta::getAll();
            $response['success'] = true;
            $response['data'] = $rutas;
            break;

        case 'get_estaciones':
            $estaciones = Estacion::getAll();
            $response['success'] = true;
            $response['data'] = $estaciones;
            break;

        case 'get_clima':
            $estacion_id = $_GET['estacion_id'] ?? null;
            if (!$estacion_id) throw new Exception('estacion_id requerido');
            
            $clima = Clima::where(['estacion_id' => $estacion_id])->first();
            $response['success'] = true;
            $response['data'] = $clima;
            break;

        case 'validar_bloqueo':
            if ($method !== 'POST') throw new Exception('Método no permitido', 405);
            
            $data = json_decode(file_get_contents('php://input'), true);
            $ruta_id = $data['ruta_id'] ?? null;
            $fecha = $data['fecha'] ?? null;

            if (!$ruta_id || !$fecha) {
                throw new Exception('Parámetros inválidos');
            }

            $canShip = Helpers::canShipOnDate($ruta_id, $fecha);
            $response['success'] = true;
            $response['data'] = ['puede_enviar' => $canShip];
            break;

        case 'calcular_flete':
            if ($method !== 'POST') throw new Exception('Método no permitido', 405);
            
            $data = json_decode(file_get_contents('php://input'), true);
            $ruta_id = $data['ruta_id'] ?? null;
            $cantidad_ganado = $data['cantidad_ganado'] ?? null;

            if (!$ruta_id || !$cantidad_ganado) {
                throw new Exception('Parámetros inválidos');
            }

            $costo = CostoFlete::calcularCosto($ruta_id, $cantidad_ganado);
            $response['success'] = true;
            $response['data'] = ['costo' => $costo];
            break;

        case 'listar_simulaciones':
            $usuario_id = Session::get('usuario_id');
            $simulaciones = EscenarioSimulacion::where(['usuario_id' => $usuario_id])->get();
            $response['success'] = true;
            $response['data'] = $simulaciones;
            break;

        case 'obtener_simulacion':
            $id = $_GET['id'] ?? null;
            if (!$id) throw new Exception('ID requerido');
            
            $simulacion = EscenarioSimulacion::find($id);
            if (!$simulacion) throw new Exception('Simulación no encontrada', 404);
            
            $response['success'] = true;
            $response['data'] = $simulacion;
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
