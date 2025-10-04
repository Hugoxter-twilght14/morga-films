<?php
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

function render_appointment_pdf(PDO $pdo, int $id) {
  [$a,$items] = appointment_with_items($pdo,$id);
  if (!$a) { http_response_code(404); exit('Cita no encontrada'); }

  ob_start(); ?>
  <html><head>
    <meta charset="utf-8">
    <style>
      body{font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size:12px;}
      h2{margin-bottom:0}
      .muted{color:#666}
      table{width:100%; border-collapse:collapse; margin-top:12px}
      th,td{border:1px solid #ddd; padding:6px}
      th{background:#f3f3f3}
      .right{text-align:right}
    </style>
  </head><body>
    <h2>Comprobante de Cita — MORGA FILMS</h2>
    <div class="muted">Generado: <?=date('Y-m-d H:i')?></div>
    <p><b>Cliente:</b> <?=htmlspecialchars($a['name'])?> — <?=htmlspecialchars($a['email'])?> — <?=htmlspecialchars($a['phone'])?></p>
    <p><b>Fecha:</b> <?=$a['event_date']?> &nbsp; <b>Hora:</b> <?=$a['start_time']?> - <?=$a['end_time']?> &nbsp; <b>Estado:</b> <?=$a['status']?></p>
    <?php if (!empty($a['notes'])): ?><p><b>Notas:</b> <?=htmlspecialchars($a['notes'])?></p><?php endif; ?>
    <table>
      <thead><tr><th>Paquete</th><th class="right">Precio</th></tr></thead>
      <tbody>
        <?php foreach($items as $it): ?>
        <tr><td><?=htmlspecialchars($it['title'])?></td><td class="right">$<?=number_format($it['price'],2)?></td></tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr><th class="right">Total</th><th class="right">$<?=number_format($a['total_price'],2)?></th></tr>
      </tfoot>
    </table>
  </body></html>
  <?php
  $html = ob_get_clean();
  $dompdf = new Dompdf();
  $dompdf->loadHtml($html);
  $dompdf->setPaper('A4','portrait');
  $dompdf->render();
  $dompdf->stream("cita-$id.pdf", ["Attachment" => false]);
}
