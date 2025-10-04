<?php
$logged = isset($_SESSION['uid']);
$role = $_SESSION['role'] ?? null;
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="<?=base_url('/public/index.php')?>">MORGA FILMS</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="nav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item"><a class="nav-link" href="<?=base_url('/public/paquetes.php')?>">Paquetes</a></li>
      <li class="nav-item"><a class="nav-link" href="<?=base_url('/public/fotos.php')?>">Galería</a></li>
      <?php if ($role==='admin'): ?>
        <li class="nav-item"><a class="nav-link" href="<?=base_url('/admin/dashboard.php')?>">Admin</a></li>
      <?php endif; ?>
    </ul>
    <ul class="navbar-nav">
      <?php if ($logged): ?>
        <?php if ($role==='cliente'): ?>
          <li class="nav-item"><a class="nav-link" href="<?=base_url('/public/mis_citas.php')?>">Mis Citas</a></li>
          <li class="nav-item"><a class="nav-link" href="<?=base_url('/public/perfil.php')?>">Perfil</a></li>
        <?php endif; ?>
        <li class="nav-item"><span class="navbar-text mr-2">Hola, <?=htmlspecialchars($_SESSION['name']??'')?> </span></li>
        <li class="nav-item"><a class="btn btn-outline-light" href="<?=base_url('/public/logout.php')?>">Salir</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="btn btn-light" href="<?=base_url('/public/login.php')?>">Iniciar sesión</a></li>
        <li class="nav-item ml-2"><a class="btn btn-outline-light" href="<?=base_url('/public/register.php')?>">Registrarse</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
