<div class="row mb-3">
  <div class="col-sm-6">
    <h3 class="m-0"><i class="fas fa-file-alt text-primary mr-1"></i> Reportes Operativos</h3>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-calendar-week text-primary mr-1"></i> Reporte de Esta Semana</h3>
      </div>
      <div class="card-body">
        <p class="text-muted mb-3">
          Del <?php echo date('d/m/Y', strtotime($fecha_inicio)); ?> al <?php echo date('d/m/Y', strtotime($fecha_fin)); ?>
        </p>
        
        <div class="bg-light p-3 rounded mb-3 text-center">
          <div style="font-size: 28px; font-weight: bold; color: <?php echo COLOR_PRIMARY; ?>;">
            <?php echo count($datos); ?>
          </div>
          <div class="text-muted small">Lotes registrados</div>
        </div>
        
        <a href="<?php echo BASE_URL; ?>/reporte/generarPDF?fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>" class="btn btn-primary btn-sm" style="background-color: <?php echo COLOR_PRIMARY; ?>; border-color: <?php echo COLOR_PRIMARY; ?>; padding: 7px 20px;">
          <i class="fas fa-download"></i> Descargar PDF
        </a>
      </div>
    </div>
  </div>
  
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-calendar-alt text-primary mr-1"></i> Reporte Personalizado</h3>
      </div>
      <div class="card-body">
        <a href="<?php echo BASE_URL; ?>/reporte/personalizado" class="btn btn-secondary btn-sm" style="padding: 7px 20px;">
          <i class="fas fa-calendar"></i> Seleccionar Rango de Fechas
        </a>
      </div>
    </div>
  </div>
</div>

<div class="card card-outline card-primary mt-3">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-table text-primary mr-1"></i> Datos de Esta Semana</h3>
  </div>
  <div class="card-body p-0">
    <?php if (!empty($datos)): ?>
      <div class="d-none d-md-block">
      <table class="table table-bordered mb-0">
        <thead>
          <tr>
            <th>Lote ID</th>
            <th>Fecha</th>
            <th>Cabezas</th>
            <th>Peso Prom</th>
            <th>Condición</th>
            <th>Estación</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($datos as $d): ?>
            <tr>
              <td><?php echo $d['id']; ?></td>
              <td><?php echo date('d/m/Y', strtotime($d['fecha_registro'])); ?></td>
              <td><?php echo $d['cabezas']; ?></td>
              <td><?php echo number_format($d['peso_promedio_kg'], 2); ?> kg</td>
              <td><?php echo $d['condicion'] ?? 'N/A'; ?></td>
              <td><?php echo $d['estacion'] ?? 'N/A'; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      </div>
      <div class="d-block d-md-none">
        <?php foreach ($datos as $d): ?>
          <div class="card mb-2" style="border-left: 4px solid <?php echo COLOR_PRIMARY; ?>; box-shadow: 0 1px 2px rgba(0,0,0,0.06);">
            <div class="card-body p-3">
              <div style="font-size: 13px; line-height: 1.7;">
                <div><span class="text-muted">Lote ID:</span> <?php echo $d['id']; ?></div>
                <div><span class="text-muted">Fecha:</span> <?php echo date('d/m/Y', strtotime($d['fecha_registro'])); ?></div>
                <div><span class="text-muted">Cabezas:</span> <?php echo $d['cabezas']; ?></div>
                <div><span class="text-muted">Peso:</span> <?php echo number_format($d['peso_promedio_kg'], 2); ?> kg</div>
                <div><span class="text-muted">Condición:</span> <?php echo $d['condicion'] ?? 'N/A'; ?></div>
                <div><span class="text-muted">Estación:</span> <?php echo $d['estacion'] ?? 'N/A'; ?></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-center text-muted p-4">
        <i class="fas fa-inbox fa-2x mb-2"></i>
        <p>No hay datos para esta semana</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-chart-pie text-primary mr-1"></i> Estadísticas</h3>
  </div>
  <div class="card-body p-0">
    <table class="table table-bordered mb-0">
      <tr>
        <td><strong>Total Lotes:</strong></td>
        <td style="text-align: right;"><?php echo $estadisticas['total_lotes']; ?></td>
      </tr>
      <tr>
        <td><strong>Total Cabezas:</strong></td>
        <td style="text-align: right;"><?php echo $estadisticas['total_cabezas']; ?></td>
      </tr>
      <tr>
        <td><strong>Precio SC:</strong></td>
        <td style="text-align: right;">Bs <?php echo number_format($estadisticas['precio_1'] ?? 0, 2); ?>/kg</td>
      </tr>
      <tr>
        <td><strong>Precio CB:</strong></td>
        <td style="text-align: right;">Bs <?php echo number_format($estadisticas['precio_2'] ?? 0, 2); ?>/kg</td>
      </tr>
    </table>
  </div>
</div>
