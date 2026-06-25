<div class="mt-3"></div>
<!-- KPI Widgets -->
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background:linear-gradient(135deg,#2E7D32 0%,#1B5E20 100%);box-shadow:0 2px 6px rgba(46,125,50,0.25);">
      <div class="inner">
        <h3 style="color:#fff;font-size:1.6rem;font-weight:700;">Bs <?php echo number_format($kpi['costo_promedio'],2); ?></h3>
        <p style="color:rgba(255,255,255,0.85);font-size:0.85rem;margin-bottom:0;">Costo Promedio</p>
      </div>
      <div class="icon"><i class="fas fa-calculator" style="color:rgba(255,255,255,0.2);font-size:70px;"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background:linear-gradient(135deg,#C62828 0%,#B71C1C 100%);box-shadow:0 2px 6px rgba(198,40,40,0.25);">
      <div class="inner">
        <h3 style="color:#fff;font-size:1.6rem;font-weight:700;">Bs <?php echo number_format($kpi['costo_max'],2); ?></h3>
        <p style="color:rgba(255,255,255,0.85);font-size:0.85rem;margin-bottom:0;">Ruta Más Cara</p>
      </div>
      <div class="icon"><i class="fas fa-arrow-up" style="color:rgba(255,255,255,0.2);font-size:70px;"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background:linear-gradient(135deg,#1565C0 0%,#0D47A1 100%);box-shadow:0 2px 6px rgba(21,101,192,0.25);">
      <div class="inner">
        <h3 style="color:#fff;font-size:1.6rem;font-weight:700;">Bs <?php echo number_format($kpi['costo_min'],2); ?></h3>
        <p style="color:rgba(255,255,255,0.85);font-size:0.85rem;margin-bottom:0;">Ruta Más Barata</p>
      </div>
      <div class="icon"><i class="fas fa-arrow-down" style="color:rgba(255,255,255,0.2);font-size:70px;"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background:linear-gradient(135deg,#F57F17 0%,#E65100 100%);box-shadow:0 2px 6px rgba(245,127,23,0.3);">
      <div class="inner">
        <h3 style="color:#fff;font-size:1.6rem;font-weight:700;"><?php echo $kpi['total_cambios']; ?></h3>
        <p style="color:rgba(255,255,255,0.85);font-size:0.85rem;margin-bottom:0;">Cambios Registrados</p>
      </div>
      <div class="icon"><i class="fas fa-history" style="color:rgba(255,255,255,0.2);font-size:70px;"></i></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-truck text-primary mr-1"></i> Actualizar Costo de Flete</h3>
      </div>
      <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/costoflete/actualizar">
          <div class="form-group" style="max-width:300px;">
            <label class="text-muted" style="font-size:0.85rem;font-weight:500;">Ruta</label>
            <select name="ruta_id" class="form-control form-control-sm" required>
              <option value="">-- Seleccione --</option>
              <?php foreach ($rutas as $r): ?>
                <option value="<?php echo $r->id; ?>"><?php echo $r->codigo; ?> - <?php echo $r->nombre; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group" style="max-width:250px;">
            <label class="text-muted" style="font-size:0.85rem;font-weight:500;">Costo por Cabeza (Bs)</label>
            <div class="input-group input-group-sm">
              <input type="number" name="costo_cabeza" class="form-control" required min="0.01" step="0.01" placeholder="Ej: 420.50">
              <div class="input-group-append"><span class="input-group-text">Bs</span></div>
            </div>
          </div>
          <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo $csrf; ?>">
          <button type="submit" class="btn btn-primary btn-sm"
                  style="background-color:<?php echo COLOR_PRIMARY; ?>;border-color:<?php echo COLOR_PRIMARY; ?>;padding:7px 20px;">
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
        <?php if (!empty($costos)):
          // CAMBIO 9: Calcular cuál es la más cara y la más barata
          $valores = array_column($costos, 'costo_cabeza');
          $minCosto = min($valores);
          $maxCosto = max($valores);
        ?>
          <table class="table table-bordered mb-0">
            <thead>
              <tr><th>Ruta</th><th class="text-right">Costo/Cabeza</th></tr>
            </thead>
            <tbody>
              <?php foreach ($costos as $costo):
                $esMasBarata = ($costo['costo_cabeza'] == $minCosto);
                $esMasCara   = ($costo['costo_cabeza'] == $maxCosto);
                $colorTexto  = $esMasBarata ? 'text-success' : ($esMasCara ? 'text-danger' : 'text-dark');
              ?>
                <tr>
                  <td>
                    <strong><?php echo $costo['codigo']; ?></strong><br>
                    <small><?php echo $costo['nombre']; ?></small>
                  </td>
                  <td class="text-right align-middle">
                    <strong class="<?php echo $colorTexto; ?>">
                      Bs <?php echo number_format($costo['costo_cabeza'],2); ?>
                    </strong>
                    <?php if ($esMasBarata): ?>
                      <span class="badge badge-success ml-1" title="Ruta más económica">ÓPTIMA</span>
                    <?php elseif ($esMasCara): ?>
                      <span class="badge badge-danger ml-1" title="Ruta más costosa">MÁS CARA</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="p-4 text-center">
            <i class="fas fa-inbox fa-2x mb-2" style="color:#dee2e6;"></i>
            <p style="color:#999;margin:0;">Sin costos registrados</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Historial -->
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
              <th>#</th><th>Ruta</th><th>Costo por Cabeza</th><th>Semana Inicio</th><th>Registrado</th><th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($historial as $h): ?>
              <tr>
                <td><strong><?php echo $h['id']; ?></strong></td>
                <td><strong><?php echo $h['codigo']; ?></strong><br><small><?php echo $h['nombre']; ?></small></td>
                <td style="text-align:right;font-weight:bold;color:<?php echo COLOR_PRIMARY; ?>;font-size:0.95rem;">
                  Bs <?php echo number_format($h['costo_cabeza'],2); ?>
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
        <i class="fas fa-inbox fa-3x mb-3" style="color:#dee2e6;"></i>
        <h5 style="color:#6c757d;">Aún no hay cambios registrados</h5>
      </div>
    <?php endif; ?>
  </div>
</div>

<style>
.historial-scroll::-webkit-scrollbar{height:8px}
.historial-scroll::-webkit-scrollbar-track{background:#f1f1f1;border-radius:4px}
.historial-scroll::-webkit-scrollbar-thumb{background:#2E7D32;border-radius:4px}
.historial-scroll::-webkit-scrollbar-thumb:hover{background:#1B5E20}
.historial-scroll{scrollbar-color:#2E7D32 #f1f1f1;scrollbar-width:thin}
</style>

<?php if (!empty($historial)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  $('#historialTable').DataTable({
    lengthChange:false, responsive:false, autoWidth:true,
    pagingType:'full_numbers', pageLength:6, order:[[0,'desc']],
    language:{
      zeroRecords:'No se encontraron resultados', emptyTable:'Ningún dato disponible',
      info:'Mostrando _START_ a _END_ de _TOTAL_ registros', infoEmpty:'Mostrando 0 registros',
      search:'<i class="fas fa-search text-muted mr-1"></i> Buscar:',
      paginate:{first:'<i class="fas fa-angle-double-left"></i>',last:'<i class="fas fa-angle-double-right"></i>',next:'<i class="fas fa-angle-right"></i>',previous:'<i class="fas fa-angle-left"></i>'}
    },
    dom:"<'row m-0 px-3 pt-3'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>><'row m-0 px-3'<'col-sm-12'tr>><'row m-0 px-3 pb-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    buttons:[
      {extend:'copy', text:'<i class="fas fa-copy"></i> Copiar',className:'btn-secondary btn-sm',exportOptions:{columns:':visible'}},
      {extend:'excel',text:'<i class="fas fa-file-excel"></i> Excel',className:'btn-success btn-sm',exportOptions:{columns:':visible'}},
      {extend:'pdf',  text:'<i class="fas fa-file-pdf"></i> PDF',className:'btn-danger btn-sm',exportOptions:{columns:':visible'}},
      {extend:'print',text:'<i class="fas fa-print"></i> Imprimir',className:'btn-info btn-sm',exportOptions:{columns:':visible'}}
    ]
  });
});
</script>
<?php endif; ?>