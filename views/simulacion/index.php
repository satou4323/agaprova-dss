<div class="row mb-3">
  <div class="col-sm-6">
    <h3 class="m-0"><i class="fas fa-flask text-primary mr-1"></i> Simulaciones de Escenarios</h3>
  </div>
</div>

<p class="text-muted mb-3">
  Ejecute simulaciones con los escenarios predefinidos (A-F) para evaluar el comportamiento del sistema bajo diferentes condiciones.
</p>

<div class="row">
  <?php foreach ($escenarios as $esc): 
      $datos = $esc->getDatos();
  ?>
    <div class="col-lg-4 col-md-6 col-12 mb-4">
      <div class="card card-outline card-primary h-100">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-code text-primary mr-1"></i> Escenario <?php echo $esc->codigo; ?></h3>
        </div>
        <div class="card-body d-flex flex-column">
          <h5><?php echo $esc->nombre; ?></h5>
          <p class="text-muted flex-grow-1"><?php echo $esc->descripcion; ?></p>
          <div class="bg-light p-3 rounded mb-3 small">
            <div><strong>Estación:</strong> <?php echo $datos['estacion_id'] == 1 ? 'Seca' : ($datos['estacion_id'] == 2 ? 'Lluviosa' : 'Transición'); ?></div>
            <div><strong>Condición:</strong> <?php echo $datos['condicion_id'] == 1 ? 'Buena' : ($datos['condicion_id'] == 2 ? 'Regular' : 'Invernal'); ?></div>
            <div><strong>Lluvia:</strong> <?php echo number_format($datos['prob_lluvia'] * 100, 0); ?>%</div>
          </div>
          <div class="row">
            <div class="col-6 pr-1">
              <a href="<?php echo BASE_URL; ?>/simulacion/ejecutar?codigo=<?php echo $esc->codigo; ?>" class="btn btn-primary btn-sm" style="width: 100%; background-color: <?php echo COLOR_PRIMARY; ?>; border-color: <?php echo COLOR_PRIMARY; ?>;">
                <i class="fas fa-play"></i> Ejecutar
              </a>
            </div>
            <div class="col-6 pl-1">
              <a href="<?php echo BASE_URL; ?>/simulacion/detalle?codigo=<?php echo $esc->codigo; ?>" class="btn btn-secondary btn-sm" style="width: 100%;">
                <i class="fas fa-info-circle"></i> Detalles
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
