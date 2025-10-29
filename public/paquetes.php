<?php
include __DIR__ . '/../partials/head.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../lib/Pagination.php';

$total = (int)$pdo->query("SELECT COUNT(*) FROM packages WHERE status='activo'")->fetchColumn();
$pg = paginate_setup($total, 8);

$st = $pdo->prepare("SELECT * FROM packages WHERE status='activo'
                     ORDER BY created_at DESC
                     LIMIT :limit OFFSET :offset");
$st->bindValue(':limit', $pg['limit'], PDO::PARAM_INT);
$st->bindValue(':offset', $pg['offset'], PDO::PARAM_INT);
$st->execute();
$packages = $st->fetchAll();
?>
<style>

.card-desc-clamp {
  display: -webkit-box;
  -webkit-line-clamp: 6;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  min-height: 5.5em;             
}
</style>

<h3 class="mb-3">Paquetes disponibles</h3>
<div class="row">
<?php foreach ($packages as $p): ?>
  <?php
    // Fallback: cortar server-side por si no hay soporte de line-clamp
    $descFull = trim(strip_tags($p['description'] ?? ''));
    $descShort = mb_strimwidth($descFull, 0, 220, '…', 'UTF-8');
  ?>
  <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
    <div class="card h-100">
      <img class="card-img-top" src="<?=htmlspecialchars($p['cover_image'])?>" alt="">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title"><?=htmlspecialchars($p['title'])?></h5>

        <!-- Fallback en title para accesibilidad: tooltip con el texto completo -->
        <div class="small text-muted mb-2 card-desc-clamp" title="<?=htmlspecialchars($descFull)?>">
          <?=htmlspecialchars($descShort)?>
        </div>

        <div class="mb-2">
          <span class="h5 mb-0">$<?=number_format($p['price'],2)?></span>
          · <?= (int)$p['duration_minutes'] ?> min
        </div>

        <div class="mt-auto d-flex justify-content-between">
          <a class="btn btn-outline-secondary btn-sm"
             href="<?=base_url('/public/paquete_detalle.php')?>?id=<?=$p['id']?>">
            Ver más información
          </a>
          <?php if (isset($_SESSION['uid']) && (($_SESSION['role']??'')==='cliente')): ?>
            <a href="<?=base_url('/public/cita_nueva.php')?>?pid=<?=$p['id']?>" class="btn btn-primary btn-sm">Agendar</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>

<?= pagination_links($pg, '/public/paquetes.php'); ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>
