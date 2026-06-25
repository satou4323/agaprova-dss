<?php
$destinos = [
    1 => ['ciudad' => 'Santa Cruz',  'mercado' => 'Mercado Mayorista Santa Cruz'],
    2 => ['ciudad' => 'Cochabamba', 'mercado' => 'Feria de Punata - Cochabamba'],
    3 => ['ciudad' => 'Santa Cruz',  'mercado' => 'Mercado Mayorista Santa Cruz'],
    4 => ['ciudad' => 'Cochabamba', 'mercado' => 'Feria de Punata - Cochabamba'],
];
$rutas_nombres = [
    1 => 'Samaipata',
    2 => 'Comarapa',
    3 => 'Ipati-Abapó',
    4 => 'Aiquile',
];
?>

<style>
.callout.callout-success {
  animation: fadeInDown 0.4s ease;
}
@keyframes fadeInDown {
  from { opacity:0; transform:translateY(-8px); }
  to   { opacity:1; transform:translateY(0); }
}
.table-sm tbody tr:hover {
  background-color: rgba(0,0,0,0.025);
}
</style>

<div class="row mb-2 mt-3">
  <div class="col-sm-6">
    <h3 class="m-0"><i class="fas fa-chart-bar text-primary mr-1"></i> Resultado de Simulación</h3>
    <small class="text-muted">Basado en datos reales del sistema</small>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?php echo BASE_URL; ?>/simulacion/index" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i> Nueva Simulación
    </a>
  </div>
</div>

<?php if ($resultado['factible']):
  $ruta_opt    = $resultado['detalles']['ruta_optima'];
  $cabezas     = $resultado['detalles']['cabezas'];
  $costo_opt   = $resultado['detalles']['costo_c' . $ruta_opt];
  $margen_opt  = $resultado['detalles']['margen_r' . $ruta_opt];
  $inversion   = $costo_opt * $cabezas;
  $ganancia    = $resultado['ganancia_total'];
  $ingreso_bruto = ($margen_opt + $costo_opt) * $cabezas;
  $ciudad_opt  = $destinos[$ruta_opt]['ciudad'];
  $mercado_opt = $destinos[$ruta_opt]['mercado'];
?>

<!-- 1. MENSAJE DE RECOMENDACIÓN -->
<div class="callout callout-success mt-2 mb-3" style="border-left-color:#28a745; background:#f0fff4;">
  <h4 style="color:#155724; margin-bottom:4px;">
    <i class="fas fa-check-circle text-success mr-2"></i>
    Es recomendable mandar a <strong><?php echo $ciudad_opt; ?></strong>
  </h4>
  <p class="mb-0" style="color:#155724; font-size:0.9rem;">
    La ruta vía <strong><?php echo $rutas_nombres[$ruta_opt]; ?></strong> (R<?php echo $ruta_opt; ?>)
    ofrece la mejor ganancia neta con
    <strong>Bs <?php echo number_format($ganancia, 2); ?></strong>
    para <?php echo number_format($cabezas, 0); ?> cabezas.
  </p>
</div>

