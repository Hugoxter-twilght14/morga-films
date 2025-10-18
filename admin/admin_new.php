<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';

$err=$ok=null;
if ($_SERVER['REQUEST_METHOD']==='POST' && hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
  try{
    $name = trim($_POST['name']??'');
    $email= trim($_POST['email']??'');
    $pass = (string)($_POST['password']??'');
    if ($name==='' || $email==='' || strlen($pass)<6) throw new Exception('Completa nombre, email y contraseña (mín. 6)');
    // email único
    $ck = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email=?");
    $ck->execute([$email]);
    if ((int)$ck->fetchColumn()>0) throw new Exception('Ese email ya existe');

    $hash = password_hash($pass, PASSWORD_BCRYPT);
    $ins = $pdo->prepare("INSERT INTO users (name,email,password,role,created_at) VALUES (?,?,?,'admin',NOW())");
    $ins->execute([$name,$email,$hash]);
    $ok='Administrador creado';
  }catch(Throwable $e){ $err=$e->getMessage(); }
}

include __DIR__ . '/../partials/head.php';
?>
<h3 class="mb-3">NUEVO ADMMINISTRADOR</h3>
<?php if ($err): ?><div class="alert alert-danger"><?=$err?></div><?php endif; ?>
<?php if ($ok): ?><div class="alert alert-success"><?=$ok?></div><?php endif; ?>

<div>
  <div>
    <form method="post" class="col-lg-7 p-0">
      <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
      <div class="form-group">
        <label>NOMBRE</label>
        <input class="form-control" name="name" required>
      </div>
      <div class="form-group">
        <label>CORREO ELECTRONICO</label>
        <input class="form-control" type="email" name="email" required>
      </div>
      <div class="form-group">
        <label>CONTRASEÑA</label>
        <input class="form-control" type="password" name="password" required minlength="6">
      </div>
      <button class="btn btn-primary">Crear administrador</button>
    </form>
  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
