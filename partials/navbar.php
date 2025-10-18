<?php
$logged = isset($_SESSION['uid']);
$role   = $_SESSION['role'] ?? null;
$name   = $_SESSION['name'] ?? '';
$email  = $_SESSION['email'] ?? '';
$script = $_SERVER['SCRIPT_NAME'] ?? '';
$active = function(string $path) use ($script){ return strpos($script, $path)!==false ? ' active' : ''; };
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-brand">
  <a class="navbar-brand" href="<?=base_url('/public/index.php')?>">MORGA FILMS</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="nav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item"><a class="nav-link<?=$active('/public/paquetes.php')?>" href="<?=base_url('/public/paquetes.php')?>">Paquetes</a></li>
      <li class="nav-item"><a class="nav-link<?=$active('/public/fotos.php')?>" href="<?=base_url('/public/fotos.php')?>">Galería</a></li>
      <?php if ($role==='admin'): ?>
        <li class="nav-item"><a class="nav-link<?=$active('/admin/')?>" href="<?=base_url('/admin/dashboard.php')?>">Admin</a></li>
      <?php endif; ?>
    </ul>

    <ul class="navbar-nav">
      <?php if ($logged): ?>
        <?php if ($role==='cliente'): ?>
          <li class="nav-item"><a class="nav-link<?=$active('/public/mis_citas.php')?>" href="<?=base_url('/public/mis_citas.php')?>">Mis Citas</a></li>
        <?php endif; ?>

        <!-- Avatar / Perfil (cliente y admin) -->
        <li class="nav-item dropdown profile-dropdown ml-2">
          <button class="avatar-btn dropdown-toggle" id="profileDD" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Perfil">
            <svg class="avatar-icon" viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="12" r="10" stroke="white" stroke-width="2" opacity=".9"/>
              <path d="M12 12a4 4 0 100-8 4 4 0 000 8zM4.6 19.2a8 8 0 0114.8 0" stroke="white" stroke-width="2" stroke-linecap="round" opacity=".9"/>
            </svg>
          </button>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDD">
            <div class="profile-head">
              <div class="profile-avatar">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                  <circle cx="12" cy="12" r="10" stroke="#ff6a00" stroke-width="2"/>
                  <path d="M12 12a4 4 0 100-8 4 4 0 000 8zM4.6 19.2a8 8 0 0114.8 0" stroke="#ff6a00" stroke-width="2" stroke-linecap="round"/>
                </svg>
              </div>
              <div>
                <p class="profile-name mb-1"><?=htmlspecialchars($name)?></p>
                <p class="profile-mail mb-0"><?=htmlspecialchars($email)?></p>
              </div>
            </div>

            <div class="dropdown-divider"></div>

            <div class="profile-body">
              <!--Icono de perfil del cliente-->
              <?php if ($role==='cliente'): ?>
                <a class="btn btn-outline-primary btn-profile" href="<?=base_url('/public/mis_citas.php')?>">Mis Citas</a>
                <a class="btn btn-primary btn-profile" href="<?=base_url('/public/perfil.php')?>">Ver perfil</a>
              <!--Icono de perfil del administrador-->
              <?php elseif ($role==='admin'): ?>
                <a class="btn btn-primary btn-profile" href="<?=base_url('/admin/profile.php')?>">Ver perfil</a>
                <a class="btn btn-outline-primary btn-profile" href="<?=base_url('/admin/admin_new.php')?>">Dar de alta admin</a>
              <?php endif; ?>
              <a class="btn btn-logout btn-profile" href="<?=base_url('/public/logout.php')?>">
                <svg class="icon-logout" viewBox="0 0 24 24" fill="none">
                  <path d="M15 12H3" stroke="white" stroke-width="2" stroke-linecap="round"/>
                  <path d="M11 8l-4 4 4 4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M15 3h3a3 3 0 013 3v12a3 3 0 01-3 3h-3" stroke="white" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Cerrar sesión
              </a>
            </div>
          </div>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a class="btn btn-nav" href="<?=base_url('/public/login.php')?>">Iniciar sesión</a>
        </li>
        <li class="nav-item ml-2">
          <a class="btn btn-outline-light" href="<?=base_url('/public/register.php')?>">Registrarse</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
