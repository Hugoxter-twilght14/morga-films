<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';
$rows = $pdo->query("SELECT p.title AS ptitle, f.* FROM photos f LEFT JOIN packages p ON p.id=f.package_id ORDER BY f.id DESC")->fetchAll();
include __DIR__ . '/../partials/head.php';
?>
<h3 class="mb-3 d-flex align-items-center justify-content-between">
  <span>Fotos</span>
  <a class="btn btn-primary" href="<?=base_url('/admin/fotos_form.php')?>">Nueva foto</a>
</h3>

<div class="table-responsive">
  <table class="table table-modern">
    <thead>
      <tr><th>Miniatura</th><th>Título</th><th>Paquete</th><th>Pública</th><th class="text-center">Acciones</th></tr>
    </thead>
    <tbody>
      <?php foreach($rows as $r): ?>
      <tr>
        <td><img src="<?=base_url('/uploads/'.$r['filename'])?>" style="height:48px;width:80px;object-fit:cover;border-radius:6px"></td>
        <td><?=htmlspecialchars($r['title'])?></td>
        <td><?=htmlspecialchars($r['ptitle']?:'—')?></td>
        <td><?=$r['is_public']?'Sí':'No'?></td>
        <td class="text-center">
          <a class="btn btn-table btn-sm" href="<?=base_url('/admin/fotos_form.php?id='.$r['id'])?>">Editar</a>
          <a class="btn btn-danger btn-sm" href="<?=base_url('/admin/fotos_delete.php?id='.$r['id'])?>" onclick="return confirm('¿Eliminar foto?')">Eliminar</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