<!-- 2. KPI Badges -->
<div class="row mb-3">
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background: linear-gradient(135deg, #43A047 0%, #2E7D32 100%);">
      <div class="inner">
        <h3 style="color:#fff; font-size:1.2rem; font-weight:700;"><i class="fas fa-check-circle mr-1"></i> FACTIBLE</h3>
        <p style="color:rgba(255,255,255,0.85); font-size:0.85rem; margin-bottom:0;">Solución</p>
      </div>
      <div class="icon"><i class="fas fa-check-circle" style="color:rgba(255,255,255,0.2); font-size:70px;"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background: linear-gradient(135deg, #1565C0 0%, #0D47A1 100%);">
      <div class="inner">
        <h3 style="color:#fff; font-size:1.1rem; font-weight:700;">R<?php echo $ruta_opt; ?> → <?php echo $ciudad_opt; ?></h3>
        <p style="color:rgba(255,255,255,0.85); font-size:0.85rem; margin-bottom:0;">Ruta Óptima</p>
      </div>
      <div class="icon"><i class="fas fa-route" style="color:rgba(255,255,255,0.2); font-size:70px;"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background: linear-gradient(135deg, #E53935 0%, #B71C1C 100%);">
      <div class="inner">
        <h3 style="color:#fff; font-size:1.1rem; font-weight:700;">Bs <?php echo number_format($inversion, 2); ?></h3>
        <p style="color:rgba(255,255,255,0.85); font-size:0.85rem; margin-bottom:0;">Inversión (Flete)</p>
      </div>
      <div class="icon"><i class="fas fa-truck" style="color:rgba(255,255,255,0.2); font-size:70px;"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background: linear-gradient(135deg, #43A047 0%, #1B5E20 100%);">
      <div class="inner">
        <h3 style="color:#fff; font-size:1.1rem; font-weight:700;">Bs <?php echo number_format($ganancia, 2); ?></h3>
        <p style="color:rgba(255,255,255,0.85); font-size:0.85rem; margin-bottom:0;">Ganancia Neta</p>
      </div>
      <div class="icon"><i class="fas fa-money-bill-wave" style="color:rgba(255,255,255,0.2); font-size:70px;"></i></div>
    </div>
  </div>
</div>

<!-- 3. TABLA RUTA ÓPTIMA -->
<div class="card card-outline card-success mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">
      <i class="fas fa-star text-warning mr-1"></i>
      Ruta Óptima — R<?php echo $ruta_opt; ?> vía <?php echo $rutas_nombres[$ruta_opt]; ?> → <?php echo $ciudad_opt; ?>
    </h3>
    <span class="badge badge-success" style="font-size:0.85rem;">ÓPTIMA</span>
  </div>
  <div class="card-body p-0">
    <table class="table table-bordered mb-0">
      <tr>
        <td width="40%"><strong><i class="fas fa-map-marker-alt mr-1"></i> Ciudad Destino</strong></td>
        <td>
          <!-- Problema 13: badge verde en lugar de rojo -->
          <span class="badge badge-success" style="font-size:0.95rem; padding:5px 12px;">
            <i class="fas fa-map-marker-alt mr-1"></i><?php echo $ciudad_opt; ?>
          </span>
          <span class="text-muted ml-2" style="font-size:0.85rem;"><?php echo $mercado_opt; ?></span>
        </td>
      </tr>
      <tr>
        <td><strong><i class="fas fa-cow text-secondary mr-1"></i> Cabezas</strong></td>
        <td><?php echo number_format($cabezas, 0); ?> cabezas</td>
      </tr>
      <tr>
        <td><strong><i class="fas fa-truck text-danger mr-1"></i> Costo Flete/cabeza</strong></td>
        <td class="text-danger">Bs <?php echo number_format($costo_opt, 2); ?></td>
      </tr>
      <tr style="background:#fff3f3;">
        <td><strong><i class="fas fa-truck text-danger mr-1"></i> Inversión Total (Flete)</strong></td>
        <td class="text-danger font-weight-bold">− Bs <?php echo number_format($inversion, 2); ?></td>
      </tr>
      <tr>
        <td><strong><i class="fas fa-coins text-primary mr-1"></i> Ingreso Bruto</strong></td>
        <td class="text-primary font-weight-bold">Bs <?php echo number_format($ingreso_bruto, 2); ?></td>
      </tr>
      <tr style="background:#f0fff4; border-top:3px solid #43A047;">
        <td><strong><i class="fas fa-money-bill-wave text-success mr-1"></i> Ganancia Neta</strong></td>
        <td class="text-success font-weight-bold" style="font-size:1.1rem;">= Bs <?php echo number_format($ganancia, 2); ?></td>
      </tr>
    </table>
  </div>
</div>

<!-- 4. COMPARATIVA CON OTRAS RUTAS -->
<!-- Problema 11: título más prominente -->
<div class="d-flex align-items-center mt-4 mb-3 pb-2" style="border-bottom:2px solid #dee2e6;">
  <i class="fas fa-exchange-alt text-secondary mr-2" style="font-size:1.1rem;"></i>
  <h5 class="mb-0 text-dark font-weight-bold">Comparativa con otras rutas</h5>
  <small class="text-muted ml-2">— calculadas con las mismas <strong><?php echo number_format($cabezas, 0); ?></strong> cabezas</small>
</div>

<?php
$todas_rutas = [
    1 => ['margen' => $resultado['detalles']['margen_r1'], 'costo' => $resultado['detalles']['costo_c1'], 'disponible' => $resultado['detalles']['disponibles'][1]],
    2 => ['margen' => $resultado['detalles']['margen_r2'], 'costo' => $resultado['detalles']['costo_c2'], 'disponible' => $resultado['detalles']['disponibles'][2]],
    3 => ['margen' => $resultado['detalles']['margen_r3'], 'costo' => $resultado['detalles']['costo_c3'], 'disponible' => $resultado['detalles']['disponibles'][3]],
    4 => ['margen' => $resultado['detalles']['margen_r4'], 'costo' => $resultado['detalles']['costo_c4'], 'disponible' => $resultado['detalles']['disponibles'][4]],
];

uasort($todas_rutas, function($a, $b) {
    if ($a['disponible'] && !$b['disponible']) return -1;
    if (!$a['disponible'] && $b['disponible']) return 1;
    return $b['margen'] - $a['margen'];
});

foreach ($todas_rutas as $num => $r):
    if ($num == $ruta_opt) continue;

    $inv_alt   = $r['costo'] * $cabezas;
    $gan_alt   = $r['margen'] * $cabezas;
    $bloqueada = !$r['disponible'];
    $diferencia = $gan_alt - $ganancia;

    // Problema 12: borde izquierdo por ciudad destino
    $borderColor = (strpos($destinos[$num]['ciudad'], 'Santa Cruz') !== false) ? '#1565C0' : '#6A1B9A';
    $card_color  = $bloqueada ? 'card-danger' : 'card-secondary';
    $badge = $bloqueada
        ? '<span class="badge badge-danger"><i class="fas fa-lock mr-1"></i>BLOQUEADA</span>'
        : '<span class="badge badge-secondary">Alternativa</span>';

    // Problema 9: colores en celda vs ruta óptima
    $claseVs = $diferencia < 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold';
    $bgVs    = $diferencia < 0 ? 'background:rgba(220,53,69,0.08);' : 'background:rgba(40,167,69,0.08);';
    $flechaVs = $diferencia < 0 ? '▼' : '▲';
?>
<div class="card card-outline <?php echo $card_color; ?> mb-3"
     style="border-left: 4px solid <?php echo $bloqueada ? '#dc3545' : $borderColor; ?>;">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">
      <i class="fas fa-route mr-1"></i>
      R<?php echo $num; ?> — <?php echo $rutas_nombres[$num]; ?> → <?php echo $destinos[$num]['ciudad']; ?>
    </h3>
    <div>
      <?php echo $badge; ?>
      <?php if (!$bloqueada): ?>
        <span class="badge badge-info ml-1">
          <i class="fas fa-map-marker-alt mr-1"></i><?php echo $destinos[$num]['ciudad']; ?>
        </span>
      <?php endif; ?>
    </div>
  </div>
  <div class="card-body p-0">
    <table class="table table-sm mb-0">
      <thead class="thead-light">
        <tr>
          <th>Cabezas</th>
          <th>Costo Flete/cab</th>
          <th>Inversión Total</th>
          <th>Margen Neto/cab</th>
          <!-- Problema 10: renombrado a "Ganancia Neta" -->
          <th>Ganancia Neta</th>
          <th>vs Ruta Óptima</th>
        </tr>
      </thead>
      <tbody>
        <tr class="<?php echo $bloqueada ? 'table-danger' : ''; ?>">
          <td><?php echo number_format($cabezas, 0); ?></td>
          <td class="text-danger">Bs <?php echo number_format($r['costo'], 2); ?></td>
          <td class="text-danger">− Bs <?php echo number_format($inv_alt, 2); ?></td>
          <td class="font-weight-bold" style="color:<?php echo COLOR_PRIMARY; ?>;">
            Bs <?php echo number_format($r['margen'], 2); ?>
          </td>
          <td class="font-weight-bold <?php echo $gan_alt >= 0 ? 'text-success' : 'text-danger'; ?>">
            <?php echo $bloqueada ? '<span class="text-muted">—</span>' : 'Bs ' . number_format($gan_alt, 2); ?>
          </td>
          <!-- Problema 9: fondo y color según diferencia -->
          <td class="<?php echo $claseVs; ?>" style="<?php echo $bloqueada ? '' : $bgVs; ?>">
            <?php if ($bloqueada): ?>
              <span class="text-muted">No disponible</span>
            <?php else: ?>
              <?php echo $flechaVs; ?> Bs <?php echo number_format(abs($diferencia), 2); ?>
            <?php endif; ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php endforeach; ?>

<?php else: ?>

<div class="callout callout-danger mt-3">
  <h5><i class="fas fa-exclamation-triangle mr-1"></i> No hay solución factible</h5>
  <p class="mb-0">Bajo los parámetros ingresados no existe una ruta disponible para completar el despacho.</p>
</div>

<?php if (!empty($resultado['detalles']['diagnostico'])): ?>
  <div class="card card-outline card-danger mt-3">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-search mr-1"></i> Diagnóstico de Restricciones</h3>
    </div>
    <div class="card-body p-0">
      <ul class="list-group list-group-flush">
        <?php foreach ($resultado['detalles']['diagnostico'] as $msg): ?>
          <li class="list-group-item text-danger">
            <i class="fas fa-times-circle mr-2"></i><?php echo $msg; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
<?php endif; ?>

<?php endif; ?>

<!-- Problema 8: botón único al final -->
<div class="text-center mt-4 mb-2">
  <a href="<?php echo BASE_URL; ?>/simulacion/index" class="btn btn-lg"
     style="background:#2E7D32; border-color:#2E7D32; color:white; padding:10px 30px;">
    <i class="fas fa-redo mr-2"></i> Nueva Simulación
  </a>
</div>