<div class="row mb-3">
  <div class="col-sm-6">
    <h3 class="m-0"><i class="fas fa-info-circle text-primary mr-1"></i> Detalles Escenario <?php echo $escenario['codigo']; ?></h3>
  </div>
</div>

<div class="callout" style="background: linear-gradient(90deg, <?php echo COLOR_PRIMARY; ?>, <?php echo COLOR_SECONDARY; ?>); color: white;">
  <h5 style="color: white;"><?php echo $escenario['nombre']; ?></h5>
  <p style="color: white; opacity: 0.9; margin: 0;"><?php echo $escenario['descripcion']; ?></p>
  <?php if (!empty($escenario['created_at'])): ?>
    <div style="font-size: 0.75rem; opacity: 0.7; margin-top: 4px;">
      <i class="far fa-calendar-alt mr-1"></i> Creado: <?php echo date('d/m/Y H:i', strtotime($escenario['created_at'])); ?>
    </div>
  <?php endif; ?>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-sliders-h text-primary mr-1"></i> Parámetros de Entrada</h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-bordered mb-0">
          <tr>
            <td><strong>Estación:</strong></td>
            <td><?php echo $datos_escenario['estacion_id'] == 1 ? 'Seca (1.00)' : ($datos_escenario['estacion_id'] == 2 ? 'Lluviosa (0.70)' : 'Transición (0.85)'); ?></td>
          </tr>
          <tr>
            <td><strong>Condición:</strong></td>
            <td><?php echo $datos_escenario['condicion_id'] == 1 ? 'Buena (1.00)' : ($datos_escenario['condicion_id'] == 2 ? 'Regular (0.90)' : 'Invernal (0.75)'); ?></td>
          </tr>
          <tr>
            <td><strong>Precio Santa Cruz:</strong></td>
            <td>Bs <?php echo number_format($datos_escenario['precio_sc'], 2); ?>/kg</td>
          </tr>
          <tr>
            <td><strong>Precio Cochabamba:</strong></td>
            <td>Bs <?php echo number_format($datos_escenario['precio_cb'], 2); ?>/kg</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-truck text-primary mr-1"></i> Costos de Flete</h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-bordered mb-0">
          <tr>
            <td><strong>C1 (Samaipata):</strong></td>
            <td>Bs <?php echo number_format($datos_escenario['costo_c1'], 2); ?>/cabeza</td>
          </tr>
          <tr>
            <td><strong>C2 (Comarapa):</strong></td>
            <td>Bs <?php echo number_format($datos_escenario['costo_c2'], 2); ?>/cabeza</td>
          </tr>
          <tr>
            <td><strong>C3 (Ipati-Abapó):</strong></td>
            <td>Bs <?php echo number_format($datos_escenario['costo_c3'], 2); ?>/cabeza</td>
          </tr>
          <tr>
            <td><strong>C4 (Aiquile):</strong></td>
            <td>Bs <?php echo number_format($datos_escenario['costo_c4'], 2); ?>/cabeza</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-exclamation-triangle text-primary mr-1"></i> Restricciones</h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-bordered mb-0">
          <tr>
            <td><strong>Probabilidad Lluvia:</strong></td>
            <td><?php echo number_format($datos_escenario['prob_lluvia'] * 100, 1); ?>%</td>
          </tr>
          <tr>
            <td><strong>Bloqueo R1:</strong></td>
            <td><?php echo $datos_escenario['bloqueo_r1'] ? '<span class="badge badge-danger">SÍ</span>' : '<span class="badge badge-success">NO</span>'; ?></td>
          </tr>
          <tr>
            <td><strong>Bloqueo R2:</strong></td>
            <td><?php echo $datos_escenario['bloqueo_r2'] ? '<span class="badge badge-danger">SÍ</span>' : '<span class="badge badge-success">NO</span>'; ?></td>
          </tr>
          <tr>
            <td><strong>Bloqueo R3:</strong></td>
            <td><?php echo $datos_escenario['bloqueo_r3'] ? '<span class="badge badge-danger">SÍ</span>' : '<span class="badge badge-success">NO</span>'; ?></td>
          </tr>
          <tr>
            <td><strong>Bloqueo R4:</strong></td>
            <td><?php echo $datos_escenario['bloqueo_r4'] ? '<span class="badge badge-danger">SÍ</span>' : '<span class="badge badge-success">NO</span>'; ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-list-ol text-primary mr-1"></i> Instrucciones</h3>
      </div>
      <div class="card-body">
        <ol style="padding-left: 20px; line-height: 1.8; margin-bottom: 0;">
          <li>Haga clic en <strong>Ejecutar Simulación</strong></li>
          <li>Se calculará automáticamente la ruta óptima</li>
          <li>Verá márgenes de ganancia por ruta</li>
          <li>La mejor ruta recibirá todas las 100 cabezas</li>
          <li>Si no hay ruta disponible: solución no factible</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3 mb-3">
  <div class="col-12 text-center">
    <a href="<?php echo BASE_URL; ?>/simulacion/ejecutar?codigo=<?php echo $escenario['codigo']; ?>" class="btn btn-primary" style="background-color: <?php echo COLOR_PRIMARY; ?>; border-color: <?php echo COLOR_PRIMARY; ?>; padding: 8px 28px;">
      <i class="fas fa-play"></i> Ejecutar Simulación
    </a>
    <a href="<?php echo BASE_URL; ?>/simulacion/index" class="btn btn-secondary" style="padding: 8px 28px;">
      <i class="fas fa-arrow-left"></i> Volver
    </a>
  </div>
</div>
