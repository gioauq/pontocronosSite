<?php
// Cronos — configuração central
declare(strict_types=1);

define('DB_PATH', __DIR__ . '/data/cronos.sqlite');
define('SESSION_NAME', 'cronos_admin');
define('MAX_LOGIN_ATTEMPTS', 5);      // tentativas
define('LOGIN_WINDOW_MIN', 15);       // janela em minutos

function is_installed(): bool {
    return file_exists(DB_PATH);
}

/** Redireciona para o instalador quando o site ainda não foi configurado —
 *  chamada antes de qualquer HTML ser enviado, no topo de cada página pública. */
function require_installed(): void {
    if (!is_installed()) {
        header('Location: install.php');
        exit;
    }
}

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        if (!is_installed()) {
            // Lança em vez de exit(): quem chama (ex: content_cache()) já trata
            // essa falha com try/catch, sem interromper a página no meio de uma tag.
            throw new RuntimeException('Banco de dados não encontrado. Execute install.php primeiro.');
        }
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA journal_mode = WAL;');
    }
    return $pdo;
}

function content_cache(): array {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try {
            foreach (db()->query('SELECT key, value FROM content') as $row) {
                $cache[$row['key']] = $row['value'];
            }
        } catch (Throwable $e) { /* instalação pendente */ }
    }
    return $cache;
}

/** Retorna um conteúdo editável pelo admin, escapado para uso direto em HTML. */
function c(string $key, string $fallback = ''): string {
    $cache = content_cache();
    return htmlspecialchars($cache[$key] ?? $fallback, ENT_QUOTES, 'UTF-8');
}

/** Valor cru (sem escape de HTML) — só para contextos que fazem seu próprio
 *  escape corretamente, como json_encode() em blocos JSON-LD. */
function c_val(string $key, string $fallback = ''): string {
    $cache = content_cache();
    return $cache[$key] ?? $fallback;
}

/** Versão sem escape (para blocos com quebras de linha controladas). */
function c_raw(string $key, string $fallback = ''): string {
    $v = c($key, $fallback);
    return nl2br($v);
}

function start_secure_session(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Strict',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    ]);
    session_start();
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check(): void {
    $sent = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', $sent)) {
        http_response_code(403);
        exit('Falha na verificação de segurança (CSRF). Recarregue a página e tente novamente.');
    }
}

function client_ip(): string {
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function too_many_attempts(): bool {
    $stmt = db()->prepare(
        "SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND at > datetime('now', ?)"
    );
    $stmt->execute([client_ip(), '-' . LOGIN_WINDOW_MIN . ' minutes']);
    return (int)$stmt->fetchColumn() >= MAX_LOGIN_ATTEMPTS;
}

function record_attempt(): void {
    db()->prepare('INSERT INTO login_attempts (ip, at) VALUES (?, datetime("now"))')
        ->execute([client_ip()]);
}

function clear_attempts(): void {
    db()->prepare('DELETE FROM login_attempts WHERE ip = ?')->execute([client_ip()]);
}

function is_logged_in(): bool {
    return !empty($_SESSION['admin_id']);
}

function require_login(): void {
    start_secure_session();
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}
