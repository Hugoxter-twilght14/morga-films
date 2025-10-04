<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../lib/Pagination.php';

// ---- Flash helpers (mensajes) ----
function flash_set($type, $msg){ $_SESSION['flash']=['t'=>$type,'m'=>$msg]; }
function flash_view(){
  if (!empty($_SESSION['flash'])) {
    $t = $_SESSION['flash']['t']; $m = $_SESSION['flash']['m'];
    unset($_SESSION['flash']);
    echo '<div class="alert alert-'.$t.'">'.$m.'</div>';
  }
}

// ---- Acciones ----
if (isset($_GET['toggle'])) {
  $id=(int)$_GET['toggle'];
  $pdo->prepare("UPDATE packages SET status=IF(status='activo','pausado','activo') WHERE id=?")->execute([$id]);
  flash_set('success','Estado del paquete actualizado.');
  redirect_to('/admin/paquetes_list.php');
}

if (isset($_GET['del'])) {
  $id=(int)$_GET['del'];

  // 1) ¿Fue usado en citas?
  $st = $pdo->prepare("SELECT COUNT(*) FROM appointment_items WHERE package_id=?");
  $st->execute([$id]);
  $used = (int)$st->fetchColumn();

  if ($used > 0) {
    // No permitimos borrar para no perder historial
    flash_set('warning', 'No se puede eliminar este paquete porque ya fue utilizado en citas. Puedes pausarlo.');
    redirect_to('/admin/paquetes_list.php');
  }

  // 2) Desasociar fotos (si tiene). Esto evita otros errores FK.
  $pdo->prepare("UPDATE photos SET package_id=NULL WHERE package_id=?")->execute([$id]);

  // 3) Eliminar paquete
  try {
    $pdo->prepare("DELETE FROM packages WHERE id=?")->execute([$id]);
    flash_set('success', 'Paquete eliminado.');
  } catch (PDOException $e) {
    flash_set('danger', 'No se pudo eliminar: '.$e->getMessage());
  }
  redirect_to('/admin/paquetes_list.php');
}

// ---- Listado con paginación ----
$total = (int)$pdo->query("SELECT COUNT(*) FROM packages")->fetchColumn();
$pg = paginate_setup($total, 15);

$st = $pdo->prepare("SELECT * FROM packages ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$st->bindValue(':limit', $pg['limit'], PDO::PARAM_INT);
$st->bindValue(':offset', $pg['offset'], PDO::PARAM_INT);
$st->execute();
$packs = $st->fetchAll();

include __DIR__ . '/../partials/head.php';
flash_view();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Paquetes</h3>
  <a class="btn btn-primary" href="<?=base_url('/admin/paquetes_form.php')?>">Nuevo paquete</a>
</div>
<table class="table table-striped">
  <thead><tr><th>Título</th><th>Precio</th><th>Duración</th><th>Estado</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($packs as $p): ?>
    <tr>
      <td><?=htmlspecialchars($p['title'])?></td>
      <td>$<?=number_format($p['price'],2)?></td>
      <td><?=$p['duration_minutes']?> min</td>
      <td><?=$p['status']?></td>
      <td class="text-right">
        <a class="btn btn-sm btn-secondary" href="<?=base_url('/admin/paquetes_form.php')?>?id=<?=$p['id']?>">Editar</a>
        <a class="btn btn-sm btn-warning" href="<?=base_url('/admin/paquetes_list.php')?>?toggle=<?=$p['id']?>"><?=$p['status']=='activo'?'Pausar':'Activar'?></a>
        <a class="btn btn-sm btn-danger" href="<?=base_url('/admin/paquetes_list.php')?>?del=<?=$p['id']?>" onclick="return confirm('¿Eliminar paquete? Esta acción no se puede deshacer.')">Eliminar</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?= pagination_links($pg, '/admin/paquetes_list.php'); ?>
<?php include __DIR__ . '/../partials/footer.php'; ?>
