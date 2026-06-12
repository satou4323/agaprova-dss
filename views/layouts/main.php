<?php use App\Session; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo APP_NAME; ?> v<?php echo APP_VERSION; ?></title>

  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/adminlte/plugins/webfonts/source-sans-pro.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/adminlte/plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/adminlte/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <style>
    :root {
      --color-primary: <?php echo COLOR_PRIMARY; ?>;
      --color-secondary: <?php echo COLOR_SECONDARY; ?>;
      --color-accent: <?php echo COLOR_ACCENT; ?>;
    }
    .content-wrapper > .content {
      max-width: 1400px;
      margin: 0 auto;
    }
    .content-wrapper > .content > .container-fluid {
      padding-left: 30px;
      padding-right: 30px;
    }
    @media (max-width: 768px) {
      .content-wrapper > .content > .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
      }
    }
    .table-responsive-wrapper {
      display: block;
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }
    .small-box .inner h3 {
      font-weight: 700;
    }
    .small-box .inner p {
      margin-bottom: 0;
    }
    .card-header .card-title {
      font-size: 0.95rem;
    }
    .card-body {
      padding: 1rem 1.15rem;
    }
    .card-header {
      padding: 0.7rem 1.15rem;
    }
    .card-outline.card-primary {
      border-top-color: <?php echo COLOR_PRIMARY; ?>;
    }
    .card-outline.card-danger {
      border-top-color: #dc3545;
    }
    .card-outline.card-success {
      border-top-color: #28a745;
    }
    .card-outline.card-warning {
      border-top-color: #ffc107;
    }
    .btn {
      white-space: nowrap;
    }
    .btn-primary {
      background-color: <?php echo COLOR_PRIMARY; ?> !important;
      border-color: <?php echo COLOR_PRIMARY; ?> !important;
    }
    .btn-primary:hover {
      background-color: <?php echo COLOR_SECONDARY; ?> !important;
      border-color: <?php echo COLOR_SECONDARY; ?> !important;
    }
    .btn-primary:focus, .btn-primary:active {
      background-color: <?php echo COLOR_SECONDARY; ?> !important;
      border-color: <?php echo COLOR_SECONDARY; ?> !important;
      box-shadow: 0 0 0 0.2rem rgba(46,125,50,0.5) !important;
    }
    .btn-secondary {
      background-color: #6c757d !important;
      border-color: #6c757d !important;
    }
    table.table th, table.table td {
      padding: 0.5rem 0.6rem !important;
      font-size: 0.875rem;
    }
    .callout {
      padding: 0.8rem 1rem;
      font-size: 0.875rem;
    }
    .badge-success {
      background-color: #28a745 !important;
    }
    .badge-danger {
      background-color: #dc3545 !important;
    }
    .badge-warning {
      background-color: #ffc107 !important;
      color: #212529 !important;
    }
    .badge-info {
      background-color: #17a2b8 !important;
    }
    .form-group {
      margin-bottom: 0.85rem;
    }
    .form-group label {
      font-size: 0.85rem;
      font-weight: 500;
      color: #495057;
      margin-bottom: 0.25rem;
    }
    .input-group-sm .form-control {
      font-size: 0.85rem;
    }
    .info-box {
      min-height: 90px;
    }
    .info-box .info-box-icon {
      font-size: 2rem;
      width: 70px;
    }
    .info-box .info-box-content {
      padding: 10px 15px;
    }
    .info-box .info-box-number {
      font-size: 1.8rem;
    }
    hr {
      border-top: 1px solid rgba(0,0,0,0.08);
    }
    .main-sidebar .brand-link {
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_desc:after {
      display: none !important;
    }
    .dt-buttons .btn {
      margin-right: 4px;
      border-radius: 3px !important;
    }
    .dt-buttons .btn-primary {
      background-color: <?php echo COLOR_PRIMARY; ?> !important;
      border-color: <?php echo COLOR_PRIMARY; ?> !important;
    }
    .dt-buttons .btn-secondary {
      background-color: #6c757d !important;
      border-color: #6c757d !important;
    }
    table.dataTable.table-valign-middle td {
      vertical-align: middle !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: <?php echo COLOR_PRIMARY; ?> !important;
      border-color: <?php echo COLOR_PRIMARY; ?> !important;
      color: #fff !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
      background: <?php echo COLOR_SECONDARY; ?> !important;
      border-color: <?php echo COLOR_SECONDARY; ?> !important;
      color: #fff !important;
    }
    div.dataTables_wrapper div.dataTables_info {
      padding-top: 0.85em;
    }
    div.dataTables_wrapper div.dataTables_paginate {
      padding-top: 0.5em;
    }
    .dataTables_filter input {
      border-radius: 20px !important;
      padding-left: 12px !important;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo BASE_URL; ?>/dashboard/index" class="nav-link">Dashboard</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-user-circle"></i>
          <span class="ml-1"><?php echo Session::get('nombre', 'Usuario'); ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-header text-center">
            <i class="fas fa-user-circle fa-2x mb-2"></i><br>
            <strong><?php echo Session::get('nombre', 'Usuario'); ?></strong><br>
            <small><?php echo Session::get('username', ''); ?></small>
          </div>
          <div class="dropdown-divider"></div>
          <a href="<?php echo BASE_URL; ?>/auth/logout" class="dropdown-item dropdown-footer">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
          </a>
        </div>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?php echo BASE_URL; ?>/dashboard/index" class="brand-link">
      <img src="<?php echo BASE_URL; ?>/assets/adminlte/img/AdminLTELogo.png" alt="AGAPROVA Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo APP_NAME; ?></span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo BASE_URL; ?>/assets/adminlte/img/avatar.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo Session::get('nombre', 'Usuario'); ?></a>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/dashboard/index" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/lote/index" class="nav-link">
              <i class="nav-icon fas fa-boxes"></i>
              <p>Lotes de Ganado</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/precio/index" class="nav-link">
              <i class="nav-icon fas fa-tag"></i>
              <p>Precios de Mercado</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/clima/index" class="nav-link">
              <i class="nav-icon fas fa-cloud-rain"></i>
              <p>Clima</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/bloqueo/index" class="nav-link">
              <i class="nav-icon fas fa-ban"></i>
              <p>Bloqueos de Ruta</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/costoflete/index" class="nav-link">
              <i class="nav-icon fas fa-truck"></i>
              <p>Costos de Flete</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/simulacion/index" class="nav-link">
              <i class="nav-icon fas fa-flask"></i>
              <p>Simulaciones</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/reporte/index" class="nav-link">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>Reportes</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/auth/logout" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Cerrar Sesión</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content">
      <div class="container-fluid">

        <?php if (Session::hasFlash('success')): ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fas fa-check-circle"></i> <?php echo Session::flash('success'); ?>
          </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('error')): ?>
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fas fa-exclamation-circle"></i> <?php echo Session::flash('error'); ?>
          </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('info')): ?>
          <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fas fa-info-circle"></i> <?php echo Session::flash('info'); ?>
          </div>
        <?php endif; ?>

        <?php echo $content; ?>

      </div>
    </div>
  </div>

  <!-- Main Footer -->
  <footer class="main-footer fixed">
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo APP_NAME; ?></a>.</strong>
    Todos los derechos reservados.
    <div class="float-right d-none d-sm-inline-block">
      <b>Versión</b> <?php echo APP_VERSION; ?>
    </div>
  </footer>
</div>

<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/overlayScrollbars/js/OverlayScrollbars.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/adminlte/js/adminlte.min.js"></script>
</body>
</html>
