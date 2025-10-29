<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$pack = ['title'=>'','description'=>'','price'=>'','duration_minutes'=>0,'status'=>'activo','cover_image'=>''];

if ($id) {
  $st = $pdo->prepare("SELECT * FROM packages WHERE id=?");
  $st->execute([$id]); $pack = $st->fetch();
  if (!$pack) { http_response_code(404); exit('No encontrado'); }
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF'); }
  $data = [
    'title'=>$_POST['title'],'description'=>$_POST['description'],'price'=>$_POST['price'],
    'duration_minutes'=>$_POST['duration_minutes'],'status'=>$_POST['status'],'cover_image'=>$_POST['cover_image']
  ];
  if ($id) {
    $pdo->prepare("UPDATE packages SET title=?,description=?,price=?,duration_minutes=?,status=?,cover_image=? WHERE id=?")
        ->execute([$data['title'],$data['description'],$data['price'],$data['duration_minutes'],$data['status'],$data['cover_image'],$id]);
  } else {
    $pdo->prepare("INSERT INTO packages (title,description,price,duration_minutes,status,cover_image) VALUES (?,?,?,?,?,?)")
        ->execute([$data['title'],$data['description'],$data['price'],$data['duration_minutes'],$data['status'],$data['cover_image']]);
    $id = (int)$pdo->lastInsertId();
  }
  redirect_to('/admin/paquetes_list.php');
}
include __DIR__ . '/../partials/head.php';
?>
<h3><?=$id?'Editar':'Nuevo'?> paquete</h3>
<form method="post" class="col-lg-8 p-0">
  <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
  <div class="form-group"><label>Título</label><input class="form-control" name="title" value="<?=htmlspecialchars($pack['title'])?>" required></div>
  <div class="form-group"><label>Descripción</label><textarea class="form-control" name="description" rows="3"><?=htmlspecialchars($pack['description'])?></textarea></div>
  <div class="form-row">
    <div class="form-group col-md-4"><label>Precio</label><input class="form-control" type="number" step="0.01" name="price" value="<?=$pack['price']?>" required></div>
    <div class="form-group col-md-4"><label>Duración (horas)</label><input class="form-control" type="number" name="duration_minutes" value="<?=$pack['duration_minutes']?>" required></div>
    <div class="form-group col-md-4">
      <label>Estado</label>
      <select class="form-control" name="status">
        <option <?=$pack['status']=='activo'?'selected':''?>>activo</option>
        <option <?=$pack['status']=='pausado'?'selected':''?>>pausado</option>
      </select>
    </div>
  </div>
  <div class="form-group"><label>Imagen (URL)</label><input class="form-control" name="cover_image" value="<?=htmlspecialchars($pack['cover_image'])?>"></div>
  <button class="btn btn-primary">Guardar</button>
  <a class="btn btn-secondary" href="<?=base_url('/admin/paquetes_list.php')?>">Cancelar</a>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
