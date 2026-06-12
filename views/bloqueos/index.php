<?php 
  $totalRutas = count($rutas);
  $rutasBloqueadas = 0;
  $rutasActivas = 0;
  $sumaTiempos = 0;
  foreach($rutas as $r) {
      if($r->bloqueado == 1) $rutasBloqueadas++;
      else $rutasActivas++;
      $sumaTiempos += $r->tiempo_horas;
  }
  $promedioTiempo = $totalRutas > 0 ? ($sumaTiempos / $totalRutas) : 0;
?>
<!-- Indicadores KPI -->
<div class="row mt-4 mb-3">
  <div class="col-lg-4 col-6">
    <div class="small-box bg-success shadow-sm">
      <div class="inner">
        <h3><?php echo $rutasActivas; ?></h3>
        <p>Rutas Libres y Activas</p>
      </div>
      <div class="icon">
        <i class="fas fa-route"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-6">
    <div class="small-box bg-danger shadow-sm">
      <div class="inner">
        <h3><?php echo $rutasBloqueadas; ?></h3>
        <p>Rutas Bloqueadas</p>
      </div>
      <div class="icon">
        <i class="fas fa-ban"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-12">
    <div class="small-box bg-info shadow-sm">
      <div class="inner">
        <h3><?php echo number_format($promedioTiempo, 1); ?><sup style="font-size: 20px">h</sup></h3>
        <p>Tiempo Promedio (Global)</p>
      </div>
      <div class="icon">
        <i class="far fa-clock"></i>
      </div>
    </div>
  </div>
