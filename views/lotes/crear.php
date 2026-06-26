<div class="row mt-4">
  <div class="col-md-5">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-info-circle text-primary mr-1"></i> Información del Lote</h3>
      </div>
      <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/lote/guardar">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="text-muted" style="font-size:0.85rem;font-weight:500;">Número de Cabezas</label>
                <div class="input-group input-group-sm">
                  <!-- CAMBIO 3: value vacío con placeholder -->
                  <input type="number" name="cabezas" class="form-control" required min="1"
                         value="" placeholder="Ej: 80">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-cow"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="text-muted" style="font-size:0.85rem;font-weight:500;">Peso Promedio</label>
                <div class="input-group input-group-sm">
                  <!-- CAMBIO 3: value vacío con placeholder -->
                  <input type="number" name="peso_promedio_kg" class="form-control" required min="100" step="0.01"
                         value="" placeholder="Ej: 400">
                  <div class="input-group-append">
                    <span class="input-group-text">kg</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="text-muted" style="font-size:0.85rem;font-weight:500;">Condición del Ganado</label>
                <select name="condicion_id" class="form-control form-control-sm" required onchange="actualizarDescCondicion(this)">
                  <option value="">-- Seleccione --</option>
                  <?php foreach ($condiciones as $cond): ?>
                    <option value="<?php echo $cond->id; ?>"
                            data-desc="<?php echo htmlspecialchars($cond->descripcion ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                      <?php echo $cond->nombre; ?> (Factor: <?php echo $cond->factor; ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
                <small id="condicion_desc" class="text-muted" style="font-size:0.75rem;font-style:italic;display:block;margin-top:3px;"></small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="text-muted" style="font-size:0.85rem;font-weight:500;">Estación</label>
                <select name="estacion_id" class="form-control form-control-sm" required onchange="actualizarDescEstacion(this)">
                  <option value="">-- Seleccione --</option>
                  <?php foreach ($estaciones as $est): ?>
                    <option value="<?php echo $est->id; ?>"
                            data-desc="<?php echo htmlspecialchars($est->descripcion ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                      <?php echo $est->nombre; ?> (Factor: <?php echo $est->factor; ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
                <small id="estacion_desc" class="text-muted" style="font-size:0.75rem;font-style:italic;display:block;margin-top:3px;"></small>
              </div>
            </div>
          </div>

          <?php $mes_actual = intval(date('n')); $es_invierno = ($mes_actual >= 5 && $mes_actual <= 8); ?>
          <?php if ($es_invierno): ?>
            <div class="callout callout-info py-2 px-3 mb-2" style="font-size:0.85rem;">
              <i class="fas fa-snowflake text-info mr-1"></i>
              <strong>Nota estacional:</strong> Estamos en temporada seca (mayo-agosto).
              Se recomienda seleccionar <strong>Estación: "Seca"</strong> para el rendimiento climático,
              y <strong>Condición del Ganado: "Invernal"</strong> si el ganado está flaco por el invierno.
            </div>
          <?php endif; ?>

          <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo $csrf; ?>">

          <hr class="mt-3 mb-3">
          <button type="submit" class="btn btn-primary"
                  style="background-color:<?php echo COLOR_PRIMARY; ?>;border-color:<?php echo COLOR_PRIMARY; ?>;padding:8px 28px;font-size:0.9rem;">
            <i class="fas fa-save"></i> Registrar y Optimizar
          </button>
        </form>
      </div>
    </div>

    <script>
    function actualizarDescCondicion(sel) {
      var desc = sel.options[sel.selectedIndex]?.getAttribute('data-desc') || '';
      document.getElementById('condicion_desc').textContent = desc;
    }
    function actualizarDescEstacion(sel) {
      var desc = sel.options[sel.selectedIndex]?.getAttribute('data-desc') || '';
      document.getElementById('estacion_desc').textContent = desc;
    }
    document.addEventListener('DOMContentLoaded', function() {
      var mes = new Date().getMonth() + 1;
      if (mes >= 5 && mes <= 8) {
        var selectCond = document.querySelector('select[name="condicion_id"]');
        if (selectCond) {
          for (var i = 0; i < selectCond.options.length; i++) {
            if (selectCond.options[i].text.indexOf('Invernal') !== -1) {
              selectCond.value = selectCond.options[i].value;
              break;
            }
          }
        }
      }
      var selCond = document.querySelector('select[name="condicion_id"]');
      if (selCond) actualizarDescCondicion(selCond);
      var selEst = document.querySelector('select[name="estacion_id"]');
      if (selEst) actualizarDescEstacion(selEst);
    });
    </script>
  </div>

  <div class="col-md-7">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-question-circle text-primary mr-1"></i> Guía de Parámetros</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <h6 class="text-primary font-weight-bold mb-2" style="font-size:0.9rem;">
              <i class="fas fa-cow mr-1"></i> Estimaciones Estándar
            </h6>
            <ul class="list-group list-group-unbordered mb-3" style="font-size:0.85rem;">
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center">
                <b>Cabezas:</b><span class="text-muted">Cantidad típica 80-150</span>
              </li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center">
                <b>Peso:</b><span class="text-muted">380-450 kg promedio</span>
              </li>
            </ul>
            <h6 class="text-primary font-weight-bold mb-2" style="font-size:0.9rem;">
              <i class="fas fa-clock mr-1"></i> Restricción Horaria
            </h6>
            <ul class="list-group list-group-unbordered mb-0" style="font-size:0.85rem;">
              <li class="list-group-item py-1"><span class="text-muted">Los camiones deben llegar <b>antes de las 08:00</b></span></li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center"><b>R1 (Samaipata→SC):</b><span class="text-muted">6.5h</span></li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center"><b>R2 (Comarapa→CB):</b><span class="text-muted">9.0h</span></li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center"><b>R3 (Ipati-Abapó→SC):</b><span class="text-muted">11.0h</span></li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center"><b>R4 (Aiquile→CB):</b><span class="text-muted">10.0h</span></li>
            </ul>
          </div>
          <div class="col-md-6">
            <h6 class="text-primary font-weight-bold mb-2" style="font-size:0.9rem;">
              <i class="fas fa-leaf mr-1"></i> Factores de Eficiencia
            </h6>
            <ul class="list-group list-group-unbordered mb-3" style="font-size:0.85rem;">
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center"><b>Seca (1.00):</b><span class="text-muted">Mayor rendimiento</span></li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center"><b>Transición (0.85):</b><span class="text-muted">Rendimiento moderado</span></li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center"><b>Lluviosa (0.70):</b><span class="text-muted">Menor rendimiento</span></li>
            </ul>
            <h6 class="text-primary font-weight-bold mb-2" style="font-size:0.9rem;">
              <i class="fas fa-users mr-1"></i> Condición del Ganado
            </h6>
            <ul class="list-group list-group-unbordered mb-3" style="font-size:0.85rem;">
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center"><b>Buena (1.00):</b><span class="text-muted">Óptima</span></li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center"><b>Regular (0.90):</b><span class="text-muted">Media</span></li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center"><b>Invernal (0.75):</b><span class="text-muted">Vaca flaca</span></li>
            </ul>
            <div class="callout callout-warning mb-0" style="padding:0.7rem 0.9rem;font-size:0.85rem;">
              <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
              <strong>Nota:</strong> El sistema calculará la ruta óptima basado en:
              <ul style="margin-top:6px;margin-bottom:0;padding-left:18px;">
                <li>Restricción climática (Ruta 3 si lluvia &gt; 40%)</li>
                <li>Restricción horaria (llegada antes de 08:00)</li>
                <li>Máximo margen de ganancia</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>