<?php
namespace App\Controllers;

use App\Controller;
use App\Session;
use App\Models\{LoteGanado, Precio, Clima, Ruta};
use App\Services\ReporteService;

class DashboardController extends Controller {
    
    public function indexAction() {
        $reporteService = new ReporteService();
        $estadisticas = $reporteService->obtenerEstadisticas();
        
        // Obtener último lote
        $ultimo_lote = LoteGanado::getUltimo();
        
        // Precios actuales
        $precios = Precio::getPreciosActivos();
        
        // Clima actual
        $clima = Clima::getActivo();
        
        // Rutas con bloqueos
        $rutas = Ruta::getWithBloqueo();

        // Últimos 5 lotes para la tabla del dashboard
        $ultimos_lotes = LoteGanado::getUltimos(5);
        
        $this->render('dashboard.index', [
            'usuario'       => Session::get('nombre'),
            'estadisticas'  => $estadisticas,
            'ultimo_lote'   => $ultimo_lote,
            'ultimos_lotes' => $ultimos_lotes,
            'precios'       => $precios,
            'clima'         => $clima,
            'rutas'         => $rutas,
            'page_title'    => 'Dashboard',
            'csrf'          => $this->generateCsrf()
        ]);
    }
}