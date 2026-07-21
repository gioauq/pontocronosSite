<?php
// Cronos — painel administrativo (login + edição de conteúdo)
require __DIR__ . '/../config.php';

if (!is_installed()) {
    header('Location: ../install.php');
    exit;
}

start_secure_session();

$error = '';
$saved = false;

// ---- Logout ----
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// ---- Login ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    csrf_check();
    if (too_many_attempts()) {
        $error = 'Muitas tentativas. Aguarde ' . LOGIN_WINDOW_MIN . ' minutos e tente novamente.';
    } else {
        $stmt = db()->prepare('SELECT id, pass_hash FROM admins WHERE username = ?');
        $stmt->execute([trim($_POST['user'] ?? '')]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($admin && password_verify($_POST['pass'] ?? '', $admin['pass_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$admin['id'];
            clear_attempts();
            header('Location: index.php');
            exit;
        }
        record_attempt();
        $error = 'Usuário ou senha incorretos.';
    }
}

// ---- Salvar conteúdo ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save']) && is_logged_in()) {
    csrf_check();
    $upd = db()->prepare('UPDATE content SET value = ? WHERE key = ?');
    foreach ($_POST['content'] ?? [] as $key => $value) {
        if (is_string($key) && is_string($value)) {
            $upd->execute([trim($value), $key]);
        }
    }
    $saved = true;
}

// ---- Trocar senha ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_pass']) && is_logged_in()) {
    csrf_check();
    $new = $_POST['new_pass'] ?? '';
    if (strlen($new) < 10) {
        $error = 'A nova senha precisa de ao menos 10 caracteres.';
    } else {
        db()->prepare('UPDATE admins SET pass_hash = ? WHERE id = ?')
            ->execute([password_hash($new, PASSWORD_DEFAULT), $_SESSION['admin_id']]);
        $saved = true;
    }
}

$logged = is_logged_in();
$token = csrf_token();

$sections = [];
if ($logged) {
    foreach (db()->query('SELECT * FROM content ORDER BY rowid') as $row) {
        $sections[$row['section']][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Admin — Cronos</title>
<style>
:root{--papel:#F4F6F5;--tinta:#14232E;--petroleo:#0E5A54;--carimbo:#B3382C;--linha:#D9DFDC;--grafite:#5A6B70}
body{font-family:system-ui,sans-serif;background:var(--papel);color:var(--tinta);margin:0;line-height:1.5}
.wrap{max-width:820px;margin:0 auto;padding:2rem 1.25rem}
h1{font-size:1.4rem}h2{font-size:1.05rem;margin:2rem 0 .8rem;padding-bottom:.4rem;border-bottom:1px solid var(--linha)}
.card{background:#fff;border:1px solid var(--linha);border-radius:10px;padding:1.6rem;margin-bottom:1.2rem}
label{display:block;font-size:.85rem;font-weight:600;margin:1rem 0 .3rem;color:var(--grafite)}
input[type=text],input[type=password],textarea{width:100%;padding:.6rem;border:1px solid var(--linha);border-radius:6px;box-sizing:border-box;font:inherit;font-size:.95rem}
textarea{min-height:90px;resize:vertical}
button{padding:.65rem 1.4rem;background:var(--petroleo);color:#fff;border:0;border-radius:6px;font-size:.95rem;font-weight:600;cursor:pointer}
button:hover{background:#0A423E}
.topbar{display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap}
.topbar a{color:var(--petroleo);font-size:.9rem}
.ok{background:#E7F1EC;color:#0A423E;padding:.7rem 1rem;border-radius:8px;margin-bottom:1rem}
.err{background:#F7E9E7;color:var(--carimbo);padding:.7rem 1rem;border-radius:8px;margin-bottom:1rem}
.save-bar{position:sticky;bottom:0;background:var(--papel);padding:1rem 0;border-top:1px solid var(--linha);margin-top:1rem}
.login-box{max-width:380px;margin:12vh auto 0}
details.pass{margin-top:2rem}
details.pass summary{cursor:pointer;font-weight:600;color:var(--grafite)}
</style>
</head>
<body>
<div class="wrap">

<?php if (!$logged): ?>
  <div class="card login-box">
    <h1>Cronos · Painel administrativo</h1>
    <?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post" autocomplete="on">
      <input type="hidden" name="csrf" value="<?= $token ?>">
      <label for="user">Usuário</label>
      <input id="user" type="text" name="user" required autocomplete="username">
      <label for="pass">Senha</label>
      <input id="pass" type="password" name="pass" required autocomplete="current-password">
      <p><button type="submit" name="login" value="1">Entrar</button></p>
    </form>
  </div>

<?php else: ?>
  <div class="topbar">
    <h1>Editar conteúdo do site</h1>
    <p><a href="/" target="_blank" rel="noopener">Ver o site ↗</a> &nbsp;·&nbsp; <a href="?logout=1">Sair</a></p>
  </div>

  <?php if ($saved): ?><div class="ok">Alterações salvas com sucesso.</div><?php endif; ?>
  <?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="post">
    <input type="hidden" name="csrf" value="<?= $token ?>">
    <?php foreach ($sections as $sectionName => $fields): ?>
      <h2><?= htmlspecialchars($sectionName) ?></h2>
      <div class="card">
        <?php foreach ($fields as $f): ?>
          <label for="f-<?= htmlspecialchars($f['key']) ?>"><?= htmlspecialchars($f['label']) ?></label>
          <?php if ($f['type'] === 'textarea'): ?>
            <textarea id="f-<?= htmlspecialchars($f['key']) ?>" name="content[<?= htmlspecialchars($f['key']) ?>]"><?= htmlspecialchars($f['value']) ?></textarea>
          <?php else: ?>
            <input type="text" id="f-<?= htmlspecialchars($f['key']) ?>" name="content[<?= htmlspecialchars($f['key']) ?>]" value="<?= htmlspecialchars($f['value']) ?>">
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
    <div class="save-bar">
      <button type="submit" name="save" value="1">Salvar todas as alterações</button>
    </div>
  </form>

  <details class="pass">
    <summary>Trocar senha do admin</summary>
    <div class="card">
      <form method="post">
        <input type="hidden" name="csrf" value="<?= $token ?>">
        <label for="new_pass">Nova senha (mín. 10 caracteres)</label>
        <input id="new_pass" type="password" name="new_pass" minlength="10" required autocomplete="new-password">
        <p><button type="submit" name="change_pass" value="1">Atualizar senha</button></p>
      </form>
    </div>
  </details>
<?php endif; ?>

</div>
</body>
</html>
