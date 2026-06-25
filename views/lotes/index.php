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
      <div class="table-responsive-wrapper d-none d-md-block">
      <table id="lotesTable" class="table table-bordered table-striped table-hover table-valign-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Cabezas</th>
            <th>Peso Promedio</th>
            <th>Condición</th>
            <th>Estación</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($lotes as $lote):
            $cond = array_values(array_filter($condiciones, fn($c) => $c->id == $lote->condicion_id))[0] ?? null;
            $est  = array_values(array_filter($estaciones,  fn($e) => $e->id == $lote->estacion_id))[0]  ?? null;
          ?>
            <tr>
              <td><strong><?php echo $lote->id; ?></strong></td>
              <td><?php echo $lote->cabezas; ?></td>
              <td><?php echo number_format($lote->peso_promedio_kg, 2); ?> kg</td>
              <td>
                <?php echo $cond?->nombre ?? 'N/A'; ?>
                <?php if ($cond && !empty($cond->descripcion)): ?>
                  <br><small style="font-size:0.7rem;color:#888;font-style:italic;"><?php echo htmlspecialchars($cond->descripcion); ?></small>
                <?php endif; ?>
              </td>
              <td>
                <?php echo $est?->nombre ?? 'N/A'; ?>
                <?php if ($est && !empty($est->descripcion)): ?>
                  <br><small style="font-size:0.7rem;color:#888;font-style:italic;"><?php echo htmlspecialchars($est->descripcion); ?></small>
                <?php endif; ?>
              </td>
              <td><?php echo date('d/m/Y', strtotime($lote->fecha_registro)); ?></td>
              <td><span class="badge badge-success">Activo</span></td>
              <td class="text-center" style="white-space:nowrap;">
                <a href="<?php echo BASE_URL; ?>/lote/editar/<?php echo $lote->id; ?>" class="btn btn-warning btn-xs mr-1" title="Editar">
                  <i class="fas fa-edit"></i> Editar
                </a>
                <button type="button"
                        class="btn btn-danger btn-xs"
                        title="Eliminar"
                        data-toggle="modal"
                        data-target="#modalEliminar"
                        data-lote-id="<?php echo $lote->id; ?>"
                        data-form-id="form-eliminar-<?php echo $lote->id; ?>">
                  <i class="fas fa-trash-alt"></i> Eliminar
                </button>
                <form id="form-eliminar-<?php echo $lote->id; ?>"
                      method="POST"
                      action="<?php echo BASE_URL; ?>/lote/eliminar/<?php echo $lote->id; ?>"
                      style="display:none;">
                  <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo $csrf; ?>">
                </form>
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
          $est  = array_values(array_filter($estaciones,  fn($e) => $e->id == $lote->estacion_id))[0]  ?? null;
        ?>
          <div class="card mb-2" style="border-left:4px solid <?php echo COLOR_PRIMARY; ?>;box-shadow:0 1px 2px rgba(0,0,0,0.06);">
            <div class="card-body p-3">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <strong style="color:<?php echo COLOR_PRIMARY; ?>;font-size:15px;">Lote #<?php echo $lote->id; ?></strong>
                <span class="badge badge-success">Activo</span>
              </div>
              <div style="font-size:13px;line-height:1.7;">
                <div><span class="text-muted">Cabezas:</span> <?php echo $lote->cabezas; ?></div>
                <div><span class="text-muted">Peso:</span> <?php echo number_format($lote->peso_promedio_kg, 2); ?> kg</div>
                <div><span class="text-muted">Condición:</span> <?php echo $cond?->nombre ?? 'N/A'; ?></div>
                <div><span class="text-muted">Estación:</span> <?php echo $est?->nombre ?? 'N/A'; ?></div>
                <div><span class="text-muted">Fecha:</span> <?php echo date('d/m/Y', strtotime($lote->fecha_registro)); ?></div>
              </div>
              <div class="mt-2 d-flex">
                <a href="<?php echo BASE_URL; ?>/lote/editar/<?php echo $lote->id; ?>" class="btn btn-warning btn-xs mr-2">
                  <i class="fas fa-edit"></i> Editar
                </a>
                <button type="button"
                        class="btn btn-danger btn-xs"
                        data-toggle="modal"
                        data-target="#modalEliminar"
                        data-lote-id="<?php echo $lote->id; ?>"
                        data-form-id="form-eliminar-<?php echo $lote->id; ?>">
                  <i class="fas fa-trash-alt"></i> Eliminar
                </button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="p-5 text-center">
        <i class="fas fa-inbox fa-3x mb-3" style="color:#dee2e6;"></i>
        <h5 style="color:#6c757d;">Aún no hay lotes registrados</h5>
        <p style="color:#adb5bd;">Los lotes que registres aparecerán aquí.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Modal Confirmar Eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
          <i class="fas fa-exclamation-triangle mr-2"></i> Confirmar Eliminación
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body text-center py-4">
        <i class="fas fa-trash-alt fa-3x text-danger mb-3 d-block"></i>
        <p class="mb-1">¿Estás seguro de eliminar el <strong>Lote #<span id="loteIdConfirm"></span></strong>?</p>
        <small class="text-muted">Esta acción no se puede deshacer.</small>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Cancelar
        </button>
        <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">
          <i class="fas fa-trash-alt mr-1"></i> Sí, eliminar
        </button>
      </div>
    </div>
  </div>
