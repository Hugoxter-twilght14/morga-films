<?php
require_once __DIR__ . '/../app/db.php';
$msg = $err = null;
if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF'); }
  try {
    $st = $pdo->prepare("INSERT INTO users (role,name,email,password_hash,phone) VALUES ('cliente',?,?,?,?)");
    $st->execute([$_POST['name'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['phone'] ?? null]);
    $msg = "Cuenta creada. Inicia sesión.";
  } catch (Throwable $e) { $err = $e->getMessage(); }
}
include __DIR__ . '/../partials/head.php';
?>
<h3 class="h3-register">REGISTRATE</h3>
<?php if ($msg): ?><div class="alert alert-success"><?=$msg?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger"><?=$err?></div><?php endif; ?>
<form method="post" class="col-md-6 p-0">
  <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
  <div class="form-group"><label>NOMBRE</label><input class="form-control" name="name" required></div>
  <div class="form-group"><label>CORREO ELECTRONICO</label><input class="form-control" type="email" name="email" required></div>
  <div class="form-group"><label>TELEFONO</label><input class="form-control" name="phone"></div>
  <div class="form-group"><label>CONTRASEÑA</label><input class="form-control" type="password" name="password" required></div>
  <button class="btn btn-primary">Registrarme</button>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
