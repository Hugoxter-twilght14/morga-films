<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';

$msg = null;
if ($_SERVER['REQUEST_METHOD']==='POST' && hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
  if (isset($_POST['delete'])) {
    $id = (int)$_POST['id'];
    // Verifica referencias
    $ref = $pdo->prepare("SELECT COUNT(*) FROM appointment_items WHERE package_id=?");
    $ref->execute([$id]);
    if ((int)$ref->fetchColumn()>0) { $msg = ['type'=>'danger','text'=>'No se puede eliminar: hay citas asociadas']; }
    else {
      $del = $pdo->prepare("DELETE FROM packages WHERE id=?");
      $del->execute([$id]);
      $msg = ['type'=>'success','text'=>'Paquete eliminado'];
    }
  }
  if (isset($_POST['toggle'])) {
    $id = (int)$_POST['id'];
    $pdo->prepare("UPDATE packages SET status = IF(status='activo','pausado','activo') WHERE id=?")->execute([$id]);
  }
}

$rows = $pdo->query("SELECT * FROM packages ORDER BY id DESC")->fetchAll();
include __DIR__ . '/../partials/head.php';
?>
<h3 class="mb-3 d-flex align-items-center justify-content-between">
  <span>Paquetes</span>
  <a class="btn btn-primary" href="<?=base_url('/admin/paquetes_form.php')?>">Nuevo paquete</a>
</h3>
<?php if ($msg): ?><div class="alert alert-<?=$msg['type']?>"><?=$msg['text']?></div><?php endif; ?>

<div class="table-responsive">
  <table class="table table-modern">
    <thead>
      <tr><th>Título</th><th>Precio</th><th>Duración</th><th>Estado</th><th class="text-center">Acciones</th></tr>
    </thead>
    <tbody>
      <?php foreach($rows as $r): ?>
      <tr>
        <td><?=htmlspecialchars($r['title'])?></td>
        <td>$<?=number_format($r['price'],2)?></td>
        <td><?=$r['duration_minutes']?> min</td>
        <td><?=htmlspecialchars($r['status'])?></td>
        <td class="text-center">
          <a class="btn btn-table btn-sm" href="<?=base_url('/admin/paquetes_form.php?id='.$r['id'])?>">Editar</a>
          <form method="post" class="d-inline">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>"><input type="hidden" name="id" value="<?=$r['id']?>">
            <button name="toggle" class="btn btn-warning btn-sm"><?=($r['status']==='activo'?'Pausar':'Activar')?></button>
          </form>
          <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar paquete?')">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>"><input type="hidden" name="id" value="<?=$r['id']?>">
            <button name="delete" class="btn btn-danger btn-sm">Eliminar</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
