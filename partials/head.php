<?php require_once __DIR__ . '/../app/config.php'; ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>MORGA FILMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Theme -->
  <link rel="stylesheet" href="<?=base_url('/assets/css/theme.css')?>">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<div class="container py-4">

<?php
  // ===== Botón "Regresar" con reglas:
  // - Oculto en: /public/index.php (inicio), /admin/dashboard.php (dashboard)
  // - Oculto en: /public/paquetes.php SOLO si el usuario logueado es cliente
  // - Visible (por ejemplo) en: /public/mis_citas.php (inicio del cliente) y demás vistas
  $script    = $_SERVER['SCRIPT_NAME'] ?? '';
  $isClient  = (($_SESSION['role'] ?? null) === 'cliente');

  $hideBack =
    preg_match('#/public/index\.php$#', $script) ||
    preg_match('#/admin/dashboard\.php$#', $script) ||
    ($isClient && preg_match('#/public/paquetes\.php$#', $script));

  if (!$hideBack) {
    // Si el referer es del mismo sitio, úsalo; si no, vuelve al inicio público
    $ref  = $_SERVER['HTTP_REFERER'] ?? '';
    $site = rtrim(base_url('/'), '/');
    $backUrl = (is_string($ref) && strpos($ref, $site) === 0)
      ? $ref
      : base_url('/public/index.php');

    echo '<div class="mb-3">
            <a class="btn btn-outline-secondary btn-sm" href="'.htmlspecialchars($backUrl).'">
              &larr; Regresar
            </a>
          </div>';
  }
?>
