<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../app/db.php';

/* --- Paginación --- */
$perPage = 10;
$page = isset($_GET['page']) && ctype_digit($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$total = (int)$pdo->query("SELECT COUNT(*) FROM photos")->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));
if ($page > $totalPages) $page = $totalPages;

$offset = ($page - 1) * $perPage;

/* --- Datos de la página actual --- */
$sql = "SELECT p.title AS ptitle, f.*
        FROM photos f
        LEFT JOIN packages p ON p.id = f.package_id
        ORDER BY f.id DESC
        LIMIT :limit OFFSET :offset";

$st = $pdo->prepare($sql);
$st->bindValue(':limit',  $perPage, PDO::PARAM_INT);
$st->bindValue(':offset', $offset,  PDO::PARAM_INT);
$st->execute();
$rows = $st->fetchAll();

include __DIR__ . '/../partials/head.php';
?>

<h3 class="mb-3 d-flex align-items-center justify-content-between">
  <span>Fotos</span>
  <a class="btn btn-primary" href="<?=base_url('/admin/fotos_form.php')?>">Nueva foto</a>
</h3>

<?php if ($total === 0): ?>
  <div class="alert alert-warning">No hay fotos cargadas.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-modern">
      <thead>
        <tr>
          <th>Miniatura</th>
          <th>Título</th>
          <th>Paquete</th>
          <th>Pública</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($rows as $r): ?>
          <tr>
            <td>
              <img src="<?=base_url('/uploads/'.rawurlencode($r['filename']))?>"
                   style="height:48px;width:80px;object-fit:cover;border-radius:6px" alt="">
            </td>
            <td><?=htmlspecialchars($r['title'] ?? '')?></td>
            <td><?=htmlspecialchars($r['ptitle'] ?: '—')?></td>
            <td><?=!empty($r['is_public']) ? 'Sí' : 'No'?></td>
            <td class="text-center">
              <a class="btn btn-table btn-sm" href="<?=base_url('/admin/fotos_form.php?id='.$r['id'])?>">Editar</a>
              <a class="btn btn-danger btn-sm"
                 href="<?=base_url('/admin/fotos_delete.php?id='.$r['id'])?>"
                 onclick="return confirm('¿Eliminar foto?')">Eliminar</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Footer de paginación -->
  <div class="d-flex align-items-center justify-content-between">
    <small class="text-muted">
      Mostrando <?= $total ? ($offset + 1) : 0 ?>–<?= min($offset + count($rows), $total) ?> de <?= $total ?>
    </small>

    <nav aria-label="Paginación fotos">
      <ul class="pagination mb-0">
        <li class="page-item <?=$page <= 1 ? 'disabled' : ''?>">
          <a class="page-link" href="<?=base_url('/admin/fotos_list.php?page='.($page-1))?>" tabindex="-1">Anterior</a>
        </li>
        <?php
          $window = 2;
          $start = max(1, $page - $window);
          $end   = min($totalPages, $page + $window);

          if ($start > 1) {
            echo '<li class="page-item"><a class="page-link" href="'.base_url('/admin/fotos_list.php?page=1').'">1</a></li>';
            if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
          }
          for ($i = $start; $i <= $end; $i++) {
            $active = $i === $page ? ' active' : '';
            echo '<li class="page-item'.$active.'"><a class="page-link" href="'.base_url('/admin/fotos_list.php?page='.$i).'">'.$i.'</a></li>';
          }
          if ($end < $totalPages) {
            if ($end < $totalPages - 1) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
            echo '<li class="page-item"><a class="page-link" href="'.base_url('/admin/fotos_list.php?page='.$totalPages).'">'.$totalPages.'</a></li>';
          }
        ?>
        <li class="page-item <?=$page >= $totalPages ? 'disabled' : ''?>">
          <a class="page-link" href="<?=base_url('/admin/fotos_list.php?page='.($page+1))?>">Siguiente</a>
        </li>
      </ul>
    </nav>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>
