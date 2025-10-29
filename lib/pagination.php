<?php
/**
 * Setup de paginación según total y por página.
 * Devuelve: ['page','pages','limit','offset']
 */
function paginate_setup(int $total, int $perPage): array {
  $page  = max(1, (int)($_GET['page'] ?? 1));
  $pages = max(1, (int)ceil($total / $perPage));
  if ($page > $pages) $page = $pages;
  $offset = ($page - 1) * $perPage;
  return ['page'=>$page, 'pages'=>$pages, 'limit'=>$perPage, 'offset'=>$offset];
}

/**
 * Renderiza <ul class="pagination"> Bootstrap 4
 * $path es la ruta (ej. '/admin/paquetes_list.php')
 * $extraParams son pares query que quieras conservar (ej. filtros)
 */
function pagination_links(array $pg, string $path, array $extraParams=[]): string {
  if ($pg['pages'] <= 1) return '';
  $base = base_url($path);
  $makeUrl = function($p) use ($base, $extraParams) {
    return $base.'?'.http_build_query(array_merge($extraParams, ['page'=>$p]));
  };

  $html = '<nav aria-label="Paginación"><ul class="pagination">';
  // Prev
  $prevDisabled = $pg['page']==1 ? ' disabled' : '';
  $prevUrl = $pg['page']==1 ? '#' : $makeUrl($pg['page']-1);
  $html .= '<li class="page-item'.$prevDisabled.'"><a class="page-link" href="'.$prevUrl.'">Anterior</a></li>';

  // Ventana alrededor de la página actual
  $start = max(1, $pg['page'] - 2);
  $end   = min($pg['pages'], $pg['page'] + 2);

  if ($start > 1) {
    $html .= '<li class="page-item"><a class="page-link" href="'.$makeUrl(1).'">1</a></li>';
    if ($start > 2) $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
  }

  for ($i=$start; $i<=$end; $i++) {
    $active = ($i == $pg['page']) ? ' active' : '';
    $html .= '<li class="page-item'.$active.'"><a class="page-link" href="'.$makeUrl($i).'">'.$i.'</a></li>';
  }

  if ($end < $pg['pages']) {
    if ($end < $pg['pages']-1) $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
    $html .= '<li class="page-item"><a class="page-link" href="'.$makeUrl($pg['pages']).'">'.$pg['pages'].'</a></li>';
  }

  // Next
  $nextDisabled = $pg['page']==$pg['pages'] ? ' disabled' : '';
  $nextUrl = $pg['page']==$pg['pages'] ? '#' : $makeUrl($pg['page']+1);
  $html .= '<li class="page-item'.$nextDisabled.'"><a class="page-link" href="'.$nextUrl.'">Siguiente</a></li>';
  $html .= '</ul></nav>';
  return $html;
}
