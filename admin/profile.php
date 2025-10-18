<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';

$err = $ok = null;
$uid = (int)$_SESSION['uid'];

// Carga datos
$st = $pdo->prepare("SELECT name, email, phone FROM users WHERE id=? AND role='admin'");
$st->execute([$uid]);
$u = $st->fetch();

if ($_SERVER['REQUEST_METHOD']==='POST' && hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
  try {
    if (isset($_POST['update_info'])) {
      $name  = trim($_POST['name'] ?? '');
      $email = trim($_POST['email'] ?? '');
      $phone = trim($_POST['phone'] ?? '');
      if ($name==='' || $email==='') throw new Exception('Nombre y email son requeridos');

      // email único
      $ck = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email=? AND id<>?");
      $ck->execute([$email,$uid]);
      if ((int)$ck->fetchColumn() > 0) throw new Exception('Ese email ya está en uso');

      $up = $pdo->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
      $up->execute([$name,$email,$phone,$uid]);
      $_SESSION['name']=$name; $_SESSION['email']=$email;
      $ok = 'Datos actualizados';
    }
    if (isset($_POST['update_pass'])) {
      $curr = $_POST['current_password'] ?? '';
      $pass = $_POST['new_password'] ?? '';
      if (strlen($pass) < 6) throw new Exception('La nueva contraseña debe tener al menos 6 caracteres');

      $stp = $pdo->prepare("SELECT password FROM users WHERE id=?");
      $stp->execute([$uid]);
      $hash = (string)$stp->fetchColumn();
      if (!password_verify($curr, $hash)) throw new Exception('La contraseña actual no es válida');

      $newHash = password_hash($pass, PASSWORD_BCRYPT);
      $up = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
      $up->execute([$newHash,$uid]);
      $ok = 'Contraseña actualizada';
    }
  } catch (Throwable $e) { $err = $e->getMessage(); }
  // Recargar datos
  $st->execute([$uid]); $u = $st->fetch();
}

include __DIR__ . '/../partials/head.php';
?>
<h3 class="mb-3">Mi perfil (Administrador)</h3>
<?php if ($err): ?><div class="alert alert-danger"><?=$err?></div><?php endif; ?>
<?php if ($ok): ?><div class="alert alert-success"><?=$ok?></div><?php endif; ?>

<div class="row">
  <div class="col-lg-7">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Información de cuenta</h5>
        <form method="post">
          <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
          <div class="form-group">
            <label>Nombre</label>
            <input class="form-control" name="name" value="<?=htmlspecialchars($u['name']??'')?>" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input class="form-control" type="email" name="email" value="<?=htmlspecialchars($u['email']??'')?>" required>
          </div>
          <div class="form-group">
            <label>Teléfono</label>
            <input class="form-control" name="phone" value="<?=htmlspecialchars($u['phone']??'')?>">
          </div>
          <button class="btn btn-primary" name="update_info">Guardar cambios</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card card-summary">
      <div class="card-body">
        <h5 class="card-title">Cambiar contraseña</h5>
        <form method="post">
          <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
          <div class="form-group">
            <label>Contraseña actual</label>
            <input class="form-control" type="password" name="current_password" required>
          </div>
          <div class="form-group">
            <label>Nueva contraseña</label>
            <input class="form-control" type="password" name="new_password" required>
          </div>
          <button class="btn btn-outline-primary" name="update_pass">Actualizar contraseña</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
