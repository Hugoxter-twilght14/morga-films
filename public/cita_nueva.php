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
    $start = $_POST['start']; // ahora viene del select
    $packages = array_map('intval', $_POST['packages'] ?? []);
    $notes = trim($_POST['notes'] ?? '');
    $aid = appointment_create($pdo, (int)$_SESSION['uid'], $date, $start, $packages, $notes);
    redirect_to('/public/cita_pdf.php?id='.$aid);
  } catch (Throwable $e) { $err = $e->getMessage(); }
}

// Traer paquetes activos (con duración y precio)
$packages = $pdo->query("SELECT id, title, price, duration_minutes FROM packages WHERE status='activo' ORDER BY title")->fetchAll();

include __DIR__ . '/../partials/head.php';
?>
<h3>Nueva cita</h3>
<?php if ($err): ?><div class="alert alert-danger"><?=$err?></div><?php endif; ?>

<form method="post" class="col-lg-8 p-0" id="formCita">
  <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

  <div class="form-row">
    <div class="form-group col-md-4">
      <label>Fecha</label>
      <input type="date" name="date" id="date" class="form-control" min="<?=date('Y-m-d')?>" required>
    </div>
    <div class="form-group col-md-4">
      <label>Hora de inicio</label>
      <select name="start" id="start" class="form-control" required disabled>
        <option value="">Seleccione fecha y paquetes…</option>
      </select>
      <small id="slotHelp" class="form-text text-muted"></small>
    </div>
    <div class="form-group col-md-4">
      <label>Duración total</label>
      <input type="text" id="duracionTotal" class="form-control" value="0 min" readonly>
    </div>
  </div>

  <div class="form-group">
    <label>Paquetes</label>
    <?php foreach ($packages as $p): ?>
      <div class="custom-control custom-checkbox">
        <input class="custom-control-input js-pack" type="checkbox"
               id="p<?=$p['id']?>" name="packages[]"
               value="<?=$p['id']?>"
               data-mins="<?=$p['duration_minutes']?>"
               <?=($pid===$p['id']?'checked':'')?>>
        <label class="custom-control-label" for="p<?=$p['id']?>">
          <b><?=htmlspecialchars($p['title'])?></b>
          — $<?=number_format($p['price'],2)?>
          (<?=$p['duration_minutes']?> min)
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

<script>
(function(){
  const BASE_SLOTS = '<?=base_url('/public/slots.php')?>';
  const dateEl     = document.getElementById('date');
  const startEl    = document.getElementById('start');
  const helpEl     = document.getElementById('slotHelp');
  const packsEls   = Array.from(document.querySelectorAll('.js-pack'));
  const durEl      = document.getElementById('duracionTotal');

  function selectedPackages(){
    return packsEls.filter(x => x.checked).map(x => parseInt(x.value));
  }
  function updateDuration(){
    let mins = packsEls.filter(x=>x.checked).reduce((s,x)=>s + parseInt(x.dataset.mins||0), 0);
    durEl.value = (mins||0) + ' min';
    return mins;
  }

  async function loadSlots(){
    const date = dateEl.value;
    const ids  = selectedPackages();
    startEl.innerHTML = '';
    startEl.disabled = true;
    helpEl.textContent = '';

    if (!date || ids.length === 0) {
      const op = document.createElement('option');
      op.value = '';
      op.textContent = 'Seleccione fecha y paquetes…';
      startEl.appendChild(op);
      return;
    }
    try{
      const url = `${BASE_SLOTS}?date=${encodeURIComponent(date)}&packages=${ids.join(',')}&step=30`;
      const res = await fetch(url, {headers: {'Accept':'application/json'}});
      const data = await res.json();
      startEl.disabled = false;
      startEl.innerHTML = '';
      if (data.ok && data.slots && data.slots.length){
        data.slots.forEach(h => {
          const op = document.createElement('option');
          op.value = h;
          op.textContent = h;
          startEl.appendChild(op);
        });
        helpEl.textContent = `Se muestran horarios disponibles (duración: ${data.duration} min).`;
      } else {
        const op = document.createElement('option');
        op.value = '';
        op.textContent = 'No hay horarios disponibles para esa combinación.';
        startEl.appendChild(op);
        startEl.disabled = true;
        helpEl.textContent = data.error ? data.error : 'Intenta con otra fecha o paquetes.';
      }
    } catch(e){
      const op = document.createElement('option');
      op.value = '';
      op.textContent = 'Error al cargar horarios';
      startEl.appendChild(op);
      startEl.disabled = true;
      helpEl.textContent = 'Reintenta más tarde.';
    }
  }

  // Eventos
  dateEl.addEventListener('change', loadSlots);
  packsEls.forEach(x => x.addEventListener('change', ()=>{ updateDuration(); loadSlots(); }));

  // Preselección si venías con ?pid=...
  updateDuration();
})();
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
