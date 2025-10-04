<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../lib/Appointments.php';
require_once __DIR__ . '/../lib/PdfService.php';
require_once __DIR__ . '/../app/db.php';

$id = (int)($_GET['id'] ?? 0);
list($app) = appointment_with_items($pdo, $id);
if (!$app || ($app['user_id'] != $_SESSION['uid'] && ($_SESSION['role']??'')!=='admin')) {
  http_response_code(404); exit('No encontrada');
}
// Genera y transmite el PDF (Dompdf)
render_appointment_pdf($pdo, $id);
