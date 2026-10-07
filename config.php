<?php
declare(strict_types=1);

session_start();

$localEnv = __DIR__ . '/.env';
if (is_file($localEnv)) {
    $values = parse_ini_file($localEnv, false, INI_SCANNER_RAW) ?: [];
    foreach ($values as $key => $value) {
        if (getenv($key) === false) putenv($key . '=' . $value);
    }
}

const DB_HOST = 'localhost';
const DB_NAME = 'nalla_chuvadu';
const DB_USER = 'nallu_app';
const DB_PASS = '';

function db(): PDO {
    static $pdo;
    if (!$pdo) {
        $host = getenv('DB_HOST') ?: DB_HOST;
        $name = getenv('DB_NAME') ?: DB_NAME;
        $user = getenv('DB_USER') ?: DB_USER;
        $pass = getenv('DB_PASS') ?: DB_PASS;
        $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $name . ';charset=utf8mb4', $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

function logged_in(): bool { return isset($_SESSION['user']); }
function is_admin(): bool { return logged_in() && $_SESSION['user']['role'] === 'admin'; }
function redirect(string $path): never { header("Location: $path"); exit; }
function esc(?string $value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function flash(string $type, string $message): void { $_SESSION['flash'] = compact('type', 'message'); }
function pull_flash(): ?array { $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f; }
function require_login(): void { if (!logged_in()) redirect('index.php'); }

function refresh_user(): void {
    if (!logged_in()) return;
    $stmt = db()->prepare('SELECT id, name, email, role, points, streak, longest_streak FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user']['id']]);
    $_SESSION['user'] = $stmt->fetch() ?: [];
}
