<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../lib/Appointments.php';

$apps = my_appointments($pdo, (int)$_SESSION['uid']);
include __DIR__ . '/../partials/head.php';
?>
<h3>Mis citas</h3>
<table class="table table-bordered table-sm">
  <thead class="thead-light">
    <tr><th>Fecha</th><th>Hora</th><th>Estado</th><th>Total</th><th>Acciones</th></tr>
  </thead>
  <tbody>
    <?php foreach ($apps as $a): ?>
    <tr>
      <td><?=$a['event_date']?></td>
      <td><?=$a['start_time']?> - <?=$a['end_time']?></td>
      <td><?=$a['status']?></td>
      <td>$<?=number_format($a['total_price'],2)?></td>
      <td>
        <!-- 👇 Enlaces con base_url para respetar /MORGA-FILMS -->
        <a class="btn btn-sm btn-secondary" href="<?=base_url('/public/cita_pdf.php')?>?id=<?=$a['id']?>">PDF</a>
        <?php if (in_array($a['status'], ['pendiente','confirmada'])): ?>
          <a class="btn btn-sm btn-outline-danger" href="<?=base_url('/public/cancelar_cita.php')?>?id=<?=$a['id']?>">Cancelar</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>
