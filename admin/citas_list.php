<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';

if ($_SERVER['REQUEST_METHOD']==='POST' && hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
  $id = (int)$_POST['id'];
  $status = $_POST['status'] ?? 'pendiente';
  $pdo->prepare("UPDATE appointments SET status=? WHERE id=?")->execute([$status,$id]);
}

$rows = $pdo->query("SELECT a.*, u.name, u.email FROM appointments a JOIN users u ON u.id=a.user_id ORDER BY a.event_date DESC, a.start_time DESC")->fetchAll();
include __DIR__ . '/../partials/head.php';
?>
<h3 class="mb-3">Citas</h3>
<div class="table-responsive">
  <table class="table table-modern">
    <thead>
      <tr><th>Cliente</th><th>Fecha/Hora</th><th>Estado</th><th>Total</th><th class="text-right">Acciones</th></tr>
    </thead>
    <tbody>
      <?php foreach($rows as $r): ?>
      <tr>
        <td>
          <div><?=htmlspecialchars($r['name'])?></div>
          <div class="text-muted"><?=htmlspecialchars($r['email'])?></div>
        </td>
        <td><?=htmlspecialchars($r['event_date'])?> <?=substr($r['start_time'],0,5)?>–<?=substr($r['end_time'],0,5)?></td>
        <td class="text-capitalize"><?=htmlspecialchars($r['status'])?></td>
        <td>$<?=number_format($r['total_price'],2)?></td>
        <td class="text-right">
          <form method="post" class="d-inline">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
            <input type="hidden" name="id" value="<?=$r['id']?>">
            <select name="status" class="custom-select custom-select-sm" style="width:140px;display:inline-block">
              <?php foreach (['pendiente','realizada','cancelada'] as $s): ?>
                <option value="<?=$s?>" <?=$s===$r['status']?'selected':''?>><?=$s?></option>
              <?php endforeach; ?>
            </select>
            <button class="btn btn-primary btn-sm">Actualizar</button>
          </form>
          <a class="btn btn-table btn-sm" target="_blank" href="<?=base_url('/public/cita_pdf.php?id='.$r['id'])?>">PDF</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
