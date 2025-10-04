<?php
require_once __DIR__ . '/../app/db.php';

function packages_all_active(PDO $pdo) {
  return $pdo->query("SELECT * FROM packages WHERE status='activo' ORDER BY created_at DESC")->fetchAll();
}
function packages_find(PDO $pdo, int $id) {
  $st = $pdo->prepare("SELECT * FROM packages WHERE id=?");
  $st->execute([$id]); return $st->fetch();
}
function packages_save(PDO $pdo, array $data, ?int $id=null) {
  if ($id) {
    $sql="UPDATE packages SET title=?, description=?, price=?, duration_minutes=?, status=?, cover_image=? WHERE id=?";
    $pdo->prepare($sql)->execute([
      $data['title'],$data['description'],$data['price'],$data['duration_minutes'],$data['status'],$data['cover_image'],$id
    ]);
    return $id;
  } else {
    $sql="INSERT INTO packages (title,description,price,duration_minutes,status,cover_image) VALUES (?,?,?,?,?,?)";
    $pdo->prepare($sql)->execute([
      $data['title'],$data['description'],$data['price'],$data['duration_minutes'],$data['status'],$data['cover_image']
    ]);
    return (int)$pdo->lastInsertId();
  }
}
function packages_delete(PDO $pdo, int $id) {
  $pdo->prepare("DELETE FROM packages WHERE id=?")->execute([$id]);
}
