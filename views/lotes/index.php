<div class="card card-outline card-primary mt-4">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-boxes text-primary mr-1"></i> Lotes Registrados</h3>
    <div class="card-tools">
      <a href="<?php echo BASE_URL; ?>/lote/crear" class="btn btn-sm btn-primary" style="background-color: <?php echo COLOR_PRIMARY; ?>; border-color: <?php echo COLOR_PRIMARY; ?>;">
        <i class="fas fa-plus-circle"></i> Nuevo Lote
      </a>
    </div>
  </div>
  <div class="card-body p-0">
    <?php if (!empty($lotes)): ?>
      <!-- Desktop table with DataTables -->
      <div class="table-responsive-wrapper d-none d-md-block">
      <table id="lotesTable" class="table table-bordered table-striped table-hover table-valign-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Cabezas</th>
            <th>Peso Promedio</th>
            <th>Condición</th>
            <th>Estación</th>
            <th>Hora Salida</th>
            <th>Fecha</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($lotes as $lote):
            $cond = array_values(array_filter($condiciones, fn($c) => $c->id == $lote->condicion_id))[0] ?? null;
            $est = array_values(array_filter($estaciones, fn($e) => $e->id == $lote->estacion_id))[0] ?? null;
          ?>
            <tr>
              <td><strong><?php echo $lote->id; ?></strong></td>
              <td><?php echo $lote->cabezas; ?></td>
              <td><?php echo number_format($lote->peso_promedio_kg, 2); ?> kg</td>
              <td><?php echo $cond?->nombre ?? 'N/A'; ?></td>
              <td><?php echo $est?->nombre ?? 'N/A'; ?></td>
              <td><?php echo $lote->hora_salida; ?></td>
              <td><?php echo date('d/m/Y', strtotime($lote->fecha_registro)); ?></td>
              <td>
                <span class="badge badge-success">Activo</span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      </div>
      <!-- Mobile cards -->
      <div class="d-block d-md-none">
        <?php foreach ($lotes as $lote):
          $cond = array_values(array_filter($condiciones, fn($c) => $c->id == $lote->condicion_id))[0] ?? null;
          $est = array_values(array_filter($estaciones, fn($e) => $e->id == $lote->estacion_id))[0] ?? null;
        ?>
          <div class="card mb-2" style="border-left: 4px solid <?php echo COLOR_PRIMARY; ?>; box-shadow: 0 1px 2px rgba(0,0,0,0.06);">
            <div class="card-body p-3">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <strong style="color: <?php echo COLOR_PRIMARY; ?>; font-size: 15px;">Lote #<?php echo $lote->id; ?></strong>
                <span class="badge badge-success">Activo</span>
              </div>
              <div style="font-size: 13px; line-height: 1.7;">
                <div><span class="text-muted">Cabezas:</span> <?php echo $lote->cabezas; ?></div>
                <div><span class="text-muted">Peso:</span> <?php echo number_format($lote->peso_promedio_kg, 2); ?> kg</div>
                <div><span class="text-muted">Condición:</span> <?php echo $cond?->nombre ?? 'N/A'; ?></div>
                <div><span class="text-muted">Estación:</span> <?php echo $est?->nombre ?? 'N/A'; ?></div>
                <div><span class="text-muted">Salida:</span> <?php echo $lote->hora_salida; ?></div>
                <div><span class="text-muted">Fecha:</span> <?php echo date('d/m/Y', strtotime($lote->fecha_registro)); ?></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="p-5 text-center">
        <i class="fas fa-inbox fa-3x mb-3" style="color: #dee2e6;"></i>
        <h5 style="color: #6c757d;">Aún no hay lotes registrados</h5>
        <p style="color: #adb5bd;">Los lotes que registres aparecerán aquí.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php if (!empty($lotes)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var table = $('#lotesTable').DataTable({
    lengthChange: false,
    responsive: false,
    autoWidth: true,
    pagingType: 'full_numbers',
    pageLength: 10,
    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos']],
    order: [[0, 'desc']],
    language: {
      processing: 'Procesando...',
      lengthMenu: 'Mostrar _MENU_ registros',
      zeroRecords: 'No se encontraron resultados',
      emptyTable: 'Ningún dato disponible en esta tabla',
      info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
      infoEmpty: 'Mostrando 0 a 0 de 0 registros',
      infoFiltered: '(filtrado de _MAX_ registros totales)',
      infoPostFix: '',
      search: '<i class="fas fa-search text-muted mr-1"></i> Buscar:',
      url: '',
      thousands: ',',
      loadingRecords: 'Cargando...',
      paginate: {
        first: '<i class="fas fa-angle-double-left"></i>',
        last: '<i class="fas fa-angle-double-right"></i>',
        next: '<i class="fas fa-angle-right"></i>',
        previous: '<i class="fas fa-angle-left"></i>'
      },
      aria: {
        sortAscending: ': Activar para ordenar ascendentemente',
        sortDescending: ': Activar para ordenar descendentemente'
      },
      buttons: {
        copy: 'Copiar',
        colvis: 'Columnas',
        print: 'Imprimir',
        excel: 'Excel',
        pdf: 'PDF',
        csv: 'CSV'
      }
    },
    dom: 
      "<'row m-0 px-3 pt-3'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
      "<'row m-0 px-3'<'col-sm-12'tr>>" +
      "<'row m-0 px-3 pb-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    buttons: [
      {
        extend: 'copy',
        text: '<i class="fas fa-copy"></i> Copiar',
        className: 'btn-secondary btn-sm',
        titleAttr: 'Copiar al portapapeles',
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'excel',
        text: '<i class="fas fa-file-excel"></i> Excel',
        className: 'btn-success btn-sm',
        titleAttr: 'Exportar a Excel',
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'pdf',
        text: '<i class="fas fa-file-pdf"></i> PDF',
        className: 'btn-danger btn-sm',
        titleAttr: 'Exportar a PDF',
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'print',
        text: '<i class="fas fa-print"></i> Imprimir',
        className: 'btn-info btn-sm',
        titleAttr: 'Imprimir tabla',
        exportOptions: {
          columns: ':visible'
        }
      }
    ]
  });
});
</script>
<?php endif; ?>
