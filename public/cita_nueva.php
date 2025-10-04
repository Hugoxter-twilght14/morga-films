<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../lib/Appointments.php';

$pid = isset($_GET['pid']) ? (int)$_GET['pid'] : null;
$err = null;

if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF'); }
  try {
    $date = $_POST['date'];
    $start = $_POST['start'];
    $packages = array_map('intval', $_POST['packages'] ?? []);
    $notes = trim($_POST['notes'] ?? '');
    $aid = appointment_create($pdo, (int)$_SESSION['uid'], $date, $start, $packages, $notes);
    // 👇 Redirección correcta respetando tu subcarpeta
    redirect_to('/public/cita_pdf.php?id='.$aid);
  } catch (Throwable $e) { $err = $e->getMessage(); }
}

$packages = $pdo->query("SELECT * FROM packages WHERE status='activo' ORDER BY title")->fetchAll();
include __DIR__ . '/../partials/head.php';
?>
<h3>Nueva cita</h3>
<?php if ($err): ?><div class="alert alert-danger"><?=$err?></div><?php endif; ?>
<form method="post" class="col-lg-8 p-0">
  <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
  <div class="form-row">
    <div class="form-group col-md-4">
      <label>Fecha</label>
      <input type="date" name="date" class="form-control" min="<?=date('Y-m-d')?>" required>
    </div>
    <div class="form-group col-md-4">
      <label>Hora de inicio</label>
      <input type="time" name="start" class="form-control" required>
    </div>
  </div>
  <div class="form-group">
    <label>Paquetes</label>
    <?php foreach ($packages as $p): ?>
      <div class="custom-control custom-checkbox">
        <input class="custom-control-input" type="checkbox" id="p<?=$p['id']?>" name="packages[]" value="<?=$p['id']?>" <?=($pid===$p['id']?'checked':'')?>>
        <label class="custom-control-label" for="p<?=$p['id']?>">
          <b><?=htmlspecialchars($p['title'])?></b> — $<?=number_format($p['price'],2)?> (<?=$p['duration_minutes']?> min)
        </label>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="form-group">
    <label>Notas</label>
    <textarea class="form-control" name="notes" rows="2" placeholder="Opcional"></textarea>
  </div>
  <button class="btn btn-primary">Agendar</button>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
