<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';

$sql = "SELECT u.id, u.name, u.email, u.phone,
               COUNT(a.id) AS citas,
               COALESCE(SUM(a.total_price),0) AS gasto
        FROM users u
        LEFT JOIN appointments a ON a.user_id=u.id
        WHERE u.role='cliente'
        GROUP BY u.id
        ORDER BY u.name";
$rows = $pdo->query($sql)->fetchAll();

include __DIR__ . '/../partials/head.php';
?>
<h3 class="mb-3">Clientes</h3>
<div class="table-responsive">
  <table class="table table-modern">
    <thead>
      <tr><th>Nombre</th><th>Contacto</th><th>Citas</th><th>Gasto total</th></tr>
    </thead>
    <tbody>
      <?php foreach($rows as $r): ?>
      <tr>
        <td><?=htmlspecialchars($r['name'])?></td>
        <td>
          <div><?=htmlspecialchars($r['email'])?></div>
          <?php if ($r['phone']): ?><div class="text-muted"><?=htmlspecialchars($r['phone'])?></div><?php endif; ?>
        </td>
        <td><?=$r['citas']?></td>
        <td>$<?=number_format($r['gasto'],2)?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
