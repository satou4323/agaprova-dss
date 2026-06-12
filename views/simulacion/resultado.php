<div class="row mb-3">
  <div class="col-sm-6">
    <h3 class="m-0"><i class="fas fa-chart-bar text-primary mr-1"></i> Resultado Simulación Escenario <?php echo $escenario['codigo']; ?></h3>
  </div>
</div>

<div class="callout" style="background: linear-gradient(90deg, <?php echo COLOR_PRIMARY; ?>, <?php echo COLOR_SECONDARY; ?>); color: white;">
  <h5 style="color: white;"><?php echo $escenario['nombre']; ?></h5>
  <p style="color: white; opacity: 0.9; margin: 0;"><?php echo $escenario['descripcion']; ?></p>
</div>

<?php if ($resultado['factible']): ?>
  <div class="row">
    <div class="col-lg-4 col-12">
      <div class="small-box" style="background: linear-gradient(135deg, #43A047 0%, #2E7D32 100%); box-shadow: 0 2px 6px rgba(67,160,71,0.25);">
        <div class="inner">
          <h3 style="color: #fff; font-size: 1.3rem; font-weight: 700;"><i class="fas fa-check-circle"></i> FACTIBLE</h3>
          <p style="color: rgba(255,255,255,0.85); font-size: 0.85rem; margin-bottom: 0;">Solución</p>
        </div>
        <div class="icon">
          <i class="fas fa-check-circle" style="color: rgba(255,255,255,0.2); font-size: 70px;"></i>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4 col-12">
      <div class="small-box" style="background: linear-gradient(135deg, #43A047 0%, #2E7D32 100%); box-shadow: 0 2px 6px rgba(67,160,71,0.25);">
        <div class="inner">
          <h3 style="color: #fff; font-size: 1.3rem; font-weight: 700;">Bs <?php echo number_format($resultado['ganancia_total'], 2); ?></h3>
          <p style="color: rgba(255,255,255,0.85); font-size: 0.85rem; margin-bottom: 0;">Ganancia Total</p>
        </div>
        <div class="icon">
          <i class="fas fa-money-bill-wave" style="color: rgba(255,255,255,0.2); font-size: 70px;"></i>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4 col-12">
      <div class="small-box" style="background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%); box-shadow: 0 2px 6px rgba(46,125,50,0.25);">
        <div class="inner">
          <h3 style="color: #fff; font-size: 1.3rem; font-weight: 700;">Ruta <?php echo $resultado['detalles']['ruta_optima']; ?></h3>
          <p style="color: rgba(255,255,255,0.85); font-size: 0.85rem; margin-bottom: 0;">Cabezas (100)</p>
        </div>
        <div class="icon">
          <i class="fas fa-cow" style="color: rgba(255,255,255,0.2); font-size: 70px;"></i>
        </div>
      </div>
    </div>
  </div>
  
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-route text-primary mr-1"></i> Asignación Óptima</h3>
    </div>
    <div class="card-body p-0">
      <table class="table table-bordered mb-0">
        <tr>
          <td><strong>Ruta 1 (Samaipata):</strong></td>
          <td><?php echo $resultado['x1']; ?> cabezas</td>
          <td style="text-align: right; color: <?php echo COLOR_PRIMARY; ?>; font-weight: bold;">
            Margen: Bs <?php echo number_format($resultado['detalles']['margen_r1'], 2); ?>/cabeza
          </td>
        </tr>
        <tr>
          <td><strong>Ruta 2 (Comarapa):</strong></td>
          <td><?php echo $resultado['x2']; ?> cabezas</td>
          <td style="text-align: right; color: <?php echo COLOR_PRIMARY; ?>; font-weight: bold;">
            Margen: Bs <?php echo number_format($resultado['detalles']['margen_r2'], 2); ?>/cabeza
          </td>
        </tr>
        <tr>
          <td><strong>Ruta 3 (Ipati-Abapó):</strong></td>
          <td><?php echo $resultado['x3']; ?> cabezas</td>
          <td style="text-align: right; color: <?php echo COLOR_PRIMARY; ?>; font-weight: bold;">
            Margen: Bs <?php echo number_format($resultado['detalles']['margen_r3'], 2); ?>/cabeza
          </td>
        </tr>
        <tr>
          <td><strong>Ruta 4 (Aiquile):</strong></td>
          <td><?php echo $resultado['x4']; ?> cabezas</td>
          <td style="text-align: right; color: <?php echo COLOR_PRIMARY; ?>; font-weight: bold;">
            Margen: Bs <?php echo number_format($resultado['detalles']['margen_r4'], 2); ?>/cabeza
          </td>
        </tr>
      </table>
    </div>
  </div>
  
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-ban text-primary mr-1"></i> Restricciones Evaluadas</h3>
    </div>
    <div class="card-body p-0">
      <table class="table table-bordered mb-0">
        <tr>
          <td><strong>Ruta 1 Disponible:</strong></td>
          <td><?php echo $resultado['detalles']['disponibles'][1] ? '<span class="badge badge-success">SÍ</span>' : '<span class="badge badge-danger">NO</span>'; ?></td>
        </tr>
        <tr>
          <td><strong>Ruta 2 Disponible:</strong></td>
          <td><?php echo $resultado['detalles']['disponibles'][2] ? '<span class="badge badge-success">SÍ</span>' : '<span class="badge badge-danger">NO</span>'; ?></td>
        </tr>
        <tr>
          <td><strong>Ruta 3 Disponible:</strong></td>
          <td><?php echo $resultado['detalles']['disponibles'][3] ? '<span class="badge badge-success">SÍ</span>' : '<span class="badge badge-danger">NO</span>'; ?></td>
        </tr>
        <tr>
          <td><strong>Ruta 4 Disponible:</strong></td>
          <td><?php echo $resultado['detalles']['disponibles'][4] ? '<span class="badge badge-success">SÍ</span>' : '<span class="badge badge-danger">NO</span>'; ?></td>
        </tr>
        <tr>
          <td><strong>Probabilidad de Lluvia (Abapó):</strong></td>
          <td><?php echo number_format($resultado['detalles']['probabilidad_lluvia'] * 100, 1); ?>%</td>
        </tr>
      </table>
    </div>
  </div>
<?php else: ?>
  <div class="callout callout-danger">
    <h5><i class="fas fa-exclamation-triangle"></i> No hay solución factible</h5>
    <p>Bajo estos parámetros de restricciones (clima, horario, bloqueos), no existe una ruta disponible para completar el despacho.</p>
  </div>

  <?php if (!empty($resultado['detalles']['diagnostico'])): ?>
    <div class="card card-outline card-danger">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-search"></i> Diagnóstico de Restricciones</h3>
      </div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          <?php foreach ($resultado['detalles']['diagnostico'] as $msg): ?>
            <li class="list-group-item text-danger">
              <i class="fas fa-times-circle mr-2"></i> <?php echo $msg; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  <?php endif; ?>

  <?php if (!empty($resultado['detalles']['margen_info'])): ?>
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Márgenes Calculados por Ruta (Bs/cabeza)</h3>
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
<?php endif; ?>

<div class="row mt-3 mb-3">
  <div class="col-12 text-center">
    <a href="<?php echo BASE_URL; ?>/simulacion/index" class="btn btn-primary" style="background-color: <?php echo COLOR_PRIMARY; ?>; border-color: <?php echo COLOR_PRIMARY; ?>; padding: 8px 28px;">
      <i class="fas fa-arrow-left"></i> Volver a Simulaciones
    </a>
  </div>
</div>