</div>

<?php if (!empty($lotes)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {

  $('#modalEliminar').on('show.bs.modal', function(e) {
    var btn    = e.relatedTarget;
    var loteId = btn.getAttribute('data-lote-id');
    var formId = btn.getAttribute('data-form-id');
    document.getElementById('loteIdConfirm').textContent = loteId;
    document.getElementById('btnConfirmarEliminar').onclick = function() {
      document.getElementById(formId).submit();
    };
  });

  $('#lotesTable').DataTable({
    lengthChange: false,
    responsive: false,
    autoWidth: true,
    pagingType: 'full_numbers',
    pageLength: 9,
    order: [[0, 'desc']],
    columnDefs: [{ orderable: false, targets: -1 }],
    language: {
      zeroRecords: 'No se encontraron resultados',
      emptyTable:  'Ningún dato disponible',
      info:        'Mostrando _START_ a _END_ de _TOTAL_ registros',
      infoEmpty:   'Mostrando 0 registros',
      infoFiltered:'(filtrado de _MAX_ totales)',
      search:      '<i class="fas fa-search text-muted mr-1"></i> Buscar:',
      paginate: {
        first:    '<i class="fas fa-angle-double-left"></i>',
        last:     '<i class="fas fa-angle-double-right"></i>',
        next:     '<i class="fas fa-angle-right"></i>',
        previous: '<i class="fas fa-angle-left"></i>'
      }
    },
    dom:
      "<'row m-0 px-3 pt-3'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
      "<'row m-0 px-3'<'col-sm-12'tr>>" +
      "<'row m-0 px-3 pb-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    buttons: [
      { extend: 'copy',  text: '<i class="fas fa-copy"></i> Copiar',    className: 'btn-secondary btn-sm', exportOptions: { columns: ':visible:not(:last-child)' } },
      { extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn-success btn-sm',  exportOptions: { columns: ':visible:not(:last-child)' } },
      { extend: 'pdf',   text: '<i class="fas fa-file-pdf"></i> PDF',    className: 'btn-danger btn-sm',   exportOptions: { columns: ':visible:not(:last-child)' } },
      { extend: 'print', text: '<i class="fas fa-print"></i> Imprimir',  className: 'btn-info btn-sm',     exportOptions: { columns: ':visible:not(:last-child)' } }
    ]
  });
});
</script>
<?php endif; ?>