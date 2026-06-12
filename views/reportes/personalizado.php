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
            <label class="text-muted" style="font-size: 0.85rem; font-weight: 500;">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control form-control-sm" required value="<?php echo $fecha_inicio_pred; ?>">
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="form-group">
            <label class="text-muted" style="font-size: 0.85rem; font-weight: 500;">Fecha Fin</label>
            <input type="date" name="fecha_fin" class="form-control form-control-sm" required value="<?php echo $fecha_fin_pred; ?>">
          </div>
        </div>
      </div>
      
      <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo $csrf; ?>">
      
      <button type="submit" class="btn btn-primary btn-sm" style="background-color: <?php echo COLOR_PRIMARY; ?>; border-color: <?php echo COLOR_PRIMARY; ?>; padding: 7px 22px;">
        <i class="fas fa-search"></i> Generar Reporte
      </button>
    </form>
  </div>
</div>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-filter text-primary mr-1"></i> Filtros Rápidos</h3>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-3 col-6 mb-2">
        <a href="<?php echo BASE_URL; ?>/reporte/personalizado?preset=hoy" class="btn btn-secondary btn-sm" style="width: 100%;">
          <i class="fas fa-calendar-day"></i> Hoy
        </a>
      </div>
      <div class="col-md-3 col-6 mb-2">
        <a href="<?php echo BASE_URL; ?>/reporte/personalizado?preset=esta_semana" class="btn btn-secondary btn-sm" style="width: 100%;">
          <i class="fas fa-calendar-week"></i> Esta Semana
        </a>
      </div>
      <div class="col-md-3 col-6 mb-2">
        <a href="<?php echo BASE_URL; ?>/reporte/personalizado?preset=este_mes" class="btn btn-secondary btn-sm" style="width: 100%;">
          <i class="fas fa-calendar-alt"></i> Este Mes
        </a>
      </div>
      <div class="col-md-3 col-6 mb-2">
        <a href="<?php echo BASE_URL; ?>/reporte/personalizado?preset=ultimo_mes" class="btn btn-secondary btn-sm" style="width: 100%;">
          <i class="fas fa-calendar-minus"></i> Mes Anterior
        </a>
      </div>
    </div>
  </div>
</div>
