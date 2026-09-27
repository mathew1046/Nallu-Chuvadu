<?php
declare(strict_types=1);

session_start();

const DB_HOST = 'localhost';
const DB_NAME = 'nalla_chuvadu';
const DB_USER = 'root';
const DB_PASS = '';

function db(): PDO {
    static $pdo;
    if (!$pdo) {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
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
