<?php
require_once __DIR__ . '/auth.php';
if (($_SESSION['role'] ?? null) !== 'admin') {
  http_response_code(403);
  echo "Acceso denegado.";
  exit;
}
