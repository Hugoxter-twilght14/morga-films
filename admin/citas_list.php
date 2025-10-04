<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../lib/Pagination.php';

if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF'); }
  $pdo->prepare("UPDATE appointments SET status=? WHERE id=?")->execute([$_POST['status'], (int)$_POST['id']]);
  redirect_to('/admin/citas_list.php');
}

$total = (int)$pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
$pg = paginate_setup($total, 15);

$st = $pdo->prepare("SELECT a.*, u.name, u.email
                     FROM appointments a JOIN users u ON u.id=a.user_id
                     ORDER BY a.event_date DESC, a.start_time DESC
                     LIMIT :limit OFFSET :offset");
$st->bindValue(':limit', $pg['limit'], PDO::PARAM_INT);
$st->bindValue(':offset', $pg['offset'], PDO::PARAM_INT);
$st->execute();
$apps = $st->fetchAll();

include __DIR__ . '/../partials/head.php';
?>
<h3>Citas</h3>
<table class="table table-striped table-sm">
  <thead class="thead-light">
    <tr><th>Cliente</th><th>Fecha/Hora</th><th>Estado</th><th>Total</th><th></th></tr>
  </thead>
  <tbody>
  <?php foreach ($apps as $a): ?>
    <tr>
      <td><?=htmlspecialchars($a['name'])?><br><small><?=htmlspecialchars($a['email'])?></small></td>
      <td><?=$a['event_date']?> <?=$a['start_time']?>-<?=$a['end_time']?></td>
      <td><?=$a['status']?></td>
      <td>$<?=number_format($a['total_price'],2)?></td>
      <td class="text-right">
        <form method="post" action="<?=base_url('/admin/citas_list.php')?>" class="form-inline justify-content-end">
          <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
          <input type="hidden" name="id" value="<?=$a['id']?>">
          <select name="status" class="form-control form-control-sm mr-2">
            <?php foreach (['pendiente','confirmada','realizada','cancelada'] as $st): ?>
              <option <?=$a['status']===$st?'selected':''?>><?=$st?></option>
            <?php endforeach; ?>
          </select>
          <button class="btn btn-sm btn-primary">Actualizar</button>
          <a class="btn btn-sm btn-secondary ml-2" href="<?=base_url('/public/cita_pdf.php')?>?id=<?=$a['id']?>">PDF</a>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?= pagination_links($pg, '/admin/citas_list.php'); ?>
<?php include __DIR__ . '/../partials/footer.php'; ?>
