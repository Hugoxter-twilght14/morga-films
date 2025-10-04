<?php
require_once __DIR__ . '/../partials/head.php';
require_once __DIR__ . '/../app/db.php';

$id = (int)($_GET['id'] ?? 0);

// Helpers locales para imágenes (acepta URL absoluta o archivo subido)
function img_src($path){
  if (!$path) return '';
  return preg_match('#^https?://#', $path) ? $path : base_url($path);
}

// Paquete
$st = $pdo->prepare("SELECT * FROM packages WHERE id=? AND status IN ('activo','pausado')");
$st->execute([$id]);
$pack = $st->fetch();
if (!$pack) { http_response_code(404); exit('<div class="alert alert-danger">Paquete no encontrado</div>'); }

// Fotos ligadas al paquete
$ph = $pdo->prepare("SELECT * FROM photos WHERE is_public=1 AND package_id=? ORDER BY created_at DESC");
$ph->execute([$id]);
$photos = $ph->fetchAll();
?>

<div class="row">
  <div class="col-lg-7">
    <div class="mb-3">
      <img src="<?=img_src($pack['cover_image'])?>" alt="" class="img-fluid rounded shadow-sm">
    </div>

    <h5 class="mb-3">Galería del paquete</h5>
    <?php if (empty($photos)): ?>
      <div class="alert alert-light">Aún no hay fotos asociadas a este paquete.</div>
    <?php else: ?>
      <div class="row">
        <?php foreach ($photos as $p): ?>
          <div class="col-6 col-md-4 mb-3">
            <a href="javascript:void(0)"
               class="d-block js-photo-thumb"
               data-src="<?=htmlspecialchars(img_src($p['url']))?>">
              <img src="<?=htmlspecialchars(img_src($p['url']))?>"
                   class="img-fluid rounded"
                   style="object-fit:cover; height:160px; width:100%"
                   alt="<?=htmlspecialchars($p['title'] ?? '')?>">
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="col-lg-5">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title"><?=htmlspecialchars($pack['title'])?></h3>
        <div class="mb-2 text-muted">Duración: <?=$pack['duration_minutes']?> min</div>
        <div class="h4 mb-3">$<?=number_format($pack['price'],2)?></div>
        <p class="mb-4"><?=nl2br(htmlspecialchars($pack['description']))?></p>

        <?php if (isset($_SESSION['uid']) && ($_SESSION['role']??'')==='cliente'): ?>
          <a href="<?=base_url('/public/cita_nueva.php')?>?pid=<?=$pack['id']?>" class="btn btn-primary btn-lg btn-block">
            Agendar
          </a>
        <?php else: ?>
          <a href="<?=base_url('/public/login.php')?>?redirect=<?=urlencode('/public/cita_nueva.php?pid='.$pack['id'])?>" class="btn btn-outline-primary btn-lg btn-block">
            Iniciar sesión para agendar
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal/Lightbox -->
<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content bg-transparent border-0">
      <button type="button" class="close text-white ml-auto pr-2 pt-2" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <img id="photoModalImg" class="img-fluid rounded shadow" alt="">
    </div>
  </div>
</div>

<script>
// Lightbox sencillo con Bootstrap Modal
document.addEventListener('DOMContentLoaded', function(){
  var modal = $('#photoModal');
  var img   = $('#photoModalImg');
  $(document).on('click', '.js-photo-thumb', function(){
    img.attr('src', $(this).data('src'));
    modal.modal('show');
  });
});
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
