<?php if ($resultado['factible']): ?>
  <?php $tipo = $resultado['detalles']['tipo_margen'] ?? 'pérdida'; ?>
  <div class="callout <?php echo $tipo === 'ganancia' ? 'callout-success' : 'callout-warning'; ?>" style="padding:0.8rem 1rem;font-size:0.9rem;">
    <h5 style="margin-bottom:0;"><i class="fas <?php echo $tipo === 'ganancia' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
    <?php echo $tipo === 'ganancia' ? 'Solución Óptima — Margen Positivo' : 'Solución Óptima — Minimización de Pérdida (Envío Obligatorio)'; ?></h5>
  </div>

  <div class="row mt-4">
    <div class="col-lg-4 col-6">
      <div class="small-box" style="background:linear-gradient(135deg,#2E7D32 0%,#1B5E20 100%);box-shadow:0 2px 6px rgba(46,125,50,0.25);">
        <div class="inner">
          <h3 style="color:#fff;font-size:1.1rem;font-weight:700;"><?php
            $ruta_names = [1=>'R01 (Samaipata)',2=>'R02 (Comarapa)',3=>'R03 (Ipati-Abapó)',4=>'R04 (Aiquile)'];
            echo $ruta_names[$resultado['detalles']['ruta_optima']] ?? 'Desconocida';
          ?></h3>
          <p style="color:rgba(255,255,255,0.85);font-size:0.85rem;margin-bottom:0;">Ruta Asignada</p>
        </div>
        <div class="icon"><i class="fas fa-truck" style="color:rgba(255,255,255,0.2);font-size:60px;"></i></div>
      </div>
    </div>
    <div class="col-lg-4 col-6">
      <div class="small-box" style="background:linear-gradient(135deg,#388E3C 0%,#2E7D32 100%);box-shadow:0 2px 6px rgba(56,142,60,0.25);">
        <div class="inner">
          <h3 style="color:#fff;font-size:1.6rem;font-weight:700;"><?php echo array_sum([$resultado['x1'],$resultado['x2'],$resultado['x3'],$resultado['x4']]); ?></h3>
          <p style="color:rgba(255,255,255,0.85);font-size:0.85rem;margin-bottom:0;">Cabezas Asignadas</p>
        </div>
        <div class="icon"><i class="fas fa-cow" style="color:rgba(255,255,255,0.2);font-size:60px;"></i></div>
      </div>
    </div>
    <div class="col-lg-4 col-6">
      <div class="small-box" style="background:linear-gradient(135deg,#43A047 0%,#2E7D32 100%);box-shadow:0 2px 6px rgba(67,160,71,0.25);">
        <div class="inner">
          <h3 style="color:#fff;font-size:1.2rem;font-weight:700;">Bs <?php echo number_format($resultado['ganancia_total'],2); ?></h3>
          <p style="color:rgba(255,255,255,0.85);font-size:0.85rem;margin-bottom:0;">Margen Neto Total</p>
        </div>
        <div class="icon"><i class="fas fa-money-bill-wave" style="color:rgba(255,255,255,0.2);font-size:60px;"></i></div>
      </div>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-md-6">
      <div class="card card-outline card-primary h-100">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-route text-primary mr-1"></i> Rutas y Márgenes</h3>
        </div>
        <?php
          $max_cab = max($resultado['x1'],$resultado['x2'],$resultado['x3'],$resultado['x4']);
          $optima  = $resultado['detalles']['ruta_optima'];
          $rutas   = [
            1=>['label'=>'R1','name'=>'Samaipata → Santa Cruz','cab'=>$resultado['x1'],'margen'=>$resultado['detalles']['margen_r1']],
            2=>['label'=>'R2','name'=>'Comarapa → Cochabamba','cab'=>$resultado['x2'],'margen'=>$resultado['detalles']['margen_r2']],
            3=>['label'=>'R3','name'=>'Ipati-Abapó → Santa Cruz','cab'=>$resultado['x3'],'margen'=>$resultado['detalles']['margen_r3']],
            4=>['label'=>'R4','name'=>'Aiquile → Cochabamba','cab'=>$resultado['x4'],'margen'=>$resultado['detalles']['margen_r4']],
          ];
        ?>
        <div class="card-body py-3 px-3">
          <?php foreach ($rutas as $num => $r): ?>
            <?php $pct = $max_cab > 0 ? round($r['cab']/$max_cab*100) : 0; ?>
            <div class="progress-group mb-4<?php echo ($num===array_key_last($rutas))?' mb-0':''; ?>">
              <div class="d-flex justify-content-between align-items-end mb-1">
                <span class="progress-text mb-0"><strong><?php echo $r['label']; ?></strong> — <?php echo $r['name']; ?></span>
                <span class="progress-number"><b><?php echo $r['cab']; ?> cab</b> <span class="text-muted">/</span> Bs <?php echo number_format($r['margen'],2); ?></span>
              </div>
              <?php if ($pct > 0): ?>
                <div class="progress sm">
                  <div class="progress-bar <?php echo ($num==$optima)?'bg-success':'bg-info'; ?>" style="width:<?php echo $pct; ?>%"><?php echo $pct; ?>%</div>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card card-primary card-tabs h-100">
        <div class="card-header p-0 pt-1">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#tab-biometria" role="tab"><i class="fas fa-weight mr-1"></i> Biometría</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-mercado" role="tab"><i class="fas fa-tag mr-1"></i> Mercado</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-logistica" role="tab"><i class="fas fa-truck mr-1"></i> Logística</a></li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-biometria" role="tabpanel">
              <table class="table table-striped table-valign-middle mb-0">
                <tr><td style="width:55%;"><strong>Peso Promedio:</strong></td><td style="text-align:right;"><?php echo number_format($resultado['detalles']['peso_promedio'],2); ?> kg</td></tr>
                <tr><td><strong>Factor Estación:</strong></td><td style="text-align:right;"><?php echo number_format($resultado['detalles']['factor_estacion'],2); ?></td></tr>
                <tr><td><strong>Factor Condición:</strong></td><td style="text-align:right;"><?php echo number_format($resultado['detalles']['factor_condicion'],2); ?></td></tr>
                <tr><td><strong>Eficiencia Efectiva:</strong></td><td style="text-align:right;"><?php echo number_format($resultado['detalles']['eficiencia_efectiva'],2); ?> kg/cab</td></tr>
                <tr><td><strong>Prob. Lluvia (Abapó):</strong></td><td style="text-align:right;"><?php echo number_format($resultado['detalles']['probabilidad_lluvia']*100,1); ?>%</td></tr>
              </table>
            </div>
            <div class="tab-pane fade" id="tab-mercado" role="tabpanel">
              <table class="table table-striped table-valign-middle mb-0">
                <tr><td style="width:50%;"><strong>Precio Santa Cruz:</strong></td><td style="text-align:right;color:<?php echo COLOR_PRIMARY; ?>;font-weight:bold;">Bs <?php echo number_format($resultado['detalles']['precio_sc'],2); ?>/kg</td></tr>
                <tr><td><strong>Precio Cochabamba:</strong></td><td style="text-align:right;color:<?php echo COLOR_PRIMARY; ?>;font-weight:bold;">Bs <?php echo number_format($resultado['detalles']['precio_cb'],2); ?>/kg</td></tr>
              </table>
            </div>
            <div class="tab-pane fade" id="tab-logistica" role="tabpanel">
              <table class="table table-striped table-valign-middle mb-0">
                <tr><td style="width:55%;"><strong>C1 (Samaipata → SC):</strong></td><td style="text-align:right;">Bs <?php echo number_format($resultado['detalles']['costo_c1'],2); ?>/cab</td></tr>
                <tr><td><strong>C2 (Comarapa → CB):</strong></td><td style="text-align:right;">Bs <?php echo number_format($resultado['detalles']['costo_c2'],2); ?>/cab</td></tr>
                <tr><td><strong>C3 (Ipati-Abapó → SC):</strong></td><td style="text-align:right;">Bs <?php echo number_format($resultado['detalles']['costo_c3'],2); ?>/cab</td></tr>
                <tr><td><strong>C4 (Aiquile → CB):</strong></td><td style="text-align:right;">Bs <?php echo number_format($resultado['detalles']['costo_c4'],2); ?>/cab</td></tr>
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
  <!-- CAMBIO 4: Estado vacío mejorado cuando no hay solución factible -->
  <div class="text-center py-5">
    <i class="fas fa-route fa-4x text-muted mb-4 d-block"></i>
    <h4 class="text-muted">No hay solución factible</h4>
    <p class="text-muted mb-3">Bajo las condiciones actuales (restricciones climáticas, horarias y de bloqueos), no se puede asignar ninguna ruta disponible.</p>

    <?php if (!empty($resultado['detalles']['diagnostico'])): ?>
      <div class="card card-outline card-danger mt-3 text-left" style="max-width:600px;margin:0 auto;">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-search text-danger mr-1"></i> Diagnóstico de Restricciones</h3>
        </div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
            <?php foreach ($resultado['detalles']['diagnostico'] as $msg): ?>
              <li class="list-group-item text-danger"><i class="fas fa-times-circle mr-2"></i><?php echo $msg; ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

    <div class="mt-4">
      <a href="<?php echo BASE_URL; ?>/clima/index" class="btn btn-secondary btn-sm mr-2">
        <i class="fas fa-cloud-rain mr-1"></i> Estado de Clima
      </a>
      <a href="<?php echo BASE_URL; ?>/bloqueo/index" class="btn btn-secondary btn-sm mr-2">
        <i class="fas fa-ban mr-1"></i> Bloqueos de Ruta
      </a>
      <a href="<?php echo BASE_URL; ?>/lote/crear" class="btn btn-primary btn-lg ml-2 px-4">
        <i class="fas fa-plus mr-2"></i> Registrar Nuevo Lote
      </a>
    </div>
  </div>
<?php endif; ?>