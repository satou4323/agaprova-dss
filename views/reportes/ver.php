<!-- Header con botón Volver arriba -->
<div class="row mb-3">
  <div class="col-sm-6">
    <h3 class="m-0"><i class="fas fa-print text-primary mr-1"></i> Reporte Generado</h3>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?php echo BASE_URL; ?>/reporte/personalizado" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i> Volver a Reportes
    </a>
  </div>
</div>

<!-- Rango y botón PDF -->
<div class="bg-light p-3 rounded mb-3 d-flex justify-content-between align-items-center">
  <div>
    <p class="mb-0">
      <strong>Rango:</strong>
      <?php echo date('d/m/Y', strtotime($rango['inicio'])); ?>
      al <?php echo date('d/m/Y', strtotime($rango['fin'])); ?>
    </p>
  </div>
  <div>
    <a href="<?php echo BASE_URL; ?>/reporte/generarPDFVer"
       class="btn btn-primary btn-sm"
       style="background-color:<?php echo COLOR_PRIMARY; ?>; border-color:<?php echo COLOR_PRIMARY; ?>; padding:7px 20px;">
      <i class="fas fa-file-pdf mr-1"></i> Descargar PDF
    </a>
  </div>
</div>

<!-- KPI mini-resumen del período -->
<?php if (!empty($resumen)): ?>
<div class="row mb-3">
  <div class="col-md-3 col-6">
    <div class="small-box text-white" style="background-color:<?php echo COLOR_PRIMARY; ?>;">
      <div class="inner">
        <h4><?php echo $resumen['total_lotes']; ?></h4>
        <p>Lotes en el período</p>
      </div>
      <div class="icon"><i class="fas fa-boxes"></i></div>
    </div>
  </div>
  <div class="col-md-3 col-6">
    <div class="small-box bg-success text-white">
      <div class="inner">
        <h4><?php echo $resumen['total_cabezas']; ?></h4>
        <p>Total Cabezas</p>
      </div>
      <div class="icon"><i class="fas fa-cow"></i></div>
    </div>
  </div>
  <div class="col-md-3 col-6">
    <div class="small-box bg-warning text-white">
      <div class="inner">
        <h4><?php echo number_format($resumen['peso_promedio'], 2); ?> kg</h4>
        <p>Peso Promedio</p>
      </div>
      <div class="icon"><i class="fas fa-weight"></i></div>
    </div>
  </div>
  <div class="col-md-3 col-6">
    <div class="small-box bg-info text-white">
      <div class="inner">
        <h4><?php echo $resumen['dias_periodo']; ?> días</h4>
        <p>Duración del período</p>
      </div>
      <div class="icon"><i class="fas fa-calendar"></i></div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Tabla de datos -->
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-table text-primary mr-1"></i> Datos del Período</h3>
  </div>
  <div class="card-body p-0">
    <?php if (!empty($datos)): ?>
      <div class="d-none d-md-block">
      <table class="table table-bordered mb-0">
        <thead>
          <tr>
            <th>ID</th>
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
        <!-- Fila de totales -->
        <?php if (count($datos) > 1): ?>
        <tfoot>
          <tr class="table-light font-weight-bold">
            <td colspan="2" class="text-right">TOTALES:</td>
            <td><?php echo array_sum(array_column($datos, 'cabezas')); ?> cab.</td>
            <td><?php echo number_format(array_sum(array_column($datos, 'peso_promedio_kg')) / count($datos), 2); ?> kg prom.</td>
            <td colspan="2"></td>
          </tr>
        </tfoot>
        <?php endif; ?>
      </table>
      </div>

      <!-- Mobile cards -->
      <div class="d-block d-md-none">
        <?php foreach ($datos as $d): ?>
          <div class="card mb-2" style="border-left:4px solid <?php echo COLOR_PRIMARY; ?>; box-shadow:0 1px 2px rgba(0,0,0,0.06);">
            <div class="card-body p-3">
              <div style="font-size:13px; line-height:1.7;">
                <div><span class="text-muted">ID:</span> <?php echo $d['id']; ?></div>
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
        <p>No hay datos en este período</p>
      </div>
    <?php endif; ?>
  </div>
</div>