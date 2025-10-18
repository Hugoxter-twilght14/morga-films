<?php
// Endpoint: devuelve horarios disponibles para una fecha + paquetes.
// GET: date=YYYY-MM-DD&packages=1,2,3&step=30
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../lib/Slots.php';

// Parseo de inputs
$date = $_GET['date'] ?? '';
$pkStr = trim($_GET['packages'] ?? '');
$step  = (int)($_GET['step'] ?? 30);
$packages = array_filter(array_map('intval', explode(',', $pkStr)));

if (!$date || empty($packages)) {
  echo json_encode(['ok'=>false, 'slots'=>[], 'error'=>'Fecha y paquetes requeridos']); exit;
}

// Sumar duración real desde BD (misma lógica que appointment_create)
try {
  $in = implode(',', array_fill(0, count($packages), '?'));
  $st = $pdo->prepare("SELECT SUM(duration_minutes) AS mins FROM packages WHERE id IN ($in)");
  $st->execute($packages);
  $mins = (int)($st->fetchColumn() ?: 0);
  if ($mins <= 0) throw new Exception('Paquetes inválidos');

  $slots = available_starts($pdo, $date, $mins, ($step > 0 ? $step : 30));
  echo json_encode(['ok'=>true, 'slots'=>$slots, 'duration'=>$mins]);
} catch (Throwable $e) {
  echo json_encode(['ok'=>false, 'slots'=>[], 'error'=>$e->getMessage()]);
}
