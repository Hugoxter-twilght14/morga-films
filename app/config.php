<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

function envv($k, $d=null){ return $_ENV[$k] ?? (getenv($k)===false ? $d : getenv($k)); }

$debug = filter_var(envv('APP_DEBUG', false), FILTER_VALIDATE_BOOL);
ini_set('display_errors', $debug ? '1' : '0');
error_reporting($debug ? E_ALL : 0);

/* ---------- BASE URL / PATH ---------- */
$BASE_URL  = rtrim(envv('APP_URL', ''), '/');              // ej: http://localhost/MORGA-FILMS
$BASE_PATH = parse_url($BASE_URL, PHP_URL_PATH) ?: '/';    // ej: /MORGA-FILMS

/* ---------- SESIÓN (ANTES de session_start) ---------- */
$secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_name(envv('SESSION_NAME','morgafilms_sess'));
session_set_cookie_params([
  'lifetime' => 0,
  'path'     => $BASE_PATH,  // clave: limita la cookie a tu subcarpeta
  'domain'   => '',
  'secure'   => $secure,
  'httponly' => true,
  'samesite' => 'Lax',
]);
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if (!isset($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

/* ---------- Helpers de URL y redirect ---------- */
if (!function_exists('str_starts_with')) {
  function str_starts_with($h, $n){ return substr($h,0,strlen($n)) === $n; }
}
function base_url($path='') {
  global $BASE_URL;
  if (!$BASE_URL) return $path;
  $p = $path ? (str_starts_with($path,'/') ? $path : '/'.ltrim($path,'/')) : '';
  return $BASE_URL . $p;
}
function redirect_to($path) {
  $url = preg_match('#^https?://#', $path) ? $path : base_url($path);
  header('Location: '.$url);
  exit;
}
