<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';
$tot_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role='cliente'")->fetchColumn();
$tot_packs = $pdo->query("SELECT COUNT(*) FROM packages")->fetchColumn();
$tot_apps  = $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
$tot_cancel= $pdo->query("SELECT COUNT(*) FROM appointments WHERE status='cancelada'")->fetchColumn();
include __DIR__ . '/../partials/head.php';
?>
<h3>Panel de administración</h3>
<div class="row">
  <div class="col-md-3 mb-3"><div class="card card-body">Clientes <h4><?=$tot_users?></h4></div></div>
  <div class="col-md-3 mb-3"><div class="card card-body">Paquetes <h4><?=$tot_packs?></h4></div></div>
  <div class="col-md-3 mb-3"><div class="card card-body">Citas <h4><?=$tot_apps?></h4></div></div>
  <div class="col-md-3 mb-3"><div class="card card-body">Canceladas <h4><?=$tot_cancel?></h4></div></div>
</div>
<div class="list-group">
  <a class="list-group-item list-group-item-action" href="<?=base_url('/admin/paquetes_list.php')?>">Gestionar Paquetes</a>
  <a class="list-group-item list-group-item-action" href="<?=base_url('/admin/fotos_list.php')?>">Gestionar Fotos</a>
  <a class="list-group-item list-group-item-action" href="<?=base_url('/admin/citas_list.php')?>">Gestionar Citas</a>
  <a class="list-group-item list-group-item-action" href="<?=base_url('/admin/clientes_list.php')?>">Clientes</a>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
