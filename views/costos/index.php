<div class="row">
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-truck text-primary mr-1"></i> Actualizar Costo de Flete</h3>
      </div>
      <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/costoflete/actualizar">
          <div class="form-group" style="max-width: 300px;">
            <label class="text-muted" style="font-size: 0.85rem; font-weight: 500;">Ruta</label>
            <select name="ruta_id" class="form-control form-control-sm" required>
              <option value="">-- Seleccione --</option>
              <?php foreach ($rutas as $r): ?>
                <option value="<?php echo $r->id; ?>">
                  <?php echo $r->codigo; ?> - <?php echo $r->nombre; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group" style="max-width: 250px;">
            <label class="text-muted" style="font-size: 0.85rem; font-weight: 500;">Costo por Cabeza (Bs)</label>
            <div class="input-group input-group-sm">
              <input type="number" name="costo_cabeza" class="form-control" required min="0.01" step="0.01" placeholder="Ej: 420.50">
              <div class="input-group-append">
                <span class="input-group-text">Bs</span>
              </div>
            </div>
          </div>

          <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo $csrf; ?>">

          <button type="submit" class="btn btn-primary btn-sm" style="background-color: <?php echo COLOR_PRIMARY; ?>; border-color: <?php echo COLOR_PRIMARY; ?>; padding: 7px 20px;">
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
        <?php if (!empty($costos)): ?>
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th>Ruta</th>
                <th>Costo/Cabeza</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($costos as $costo): ?>
                <tr>
                  <td>
                    <strong><?php echo $costo['codigo']; ?></strong><br>
                    <small><?php echo $costo['nombre']; ?></small>
                  </td>
                  <td style="text-align: right; font-weight: bold; color: <?php echo COLOR_PRIMARY; ?>; font-size: 1rem;">
                    Bs <?php echo number_format($costo['costo_cabeza'], 2); ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="p-4 text-center">
            <i class="fas fa-inbox fa-2x mb-2" style="color: #dee2e6;"></i>
            <p style="color: #999; margin: 0;">Sin costos registrados</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
