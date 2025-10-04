<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../app/db.php';

$id = (int)($_GET['id'] ?? 0);
$st = $pdo->prepare("UPDATE appointments SET status='cancelada' WHERE id=? AND user_id=? AND status IN ('pendiente','confirmada')");
$st->execute([$id, (int)$_SESSION['uid']]);
// 👇 Vuelve a Mis Citas con URL base
redirect_to('/public/mis_citas.php');
