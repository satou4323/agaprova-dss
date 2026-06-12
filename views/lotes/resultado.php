<?php if ($resultado['factible']): ?>
  <div class="callout callout-success" style="padding: 0.8rem 1rem; font-size: 0.9rem;">
    <h5 style="margin-bottom: 0;"><i class="fas fa-check-circle"></i> Solución Factible Encontrada</h5>
  </div>

  <div class="row mt-4">
    <div class="col-lg-4 col-6">
      <div class="small-box" style="background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%); box-shadow: 0 2px 6px rgba(46,125,50,0.25);">
        <div class="inner">
          <h3 style="color: #fff; font-size: 1.1rem; font-weight: 700;"><?php
            $ruta_names = [
              1 => 'R01 (Samaipata)',
              2 => 'R02 (Comarapa)',
              3 => 'R03 (Ipati-Abapó)',
              4 => 'R04 (Aiquile)'
            ];
            echo $ruta_names[$resultado['detalles']['ruta_optima']] ?? 'Desconocida';
          ?></h3>
          <p style="color: rgba(255,255,255,0.85); font-size: 0.85rem; margin-bottom: 0;">Ruta Asignada</p>
        </div>
        <div class="icon">
          <i class="fas fa-truck" style="color: rgba(255,255,255,0.2); font-size: 60px;"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-6">
      <div class="small-box" style="background: linear-gradient(135deg, #388E3C 0%, #2E7D32 100%); box-shadow: 0 2px 6px rgba(56,142,60,0.25);">
        <div class="inner">
          <h3 style="color: #fff; font-size: 1.6rem; font-weight: 700;"><?php echo array_sum([$resultado['x1'], $resultado['x2'], $resultado['x3'], $resultado['x4']]); ?></h3>
          <p style="color: rgba(255,255,255,0.85); font-size: 0.85rem; margin-bottom: 0;">Cabezas Asignadas</p>
        </div>
        <div class="icon">
          <i class="fas fa-cow" style="color: rgba(255,255,255,0.2); font-size: 60px;"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-6">
      <div class="small-box" style="background: linear-gradient(135deg, #43A047 0%, #2E7D32 100%); box-shadow: 0 2px 6px rgba(67,160,71,0.25);">
        <div class="inner">
          <h3 style="color: #fff; font-size: 1.2rem; font-weight: 700;">Bs <?php echo number_format($resultado['ganancia_total'], 2); ?></h3>
          <p style="color: rgba(255,255,255,0.85); font-size: 0.85rem; margin-bottom: 0;">Ganancia Total</p>
        </div>
        <div class="icon">
          <i class="fas fa-money-bill-wave" style="color: rgba(255,255,255,0.2); font-size: 60px;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-3">
    <!-- COLUMNA IZQUIERDA: RUTAS -->
    <div class="col-md-6">
      <div class="card card-outline card-primary h-100">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-route text-primary mr-1"></i> Rutas y Márgenes</h3>
        </div>
        <?php $max_cab = max($resultado['x1'], $resultado['x2'], $resultado['x3'], $resultado['x4']); ?>
        <?php $optima = $resultado['detalles']['ruta_optima']; ?>
        <?php $rutas = [
          1 => ['label' => 'R1', 'name' => 'Samaipata → Santa Cruz', 'cab' => $resultado['x1'], 'margen' => $resultado['detalles']['margen_r1']],
          2 => ['label' => 'R2', 'name' => 'Comarapa → Cochabamba', 'cab' => $resultado['x2'], 'margen' => $resultado['detalles']['margen_r2']],
          3 => ['label' => 'R3', 'name' => 'Ipati-Abapó → Santa Cruz', 'cab' => $resultado['x3'], 'margen' => $resultado['detalles']['margen_r3']],
          4 => ['label' => 'R4', 'name' => 'Aiquile → Cochabamba', 'cab' => $resultado['x4'], 'margen' => $resultado['detalles']['margen_r4']],
        ]; ?>
        <div class="card-body py-3 px-3">
          <?php foreach ($rutas as $num => $r): ?>
            <?php $pct = $max_cab > 0 ? round($r['cab'] / $max_cab * 100) : 0; ?>
            <div class="progress-group mb-4<?php echo ($num === array_key_last($rutas)) ? ' mb-0' : ''; ?>">
              <div class="d-flex justify-content-between align-items-end mb-1">
                <span class="progress-text mb-0">
                  <strong><?php echo $r['label']; ?></strong> — <?php echo $r['name']; ?>
                </span>
                <span class="progress-number">
                  <b><?php echo $r['cab']; ?> cab</b> <span class="text-muted">/</span> Bs <?php echo number_format($r['margen'], 2); ?>
                </span>
              </div>
              <?php if ($pct > 0): ?>
                <div class="progress sm">
                  <div class="progress-bar <?php echo ($num == $optima) ? 'bg-success' : 'bg-info'; ?>" 
                       style="width: <?php echo $pct; ?>%">
                    <?php echo $pct; ?>%
                  </div>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- COLUMNA DERECHA: PESTAÑAS DE CÁLCULO -->
    <div class="col-md-6">
      <div class="card card-primary card-tabs h-100">
        <div class="card-header p-0 pt-1">
          <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="custom-tabs-biometria-tab" data-toggle="pill" href="#custom-tabs-biometria" role="tab" aria-controls="custom-tabs-biometria" aria-selected="true"><i class="fas fa-weight mr-1"></i> Biometría</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-tabs-mercado-tab" data-toggle="pill" href="#custom-tabs-mercado" role="tab" aria-controls="custom-tabs-mercado" aria-selected="false"><i class="fas fa-tag mr-1"></i> Mercado</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-tabs-logistica-tab" data-toggle="pill" href="#custom-tabs-logistica" role="tab" aria-controls="custom-tabs-logistica" aria-selected="false"><i class="fas fa-truck mr-1"></i> Logística</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-one-tabContent">
            
            <div class="tab-pane fade show active" id="custom-tabs-biometria" role="tabpanel" aria-labelledby="custom-tabs-biometria-tab">
              <table class="table table-striped table-valign-middle mb-0">
                <tr>
                  <td style="width: 55%;"><strong>Peso Promedio:</strong></td>
                  <td style="text-align: right;"><?php echo number_format($resultado['detalles']['peso_promedio'], 2); ?> kg</td>
                </tr>
                <tr>
                  <td><strong>Factor Estación:</strong></td>
                  <td style="text-align: right;"><?php echo number_format($resultado['detalles']['factor_estacion'], 2); ?></td>
                </tr>
                <tr>
                  <td><strong>Factor Condición:</strong></td>
                  <td style="text-align: right;"><?php echo number_format($resultado['detalles']['factor_condicion'], 2); ?></td>
                </tr>
                <tr>
                  <td><strong>Eficiencia Efectiva:</strong></td>
                  <td style="text-align: right;"><?php echo number_format($resultado['detalles']['eficiencia_efectiva'], 2); ?> kg/cab</td>
                </tr>
                <tr>
                  <td><strong>Prob. Lluvia (Abapó):</strong></td>
                  <td style="text-align: right;"><?php echo number_format($resultado['detalles']['probabilidad_lluvia'] * 100, 1); ?>%</td>
                </tr>
              </table>
            </div>
            
            <div class="tab-pane fade" id="custom-tabs-mercado" role="tabpanel" aria-labelledby="custom-tabs-mercado-tab">
              <table class="table table-striped table-valign-middle mb-0">
                <tr>
                  <td style="width: 50%;"><strong>Precio Santa Cruz:</strong></td>
                  <td style="text-align: right; color: <?php echo COLOR_PRIMARY; ?>; font-weight: bold;">Bs <?php echo number_format($resultado['detalles']['precio_sc'], 2); ?>/kg</td>
                </tr>
                <tr>
                  <td><strong>Precio Cochabamba:</strong></td>
                  <td style="text-align: right; color: <?php echo COLOR_PRIMARY; ?>; font-weight: bold;">Bs <?php echo number_format($resultado['detalles']['precio_cb'], 2); ?>/kg</td>
                </tr>
              </table>
            </div>

            <div class="tab-pane fade" id="custom-tabs-logistica" role="tabpanel" aria-labelledby="custom-tabs-logistica-tab">
              <table class="table table-striped table-valign-middle mb-0">
                <tr>
                  <td style="width: 55%;"><strong>Costo C1 (Samaipata → SC):</strong></td>
                  <td style="text-align: right;">Bs <?php echo number_format($resultado['detalles']['costo_c1'], 2); ?>/cab</td>
                </tr>
                <tr>
                  <td><strong>Costo C2 (Comarapa → CB):</strong></td>
                  <td style="text-align: right;">Bs <?php echo number_format($resultado['detalles']['costo_c2'], 2); ?>/cab</td>
                </tr>
                <tr>
                  <td><strong>Costo C3 (Ipati-Abapó → SC):</strong></td>
                  <td style="text-align: right;">Bs <?php echo number_format($resultado['detalles']['costo_c3'], 2); ?>/cab</td>
                </tr>
                <tr>
                  <td><strong>Costo C4 (Aiquile → CB):</strong></td>
                  <td style="text-align: right;">Bs <?php echo number_format($resultado['detalles']['costo_c4'], 2); ?>/cab</td>
                </tr>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4 mb-4">
    <div class="col-12 text-center">
      <a href="<?php echo BASE_URL; ?>/lote/crear" class="btn btn-success btn-lg px-4 mr-3">
        <i class="fas fa-plus mr-2"></i> Registrar Otro Lote
      </a>
      <a href="<?php echo BASE_URL; ?>/dashboard/index" class="btn btn-secondary btn-lg px-4">
        <i class="fas fa-arrow-left mr-2"></i> Volver a Dashboard
      </a>
    </div>
  </div>