</div>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-map-marked-alt text-primary mr-1"></i> Estado Detallado de Rutas</h3>
  </div>
  <div class="card-body">
    <div class="row">
      <?php foreach ($rutas as $ruta): ?>
        <?php 
          $isBloqueado = ($ruta->bloqueado == 1);
          $bgClass = $isBloqueado ? 'bg-danger' : 'bg-success';
          $iconClass = $isBloqueado ? 'fas fa-lock' : 'fas fa-road';
          $statusText = $isBloqueado ? 'BLOQUEADA' : 'ACTIVA';
        ?>
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
          <!-- Widget: user widget style 1 -->
          <div class="card card-widget widget-user shadow-sm h-100" style="margin-bottom: 0;">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header text-white <?php echo $bgClass; ?>" style="padding: 0.5rem 0.8rem; height: 60px;">
              <h3 class="widget-user-username font-weight-bold text-right" style="font-size: 1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.3); margin-bottom: 2px;"><?php echo $ruta->codigo; ?></h3>
              <h5 class="widget-user-desc text-right" style="font-size: 0.75rem; font-weight: 500;"><i class="<?php echo $iconClass; ?> mr-1"></i> <?php echo $statusText; ?></h5>
            </div>
            <div class="widget-user-image" style="top: 35px;">
              <div class="elevation-2 d-flex align-items-center justify-content-center" style="background: white; border-radius: 50%; width: 50px; height: 50px; border: 2px solid #fff; font-size: 1.2rem; color: <?php echo $isBloqueado ? '#dc3545' : '#28a745'; ?>; margin-left: -25px;">
                <i class="<?php echo $iconClass; ?>"></i>
              </div>
            </div>
            <div class="card-footer bg-white d-flex flex-column justify-content-between" style="padding: 30px 15px 10px 15px; flex-grow: 1;">
              <div>
                <h5 class="text-center font-weight-bold" style="color: #333; margin-bottom: 8px; font-size: 0.95rem;"><?php echo $ruta->nombre; ?></h5>
                <ul class="list-group list-group-unbordered mb-2">
                  <li class="list-group-item" style="padding: 0.25rem 0; border-top: none;">
                    <b style="font-size: 0.8rem;">Trayecto</b> <a class="float-right text-muted" style="font-size: 0.8rem;"><?php echo $ruta->origen; ?> &rarr; <?php echo $ruta->destino; ?></a>
                  </li>
                  <li class="list-group-item" style="padding: 0.25rem 0;">
                    <b style="font-size: 0.8rem;">Tiempo</b> <a class="float-right text-muted" style="font-size: 0.8rem;"><?php echo number_format($ruta->tiempo_horas, 1); ?>h</a>
                  </li>
                  <li class="list-group-item" style="padding: 0.25rem 0; border-bottom: none;">
                    <b style="font-size: 0.8rem;">Vía</b> <a class="float-right text-muted" style="font-size: 0.8rem;"><?php echo $ruta->tipo_via; ?></a>
                  </li>
                </ul>
              </div>

              <form method="POST" action="<?php echo BASE_URL; ?>/bloqueo/toggle" class="mt-auto ruta-toggle-form">
                <input type="hidden" name="ruta_id" value="<?php echo $ruta->id; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf; ?>">
                <?php if ($isBloqueado): ?>
                  <input type="hidden" name="accion" value="desactivar">
                  <button type="submit" class="btn btn-outline-success btn-sm btn-block font-weight-bold" style="border-width: 1px; padding: 2px 10px; font-size: 0.8rem;">
                    <i class="fas fa-unlock mr-1"></i> Desbloquear Ruta
                  </button>
                <?php else: ?>
                  <input type="hidden" name="accion" value="activar">
                  <button type="submit" class="btn btn-outline-danger btn-sm btn-block font-weight-bold" style="border-width: 1px; padding: 2px 10px; font-size: 0.8rem;">
                    <i class="fas fa-ban mr-1"></i> Bloquear Ruta
                  </button>
                <?php endif; ?>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php if (!empty($bloqueos)): ?>
  <div class="card card-outline card-primary mt-3">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-history text-primary mr-1"></i> Histórico de Bloqueos</h3>
    </div>
    <div class="card-body">
      <table id="historicoBloqueosTable" class="table table-bordered table-striped table-hover w-100">
        <thead>
          <tr class="bg-light">
            <th>Nombre de Ruta</th>
            <th>Código</th>
            <th>Estado Registrado</th>
            <th>Fecha de Movimiento</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($bloqueos as $bloqueo): ?>
            <?php 
              $isBloqHist = ($bloqueo['activo'] == 1);
              $badgeClass = $isBloqHist ? 'badge-danger' : 'badge-success';
              $iconHist = $isBloqHist ? 'fa-ban' : 'fa-check-circle';
              $textHist = $isBloqHist ? 'Bloqueada' : 'Desbloqueada';
            ?>
            <tr>
              <td class="align-middle font-weight-bold text-dark"><?php echo $bloqueo['nombre']; ?></td>
              <td class="align-middle text-muted"><?php echo $bloqueo['codigo']; ?></td>
              <td class="align-middle">
                <span class="badge <?php echo $badgeClass; ?> p-2" style="font-size: 0.85rem;">
                  <i class="fas <?php echo $iconHist; ?> mr-1"></i> <?php echo $textHist; ?>
                </span>
              </td>
              <td class="align-middle"><i class="far fa-calendar-alt text-muted mr-1"></i> <?php echo date('d/m/Y H:i', strtotime($bloqueo['fecha_inicio'])); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<!-- SweetAlert2 y DataTables CDNs -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // 1. Inicializar DataTables
    if (typeof jQuery !== 'undefined' && $.fn.DataTable) {
      $('#historicoBloqueosTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "pageLength": 9,
        "autoWidth": false,
        "order": [[3, "desc"]], // Ordenar por fecha desc
        "dom": "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        "buttons": [
          { extend: 'csvHtml5', text: '<i class="fas fa-file-csv"></i> CSV', className: 'btn btn-success btn-sm' },
          { extend: 'pdfHtml5', text: '<i class="fas fa-file-pdf"></i> PDF', className: 'btn btn-danger btn-sm' },
          { extend: 'print', text: '<i class="fas fa-print"></i> Imprimir', className: 'btn btn-info btn-sm' }
        ],
        "language": {
          "search": "Buscar:",
          "zeroRecords": "No se encontraron registros",
          "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
          "infoEmpty": "Mostrando 0 registros",
          "infoFiltered": "(filtrado de _MAX_ totales)",
          "paginate": { "first": "Primero", "last": "Último", "next": "Siguiente", "previous": "Anterior" }
        }
      });
    }

    // 2. Interceptar el formulario para mostrar alerta visual antes del envío
    const forms = document.querySelectorAll('.ruta-toggle-form');
    forms.forEach(form => {
      form.addEventListener('submit', function(e) {
        // No prevenimos el envío, solo disparamos la alerta de SweetAlert para que el usuario la vea
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true
        });
        const accion = form.querySelector('input[name="accion"]').value;
        if(accion === 'activar') {
           Toast.fire({ icon: 'error', title: 'Bloqueando ruta...' });
        } else {
           Toast.fire({ icon: 'success', title: 'Desbloqueando ruta...' });
        }
      });
    });

    // 3. Revisar si hay mensaje de éxito desde PHP
    <?php if(\App\Session::hasFlash('success')): ?>
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      });
      Toast.fire({
        icon: 'success',
        title: '<?php echo \App\Session::flash('success'); ?>'
      });
    <?php endif; ?>
  });
</script>
