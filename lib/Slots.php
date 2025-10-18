<?php
// Slots de agenda
require_once __DIR__ . '/Appointments.php'; // aquí está _windows_for_date

// --- Fallback por si no quedó cargada (evita "undefined function") ---
if (!function_exists('_windows_for_date')) {
  function _time_norm(string $t): string {
    return preg_match('/^\d{2}:\d{2}$/', $t) ? ($t . ':00') : $t;
  }
  function _weekday_of(string $date): int {
    return (int)date('N', strtotime($date)) - 1; // 0..6 (L-D)
  }
  function _has_base_availability(PDO $pdo): bool {
    return (int)$pdo->query("SELECT COUNT(*) FROM availability")->fetchColumn() > 0;
  }
  function _windows_for_date(PDO $pdo, string $date): array {
    // Excepciones del día
    $st = $pdo->prepare("SELECT is_open, start_time, end_time
                         FROM availability_exceptions WHERE date=? ORDER BY id");
    $st->execute([$date]);
    $ex = $st->fetchAll();
    if ($ex) {
      foreach ($ex as $row) { if ((int)$row['is_open'] === 0) return []; }
      $win = [];
      foreach ($ex as $row) {
        if ((int)$row['is_open'] === 1 && $row['start_time'] && $row['end_time']) {
          $win[] = [_time_norm($row['start_time']), _time_norm($row['end_time'])];
        }
      }
      return $win;
    }
    // Horario base
    if (!_has_base_availability($pdo)) return [['00:00:00','23:59:59']];
    $wd = _weekday_of($date);
    $st = $pdo->prepare("SELECT start_time, end_time FROM availability WHERE weekday=? ORDER BY start_time");
    $st->execute([$wd]);
    $rows = $st->fetchAll();
    $win = [];
    foreach ($rows as $r) $win[] = [_time_norm($r['start_time']), _time_norm($r['end_time'])];
    return $win;
  }
}

// --- Utilidades para slots ---
function _hm_to_min(string $t): int {
  $t = preg_match('/^\d{2}:\d{2}$/', $t) ? $t.':00' : $t;
  [$H,$M] = array_map('intval', explode(':',$t));
  return $H*60 + $M;
}
function _min_to_hm(int $m): string {
  $h = intdiv($m,60); $mi = $m%60; return sprintf('%02d:%02d', $h, $mi);
}

/**
 * Devuelve horas de inicio disponibles (HH:MM) para una fecha dada,
 * respetando availability + exceptions y evitando solapes con citas.
 * $step: tamaño del slot en minutos (p.ej. 30).
 */
function available_starts(PDO $pdo, string $date, int $durationMinutes, int $step = 30): array {
  $date = date('Y-m-d', strtotime($date));
  if ($durationMinutes <= 0) return [];

  $windows = _windows_for_date($pdo, $date); // <- siempre presente (fallback arriba)
  if (empty($windows)) return [];

  // Citas tomadas (no canceladas)
  $st = $pdo->prepare("SELECT start_time, end_time
                       FROM appointments
                       WHERE event_date=? AND status <> 'cancelada'");
  $st->execute([$date]);
  $busy = $st->fetchAll(PDO::FETCH_ASSOC);
  $busyRanges = array_map(function($r){
    return [_hm_to_min($r['start_time']), _hm_to_min($r['end_time'])];
  }, $busy);

  $slots = [];
  foreach ($windows as $w) {
    $ws = _hm_to_min($w[0]); $we = _hm_to_min($w[1]);
    $lastStart = $we - $durationMinutes;
    for ($t = $ws; $t <= $lastStart; $t += $step) {
      $te = $t + $durationMinutes;
      $conflict = false;
      foreach ($busyRanges as [$bs,$be]) {
        if (!($te <= $bs || $t >= $be)) { $conflict = true; break; } // solapa
      }
      if (!$conflict) $slots[] = _min_to_hm($t);
    }
  }
  $slots = array_values(array_unique($slots));
  sort($slots);
  return $slots;
}
