<div class="row mb-3">
  <div class="col-sm-6">
    <h3 class="m-0"><i class="fas fa-file-alt text-primary mr-1"></i> Reportes Operativos</h3>
  </div>
</div>

<div class="row">

  <!-- Reporte de Esta Semana -->
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-calendar-week text-primary mr-1"></i> Reporte de Esta Semana</h3>
      </div>
      <div class="card-body">
        <p class="text-muted mb-3">
          Del <?php echo date('d/m/Y', strtotime($fecha_inicio)); ?> al <?php echo date('d/m/Y', strtotime($fecha_fin)); ?>
        </p>

        <!-- KPI mini cards semana -->
        <div class="row text-center mb-3">
          <div class="col-4">
            <div class="rounded p-2 text-white" style="background-color:<?php echo COLOR_PRIMARY; ?>;">
              <div style="font-size:1.8rem; font-weight:700; line-height:1;"><?php echo count($datos); ?></div>
              <small>Lotes</small>
            </div>
          </div>
          <div class="col-4">
            <div class="bg-success rounded p-2 text-white">
              <div style="font-size:1.8rem; font-weight:700; line-height:1;"><?php echo $total_cabezas_semana; ?></div>
              <small>Cabezas</small>
            </div>
          </div>
          <div class="col-4">
            <div class="bg-warning rounded p-2 text-white">
              <div style="font-size:1.3rem; font-weight:700; line-height:1;"><?php echo $peso_prom_semana; ?> kg</div>
              <small>Peso prom.</small>
            </div>
          </div>
        </div>

        <a href="<?php echo BASE_URL; ?>/reporte/generarPDF?fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>"
           class="btn btn-primary btn-sm"
           style="background-color:<?php echo COLOR_PRIMARY; ?>; border-color:<?php echo COLOR_PRIMARY; ?>; padding:7px 20px;">
          <i class="fas fa-file-pdf mr-1"></i> Descargar PDF del Reporte
        </a>
        <p class="text-muted mt-1 mb-0" style="font-size:0.78rem;">
          <i class="fas fa-info-circle mr-1"></i> Descarga el reporte semanal formateado.<br>
          Usa los botones de la tabla para exportar los datos en bruto.
        </p>
      </div>
    </div>
  </div>

  <!-- Reporte Personalizado -->
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-calendar-alt text-primary mr-1"></i> Reporte Personalizado</h3>
      </div>
      <div class="card-body">
        <p class="text-muted mb-2">
          <i class="fas fa-calendar-week mr-1"></i>
          Semana actual: <strong><?php echo date('d/m/Y', strtotime($fecha_inicio)); ?></strong>
          al <strong><?php echo date('d/m/Y', strtotime($fecha_fin)); ?></strong>
        </p>
        <p class="text-muted mb-3">
          Genera un reporte para cualquier rango de fechas personalizado.
        </p>
        <a href="<?php echo BASE_URL; ?>/reporte/personalizado"
           class="btn btn-primary btn-sm"
           style="background-color:<?php echo COLOR_PRIMARY; ?>; border-color:<?php echo COLOR_PRIMARY; ?>; padding:7px 20px;">
          <i class="fas fa-calendar-alt mr-1"></i> Seleccionar Rango de Fechas
        </a>
      </div>
    </div>
  </div>

</div>

<!-- Tabla de datos de la semana -->
<div class="card card-outline card-primary mt-3">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-table text-primary mr-1"></i> Datos de Esta Semana</h3>
  </div>
  <div class="card-body p-0">
    <?php if (!empty($datos)): ?>
      <div class="d-none d-md-block">
      <table id="datosSemanaTable" class="table table-bordered table-striped table-hover table-valign-middle mb-0">
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
          <div class="card mb-2" style="border-left:4px solid <?php echo COLOR_PRIMARY; ?>; box-shadow:0 1px 2px rgba(0,0,0,0.06);">
            <div class="card-body p-3">
              <div style="font-size:13px; line-height:1.7;">
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

<?php if (!empty($datos)): ?>
<style>
#datosSemanaTable_wrapper .dataTables_filter input {
  border-radius: 20px !important;
  padding-left: 12px !important;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
  $('#datosSemanaTable').DataTable({
    lengthChange: false,
    responsive: false,
    autoWidth: true,
    pagingType: 'full_numbers',
    pageLength: 10,
    order: [[0, 'desc']],
    language: {
      processing: 'Procesando...',
      zeroRecords: 'No se encontraron resultados',
      emptyTable: 'Ningún dato disponible',
      info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
      infoEmpty: 'Mostrando 0 registros',
      infoFiltered: '(filtrado de _MAX_ totales)',
      search: '<i class="fas fa-search text-muted mr-1"></i> Buscar:',
      paginate: {
        first: '<i class="fas fa-angle-double-left"></i>',
        last: '<i class="fas fa-angle-double-right"></i>',
        next: '<i class="fas fa-angle-right"></i>',
        previous: '<i class="fas fa-angle-left"></i>'
      }
    },
    dom:
      "<'row m-0 px-3 pt-3'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
      "<'row m-0 px-3'<'col-sm-12'tr>>" +
      "<'row m-0 px-3 pb-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    buttons: [
      { extend: 'copy',  text: '<i class="fas fa-copy"></i> Copiar',  className: 'btn-secondary btn-sm', exportOptions: { columns: ':visible' } },
      { extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn-success btn-sm',  exportOptions: { columns: ':visible' } },
      { extend: 'pdf',   text: '<i class="fas fa-file-pdf"></i> PDF',  className: 'btn-danger btn-sm',   exportOptions: { columns: ':visible' } },
      { extend: 'print', text: '<i class="fas fa-print"></i> Imprimir', className: 'btn-info btn-sm',    exportOptions: { columns: ':visible' } }
    ]
  });
});
</script>
<?php endif; ?>

<!-- Estadísticas como info-boxes -->
<div class="card card-outline card-primary mt-3">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-chart-pie text-primary mr-1"></i> Estadísticas</h3>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-6 col-md-3 mb-3">
        <div class="info-box shadow-sm" style="background-color:<?php echo COLOR_PRIMARY; ?>;">
          <span class="info-box-icon"><i class="fas fa-boxes"></i></span>
          <div class="info-box-content">
            <span class="info-box-text" style="color:rgba(255,255,255,0.85);">Total Lotes</span>
            <span class="info-box-number" style="color:#fff;"><?php echo $estadisticas['total_lotes']; ?></span>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3 mb-3">
        <div class="info-box bg-success shadow-sm">
          <span class="info-box-icon"><i class="fas fa-cow"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Cabezas</span>
            <span class="info-box-number"><?php echo $estadisticas['total_cabezas']; ?></span>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3 mb-3">
        <div class="info-box bg-warning shadow-sm">
          <span class="info-box-icon"><i class="fas fa-map-marker-alt"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Precio Santa Cruz</span>
            <span class="info-box-number" style="font-size:1.1rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
              Bs <?php echo number_format($estadisticas['precio_1'] ?? 0, 2); ?>/kg
            </span>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3 mb-3">
        <div class="info-box bg-info shadow-sm">
          <span class="info-box-icon"><i class="fas fa-map-marker-alt"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Precio Cochabamba</span>
            <span class="info-box-number" style="font-size:1.1rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
              Bs <?php echo number_format($estadisticas['precio_2'] ?? 0, 2); ?>/kg
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>