<?php include __DIR__ . '/../partials/head.php'; require_once __DIR__ . '/../app/db.php'; ?>
<hr>
<h3 class="mb-3">Paquetes</h3>
<?php $packages = $pdo->query("SELECT * FROM packages WHERE status='activo' ORDER BY created_at DESC LIMIT 8")->fetchAll(); ?>
<div class="row">
<?php foreach ($packages as $p): ?>
  <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
    <div class="card h-100">
      <img class="card-img-top" src="<?=htmlspecialchars($p['cover_image'])?>" alt="">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title mb-1"><?=htmlspecialchars($p['title'])?></h5>
        <div class="text-muted small mb-2"><?=htmlspecialchars(mb_strimwidth($p['description'],0,90,'…'))?></div>
        <div class="mb-2"><span class="h5 mb-0">$<?=number_format($p['price'],2)?></span> · <?=$p['duration_minutes']?> min</div>

        <div class="mt-auto d-flex justify-content-between">
          <a class="btn btn-outline-secondary btn-sm"
             href="<?=base_url('/public/paquete_detalle.php')?>?id=<?=$p['id']?>">
            Ver información
          </a>

          <?php if (isset($_SESSION['uid']) && ($_SESSION['role']??'')==='cliente'): ?>
            <a href="<?=base_url('/public/cita_nueva.php')?>?pid=<?=$p['id']?>" class="btn btn-primary btn-sm">Agendar</a>
          <?php else: ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<a class="btn btn-outline-primary" href="<?=base_url('/public/fotos.php')?>">Ver más fotos</a>
...
<a class="btn btn-outline-primary" href="<?=base_url('/public/paquetes.php')?>">Ver todos los paquetes</a>

<?php include __DIR__ . '/../partials/footer.php'; ?>
