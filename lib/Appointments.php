<?php
require_once __DIR__ . '/../app/db.php';

function has_overlap(PDO $pdo, string $date, string $start, string $end): bool {
  $st = $pdo->prepare("SELECT COUNT(*) c FROM appointments
    WHERE status IN ('pendiente','confirmada','realizada')
      AND event_date = :d AND (start_time < :end AND end_time > :start)");
  $st->execute([':d'=>$date, ':start'=>$start, ':end'=>$end]);
  return $st->fetchColumn() > 0;
}

function appointment_total_and_duration(PDO $pdo, array $pkgIds): array {
  if (empty($pkgIds)) return [0, 60];
  $in = implode(',', array_fill(0,count($pkgIds),'?'));
  $st = $pdo->prepare("SELECT id, price, duration_minutes FROM packages WHERE status='activo' AND id IN ($in)");
  $st->execute($pkgIds);
  $total=0; $minutes=0; $found=[];
  foreach ($st as $row) {
    $found[(int)$row['id']] = true;
    $total += (float)$row['price'];
    $minutes += (int)$row['duration_minutes'];
  }
  // Filtra ids inválidos
  $valid = array_values(array_filter($pkgIds, fn($id)=>isset($found[(int)$id])));
  if ($minutes<=0) $minutes = 60;
  return [$total, $minutes, $valid];
}

function appointment_create(PDO $pdo, int $userId, string $date, string $start, array $pkgIds, ?string $notes=null): int {
  list($total,$minutes,$pkgIds) = appointment_total_and_duration($pdo, $pkgIds);
  $end = date('H:i:s', strtotime("$start +$minutes minutes"));

  if (has_overlap($pdo,$date,$start,$end)) {
    throw new RuntimeException("Horario no disponible");
  }

  $pdo->beginTransaction();
  $ins = $pdo->prepare("INSERT INTO appointments (user_id,event_date,start_time,end_time,notes,total_price)
                        VALUES (?,?,?,?,?,?)");
  $ins->execute([$userId,$date,$start,$end,$notes,$total]);
  $aid = (int)$pdo->lastInsertId();

  if (!empty($pkgIds)) {
    $in = implode(',', array_fill(0,count($pkgIds),'?'));
    $ps = $pdo->prepare("SELECT id, price FROM packages WHERE id IN ($in)");
    $ps->execute($pkgIds);
    $map = $ps->fetchAll(PDO::FETCH_KEY_PAIR);
    $it = $pdo->prepare("INSERT INTO appointment_items (appointment_id,package_id,qty,price) VALUES (?,?,1,?)");
    foreach ($pkgIds as $id) $it->execute([$aid,$id,$map[$id]]);
  }

  $pdo->commit();
  return $aid;
}

function my_appointments(PDO $pdo, int $userId) {
  $st = $pdo->prepare("SELECT * FROM appointments WHERE user_id=? ORDER BY event_date DESC, start_time DESC");
  $st->execute([$userId]); return $st->fetchAll();
}

function appointment_with_items(PDO $pdo, int $id) {
  $a = $pdo->prepare("SELECT a.*, u.name, u.email, u.phone
                      FROM appointments a JOIN users u ON u.id=a.user_id WHERE a.id=?");
  $a->execute([$id]); $app = $a->fetch();
  if (!$app) return null;
  $it = $pdo->prepare("SELECT p.title, i.price FROM appointment_items i
                       JOIN packages p ON p.id=i.package_id WHERE appointment_id=?");
  $it->execute([$id]); $items = $it->fetchAll();
  return [$app,$items];
}
