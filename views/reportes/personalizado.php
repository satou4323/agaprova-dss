<div class="row mb-3">
  <div class="col-sm-6">
    <h3 class="m-0"><i class="fas fa-calendar text-primary mr-1"></i> Reporte Personalizado</h3>
  </div>
</div>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-calendar-range text-primary mr-1"></i> Seleccionar Rango de Fechas</h3>
  </div>
  <div class="card-body">
    <form method="POST" action="<?php echo BASE_URL; ?>/reporte/personalizado">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="text-muted" style="font-size:0.85rem; font-weight:500;">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control form-control-sm"
                   required value="<?php echo $fecha_inicio_pred; ?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="text-muted" style="font-size:0.85rem; font-weight:500;">Fecha Fin</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control form-control-sm"
                   required value="<?php echo $fecha_fin_pred; ?>">
          </div>
        </div>
      </div>

      <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo $csrf; ?>">

      <button type="submit" class="btn btn-primary btn-sm"
              style="background-color:<?php echo COLOR_PRIMARY; ?>; border-color:<?php echo COLOR_PRIMARY; ?>; padding:7px 22px;">
        <i class="fas fa-search mr-1"></i> Generar Reporte
      </button>
    </form>
  </div>
</div>

<!-- Filtros Rápidos -->
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-filter text-primary mr-1"></i> Filtros Rápidos</h3>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-3 col-6 mb-2">
        <a href="#" id="btn-hoy"
           class="btn btn-outline-primary btn-sm btn-block filtro-rapido"
           onclick="setFechas('<?php echo date('Y-m-d'); ?>', '<?php echo date('Y-m-d'); ?>'); return false;">
          <i class="fas fa-calendar-day mr-1"></i> Hoy
        </a>
      </div>
      <div class="col-md-3 col-6 mb-2">
        <a href="#" id="btn-semana"
           class="btn btn-outline-primary btn-sm btn-block filtro-rapido"
           onclick="setFechas('<?php echo date('Y-m-d', strtotime('monday this week')); ?>', '<?php echo date('Y-m-d', strtotime('sunday this week')); ?>'); return false;">
          <i class="fas fa-calendar-week mr-1"></i> Esta Semana
        </a>
      </div>
      <div class="col-md-3 col-6 mb-2">
        <a href="#" id="btn-mes"
           class="btn btn-outline-primary btn-sm btn-block filtro-rapido"
           onclick="setFechas('<?php echo date('Y-m-01'); ?>', '<?php echo date('Y-m-t'); ?>'); return false;">
          <i class="fas fa-calendar-alt mr-1"></i> Este Mes
        </a>
      </div>
      <div class="col-md-3 col-6 mb-2">
        <a href="#" id="btn-mes-ant"
           class="btn btn-outline-primary btn-sm btn-block filtro-rapido"
           onclick="setFechas('<?php echo date('Y-m-01', strtotime('first day of last month')); ?>', '<?php echo date('Y-m-t', strtotime('last day of last month')); ?>'); return false;">
          <i class="fas fa-calendar-minus mr-1"></i> Mes Anterior
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Texto explicativo -->
<div class="alert alert-light border mt-3 text-muted text-center" style="font-size:0.9rem;">
  <i class="fas fa-search fa-2x d-block mb-2 text-secondary"></i>
  Selecciona un rango de fechas o usa los filtros rápidos y presiona
  <strong>"Generar Reporte"</strong> para ver los lotes del período.
</div>

<script>
function setFechas(inicio, fin) {
  document.getElementById('fecha_inicio').value = inicio;
  document.getElementById('fecha_fin').value    = fin;

  // Feedback visual: marcar botón activo
  document.querySelectorAll('.filtro-rapido').forEach(function(btn) {
    btn.classList.remove('active', 'btn-primary');
    btn.classList.add('btn-outline-primary');
  });
  event.currentTarget.classList.remove('btn-outline-primary');
  event.currentTarget.classList.add('btn-primary', 'active');
}
</script>