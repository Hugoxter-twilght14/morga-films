<?php
require_once __DIR__ . '/../middleware/auth.php';   // exige sesión
require_once __DIR__ . '/../app/db.php';

if (($_SESSION['role'] ?? null) !== 'cliente') {
  http_response_code(403);
  exit('Solo clientes.');
}

// Helpers simples para mensajes flash locales
function flash_set($type, $msg){ $_SESSION['flash']=['t'=>$type,'m'=>$msg]; }
function flash_view(){
  if (!empty($_SESSION['flash'])) {
    $t = $_SESSION['flash']['t']; $m = $_SESSION['flash']['m'];
    unset($_SESSION['flash']);
    echo '<div class="alert alert-'.$t.'">'.$m.'</div>';
  }
}

$uid = (int)$_SESSION['uid'];

// Procesar POST (actualizar perfil o password)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF'); }

  $action = $_POST['action'] ?? '';

  if ($action === 'profile') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($name === '' || $email === '') {
      flash_set('danger', 'Nombre y email son obligatorios.');
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      flash_set('danger', 'Email no válido.');
    } else {
      // Validar que el email no exista en otro usuario
      $st = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email=? AND id<>?");
      $st->execute([$email, $uid]);
      if ((int)$st->fetchColumn() > 0) {
        flash_set('warning', 'Ese email ya está registrado por otro usuario.');
      } else {
        $st = $pdo->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
        $st->execute([$name, $email, $phone, $uid]);
        $_SESSION['name'] = $name; // refresca navbar
        flash_set('success', 'Perfil actualizado.');
      }
    }
    header('Location: '.base_url('/public/perfil.php')); exit;
  }

  if ($action === 'password') {
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Traer hash actual
    $st = $pdo->prepare("SELECT password_hash FROM users WHERE id=?");
    $st->execute([$uid]);
    $row = $st->fetch();

    if (!$row || !password_verify($current, $row['password_hash'])) {
      flash_set('danger', 'Tu contraseña actual no es correcta.');
    } else if (strlen($new) < 8) {
      flash_set('warning', 'La nueva contraseña debe tener al menos 8 caracteres.');
    } else if ($new !== $confirm) {
      flash_set('warning', 'La confirmación no coincide.');
    } else {
      $hash = password_hash($new, PASSWORD_DEFAULT);
      $pdo->prepare("UPDATE users SET password_hash=? WHERE id=?")->execute([$hash, $uid]);
      flash_set('success', 'Contraseña actualizada.');
    }
    header('Location: '.base_url('/public/perfil.php')); exit;
  }
}

// Cargar info del usuario
$st = $pdo->prepare("SELECT id, name, email, phone, created_at FROM users WHERE id=?");
$st->execute([$uid]);
$user = $st->fetch();

// Pequeño dashboard de citas
$tot = (int)$pdo->prepare("SELECT COUNT(*) FROM appointments WHERE user_id=?")
                ->execute([$uid]) ?: 0;
$tot = (int)$pdo->query("SELECT COUNT(*) FROM appointments WHERE user_id={$uid}")->fetchColumn();

$realizadas = (int)$pdo->query("SELECT COUNT(*) FROM appointments WHERE user_id={$uid} AND status='realizada'")->fetchColumn();
$canceladas = (int)$pdo->query("SELECT COUNT(*) FROM appointments WHERE user_id={$uid} AND status='cancelada'")->fetchColumn();
$proximas   = (int)$pdo->query("SELECT COUNT(*) FROM appointments WHERE user_id={$uid} AND status IN ('pendiente','confirmada') AND event_date >= CURDATE()")->fetchColumn();

include __DIR__ . '/../partials/head.php';
flash_view();
?>

<h3 class="mb-3">Mi perfil</h3>

<div class="row">
  <div class="col-lg-7">
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Información de cuenta</h5>
        <form method="post" class="mt-3">
          <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
          <input type="hidden" name="action" value="profile">
          <div class="form-group">
            <label>Nombre</label>
            <input class="form-control" name="name" value="<?=htmlspecialchars($user['name'])?>" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input class="form-control" type="email" name="email" value="<?=htmlspecialchars($user['email'])?>" required>
          </div>
          <div class="form-group">
            <label>Teléfono</label>
            <input class="form-control" name="phone" value="<?=htmlspecialchars($user['phone'])?>">
          </div>
          <button class="btn btn-primary">Guardar cambios</button>
          <a class="btn btn-outline-secondary" href="<?=base_url('/public/mis_citas.php')?>">Ver mis citas</a>
        </form>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Cambiar contraseña</h5>
        <form method="post" class="mt-3">
          <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
          <input type="hidden" name="action" value="password">
          <div class="form-group">
            <label>Contraseña actual</label>
            <input class="form-control" type="password" name="current_password" required>
          </div>
          <div class="form-group">
            <label>Nueva contraseña</label>
            <input class="form-control" type="password" name="new_password" minlength="8" required>
          </div>
          <div class="form-group">
            <label>Confirmar nueva contraseña</label>
            <input class="form-control" type="password" name="confirm_password" minlength="8" required>
          </div>
          <button class="btn btn-warning">Actualizar contraseña</button>
        </form>
      </div>
    </div>
  </div>

   <div class="col-lg-5">
    <div class="card card-summary">
      <div class="card-body">
        <h5 class="card-title mb-3">Resumen</h5>
        <ul class="list-group list-group-flush list-stat">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Citas totales <span class="badge-soft blue"><?=$tot?></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Próximas <span class="badge-soft teal"><?=$proximas?></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Realizadas <span class="badge-soft green"><?=$realizadas?></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Canceladas <span class="badge-soft red"><?=$canceladas?></span>
          </li>
        </ul>
      </div>
    </div>
  </div>

</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
