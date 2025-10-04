<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

$photo = ['package_id'=>null,'title'=>'','url'=>'','is_public'=>1];
$packages = $pdo->query("SELECT id,title FROM packages ORDER BY title")->fetchAll();

if ($id) {
  $st=$pdo->prepare("SELECT * FROM photos WHERE id=?"); $st->execute([$id]);
  $photo=$st->fetch(); if(!$photo){http_response_code(404); exit('No encontrada');}
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF'); }
  $data = [$_POST['package_id'] ?: null, $_POST['title'], $_POST['url'], isset($_POST['is_public'])?1:0];
  if ($id) {
    $pdo->prepare("UPDATE photos SET package_id=?, title=?, url=?, is_public=? WHERE id=?")
        ->execute([$data[0],$data[1],$data[2],$data[3],$id]);
  } else {
    $pdo->prepare("INSERT INTO photos (package_id,title,url,is_public) VALUES (?,?,?,?)")
        ->execute($data);
  }
  redirect_to('/admin/fotos_list.php');
}
include __DIR__ . '/../partials/head.php';
?>
<h3><?=$id?'Editar':'Nueva'?> foto</h3>
<form method="post" class="col-lg-8 p-0">
  <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
  <div class="form-group">
    <label>Paquete (opcional)</label>
    <select class="form-control" name="package_id">
      <option value="">— Ninguno —</option>
      <?php foreach($packages as $p): ?>
        <option value="<?=$p['id']?>" <?=$photo['package_id']==$p['id']?'selected':''?>><?=htmlspecialchars($p['title'])?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group"><label>Título</label><input class="form-control" name="title" value="<?=htmlspecialchars($photo['title'])?>"></div>
  <div class="form-group"><label>URL de imagen</label><input class="form-control" name="url" value="<?=htmlspecialchars($photo['url'])?>" required></div>
  <div class="form-group form-check">
    <input class="form-check-input" type="checkbox" id="pub" name="is_public" <?=$photo['is_public']?'checked':''?>>
    <label class="form-check-label" for="pub">Pública</label>
  </div>
  <button class="btn btn-primary">Guardar</button>
  <a class="btn btn-secondary" href="<?=base_url('/admin/fotos_list.php')?>">Cancelar</a>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
