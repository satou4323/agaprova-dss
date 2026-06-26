<div class="mt-3"></div>

<div class="card card-outline card-primary">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title"><i class="fas fa-route text-primary mr-1"></i> Nueva Ruta</h3>
    <a href="<?php echo BASE_URL; ?>/bloqueo/index" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i> Volver
    </a>
  </div>
  <div class="card-body">
    <form method="POST" action="<?php echo BASE_URL; ?>/bloqueo/guardar">
      <input type="hidden" name="csrf_token" value="<?php echo $csrf; ?>">

      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="codigo">Código de Ruta <span class="text-danger">*</span></label>
            <input type="text" name="codigo" id="codigo" class="form-control"
                   placeholder="Ej: RT-01" maxlength="20" required>
            <small class="text-muted">Se guardará en mayúsculas</small>
          </div>
        </div>
        <div class="col-md-8">
          <div class="form-group">
            <label for="nombre">Nombre de la Ruta <span class="text-danger">*</span></label>
            <input type="text" name="nombre" id="nombre" class="form-control"
                   placeholder="Ej: Ruta Santa Cruz - Cochabamba" maxlength="150" required>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="origen">Origen <span class="text-danger">*</span></label>
            <input type="text" name="origen" id="origen" class="form-control"
                   placeholder="Ej: Santa Cruz" maxlength="100" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="destino">Destino <span class="text-danger">*</span></label>
            <input type="text" name="destino" id="destino" class="form-control"
                   placeholder="Ej: Cochabamba" maxlength="100" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="tiempo_horas">Tiempo a destino (horas) <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="number" name="tiempo_horas" id="tiempo_horas" class="form-control"
                     placeholder="Ej: 6.5" step="0.5" min="0.5" max="99" required>
              <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-clock text-info"></i></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="tipo_via">Tipo de Vía</label>
            <select name="tipo_via" id="tipo_via" class="form-control">
              <option value="Carretera asfaltada">Carretera asfaltada</option>
              <option value="Carretera ripiada">Carretera ripiada</option>
              <option value="Camino de tierra">Camino de tierra</option>
              <option value="Mixta">Mixta</option>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="mercado_id">Mercado de Destino</label>
            <select name="mercado_id" id="mercado_id" class="form-control">
              <option value="">-- Sin mercado asignado --</option>
              <option value="1">Mercado Mayorista Santa Cruz</option>
              <option value="2">Feria de Punata - Cochabamba</option>
            </select>
          </div>
        </div>
      </div>

      <hr>
      <div class="d-flex justify-content-end">
        <a href="<?php echo BASE_URL; ?>/bloqueo/index" class="btn btn-secondary mr-2">
          <i class="fas fa-times mr-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save mr-1"></i> Guardar Ruta
        </button>
      </div>
    </form>
  </div>
</div>
