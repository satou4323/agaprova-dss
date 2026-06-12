<!-- KPI Widgets -->
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background: linear-gradient(135deg, #2E7D32, #388E3C); color: #fff;">
      <div class="inner">
        <h3>Bs <?php echo number_format($kpi['costo_promedio'], 2); ?></h3>
        <p>Costo Promedio</p>
      </div>
      <div class="icon">
        <i class="fas fa-calculator"></i>
      </div>
      <div class="small-box-footer" style="background: rgba(0,0,0,0.12);">
        <span>Promedio de costos vigentes</span>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background: linear-gradient(135deg, #C62828, #E53935); color: #fff;">
      <div class="inner">
        <h3>Bs <?php echo number_format($kpi['costo_max'], 2); ?></h3>
        <p>Ruta Más Cara</p>
      </div>
      <div class="icon">
        <i class="fas fa-arrow-up"></i>
      </div>
      <div class="small-box-footer" style="background: rgba(0,0,0,0.12);">
        <span><?php echo $kpi['ruta_max_codigo']; ?> — <?php echo $kpi['ruta_max_nombre']; ?></span>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background: linear-gradient(135deg, #1565C0, #1E88E5); color: #fff;">
      <div class="inner">
        <h3>Bs <?php echo number_format($kpi['costo_min'], 2); ?></h3>
        <p>Ruta Más Barata</p>
      </div>
      <div class="icon">
        <i class="fas fa-arrow-down"></i>
      </div>
      <div class="small-box-footer" style="background: rgba(0,0,0,0.12);">
        <span><?php echo $kpi['ruta_min_codigo']; ?> — <?php echo $kpi['ruta_min_nombre']; ?></span>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background: linear-gradient(135deg, #F57F17, #FBC02D); color: #fff;">
      <div class="inner">
        <h3><?php echo $kpi['total_cambios']; ?></h3>
        <p>Cambios Registrados</p>
      </div>
      <div class="icon">
        <i class="fas fa-history"></i>
      </div>
      <div class="small-box-footer" style="background: rgba(0,0,0,0.12);">
        <span>Total en el sistema</span>
      </div>
    </div>
  </div>
</div>

<!-- Existing form + active costs -->
<div class="row">
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-truck text-primary mr-1"></i> Actualizar Costo de Flete</h3>
      </div>
      <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/costoflete/actualizar">
          <div class="form-group" style="max-width: 300px;">
            <label class="text-muted" style="font-size: 0.85rem; font-weight: 500;">Ruta</label>
            <select name="ruta_id" class="form-control form-control-sm" required>
              <option value="">-- Seleccione --</option>
              <?php foreach ($rutas as $r): ?>
                <option value="<?php echo $r->id; ?>">
                  <?php echo $r->codigo; ?> - <?php echo $r->nombre; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group" style="max-width: 250px;">
            <label class="text-muted" style="font-size: 0.85rem; font-weight: 500;">Costo por Cabeza (Bs)</label>
            <div class="input-group input-group-sm">
              <input type="number" name="costo_cabeza" class="form-control" required min="0.01" step="0.01" placeholder="Ej: 420.50">
              <div class="input-group-append">
                <span class="input-group-text">Bs</span>
              </div>
            </div>
          </div>

          <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo $csrf; ?>">

          <button type="submit" class="btn btn-primary btn-sm" style="background-color: <?php echo COLOR_PRIMARY; ?>; border-color: <?php echo COLOR_PRIMARY; ?>; padding: 7px 20px;">
            <i class="fas fa-save"></i> Actualizar Costo
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-dollar-sign text-primary mr-1"></i> Costos Vigentes</h3>
      </div>
      <div class="card-body p-0">
        <?php if (!empty($costos)): ?>
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th>Ruta</th>
                <th>Costo/Cabeza</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($costos as $costo): ?>
                <tr>
                  <td>
                    <strong><?php echo $costo['codigo']; ?></strong><br>
                    <small><?php echo $costo['nombre']; ?></small>
                  </td>
                  <td style="text-align: right; font-weight: bold; color: <?php echo COLOR_PRIMARY; ?>; font-size: 1rem;">
                    Bs <?php echo number_format($costo['costo_cabeza'], 2); ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="p-4 text-center">
            <i class="fas fa-inbox fa-2x mb-2" style="color: #dee2e6;"></i>
            <p style="color: #999; margin: 0;">Sin costos registrados</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Historical changes DataTable -->
<div class="card card-outline card-primary mt-4">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-history text-primary mr-1"></i> Historial de Cambios de Costos</h3>
  </div>
  <div class="card-body p-0">
    <?php if (!empty($historial)): ?>
      <div class="table-responsive-wrapper historial-scroll">
        <table id="historialTable" class="table table-bordered table-striped table-hover table-valign-middle mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Ruta</th>
              <th>Costo por Cabeza</th>
              <th>Semana Inicio</th>
              <th>Registrado</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($historial as $h): ?>
              <tr>
                <td><strong><?php echo $h['id']; ?></strong></td>
                <td>
                  <strong><?php echo $h['codigo']; ?></strong><br>
                  <small><?php echo $h['nombre']; ?></small>
                </td>
                <td style="text-align: right; font-weight: bold; color: <?php echo COLOR_PRIMARY; ?>; font-size: 0.95rem;">
                  Bs <?php echo number_format($h['costo_cabeza'], 2); ?>
                </td>
                <td><?php echo date('d/m/Y', strtotime($h['semana_inicio'])); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($h['created_at'])); ?></td>
                <td>
                  <?php if ($h['activo']): ?>
                    <span class="badge badge-success">Vigente</span>
                  <?php else: ?>
                    <span class="badge badge-secondary">Reemplazado</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="p-5 text-center">
        <i class="fas fa-inbox fa-3x mb-3" style="color: #dee2e6;"></i>
        <h5 style="color: #6c757d;">Aún no hay cambios registrados</h5>
        <p style="color: #adb5bd;">Cada actualización de costo quedará registrada aquí.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<style>
.historial-scroll::-webkit-scrollbar {
  height: 8px;
}
.historial-scroll::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}
.historial-scroll::-webkit-scrollbar-thumb {
  background: #2E7D32;
  border-radius: 4px;
}
.historial-scroll::-webkit-scrollbar-thumb:hover {
  background: #1B5E20;
}
.historial-scroll {
  scrollbar-color: #2E7D32 #f1f1f1;
  scrollbar-width: thin;
}
</style>

<?php if (!empty($historial)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  $('#historialTable').DataTable({
    lengthChange: false,
    responsive: false,
    autoWidth: true,
    pagingType: 'full_numbers',
    pageLength: 9,
    lengthMenu: [[9, 18, 36, -1], [9, 18, 36, 'Todos']],
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
