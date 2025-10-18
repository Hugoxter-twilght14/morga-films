<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';

// KPIs
$clients     = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='cliente'")->fetchColumn();
$packages    = (int)$pdo->query("SELECT COUNT(*) FROM packages")->fetchColumn();
$appointments= (int)$pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
$canceled    = (int)$pdo->query("SELECT COUNT(*) FROM appointments WHERE status='cancelada'")->fetchColumn();

// Próximas 5 citas
$next = $pdo->query("
  SELECT a.id, a.event_date, a.start_time, a.end_time, a.status, u.name
  FROM appointments a
  JOIN users u ON u.id=a.user_id
  WHERE a.event_date >= CURDATE()
  ORDER BY a.event_date ASC, a.start_time ASC
  LIMIT 5
")->fetchAll();

include __DIR__ . '/../partials/head.php';
?>

<h3 class="mb-4">Panel de administración</h3>

<div class="row">
  <!-- Clientes -->
  <div class="col-md-6 col-lg-3 mb-3">
    <div class="dash-card clients">
      <div class="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M16 19v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/></svg>
      </div>
      <div>
        <div class="label">Clientes</div>
        <div class="kpi"><?=$clients?></div>
      </div>
    </div>
  </div>
  <!-- Paquetes -->
  <div class="col-md-6 col-lg-3 mb-3">
    <div class="dash-card packages">
      <div class="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M21 16V8l-9-5-9 5v8l9 5 9-5z" stroke="currentColor" stroke-width="2"/><path d="M3.3 7.3L12 12l8.7-4.7M12 12v9" stroke="currentColor" stroke-width="2"/></svg>
      </div>
      <div>
        <div class="label">Paquetes</div>
        <div class="kpi"><?=$packages?></div>
      </div>
    </div>
  </div>
  <!-- Citas -->
  <div class="col-md-6 col-lg-3 mb-3">
    <div class="dash-card appointments">
      <div class="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/><path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="2"/></svg>
      </div>
      <div>
        <div class="label">Citas</div>
        <div class="kpi"><?=$appointments?></div>
      </div>
    </div>
  </div>
  <!-- Canceladas -->
  <div class="col-md-6 col-lg-3 mb-3">
    <div class="dash-card canceled">
      <div class="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M15 9l-6 6M9 9l6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </div>
      <div>
        <div class="label">Canceladas</div>
        <div class="kpi"><?=$canceled?></div>
      </div>
    </div>
  </div>
</div>

<!-- Acciones rápidas -->
<div class="row mt-3">
  <div class="col-lg-6 mb-3">
    <a class="action-tile d-block" href="<?=base_url('/admin/paquetes_list.php')?>">
      <div class="l">
        <div class="i">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M21 16V8l-9-5-9 5v8l9 5 9-5z" stroke="#ff6a00" stroke-width="2"/></svg>
        </div>
        <div class="t">Gestionar Paquetes</div>
      </div>
      <span class="text-muted">crear / editar / pausar</span>
    </a>
  </div>

  <div class="col-lg-6 mb-3">
    <a class="action-tile d-block" href="<?=base_url('/admin/fotos_list.php')?>">
      <div class="l">
        <div class="i">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="16" rx="2" stroke="#ff6a00" stroke-width="2"/><path d="M8 14l2.5-3 2 3 2.5-2.5L19 14" stroke="#ff6a00" stroke-width="2" stroke-linecap="round"/></svg>
        </div>
        <div class="t">Gestionar Fotos</div>
      </div>
      <span class="text-muted">subir / asignar a paquete</span>
    </a>
  </div>

  <div class="col-lg-6 mb-3">
    <a class="action-tile d-block" href="<?=base_url('/admin/citas_list.php')?>">
      <div class="l">
        <div class="i">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="16" rx="2" stroke="#ff6a00" stroke-width="2"/><path d="M8 2v4M16 2v4M3 10h18" stroke="#ff6a00" stroke-width="2"/></svg>
        </div>
        <div class="t">Gestionar Citas</div>
      </div>
      <span class="text-muted">actualizar estado / PDF</span>
    </a>
  </div>

  <div class="col-lg-6 mb-3">
    <a class="action-tile d-block" href="<?=base_url('/admin/clientes_list.php')?>">
      <div class="l">
        <div class="i">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M16 19v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1" stroke="#ff6a00" stroke-width="2"/><circle cx="12" cy="7" r="4" stroke="#ff6a00" stroke-width="2"/></svg>
        </div>
        <div class="t">Clientes</div>
      </div>
      <span class="text-muted">contacto / historial</span>
    </a>
  </div>
</div>

<!-- Próximas citas -->
<div class="card mt-4">
  <div class="card-body">
    <h5 class="card-title mb-3">Próximas citas</h5>
    <?php if (!$next): ?>
      <div class="text-muted">No hay citas próximas.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-modern">
          <thead>
            <tr><th>Cliente</th><th>Fecha</th><th>Hora</th><th>Estado</th><th class="text-right">Acciones</th></tr>
          </thead>
          <tbody>
            <?php foreach ($next as $n): ?>
            <tr>
              <td><?=htmlspecialchars($n['name'])?></td>
              <td><?=htmlspecialchars($n['event_date'])?></td>
              <td><?=substr($n['start_time'],0,5)?> – <?=substr($n['end_time'],0,5)?></td>
              <td class="text-capitalize"><?=htmlspecialchars($n['status'])?></td>
              <td class="text-right">
                <a class="btn btn-table btn-sm" href="<?=base_url('/admin/citas_list.php')?>">Gestionar</a>
                <a class="btn btn-table btn-sm" target="_blank" href="<?=base_url('/public/cita_pdf.php?id='.$n['id'])?>">PDF</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
