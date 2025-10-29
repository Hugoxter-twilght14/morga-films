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
    if ((int)$ref->fetchColumn()>0) {
      $msg = ['type'=>'danger','text'=>'No se puede eliminar: hay citas asociadas'];
    } else {
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

/* --- Paginación --- */
$perPage = 10;
$page = (isset($_GET['page']) && ctype_digit($_GET['page'])) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$total = (int)$pdo->query("SELECT COUNT(*) FROM packages")->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));
if ($page > $totalPages) $page = $totalPages;

$offset = ($page - 1) * $perPage;

/* --- Datos de la página actual --- */
$sql = "SELECT * FROM packages ORDER BY id DESC LIMIT :limit OFFSET :offset";
$st = $pdo->prepare($sql);
$st->bindValue(':limit',  $perPage, PDO::PARAM_INT);
$st->bindValue(':offset', $offset,  PDO::PARAM_INT);
$st->execute();
$rows = $st->fetchAll();

include __DIR__ . '/../partials/head.php';
?>
<h3 class="mb-3 d-flex align-items-center justify-content-between">
  <span>Paquetes</span>
  <a class="btn btn-primary" href="<?=base_url('/admin/paquetes_form.php')?>">Nuevo paquete</a>
</h3>
<?php if ($msg): ?><div class="alert alert-<?=$msg['type']?>"><?=$msg['text']?></div><?php endif; ?>

<?php if ($total === 0): ?>
  <div class="alert alert-warning">No hay paquetes registrados.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-modern">
      <thead>
        <tr>
          <th>Título</th>
          <th>Precio</th>
          <th>Duración</th>
          <th>Estado</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($rows as $r): ?>
        <tr>
          <td><?=htmlspecialchars($r['title'])?></td>
          <td>$<?=number_format($r['price'],2)?></td>
          <td><?= (int)$r['duration_minutes'] ?> min</td>
          <td><?=htmlspecialchars($r['status'])?></td>
          <td class="text-center">
            <a class="btn btn-table btn-sm" href="<?=base_url('/admin/paquetes_form.php?id='.$r['id'])?>">Editar</a>
            <form method="post" class="d-inline">
              <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
              <input type="hidden" name="id" value="<?=$r['id']?>">
              <button name="toggle" class="btn btn-warning btn-sm"><?=($r['status']==='activo'?'Pausar':'Activar')?></button>
            </form>
            <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar paquete?')">
              <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
              <input type="hidden" name="id" value="<?=$r['id']?>">
              <button name="delete" class="btn btn-danger btn-sm">Eliminar</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Paginación -->
  <div class="d-flex align-items-center justify-content-between">
    <small class="text-muted">
      Mostrando <?= ($total ? ($offset + 1) : 0) ?>–<?= min($offset + count($rows), $total) ?> de <?= $total ?>
    </small>

    <nav aria-label="Paginación paquetes">
      <ul class="pagination mb-0">
        <li class="page-item <?=$page <= 1 ? 'disabled' : ''?>">
          <a class="page-link" href="<?=base_url('/admin/paquetes_list.php?page='.($page-1))?>" tabindex="-1">Anterior</a>
        </li>
        <?php
          $window = 2;
          $start = max(1, $page - $window);
          $end   = min($totalPages, $page + $window);

          if ($start > 1) {
            echo '<li class="page-item"><a class="page-link" href="'.base_url('/admin/paquetes_list.php?page=1').'">1</a></li>';
            if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
          }
          for ($i = $start; $i <= $end; $i++) {
            $active = $i === $page ? ' active' : '';
            echo '<li class="page-item'.$active.'"><a class="page-link" href="'.base_url('/admin/paquetes_list.php?page='.$i).'">'.$i.'</a></li>';
          }
          if ($end < $totalPages) {
            if ($end < $totalPages - 1) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
            echo '<li class="page-item"><a class="page-link" href="'.base_url('/admin/paquetes_list.php?page='.$totalPages).'">'.$totalPages.'</a></li>';
          }
        ?>
        <li class="page-item <?=$page >= $totalPages ? 'disabled' : ''?>">
          <a class="page-link" href="<?=base_url('/admin/paquetes_list.php?page='.($page+1))?>">Siguiente</a>
        </li>
      </ul>
    </nav>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>
