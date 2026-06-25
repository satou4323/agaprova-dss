<div class="row mb-2 mt-3">
  <div class="col-sm-6">
    <h3 class="m-0"><i class="fas fa-flask text-primary mr-1"></i> Simulación de Rutas</h3>
    <small class="text-muted d-block" style="font-size:0.85rem;">
      <i class="fas fa-info-circle mr-1"></i>
      Los datos del sistema se cargan automáticamente. Para modificarlos, ve a su sección.
    </small>
  </div>
</div>

<style>
input[readonly].form-control {
  background-color: #f8f9fa !important;
  border-color: #e9ecef !important;
  color: #495057 !important;
  font-weight: 600;
}
.card.card-outline.card-info,
.card.card-outline.card-warning {
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.table-sm tbody tr:hover {
  background-color: rgba(0,0,0,0.025);
}
</style>

<!-- DATOS DEL SISTEMA (solo lectura) -->
<div class="row mb-3 mt-3">

  <!-- Costos de Flete -->
  <div class="col-md-4">
    <div class="card card-outline card-info h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-truck mr-1"></i> Costos de Flete Vigentes</h3>
        <a href="<?php echo BASE_URL; ?>/costoflete/index" class="btn btn-xs btn-outline-secondary" style="font-size:0.75rem;">
          <i class="fas fa-external-link-alt mr-1"></i> Editar
        </a>
      </div>
      <div class="card-body p-0">
        <?php if (!empty($costos_vigentes)): ?>
          <table class="table table-sm mb-0">
            <thead><tr><th>Ruta</th><th>Costo/Cabeza</th></tr></thead>
            <tbody>
              <?php foreach ($costos_vigentes as $costo): ?>
                <tr>
                  <td><?php echo $costo->codigo; ?> <small class="text-muted">— <?php echo $costo->nombre_ruta; ?></small></td>
                  <td class="text-success font-weight-bold">Bs <?php echo number_format($costo->costo_cabeza, 2); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="p-3"><p class="text-muted mb-0">Sin costos registrados</p></div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Estado de Rutas -->
  <div class="col-md-4">
    <div class="card card-outline card-warning h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-route mr-1"></i> Estado de Rutas</h3>
        <a href="<?php echo BASE_URL; ?>/bloqueo/index" class="btn btn-xs btn-outline-secondary" style="font-size:0.75rem;">
          <i class="fas fa-external-link-alt mr-1"></i> Editar
        </a>
      </div>
      <div class="card-body p-0">
        <?php if (!empty($rutas)): ?>
          <table class="table table-sm mb-0">
            <thead><tr><th>Ruta</th><th>Tiempo</th><th>Estado</th></tr></thead>
            <tbody>
              <?php foreach ($rutas as $ruta): ?>
                <tr>
                  <td><?php echo $ruta->codigo; ?> <small class="text-muted">— <?php echo $ruta->nombre; ?></small></td>
                  <td><?php echo $ruta->tiempo_horas; ?>h</td>
                  <td>
                    <?php if ($ruta->bloqueado == 1): ?>
                      <span class="badge badge-danger">BLOQUEADA</span>
                    <?php else: ?>
                      <span class="badge badge-success">ACTIVA</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="p-3"><p class="text-muted mb-0">Sin rutas registradas</p></div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Condiciones Actuales -->
  <div class="col-md-4">
    <div class="card card-outline card-secondary h-100">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-cloud-sun mr-1"></i> Condiciones Actuales</h3>
      </div>
      <div class="card-body">
        <?php if ($clima): ?>
          <?php $pct = $clima->probabilidad_lluvia * 100; ?>
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span style="font-size:0.85rem;"><b><i class="fas fa-cloud-rain text-info mr-1"></i> Lluvia en Abapó:</b></span>
            <span class="badge <?php echo $pct >= 40 ? 'badge-danger' : 'badge-info'; ?>">
              <?php echo number_format($pct, 1); ?>%
            </span>
          </div>
          <?php if ($pct >= 40): ?>
            <small class="text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> R03 bloqueada por clima</small>
          <?php endif; ?>
          <div class="text-right mt-1">
            <a href="<?php echo BASE_URL; ?>/clima/index" class="text-primary" style="font-size:0.75rem; text-decoration:none;">
              <i class="fas fa-pencil-alt mr-1"></i>Editar clima
            </a>
          </div>
        <?php else: ?>
          <p class="text-muted mb-1" style="font-size:0.85rem;">Sin datos de clima</p>
          <a href="<?php echo BASE_URL; ?>/clima/index" class="text-primary" style="font-size:0.75rem; text-decoration:none;">
            <i class="fas fa-pencil-alt mr-1"></i>Registrar clima
          </a>
        <?php endif; ?>

        <hr class="my-2">

        <?php if ($ultimo_lote): ?>
          <p class="mb-1" style="font-size:0.85rem;"><strong>Último lote registrado:</strong></p>
          <ul class="list-group list-group-unbordered mb-1" style="font-size:0.85rem;">
            <li class="list-group-item px-0 py-1">
              <b>Cabezas:</b> <span class="float-right"><?php echo $ultimo_lote->cabezas; ?></span>
            </li>
            <li class="list-group-item px-0 py-1">
              <b>Peso promedio:</b> <span class="float-right"><?php echo number_format($ultimo_lote->peso_promedio_kg, 2); ?> kg</span>
            </li>
          </ul>
          <div class="mt-1">
            <a href="<?php echo BASE_URL; ?>/lote/index" class="text-primary" style="font-size:0.75rem; text-decoration:none;">
              <i class="fas fa-pencil-alt mr-1"></i>Editar lotes
            </a>
          </div>
        <?php else: ?>
          <p class="text-muted mb-1" style="font-size:0.85rem;">Sin lotes registrados</p>
          <a href="<?php echo BASE_URL; ?>/lote/crear" class="text-primary" style="font-size:0.75rem; text-decoration:none;">
            <i class="fas fa-pencil-alt mr-1"></i>Registrar lote
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>

<!-- FORMULARIO DE SIMULACIÓN -->
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-play-circle mr-1"></i> Parámetros de Simulación</h3>
  </div>
  <div class="card-body">
    <form method="POST" action="<?php echo BASE_URL; ?>/simulacion/ejecutar">
      <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo $csrf; ?>">

      <div class="row">

        <!-- Cabezas: readonly con candado al exterior -->
        <div class="col-md-3">
          <div class="form-group">
            <label class="text-muted" style="font-size:0.85rem; font-weight:500;">
              <i class="fas fa-cow mr-1"></i> Número de Cabezas
            </label>
            <div class="input-group input-group-sm">
              <div class="input-group-prepend">
                <span class="input-group-text bg-light border-right-0">
                  <i class="fas fa-lock text-muted" style="font-size:0.7rem;"></i>
                </span>
              </div>
              <input type="number" name="cabezas" class="form-control bg-light border-left-0 text-muted font-weight-bold"
                     value="<?php echo $ultimo_lote->cabezas ?? CABEZAS_DEFAULT; ?>"
                     readonly style="cursor:not-allowed;">
            </div>
            <div class="mt-1">
              <a href="<?php echo BASE_URL; ?>/lote/index" class="text-primary" style="font-size:0.75rem; text-decoration:none;">
                <i class="fas fa-pencil-alt mr-1"></i>Editar en Lotes de Ganado
              </a>
            </div>
          </div>
        </div>

        <!-- Peso: readonly con candado al exterior -->
        <div class="col-md-3">
          <div class="form-group">
            <label class="text-muted" style="font-size:0.85rem; font-weight:500;">
              <i class="fas fa-weight mr-1"></i> Peso Promedio (kg)
            </label>
            <div class="input-group input-group-sm">
              <div class="input-group-prepend">
                <span class="input-group-text bg-light border-right-0">
                  <i class="fas fa-lock text-muted" style="font-size:0.7rem;"></i>
                </span>
              </div>
              <input type="number" name="peso_promedio_kg" class="form-control bg-light border-left-0 text-muted font-weight-bold"
                     value="<?php echo $ultimo_lote->peso_promedio_kg ?? PESO_DEFAULT; ?>"
                     readonly style="cursor:not-allowed;">
              <div class="input-group-append">
                <span class="input-group-text bg-light">kg</span>
              </div>
            </div>
            <div class="mt-1">
              <a href="<?php echo BASE_URL; ?>/lote/index" class="text-primary" style="font-size:0.75rem; text-decoration:none;">
                <i class="fas fa-pencil-alt mr-1"></i>Editar en Lotes de Ganado
              </a>
            </div>
          </div>
        </div>

        <!-- Condición: pill limpio -->
        <div class="col-md-3">
          <div class="form-group">
            <label class="text-muted" style="font-size:0.85rem; font-weight:500;">
              <i class="fas fa-heart mr-1"></i> Condición del Ganado
            </label>
            <?php
              $cond_actual = null;
              foreach ($condiciones as $c) {
                  if ($ultimo_lote && $c->id == $ultimo_lote->condicion_id) {
                      $cond_actual = $c;
                      break;
                  }
              }
            ?>
            <input type="hidden" name="condicion_id" value="<?php echo $ultimo_lote->condicion_id ?? 1; ?>">
            <div class="d-flex align-items-center p-2 rounded"
                 style="background:#f8f9fa; border:1px solid #dee2e6; min-height:31px;">
              <i class="fas fa-lock text-muted mr-2" style="font-size:0.65rem;"></i>
              <span class="font-weight-bold text-secondary" style="font-size:0.85rem;">
                <?php echo $cond_actual ? $cond_actual->nombre . ' (Factor: ' . $cond_actual->factor . ')' : 'N/A'; ?>
              </span>
            </div>
            <div class="mt-1">
              <a href="<?php echo BASE_URL; ?>/lote/index" class="text-primary" style="font-size:0.75rem; text-decoration:none;">
                <i class="fas fa-pencil-alt mr-1"></i>Editar en Lotes de Ganado
              </a>
            </div>
          </div>
        </div>

        <!-- Estación: pill limpio -->
        <div class="col-md-3">
          <div class="form-group">
            <label class="text-muted" style="font-size:0.85rem; font-weight:500;">
              <i class="fas fa-leaf mr-1"></i> Estación
            </label>
            <?php
              $est_actual = null;
              foreach ($estaciones as $e) {
                  if ($ultimo_lote && $e->id == $ultimo_lote->estacion_id) {
                      $est_actual = $e;
                      break;
                  }
              }
            ?>
            <input type="hidden" name="estacion_id" value="<?php echo $ultimo_lote->estacion_id ?? 1; ?>">
            <div class="d-flex align-items-center p-2 rounded"
                 style="background:#f8f9fa; border:1px solid #dee2e6; min-height:31px;">
              <i class="fas fa-lock text-muted mr-2" style="font-size:0.65rem;"></i>
              <span class="font-weight-bold text-secondary" style="font-size:0.85rem;">
                <?php echo $est_actual ? $est_actual->nombre . ' (Factor: ' . $est_actual->factor . ')' : 'N/A'; ?>
              </span>
            </div>
            <div class="mt-1">
              <a href="<?php echo BASE_URL; ?>/lote/index" class="text-primary" style="font-size:0.75rem; text-decoration:none;">
                <i class="fas fa-pencil-alt mr-1"></i>Editar en Lotes de Ganado
              </a>
            </div>
          </div>
        </div>

      </div>

      <!-- Horas sugeridas compactas -->
      <?php if (!empty($rutas)): ?>
      <div class="callout callout-info py-2 px-3 mb-0"
           style="font-size:0.82rem; border-left-color:#17a2b8; background:#f0f8ff;">
        <p class="mb-1 font-weight-bold">
          <i class="fas fa-clock mr-1 text-info"></i>
          Horas de salida sugeridas para llegar antes de las 08:00:
        </p>
        <ul class="mb-0 pl-3">
          <?php foreach ($rutas as $ruta): ?>
            <?php
              $salida = strtotime('08:00:00') - ($ruta->tiempo_horas * 3600);
              $hora_sugerida = date('H:i', $salida);
              $disponible = ($ruta->bloqueado == 0);
            ?>
            <li style="<?php echo !$disponible ? 'color:#dc3545;' : ''; ?>">
              <?php echo $ruta->codigo; ?> (<?php echo $ruta->nombre; ?>):
              salir a las <strong><?php echo $hora_sugerida; ?></strong>
              <?php if (!$disponible): ?>
                <span class="badge badge-danger ml-1">BLOQUEADA</span>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <hr class="mt-3 mb-3">
      <?php else: ?>
      <hr class="mt-2 mb-3">
      <?php endif; ?>

      <!-- Botón con descripción -->
      <div class="d-flex align-items-center">
        <button type="submit" class="btn btn-lg"
                style="background-color:#2E7D32; border-color:#2E7D32; color:white; padding:10px 35px; border-radius:6px;">
          <i class="fas fa-play mr-2"></i> Ejecutar Simulación
        </button>
        <small class="text-muted ml-3">
          <i class="fas fa-info-circle mr-1"></i>
          Analiza todas las rutas y muestra la más rentable
        </small>
      </div>

    </form>
  </div>
</div>