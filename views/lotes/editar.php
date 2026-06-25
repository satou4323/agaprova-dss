<div class="row mt-4">
  <div class="col-md-5">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-edit text-primary mr-1"></i> Editar Lote #<?php echo $lote->id; ?></h3>
        <a href="<?php echo BASE_URL; ?>/lote/index" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
      </div>
      <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/lote/actualizar/<?php echo $lote->id; ?>">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="text-muted" style="font-size: 0.85rem; font-weight: 500;">Número de Cabezas</label>
                <div class="input-group input-group-sm">
                  <input type="number" name="cabezas" class="form-control" required min="1"
                         value="<?php echo $lote->cabezas; ?>">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-cow"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="text-muted" style="font-size: 0.85rem; font-weight: 500;">Peso Promedio</label>
                <div class="input-group input-group-sm">
                  <input type="number" name="peso_promedio_kg" class="form-control" required min="100" step="0.01"
                         value="<?php echo $lote->peso_promedio_kg; ?>">
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
                <label class="text-muted" style="font-size: 0.85rem; font-weight: 500;">Condición del Ganado</label>
                <select name="condicion_id" class="form-control form-control-sm" required onchange="actualizarDescCondicion(this)">
                  <option value="">-- Seleccione --</option>
                  <?php foreach ($condiciones as $cond): ?>
                    <option value="<?php echo $cond->id; ?>"
                            data-desc="<?php echo htmlspecialchars($cond->descripcion ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                            <?php echo ($lote->condicion_id == $cond->id) ? 'selected' : ''; ?>>
                      <?php echo $cond->nombre; ?> (Factor: <?php echo $cond->factor; ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
                <small id="condicion_desc" class="text-muted" style="font-size: 0.75rem; font-style: italic; display: block; margin-top: 3px;"></small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="text-muted" style="font-size: 0.85rem; font-weight: 500;">Estación</label>
                <select name="estacion_id" class="form-control form-control-sm" required onchange="actualizarDescEstacion(this)">
                  <option value="">-- Seleccione --</option>
                  <?php foreach ($estaciones as $est): ?>
                    <option value="<?php echo $est->id; ?>"
                            data-desc="<?php echo htmlspecialchars($est->descripcion ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                            <?php echo ($lote->estacion_id == $est->id) ? 'selected' : ''; ?>>
                      <?php echo $est->nombre; ?> (Factor: <?php echo $est->factor; ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
                <small id="estacion_desc" class="text-muted" style="font-size: 0.75rem; font-style: italic; display: block; margin-top: 3px;"></small>
              </div>
            </div>
          </div>

          <?php $mes_actual = intval(date('n')); $es_invierno = ($mes_actual >= 5 && $mes_actual <= 8); ?>
          <?php if ($es_invierno): ?>
            <div class="callout callout-info py-2 px-3 mb-2" style="font-size: 0.85rem;">
              <i class="fas fa-snowflake text-info mr-1"></i>
              <strong>Nota estacional:</strong> Estamos en temporada de invierno (mayo-agosto).
              Se recomienda seleccionar condición <strong>"Invernal"</strong> si aplica.
            </div>
          <?php endif; ?>

          <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo $csrf; ?>">

          <hr class="mt-3 mb-3">
          <div class="d-flex justify-content-end">
            <a href="<?php echo BASE_URL; ?>/lote/index" class="btn btn-secondary mr-2">
              <i class="fas fa-times mr-1"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-primary" style="background-color: <?php echo COLOR_PRIMARY; ?>; border-color: <?php echo COLOR_PRIMARY; ?>; padding: 8px 28px; font-size: 0.9rem;">
              <i class="fas fa-save mr-1"></i> Guardar Cambios
            </button>
          </div>
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
            <h6 class="text-primary font-weight-bold mb-2" style="font-size: 0.9rem;">
              <i class="fas fa-cow mr-1"></i> Estimaciones Estándar
            </h6>
            <ul class="list-group list-group-unbordered mb-3" style="font-size: 0.85rem;">
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center">
                <b>Cabezas:</b>
                <span class="text-muted">Cantidad típica 80-150</span>
              </li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center">
                <b>Peso:</b>
                <span class="text-muted">380-450 kg promedio</span>
              </li>
            </ul>

            <h6 class="text-primary font-weight-bold mb-2" style="font-size: 0.9rem;">
              <i class="fas fa-leaf mr-1"></i> Factores de Eficiencia
            </h6>
            <ul class="list-group list-group-unbordered mb-0" style="font-size: 0.85rem;">
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center">
                <b>Seca (1.00):</b>
                <span class="text-muted">Mayor rendimiento</span>
              </li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center">
                <b>Transición (0.85):</b>
                <span class="text-muted">Rendimiento moderado</span>
              </li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center">
                <b>Lluviosa (0.70):</b>
                <span class="text-muted">Menor rendimiento</span>
              </li>
            </ul>
          </div>
          <div class="col-md-6">
            <h6 class="text-primary font-weight-bold mb-2" style="font-size: 0.9rem;">
              <i class="fas fa-users mr-1"></i> Condición del Ganado
            </h6>
            <ul class="list-group list-group-unbordered mb-3" style="font-size: 0.85rem;">
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center">
                <b>Buena (1.00):</b>
                <span class="text-muted">Óptima</span>
              </li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center">
                <b>Regular (0.90):</b>
                <span class="text-muted">Media</span>
              </li>
              <li class="list-group-item py-1 d-flex justify-content-between align-items-center">
                <b>Invernal (0.75):</b>
                <span class="text-muted">Vaca flaca</span>
              </li>
            </ul>

            <div class="callout callout-warning mb-0" style="padding: 0.7rem 0.9rem; font-size: 0.85rem;">
              <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
              <strong>Nota:</strong> Al guardar cambios el sistema <strong>no recalcula</strong> la optimización Simplex automáticamente. Si necesitas recalcular, registra un nuevo lote.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
