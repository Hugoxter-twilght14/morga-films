<?php
require_once __DIR__ . '/../app/db.php';
include __DIR__ . '/../partials/head.php';
?>

<!-- HERO / SLIDER -->
<section class="hero container mb-4">
  <div class="swiper mySwiper-1">
    <div class="swiper-wrapper">

      <div class="swiper-slide">
        <div class="slider">
          <div class="slider-txt">
            <h1>Sesión de embarazo</h1>
            <p>
              Captura uno de los momentos más especiales con iluminación y dirección
              pensadas para resaltar cada detalle.
            </p>
            <div class="botones">
              <a href="<?=base_url('/public/paquetes.php')?>" class="btn-1">Ver paquetes</a>
            </div>
          </div>
          <div class="slider-img">
            <img src="<?=base_url('/public/imagenes/slaider.jpg')?>" alt="Sesión de embarazo">
          </div>
        </div>
      </div>

      <div class="swiper-slide">
        <div class="slider">
          <div class="slider-txt">
            <h1>Casual estudio</h1>
            <p>
              Retratos frescos y modernos en estudio: fondos, poses y edición que combinan con tu estilo.
            </p>
            <div class="botones">
              <a href="<?=base_url('/public/paquetes.php')?>" class="btn-1">Ver Paquetes</a>
            </div>
          </div>
          <div class="slider-img">
            <img src="<?=base_url('/public/imagenes/slaider2.jpg')?>" alt="Casual estudio">
          </div>
        </div>
      </div>

    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
  </div>
</section>

<hr>

<h3 class="mb-3">Paquetes</h3>
<?php
  $packages = $pdo->query("
    SELECT * FROM packages
    WHERE status='activo'
    ORDER BY created_at DESC
    LIMIT 8
  ")->fetchAll();
?>
<div class="row">
<?php foreach ($packages as $p): ?>
  <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
    <div class="card h-100">
      <?php if (!empty($p['cover_image'])): ?>
        <img class="card-img-top" src="<?=htmlspecialchars($p['cover_image'])?>" alt="<?=htmlspecialchars($p['title'])?>">
      <?php endif; ?>
      <div class="card-body d-flex flex-column">
        <h5 class="card-title mb-1"><?=htmlspecialchars($p['title'])?></h5>
        <div class="text-muted small mb-2">
          <?=htmlspecialchars(mb_strimwidth($p['description'], 0, 90, '…'))?>
        </div>
        <div class="mb-2">
          <span class="h5 mb-0">$<?=number_format($p['price'],2)?></span> · <?= (int)$p['duration_minutes']?> min
        </div>

        <div class="mt-auto d-flex justify-content-between">
          <a class="btn btn-outline-secondary btn-sm"
             href="<?=base_url('/public/paquete_detalle.php')?>?id=<?=$p['id']?>">
            Ver información
          </a>

          <?php if (isset($_SESSION['uid']) && (($_SESSION['role'] ?? '') === 'cliente')): ?>
            <a href="<?=base_url('/public/cita_nueva.php')?>?pid=<?=$p['id']?>"
               class="btn btn-primary btn-sm">
              Agendar
            </a>
          <?php else: ?>
            <!-- Si no hay sesión de cliente, puedes mostrar un login o dejarlo vacío como lo tenías -->
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>

<div class="d-flex gap-2">
  <a class="btn btn-outline-primary mr-2" href="<?=base_url('/public/fotos.php')?>">Ver más fotos</a>
  <a class="btn btn-outline-primary" href="<?=base_url('/public/paquetes.php')?>">Ver todos los paquetes</a>
</div>

<!-- ===== SOBRE MÍ ===== -->
<section class="container my-5">
  <div class="about-card">
    <div class="about-img">
      <img src="<?=base_url('/public/imagenes/info.jpg')?>" alt="Sobre mí">
    </div>
    <div class="about-txt">
      <h2 class="mb-2">Sobre mí</h2>
      <p class="mb-3">
        Soy apasionado de la fotografía y me encanta contar historias reales con luz y composición.
        Mi objetivo es que cada sesión sea una experiencia cómoda y divertida, y que el resultado
        te emocione cada vez que lo veas.
      </p>
      <a href="<?=base_url('/public/paquetes.php')?>" class="btn btn-2">Más información</a>
    </div>
  </div>
</section>

<!-- ===== MAPA ===== -->
<section class="container my-4">
  <h2 class="mb-2">Ubicación</h2>
  <div class="map-card">
    <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3855.925431901413!2d-92.2563171386719!3d14.8854475021362!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x858e0ef972b8399b%3A0x771074240715fb1b!2sBugambilia%2013%2C%20Los%20Naranjos%2C%20San%20Miguel%2C%2030794%20Tapachula%20de%20C%C3%B3rdova%20y%20Ord%C3%B3%C3%B1ez%2C%20Chis.!5e0!3m2!1ses!2smx!4v1757297284714!5m2!1ses!2smx"
      width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy"
      referrerpolicy="no-referrer-when-downgrade"></iframe>
  </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
