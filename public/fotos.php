<?php
include __DIR__ . '/../partials/head.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../lib/Pagination.php';

$total = (int)$pdo->query("SELECT COUNT(*) FROM photos WHERE is_public=1")->fetchColumn();
$pg = paginate_setup($total, 10);

$st = $pdo->prepare("SELECT * FROM photos WHERE is_public=1
                     ORDER BY created_at DESC
                     LIMIT :limit OFFSET :offset");
$st->bindValue(':limit', $pg['limit'], PDO::PARAM_INT);
$st->bindValue(':offset', $pg['offset'], PDO::PARAM_INT);
$st->execute();
$photos = $st->fetchAll();
?>
<h3 class="mb-3">Galería</h3>
<div class="row">
<?php foreach ($photos as $ph): ?>
  <div class="col-6 col-md-3 mb-4">
    <div class="card">
      <img class="card-img-top" src="<?=htmlspecialchars($ph['url'])?>" alt="">
      <div class="card-body p-2"><div class="small"><?=htmlspecialchars($ph['title'])?></div></div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?= pagination_links($pg, '/public/fotos.php'); ?>
<?php include __DIR__ . '/../partials/footer.php'; ?>
