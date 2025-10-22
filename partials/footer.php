<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  if (typeof Swiper === 'undefined') {
    console.error('Swiper no cargó. Revisa el <script src="...swiper-bundle.min.js">');
    return;
  }
  new Swiper('.mySwiper-1', {
    loop: true,
    speed: 600,
    slidesPerView: 1,
    spaceBetween: 0,
    autoplay: { delay: 4500, disableOnInteraction: false, pauseOnMouseEnter: true },
    navigation: { nextEl: '.mySwiper-1 .swiper-button-next', prevEl: '.mySwiper-1 .swiper-button-prev' },
    pagination: { el: '.mySwiper-1 .swiper-pagination', clickable: true }
  });
});
</script>

<footer class="site-footer text-center py-4">
  <div class="footer-social mb-2">
    <!-- Facebook -->
    <a class="social-link" href="https://facebook.com/tu_pagina" target="_blank" rel="noopener" aria-label="Facebook">
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <!-- círculo opcional para consistencia visual -->
        <!-- <circle cx="12" cy="12" r="11" fill="none"></circle> -->
        <path fill="currentColor"
          d="M22 12.07C22 6.5 17.52 2 12 2S2 6.5 2 12.07C2 17.1 5.66 21.17 10.44 22v-7.03H7.9v-2.9h2.54V9.41c0-2.5 1.5-3.89 3.78-3.89 1.1 0 2.25.2 2.25.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.9h-2.34V22C18.34 21.17 22 17.1 22 12.07z"/>
      </svg>
    </a>

    <!-- Instagram -->
    <a class="social-link" href="https://instagram.com/tu_usuario" target="_blank" rel="noopener" aria-label="Instagram">
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path fill="currentColor" d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm0 2a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H7z"/>
        <path fill="currentColor" d="M12 7.5A4.5 4.5 0 117.5 12 4.5 4.5 0 0112 7.5zm0 2A2.5 2.5 0 1014.5 12 2.5 2.5 0 0012 9.5z"/>
        <circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/>
      </svg>
    </a>

    <!-- TikTok -->
    <a class="social-link" href="https://www.tiktok.com/@tu_usuario" target="_blank" rel="noopener" aria-label="TikTok">
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path fill="currentColor"
          d="M13 2h3c.1 1 .3 2 .8 2.9.8 1.3 2 2.2 3.7 2.6V11c-2 0-3.9-.6-5.5-1.8v7A7.6 7.6 0 119 8.9V12a3.6 3.6 0 103.6 3V2z"/>
      </svg>
    </a>
  </div>

  <div class="text-muted">© <?=date('Y')?> MORGA FILMS</div>
</footer>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body></html>
