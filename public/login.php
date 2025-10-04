<?php
require_once __DIR__ . '/../app/db.php';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';
  $st = $pdo->prepare("SELECT id, role, name, password_hash FROM users WHERE email=?");
  $st->execute([$email]);
  $u = $st->fetch();

  if ($u && password_verify($pass, $u['password_hash'])) {
    $_SESSION['uid']  = (int)$u['id'];
    $_SESSION['role'] = $u['role'];
    $_SESSION['name'] = $u['name'];

    $to = $_GET['redirect'] ?? (($u['role']==='admin') ? '/admin/dashboard.php' : '/public/paquetes.php');
    redirect_to($to);
  } else {
    $error = "Credenciales inválidas";
  }
}
include __DIR__ . '/../partials/head.php';
?>
<h3>Iniciar sesión</h3>
<?php if ($error): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
<form method="post" class="col-md-6 p-0">
  <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
  <div class="form-group"><label>Email</label><input class="form-control" type="email" name="email" required></div>
  <div class="form-group"><label>Contraseña</label><input class="form-control" type="password" name="password" required></div>
  <button class="btn btn-primary">Entrar</button>
  <a class="btn btn-link" href="<?=base_url('/public/register.php')?>">Crear cuenta</a>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
