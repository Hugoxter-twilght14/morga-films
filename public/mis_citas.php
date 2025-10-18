<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../lib/Appointments.php';

$rows = my_appointments($pdo, (int)$_SESSION['uid']);

include __DIR__ . '/../partials/head.php';
?>
<h3 class="mb-3">Mis citas</h3>

<?php if (empty($rows)): ?>
  <div class="card border-0" style="background:#15161a">
    <div class="card-body text-center py-5">
      <div class="mb-2" style="font-size:2.2rem">🗓️</div>
      <h5 class="mb-2">Aún no tienes citas</h5>
      <p class="text-muted mb-4">Agenda tu primera sesión y te reservamos un horario.</p>
      <a class="btn btn-primary" href="<?=base_url('/public/paquetes.php')?>">Ver paquetes</a>
    </div>
  </div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-modern table-hover">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Hora</th>
          <th>Estado</th>
          <th class="text-right">Total</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?=htmlspecialchars($r['event_date'])?></td>
            <td><?=substr($r['start_time'],0,5)?> – <?=substr($r['end_time'],0,5)?></td>
            <td class="text-capitalize"><?=htmlspecialchars($r['status'])?></td>
            <td class="text-right">$<?=number_format($r['total_price'],2)?></td>
            <td class="text-center">
              <a class="btn btn-table btn-sm" target="_blank"
                 href="<?=base_url('/public/cita_pdf.php?id='.$r['id'])?>">PDF</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>
