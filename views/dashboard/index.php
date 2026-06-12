<?php
use App\Services\ClimaService;
?>
<div class="mt-3"></div>
<div class="row mt-4">
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%); box-shadow: 0 2px 6px rgba(46,125,50,0.25);">
      <div class="inner">
        <h3 style="color: #fff; font-size: 1.6rem; font-weight: 700;"><?php echo $estadisticas['total_lotes'] ?? 0; ?></h3>
        <p style="color: rgba(255,255,255,0.85); font-size: 0.85rem; margin-bottom: 0;">Total Lotes</p>
      </div>
      <div class="icon">
        <i class="fas fa-boxes" style="color: rgba(255,255,255,0.2); font-size: 70px;"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background: linear-gradient(135deg, #388E3C 0%, #2E7D32 100%); box-shadow: 0 2px 6px rgba(56,142,60,0.25);">
      <div class="inner">
        <h3 style="color: #fff; font-size: 1.6rem; font-weight: 700;"><?php echo $estadisticas['total_cabezas'] ?? 0; ?></h3>
        <p style="color: rgba(255,255,255,0.85); font-size: 0.85rem; margin-bottom: 0;">Total Cabezas</p>
      </div>
      <div class="icon">
        <i class="fas fa-cow" style="color: rgba(255,255,255,0.2); font-size: 70px;"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background: linear-gradient(135deg, #F9A825 0%, #F57F17 100%); box-shadow: 0 2px 6px rgba(249,168,37,0.3);">
      <div class="inner">
        <h3 style="color: #fff; font-size: clamp(1.1rem, 2.5vw, 1.4rem); font-weight: 700;">Bs/kg <?php echo number_format($estadisticas['precio_1'] ?? 32, 2); ?></h3>
        <p style="color: rgba(255,255,255,0.85); font-size: 0.85rem; margin-bottom: 0;">Precio Santa Cruz</p>
      </div>
      <div class="icon">
        <i class="fas fa-tag" style="color: rgba(255,255,255,0.2); font-size: 70px;"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background: linear-gradient(135deg, #F9A825 0%, #F57F17 100%); box-shadow: 0 2px 6px rgba(249,168,37,0.3);">
      <div class="inner">
        <h3 style="color: #fff; font-size: clamp(1.1rem, 2.5vw, 1.4rem); font-weight: 700;">Bs/kg <?php echo number_format($estadisticas['precio_2'] ?? 34, 2); ?></h3>
        <p style="color: rgba(255,255,255,0.85); font-size: 0.85rem; margin-bottom: 0;">Precio Cochabamba</p>
      </div>
      <div class="icon">
        <i class="fas fa-tag" style="color: rgba(255,255,255,0.2); font-size: 70px;"></i>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-8 col-md-7">

    <div class="card card-outline card-success">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-truck"></i> Precios de Mercado Vigentes</h3>
      </div>
      <div class="card-body p-0">
        <?php if (!empty($precios)): ?>
          <table class="table table-striped table-valign-middle mb-0">
            <thead>
              <tr>
                <th>Mercado</th>
                <th>Precio (Bs/kg)</th>
                <th>Fecha Registro</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($precios as $precio): ?>
                <tr>
                  <td><?php echo $precio['mercado_nombre']; ?></td>
                  <td><strong><?php echo number_format($precio['precio_kg'], 2); ?></strong> <i class="fas fa-arrow-up text-success"></i></td>
                  <td><?php echo date('d/m/Y', strtotime($precio['fecha_registro'])); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="p-3">
            <p style="color: #999;">Sin precios registrados</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-boxes"></i> Último Lote Registrado</h3>
      </div>
      <div class="card-body p-0">
        <?php if ($ultimo_lote): ?>
          <table class="table table-borderless table-sm mb-0">
            <tbody>
              <tr>
                <td style="width: 50%; padding-left: 1.15rem;"><strong>Cabezas:</strong></td>
                <td><?php echo $ultimo_lote->cabezas; ?></td>
              </tr>
              <tr>
                <td style="padding-left: 1.15rem;"><strong>Peso Promedio:</strong></td>
                <td><?php echo number_format($ultimo_lote->peso_promedio_kg, 2); ?> kg</td>
              </tr>
              <tr>
                <td style="padding-left: 1.15rem;"><strong>Hora de Salida:</strong></td>
                <td><?php echo $ultimo_lote->hora_salida; ?></td>
              </tr>
              <tr>
                <td style="padding-left: 1.15rem;"><strong>Fecha Registro:</strong></td>
                <td><?php echo date('d/m/Y', strtotime($ultimo_lote->fecha_registro)); ?></td>
              </tr>
            </tbody>
          </table>
        <?php else: ?>
          <div class="p-3">
            <p style="color: #999;">Sin lotes registrados</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>

  <div class="col-lg-4 col-md-5">

    <?php if ($clima):
      $rainClass = $clima->probabilidad_lluvia >= 0.40 ? 'bg-gradient-warning' : 'bg-gradient-info';
      $rainIcon = $clima->probabilidad_lluvia >= 0.40 ? 'fa-exclamation-triangle' : 'fa-cloud-rain';
      $probPct = number_format($clima->probabilidad_lluvia * 100, 1);
      $probWidth = min(100, $clima->probabilidad_lluvia * 100);
    ?>
      <div class="info-box mb-3 <?php echo $rainClass; ?>">
        <span class="info-box-icon"><i class="fas <?php echo $rainIcon; ?>"></i></span>
        <div class="info-box-content">
          <span class="info-box-text" style="color: rgba(255,255,255,0.9);">Probabilidad de lluvia en Abapó</span>
          <span class="info-box-number" style="color: #fff; font-size: 1.8rem; font-weight: 700;"><?php echo $probPct; ?>%</span>
          <div class="progress" style="background: rgba(255,255,255,0.3);">
            <div class="progress-bar" style="width: <?php echo $probWidth; ?>%; background: rgba(255,255,255,0.85);"></div>
          </div>
          <span class="progress-description" style="color: rgba(255,255,255,0.8); font-size: 0.75rem;">
            Registrado: <?php echo date('d/m/Y H:i', strtotime($clima->created_at)); ?>
            <?php if ($clima->probabilidad_lluvia >= 0.40): ?>
              <br><i class="fas fa-exclamation-triangle"></i> Ruta 3 (Ipati-Abapó) bloqueada por restricción climática
            <?php endif; ?>
          </span>
        </div>
      </div>
    <?php else: ?>
      <div class="info-box mb-3 bg-secondary">
        <span class="info-box-icon"><i class="fas fa-cloud-rain"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Condición Climática</span>
          <span class="info-box-number" style="font-size: 1rem;">Sin datos de clima registrados</span>
        </div>
      </div>
    <?php endif; ?>

    <div class="card card-outline card-danger">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-ban"></i> Rutas Bloqueadas</h3>
      </div>
      <div class="card-body p-0">
        <?php
          $rutasBloqueadas = array_filter($rutas, fn($r) => $r['bloqueado'] == 1);
        ?>
        <?php if (!empty($rutasBloqueadas)): ?>
          <ul class="list-group list-group-flush">
            <?php foreach ($rutasBloqueadas as $ruta): ?>
              <li class="list-group-item">
                <span class="badge bg-danger"><?php echo $ruta['codigo']; ?></span>
                <?php echo $ruta['nombre']; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <div class="p-3">
            <p style="color: #999; margin: 0;"><i class="fas fa-check-circle" style="color: #28a745;"></i> Todas las rutas activas</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

<div style="text-align: center; margin-top: 30px; margin-bottom: 20px;">
  <a href="<?php echo BASE_URL; ?>/lote/crear" class="btn btn-primary" style="padding: 7px 22px; font-size: 13px; background-color: <?php echo COLOR_PRIMARY; ?>; border-color: <?php echo COLOR_PRIMARY; ?>;">
    <i class="fas fa-plus"></i> Registrar Nuevo Lote
  </a>
</div>
