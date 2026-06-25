<div class="row">
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-tag text-primary mr-1"></i> Actualizar Precios</h3>
      </div>
      <div class="card-body">
        <form id="formPrecios" method="POST" action="<?php echo BASE_URL; ?>/precio/actualizar" novalidate>
          <div class="form-group" style="max-width:300px;">
            <label class="text-muted" style="font-size:0.85rem;font-weight:500;">Mercado</label>
            <select name="mercado_id" class="form-control select2" style="width:100%;" required>
              <option value="">-- Seleccione --</option>
              <?php foreach ($mercados as $m): ?>
                <option value="<?php echo $m['id']; ?>"><?php echo $m['nombre']; ?> (<?php echo $m['ciudad']; ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group" style="max-width:250px;">
            <label class="text-muted" style="font-size:0.85rem;font-weight:500;">Precio por kg (Bs)</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-money-bill-wave text-success"></i></span>
              </div>
              <input type="number" name="precio_kg" class="form-control" required min="0.01" step="0.01" placeholder="Ej: 32.50" style="font-weight:bold;">
              <div class="input-group-append"><span class="input-group-text font-weight-bold">Bs/kg</span></div>
            </div>
          </div>
          <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo $csrf; ?>">
          <button type="submit" class="btn btn-primary btn-sm"
                  style="background-color:<?php echo COLOR_PRIMARY; ?>;border-color:<?php echo COLOR_PRIMARY; ?>;padding:7px 20px;">
            <i class="fas fa-save"></i> Actualizar Precio
          </button>
        </form>
      </div>
    </div>

    <div class="card card-outline card-info mt-3">
      <div class="card-header border-0">
        <h3 class="card-title"><i class="fas fa-history text-info mr-1"></i> Últimos Movimientos</h3>
      </div>
      <div class="card-body p-0 table-responsive table-scroll-custom" style="max-height:340px;overflow-y:auto;">
        <table class="table table-striped table-valign-middle mb-0" style="font-size:0.85rem;">
          <thead><tr><th>Fecha</th><th>Mercado</th><th>Precio (Bs)</th></tr></thead>
          <tbody>
            <?php if (!empty($hist_data_raw)): ?>
              <?php foreach ($hist_data_raw as $h): ?>
                <tr>
                  <td><?php echo date('d/m/Y', strtotime($h['fecha_registro'])); ?></td>
                  <td><?php echo $h['mercado_nombre']; ?></td>
                  <td class="font-weight-bold text-success"><?php echo number_format($h['precio_kg'],2); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="3" class="text-center text-muted">No hay historial</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <?php if (!empty($mejor_precio)): ?>
    <div class="callout callout-success shadow-sm" style="background-color:#f8fff9;">
      <h5 class="text-success"><i class="fas fa-lightbulb"></i> Sugerencia del Sistema</h5>
      <p style="margin-bottom:0;">Actualmente, el mercado de <strong><?php echo $mejor_precio['mercado_nombre']; ?></strong> ofrece la mayor rentabilidad (<strong>Bs <?php echo number_format($mejor_precio['precio_kg'],2); ?>/kg</strong>).</p>
    </div>
    <?php endif; ?>

    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chart-line text-primary mr-1"></i> Precios Vigentes</h3>
      </div>
      <div class="card-body bg-light">
        <?php if (!empty($precios)): ?>
          <div class="row">
            <?php foreach ($precios as $p): ?>
              <div class="col-12">
                <div class="info-box shadow-sm mb-2" style="border-radius:6px;min-height:60px;padding:0.5rem;">
                  <span class="info-box-icon bg-success elevation-1" style="border-radius:6px;width:50px;font-size:1.2rem;"><i class="fas fa-money-bill-wave"></i></span>
                  <div class="info-box-content" style="justify-content:center;padding:0 10px;">
                    <span class="info-box-text text-uppercase text-muted font-weight-bold" style="font-size:0.8rem;"><?php echo $p['mercado_nombre']; ?></span>
                    <span class="info-box-number" style="font-size:1.2rem;color:<?php echo COLOR_PRIMARY; ?>;">
                      Bs <?php echo number_format($p['precio_kg'],2); ?> <small style="font-size:0.8rem;">/ kg</small>
                      <?php if (isset($p['delta']) && $p['delta'] != 0): ?>
                        <span class="badge <?php echo $p['delta']>0?'badge-success':'badge-danger'; ?> ml-2" style="font-size:0.7rem;">
                          <i class="fas <?php echo $p['delta']>0?'fa-arrow-up':'fa-arrow-down'; ?>"></i>
                          <?php echo $p['delta']>0?'+':''; ?><?php echo number_format($p['delta'],2); ?> Bs
                        </span>
                      <?php else: ?>
                        <span class="badge badge-secondary ml-2" style="font-size:0.7rem;"><i class="fas fa-minus"></i> 0.00 Bs</span>
                      <?php endif; ?>
                    </span>
                    <span class="progress-description text-muted" style="font-size:0.75rem;margin-top:-2px;">
                      <i class="far fa-clock mr-1"></i> Registrado: <?php echo date('d/m/Y', strtotime($p['fecha_registro'])); ?>
                    </span>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="p-4 text-center">
            <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
            <p class="text-muted" style="font-size:1.1rem;margin:0;">Sin precios registrados</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="card card-outline card-success mt-3">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chart-bar text-success mr-1"></i> Comparativa de Mercados</h3>
      </div>
      <div class="card-body p-2">
        <?php if (!empty($precios)): ?>
          <canvas id="barChart" style="min-height:200px;height:200px;max-height:200px;max-width:100%;"></canvas>
        <?php else: ?>
          <div class="p-4 text-center">
            <i class="fas fa-chart-bar fa-2x mb-2 text-muted"></i>
            <p class="text-muted" style="font-size:0.9rem;margin:0;">Sin precios para comparar.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<style>
.table-scroll-custom::-webkit-scrollbar{width:8px;height:8px}
.table-scroll-custom::-webkit-scrollbar-track{background:#e8e8e8;border-radius:4px}
.table-scroll-custom::-webkit-scrollbar-thumb{background:<?php echo COLOR_PRIMARY; ?>;border-radius:4px}
.table-scroll-custom::-webkit-scrollbar-thumb:hover{background:#1b5e20}
.table-scroll-custom{scrollbar-color:<?php echo COLOR_PRIMARY; ?> #e8e8e8;scrollbar-width:thin}
</style>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/adminlte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/chart.js/chart.umd.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/select2/js/select2.full.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (typeof jQuery !== 'undefined' && $.fn.select2) {
      $('.select2').select2({ theme: 'bootstrap4' });
    }

    if (typeof jQuery !== 'undefined' && typeof Swal !== 'undefined') {
      var alertSuccess = $('.alert-success');
      var alertError   = $('.alert-danger');
      if (alertSuccess.length) {
        var msg = alertSuccess.text().replace('×','').trim();
        alertSuccess.hide();
        Swal.fire({ toast:true, position:'top-end', showConfirmButton:false, timer:4000, icon:'success', title:msg });
      }
      if (alertError.length) {
        var msgErr = alertError.text().replace('×','').trim();
        alertError.hide();
        Swal.fire({ toast:true, position:'top-end', showConfirmButton:false, timer:5000, icon:'error', title:msgErr });
      }
    }

    if (typeof jQuery !== 'undefined' && $.fn.validate) {
      $('form').validate({
        rules: { precio_kg:{ required:true, number:true, min:0.01 }, mercado_id:{ required:true } },
        messages: { precio_kg:{ required:"Ingrese el precio", number:"Solo números", min:"Debe ser mayor a 0" }, mercado_id:{ required:"Seleccione un mercado" } },
        errorElement:'span',
        errorPlacement:function(error,element){ error.addClass('invalid-feedback'); element.closest('.input-group').length?element.closest('.input-group').after(error):element.closest('.form-group').append(error); },
        highlight:function(element){ $(element).addClass('is-invalid').removeClass('is-valid'); },
        unhighlight:function(element){ $(element).removeClass('is-invalid').addClass('is-valid'); }
      });
    }

    <?php if (!empty($precios)): ?>
    if (typeof Chart !== 'undefined') {
      var preciosData = <?php echo json_encode($precios); ?>;
      var labels      = preciosData.map(p => p.mercado_nombre);
      var dataPoints  = preciosData.map(p => parseFloat(p.precio_kg));
      var ctx         = document.getElementById('barChart').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Precio Actual (Bs/kg)',
            data: dataPoints,
            backgroundColor: ['rgba(40,167,69,0.8)','rgba(0,123,255,0.8)','rgba(255,193,7,0.8)','rgba(220,53,69,0.8)'],
            borderColor:     ['#28a745','#007bff','#ffc107','#dc3545'],
            borderWidth: 1, borderRadius: 4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            y: {
              beginAtZero: false,
              suggestedMin: Math.max(0, Math.min(...dataPoints) - 5),
              // CAMBIO 6: Título del eje Y activado
              title: {
                display: true,
                text: 'Precio (Bs/kg)',
                color: '#666',
                font: { size: 12 }
              },
              ticks: {
                callback: function(value) { return 'Bs ' + value; }
              }
            }
          }
        }
      });
    }
    <?php endif; ?>
  });
</script>