<?php else: ?>
  <div class="callout callout-danger" style="padding: 0.8rem 1rem; font-size: 0.9rem;">
    <h5 style="margin-bottom: 4px;"><i class="fas fa-exclamation-triangle"></i> No hay solución factible</h5>
    <p style="margin-bottom: 0;">Bajo las condiciones actuales (restricciones climáticas, horarias y de bloqueos), no se puede asignar ninguna ruta disponible. Verifique la hora de salida, clima y estado de bloqueos.</p>
  </div>

  <?php if (!empty($resultado['detalles']['diagnostico'])): ?>
    <div class="card card-outline card-danger mt-3">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-search text-danger mr-1"></i> Diagnóstico de Restricciones</h3>
      </div>
      <div class="card-body">
        <div class="timeline timeline-inverse">
          <?php $paso = 1; ?>
          <?php foreach ($resultado['detalles']['diagnostico'] as $msg): ?>
            <div class="time-label">
              <span class="bg-danger">Paso <?php echo $paso++; ?></span>
            </div>
            <div>
              <i class="fas fa-times-circle bg-danger"></i>
              <div class="timeline-item">
                <div class="timeline-body text-danger">
                  <i class="fas fa-times-circle mr-2"></i> <?php echo $msg; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          <div>
            <i class="fas fa-exclamation-triangle bg-danger"></i>
            <div class="timeline-item">
              <div class="timeline-header text-danger font-weight-bold">
                Resultado: Cero rutas disponibles
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php if (!empty($resultado['detalles']['margen_info'])): ?>
    <div class="card card-outline card-primary mt-3">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chart-line text-primary mr-1"></i> Márgenes Calculados por Ruta (Bs/cabeza)</h3>
      </div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          <?php foreach ($resultado['detalles']['margen_info'] as $m): ?>
            <li class="list-group-item"><?php echo $m; ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  <?php endif; ?>

  <div class="card card-outline card-primary mt-3">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-exclamation-triangle text-primary mr-1"></i> Verificar Restricciones</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <a href="<?php echo BASE_URL; ?>/clima/index" class="btn btn-secondary btn-sm" style="width: 100%; padding: 8px 16px;">
            <i class="fas fa-cloud-rain"></i> Estado de Clima
          </a>
        </div>
        <div class="col-md-6">
          <a href="<?php echo BASE_URL; ?>/bloqueo/index" class="btn btn-secondary btn-sm" style="width: 100%; padding: 8px 16px;">
            <i class="fas fa-ban"></i> Bloqueos de Ruta
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4 mb-4">
    <div class="col-12 text-center">
      <a href="<?php echo BASE_URL; ?>/lote/crear" class="btn btn-primary btn-lg px-4">
        <i class="fas fa-arrow-left mr-2"></i> Volver a Formulario
      </a>
    </div>
  </div>
<?php endif; ?>
