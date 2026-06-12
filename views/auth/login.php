<?php
// Vista de Login - Sin layout
use App\Session;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - <?php echo APP_NAME; ?></title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/adminlte/plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/adminlte/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="<?php echo BASE_URL; ?>/auth/login"><b>AGAPROVA</b></a>
  </div>

  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Optimización Logística para Ganado Bovino</p>

      <?php if (Session::hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <i class="icon fas fa-exclamation-circle"></i> <?php echo Session::flash('error'); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?php echo BASE_URL; ?>/auth/login">
        <div class="input-group mb-3">
          <input type="text" id="username" name="username" class="form-control" required placeholder="Usuario" autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" id="password" name="password" class="form-control" required placeholder="Contraseña">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            &nbsp;
          </div>
          <div class="col-4">
            <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo $csrf; ?>">
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fas fa-sign-in-alt"></i> Ingresar
            </button>
          </div>
        </div>
      </form>

      <p class="mb-0 mt-3 text-center" style="font-size: 13px;">
        Usuario de prueba: <strong>operador</strong> | Contraseña: <strong>admin123</strong>
      </p>
    </div>
  </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/js/adminlte.min.js"></script>
</body>
</html>
