<?php
use App\Services\ClimaService;
?>
<div class="row">
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-cloud-rain text-primary mr-1"></i> Actualizar Probabilidad de Lluvia en Abapó</h3>
      </div>
      <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/clima/actualizar" id="climaForm">
          <div class="row align-items-center">
            <!-- Columna 1: Etiqueta y Slider -->
            <div class="col-md-7 border-right">
              <div class="form-group mb-0">
                <label class="text-muted" style="font-size: 0.9rem; font-weight: bold;">Probabilidad de Lluvia</label>
                <div style="margin-top: 15px; margin-bottom: 5px; padding: 0 10px;">
                  <input id="prob_slider" type="text" name="probabilidad_lluvia" value="" placeholder="Ej: 0.25">
                </div>
              </div>
            </div>
            
            <!-- Columna 2: Textos de ayuda -->
            <div class="col-md-5 d-flex flex-column justify-content-center">
              <small class="text-muted d-block mb-2">0.00 = Sin lluvia | 0.50 = 50% | 1.00 = Lluvia segura</small>
              <small class="text-info d-block mb-0"><i class="fas fa-info-circle"></i> Deslice para ajustar la probabilidad.</small>
            </div>
          </div>

          <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-sm shadow-sm" style="background-color: <?php echo COLOR_PRIMARY; ?>; border-color: <?php echo COLOR_PRIMARY; ?>; padding: 7px 30px;">
              <i class="fas fa-cloud-upload-alt mr-1"></i> Guardar Condición Climática
            </button>
          </div>

          <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo $csrf; ?>">
        </form>

        <div class="callout callout-info mt-3 mb-0" style="padding: 0.7rem 0.9rem; font-size: 0.85rem;">
          <i class="fas fa-exclamation-circle text-info mr-1"></i>
          <strong>Restricción:</strong> Si probabilidad &ge; 0.40, la Ruta 3 (Ipati-Abapó) será bloqueada automáticamente.
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <?php if ($clima_actual): ?>
      <?php
        $prob = $clima_actual->probabilidad_lluvia;
        // Definir clases CSS dinámicas según la probabilidad
        if ($prob < 0.2) { $bgClass = 'bg-info'; $emoji = '☀️'; $iconClass = 'fas fa-sun'; }
        elseif ($prob < 0.4) { $bgClass = 'bg-primary'; $emoji = '🌤️'; $iconClass = 'fas fa-cloud-sun'; }
        elseif ($prob < 0.6) { $bgClass = 'bg-secondary'; $emoji = '⛅'; $iconClass = 'fas fa-cloud'; }
        elseif ($prob < 0.8) { $bgClass = 'bg-dark'; $emoji = '🌦️'; $iconClass = 'fas fa-cloud-showers-heavy'; }
        else { $bgClass = 'bg-danger'; $emoji = '🌧️'; $iconClass = 'fas fa-poo-storm'; }
      ?>
      <!-- Widget: user widget style 1 -->
      <div class="card card-widget widget-user shadow-sm" style="margin-bottom: 0;">
        <div class="widget-user-header text-white <?php echo $bgClass; ?>" style="height: 140px; padding: 1.5rem;">
          <h3 class="widget-user-username font-weight-bold text-right" style="font-size: 2.2rem;"><?php echo number_format($prob * 100, 1); ?>%</h3>
          <h5 class="widget-user-desc text-right"><i class="<?php echo $iconClass; ?> mr-1"></i> <?php echo ClimaService::getInterpretacion($prob); ?></h5>
        </div>
        <div class="widget-user-image" style="top: 80px;">
          <div class="elevation-2" style="background: white; border-radius: 50%; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; font-size: 50px; border: 3px solid #fff; margin-left: -50px;">
            <?php echo $emoji; ?>
          </div>
        </div>
        <div class="card-footer" style="padding-top: 50px;">
          <div class="row">
            <div class="col-sm-6 border-right">
              <div class="description-block">
                <h5 class="description-header text-muted" style="font-weight: normal; font-size: 0.8rem;">ESTADO DE RUTA 3</h5>
                <?php if ($prob >= 0.40): ?>
                  <span class="description-text text-danger font-weight-bold" style="font-size: 1rem;"><i class="fas fa-ban mr-1"></i> BLOQUEADA</span>
                <?php else: ?>
                  <span class="description-text text-success font-weight-bold" style="font-size: 1rem;"><i class="fas fa-check-circle mr-1"></i> DISPONIBLE</span>
                <?php endif; ?>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="description-block">
                <h5 class="description-header text-muted" style="font-weight: normal; font-size: 0.8rem;">ÚLTIMO REGISTRO</h5>
                <span class="description-text text-dark" style="text-transform: none; font-weight: 500; font-size: 1rem;"><i class="far fa-clock mr-1"></i> <?php echo date('d/m/Y H:i', strtotime($clima_actual->created_at)); ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-chart-bar text-primary mr-1"></i> Condición Actual</h3>
        </div>
        <div class="card-body">
          <div class="text-center py-4">
            <i class="fas fa-inbox fa-3x mb-3" style="color: #dee2e6;"></i>
            <p style="color: #999; font-size: 1.1rem; margin: 0;">Sin datos de clima</p>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-history text-primary mr-1"></i> Histórico de Registros</h3>
  </div>
  <div class="card-body">
    <?php if (!empty($historico)): ?>
      <table id="climaTable" class="table table-bordered table-striped table-hover w-100">
        <thead>
          <tr class="bg-light">
            <th style="width: 150px;">Fecha y Hora</th>
            <th>Probabilidad</th>
            <th>Interpretación y Nivel de Alerta</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($historico as $h): ?>
            <?php 
              $p = $h['probabilidad_lluvia'];
              $rowClass = '';
              if ($p < 0.2) { $badge = 'badge-success'; $icon = 'fa-sun'; }
              elseif ($p < 0.4) { $badge = 'badge-info'; $icon = 'fa-cloud-sun'; }
              elseif ($p < 0.6) { $badge = 'badge-secondary'; $icon = 'fa-cloud'; }
              elseif ($p < 0.8) { $badge = 'badge-warning'; $icon = 'fa-cloud-showers-heavy'; $rowClass = 'table-warning'; }
              else { $badge = 'badge-danger'; $icon = 'fa-poo-storm'; $rowClass = 'table-danger'; }
            ?>
            <tr class="<?php echo $rowClass; ?>">
              <td class="align-middle"><i class="far fa-calendar-alt text-muted mr-1"></i> <?php echo date('d/m/Y H:i', strtotime($h['fecha_registro'])); ?></td>
              <td class="align-middle font-weight-bold" style="font-size: 1.1rem;"><?php echo number_format($p * 100, 1); ?>%</td>
              <td class="align-middle">
                <span class="badge <?php echo $badge; ?> p-2" style="font-size: 0.85rem;">
                  <i class="fas <?php echo $icon; ?> mr-1"></i> <?php echo ClimaService::getInterpretacion($p); ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="p-4 text-center">
        <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
        <p class="text-muted" style="font-size: 1.1rem; margin: 0;">Aún no se ha registrado historial de clima.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Estilos para Ion Slider y Daterange Picker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // 1. Inicializar Ion Range Slider
    if (typeof jQuery !== 'undefined' && $.fn.ionRangeSlider) {
      $('#prob_slider').ionRangeSlider({
        min: 0,
        max: 1,
        from: <?php echo $clima_actual ? $clima_actual->probabilidad_lluvia : 0.25; ?>,
        step: 0.01,
        type: 'single',
        postfix: " (probabilidad)",
        prettify: function (num) {
          return (num * 100).toFixed(0) + '%';
        },
        grid: true,
        grid_num: 4,
        skin: "round"
      });
    }

    // 2. Inicializar DataTables para el Historial
    if (typeof jQuery !== 'undefined' && $.fn.DataTable) {
      var table = $('#climaTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "pageLength": 5,
        "autoWidth": false,
        "order": [[0, "desc"]], // Ordenar por fecha más reciente
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
  });
</script>
