<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';

$rows = $pdo->query("SELECT u.id, u.name, u.email, u.phone,
   (SELECT COUNT(*) FROM appointments a WHERE a.user_id=u.id) AS citas,
   (SELECT IFNULL(SUM(a.total_price),0) FROM appointments a WHERE a.user_id=u.id) AS gasto
   FROM users u WHERE role='cliente' ORDER BY u.created_at DESC")->fetchAll();

include __DIR__ . '/../partials/head.php';
?>
<h3>Clientes</h3>
<table class="table table-striped table-sm">
  <thead class="thead-light"><tr><th>Nombre</th><th>Contacto</th><th>Citas</th><th>Gasto total</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><?=htmlspecialchars($r['name'])?></td>
      <td><?=htmlspecialchars($r['email'])?><br><small><?=htmlspecialchars($r['phone'])?></small></td>
      <td><?=$r['citas']?></td>
      <td>$<?=number_format($r['gasto'],2)?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>
