<?php
require_once __DIR__ . '/../app/config.php';
if (!isset($_SESSION['uid'])) {
  $back = $_SERVER['REQUEST_URI'] ?? '/public/mis_citas.php';
  redirect_to('/public/login.php?redirect=' . urlencode($back));
}
