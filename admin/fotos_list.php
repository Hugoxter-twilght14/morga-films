<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../lib/Pagination.php';

if (isset($_GET['del'])) {
  $pdo->prepare("DELETE FROM photos WHERE id=?")->execute([(int)$_GET['del']]);
  redirect_to('/admin/fotos_list.php');
}

$total = (int)$pdo->query("SELECT COUNT(*) FROM photos")->fetchColumn();
$pg = paginate_setup($total, 15);

$st = $pdo->prepare("SELECT ph.*, pk.title AS pack_title
                     FROM photos ph LEFT JOIN packages pk ON pk.id=ph.package_id
                     ORDER BY ph.created_at DESC
                     LIMIT :limit OFFSET :offset");
$st->bindValue(':limit', $pg['limit'], PDO::PARAM_INT);
$st->bindValue(':offset', $pg['offset'], PDO::PARAM_INT);
$st->execute();
$photos = $st->fetchAll();

include __DIR__ . '/../partials/head.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Fotos</h3>
  <a class="btn btn-primary" href="<?=base_url('/admin/fotos_form.php')?>">Nueva foto</a>
</div>
<table class="table table-striped table-sm">
  <thead><tr><th>Miniatura</th><th>Título</th><th>Paquete</th><th>Pública</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($photos as $ph): ?>
    <tr>
      <td style="width:120px"><img src="<?=htmlspecialchars($ph['url'])?>" style="width:120px;height:70px;object-fit:cover"></td>
      <td><?=htmlspecialchars($ph['title'])?></td>
      <td><?=htmlspecialchars($ph['pack_title'] ?? '-')?></td>
      <td><?=$ph['is_public']?'Sí':'No'?></td>
      <td class="text-right">
        <a class="btn btn-sm btn-secondary" href="<?=base_url('/admin/fotos_form.php')?>?id=<?=$ph['id']?>">Editar</a>
        <a class="btn btn-sm btn-danger" href="<?=base_url('/admin/fotos_list.php')?>?del=<?=$ph['id']?>" onclick="return confirm('¿Eliminar foto?')">Eliminar</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?= pagination_links($pg, '/admin/fotos_list.php'); ?>
<?php include __DIR__ . '/../partials/footer.php'; ?>
