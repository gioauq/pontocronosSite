<?php
// Cronos — instalador. Acesse uma vez, defina a senha do admin e APAGUE este arquivo.
declare(strict_types=1);

$dataDir = __DIR__ . '/data';
$dbPath  = $dataDir . '/cronos.sqlite';

if (!is_dir($dataDir)) mkdir($dataDir, 0755, true);

$alreadyInstalled = file_exists($dbPath);
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$alreadyInstalled) {
    $user = trim($_POST['user'] ?? '');
    $pass = $_POST['pass'] ?? '';
    if (strlen($user) < 3 || strlen($pass) < 10) {
        $msg = 'Usuário precisa de ao menos 3 caracteres e a senha de ao menos 10.';
    } else {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec('CREATE TABLE admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            pass_hash TEXT NOT NULL
        )');
        $pdo->exec('CREATE TABLE login_attempts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            ip TEXT NOT NULL,
            at TEXT NOT NULL
        )');
        $pdo->exec('CREATE TABLE content (
            key TEXT PRIMARY KEY,
            value TEXT NOT NULL,
            label TEXT NOT NULL,
            section TEXT NOT NULL,
            type TEXT NOT NULL DEFAULT "text"
        )');

        $stmt = $pdo->prepare('INSERT INTO admins (username, pass_hash) VALUES (?, ?)');
        $stmt->execute([$user, password_hash($pass, PASSWORD_DEFAULT)]);

        $seed = [
            // key, value, label, section, type
            ['meta_title', 'Cronos — Sistema de Ponto Eletrônico, Escalas e Controle de Jornada', 'Título da aba/Google (meta title)', 'SEO', 'text'],
            ['meta_desc', 'Sistema de ponto eletrônico online com integração Hikvision, apuração automática, banco de horas e gestão de escalas. Feche a folha em horas, não em dias.', 'Descrição para o Google (meta description)', 'SEO', 'textarea'],

            ['hero_eyebrow', 'Gestão de ponto, escalas e jornada', 'Hero — linha superior', 'Hero', 'text'],
            ['hero_title', 'Cada batida registrada. Cada hora apurada. Sem retrabalho.', 'Hero — título principal', 'Hero', 'text'],
            ['hero_sub', 'O Cronos apura o ponto da sua equipe automaticamente, direto dos relógios e câmeras de reconhecimento facial Hikvision. Banco de horas, escalas e atestados em um só fluxo — e a folha fechada em horas, não em dias.', 'Hero — subtítulo', 'Hero', 'textarea'],
            ['cta_primary', 'Solicitar demonstração', 'Botão principal (CTA)', 'Hero', 'text'],
            ['cta_secondary', 'Ver funcionalidades', 'Botão secundário (CTA)', 'Hero', 'text'],

            ['func_title', 'Tudo que o espelho de ponto exige. Nada que atrase o RH.', 'Título da seção', 'Funcionalidades', 'text'],
            ['func_intro', 'Do registro da batida ao fechamento da folha, cada etapa da jornada tem um fluxo próprio no Cronos — com aprovação, trilha de auditoria e regra configurável.', 'Texto de abertura', 'Funcionalidades', 'textarea'],

            ['feat1_title', 'Registro e apuração automática de ponto', 'Título', 'Funcionalidade 1 · REG', 'text'],
            ['feat1_body', 'Entradas, saídas e intervalos entram no sistema em tempo real e são apurados todos os dias, sem ação manual: horas trabalhadas, horas extras com percentuais distintos, atrasos e faltas já calculados quando o RH abre o timesheet pela manhã.', 'Descrição', 'Funcionalidade 1 · REG', 'textarea'],
            ['feat1_tags', 'apuração diária · timesheet · extras 50% e 100%', 'Etiquetas (linha pequena à direita)', 'Funcionalidade 1 · REG', 'text'],

            ['feat2_title', 'Integração nativa com dispositivos Hikvision', 'Título', 'Funcionalidade 2 · HIK', 'text'],
            ['feat2_body', 'Relógios de ponto, catracas e câmeras de reconhecimento facial Hikvision sincronizam direto com o Cronos. Nada de exportar arquivo AFD na mão ou digitar batida: o hardware registra, o sistema apura.', 'Descrição', 'Funcionalidade 2 · HIK', 'textarea'],
            ['feat2_tags', 'facial · biometria · catraca · tempo real', 'Etiquetas (linha pequena à direita)', 'Funcionalidade 2 · HIK', 'text'],

            ['feat3_title', 'Banco de horas com regras suas, não do software', 'Título', 'Funcionalidade 3 · BCO', 'text'],
            ['feat3_body', 'Defina como as horas acumulam, compensam e expiram — por empresa, por unidade ou por acordo coletivo. O Cronos aplica a regra e mantém o saldo de cada colaborador atualizado a cada apuração.', 'Descrição', 'Funcionalidade 3 · BCO', 'textarea'],
            ['feat3_tags', 'regras customizáveis · saldos · compensação', 'Etiquetas (linha pequena à direita)', 'Funcionalidade 3 · BCO', 'text'],

            ['feat4_title', 'Escalas e jornadas complexas sem planilha paralela', 'Título', 'Funcionalidade 4 · ESC', 'text'],
            ['feat4_body', 'Turnos fixos, revezamento 12x36, 5x1, jornadas flexíveis: monte a escala no sistema e a apuração se ajusta automaticamente à jornada de cada pessoa — inclusive nos feriados de cada cidade.', 'Descrição', 'Funcionalidade 4 · ESC', 'textarea'],
            ['feat4_tags', '12x36 · turnos · revezamento · feriados locais', 'Etiquetas (linha pequena à direita)', 'Funcionalidade 4 · ESC', 'text'],

            ['feat5_title', 'Ausências, atestados e ajustes com fluxo de aprovação', 'Título', 'Funcionalidade 5 · AUS', 'text'],
            ['feat5_body', 'Atestados, licenças, férias e correções no espelho de ponto seguem um fluxo único: alguém solicita com o documento anexo, o responsável aprova ou recusa, e a decisão fica registrada. Nada se perde no e-mail.', 'Descrição', 'Funcionalidade 5 · AUS', 'textarea'],
            ['feat5_tags', 'atestados · férias · ajustes de espelho · aprovação', 'Etiquetas (linha pequena à direita)', 'Funcionalidade 5 · AUS', 'text'],

            ['compact_title', 'E ainda:', 'Título da lista compacta', 'Outras Funcionalidades', 'text'],
            ['compact1_title', 'Gestão de feriados', 'Título', 'Outras Funcionalidades', 'text'],
            ['compact1_body', 'nacionais, estaduais, municipais e pontuais, aplicados por unidade.', 'Descrição (continua a frase do título)', 'Outras Funcionalidades', 'text'],
            ['compact2_title', 'Unidades de trabalho e delegações', 'Título', 'Outras Funcionalidades', 'text'],
            ['compact2_body', 'organize filiais e departamentos e delegue responsabilidades por unidade.', 'Descrição (continua a frase do título)', 'Outras Funcionalidades', 'text'],
            ['compact3_title', 'Trilha de auditoria completa', 'Título', 'Outras Funcionalidades', 'text'],
            ['compact3_body', 'cada ação e alteração no sistema fica registrada: quem, quando e o quê.', 'Descrição (continua a frase do título)', 'Outras Funcionalidades', 'text'],
            ['compact4_title', 'Usuários e perfis de acesso', 'Título', 'Outras Funcionalidades', 'text'],
            ['compact4_body', 'permissões separadas para administradores, RH, gestores e colaboradores.', 'Descrição (continua a frase do título)', 'Outras Funcionalidades', 'text'],
            ['compact5_title', 'Verificação de folha', 'Título', 'Outras Funcionalidades', 'text'],
            ['compact5_body', 'confira o timesheet consolidado do período antes de enviar à folha de pagamento.', 'Descrição (continua a frase do título)', 'Outras Funcionalidades', 'text'],
            ['compact6_title', 'Acompanhamento em tempo real', 'Título', 'Outras Funcionalidades', 'text'],
            ['compact6_body', 'gestores veem presenças e ausências das equipes no próprio dia.', 'Descrição (continua a frase do título)', 'Outras Funcionalidades', 'text'],

            ['benef_title', 'Por que empresas trocam a planilha pelo Cronos', 'Título da seção', 'Benefícios', 'text'],
            ['benef1_title', 'Folha fechada em horas, não em dias', 'Título', 'Benefícios', 'text'],
            ['benef1_body', 'A apuração acontece todo dia, não no fim do mês. Quando chega o fechamento, o RH revisa exceções em vez de conferir batida por batida — e os fluxos de aprovação de ajustes e atestados já resolveram as pendências pelo caminho.', 'Descrição', 'Benefícios', 'textarea'],
            ['benef2_title', 'Menos erro na folha de pagamento', 'Título', 'Benefícios', 'text'],
            ['benef2_body', 'Batida que vem direto do relógio Hikvision não passa por digitação. Regra de banco de horas aplicada pelo sistema não depende de fórmula de planilha. Cada etapa automatizada é um erro a menos chegando ao contracheque.', 'Descrição', 'Benefícios', 'textarea'],
            ['benef3_title', 'Segurança jurídica documentada', 'Título', 'Benefícios', 'text'],
            ['benef3_body', 'Em uma fiscalização ou ação trabalhista, você mostra o histórico: a trilha de auditoria registra toda alteração de ponto, e cada ajuste ou abono tem solicitação, documento e aprovação vinculados.', 'Descrição', 'Benefícios', 'textarea'],
            ['benef4_title', 'Cresce junto com a operação', 'Título', 'Benefícios', 'text'],
            ['benef4_body', 'Abriu filial em outra cidade? Crie a unidade, aplique os feriados locais, delegue a gestão ao responsável e mantenha a visão consolidada na matriz. Regras diferentes por unidade convivem no mesmo sistema.', 'Descrição', 'Benefícios', 'textarea'],

            ['seg_title', 'Integração de hardware e segurança jurídica', 'Título', 'Integrações', 'text'],
            ['seg_text', 'O Cronos conversa nativamente com relógios de ponto, catracas e dispositivos de reconhecimento facial Hikvision. Cada batida entra no sistema em tempo real, e cada alteração — quem fez, quando e o que mudou — fica gravada na trilha de auditoria.', 'Texto', 'Integrações', 'textarea'],
            ['seg_item1', 'Reconhecimento facial e biometria Hikvision', 'Item da lista 1', 'Integrações', 'text'],
            ['seg_item2', 'Catracas e relógios de ponto sincronizados', 'Item da lista 2', 'Integrações', 'text'],
            ['seg_item3', 'Trilha de auditoria de todas as ações', 'Item da lista 3', 'Integrações', 'text'],
            ['seg_item4', 'Perfis de acesso por papel: admin, RH, gestor, colaborador', 'Item da lista 4', 'Integrações', 'text'],

            ['faq1_q', 'O Cronos atende à Portaria 671 do Ministério do Trabalho?', 'Pergunta 1', 'FAQ', 'text'],
            ['faq1_a', 'Sim. O Cronos opera com registro eletrônico de ponto, trilha de auditoria completa de cada alteração e fluxos de aprovação documentados, atendendo aos requisitos de tratamento de dados de jornada previstos na Portaria 671.', 'Resposta 1', 'FAQ', 'textarea'],
            ['faq2_q', 'Como funciona a integração com relógios de ponto Hikvision?', 'Pergunta 2', 'FAQ', 'text'],
            ['faq2_a', 'O Cronos sincroniza diretamente com relógios de ponto, catracas e dispositivos de reconhecimento facial Hikvision. As batidas entram no sistema em tempo real, sem importação manual de arquivos.', 'Resposta 2', 'FAQ', 'textarea'],
            ['faq3_q', 'O sistema calcula banco de horas automaticamente?', 'Pergunta 3', 'FAQ', 'text'],
            ['faq3_a', 'Sim. Você define as regras de acúmulo, compensação e limites, e o Cronos calcula os saldos diariamente a partir das batidas apuradas, incluindo horas extras com percentuais distintos.', 'Resposta 3', 'FAQ', 'textarea'],
            ['faq4_q', 'É possível gerenciar escalas de revezamento como 12x36?', 'Pergunta 4', 'FAQ', 'text'],
            ['faq4_a', 'Sim. O módulo de escalas suporta turnos fixos, escalas de revezamento como 12x36 e 5x1, jornadas flexíveis e feriados por localidade, com apuração ajustada a cada jornada.', 'Resposta 4', 'FAQ', 'textarea'],
            ['faq5_q', 'Como são tratados atestados e ajustes de ponto?', 'Pergunta 5', 'FAQ', 'text'],
            ['faq5_a', 'Colaboradores ou gestores registram a solicitação com o documento anexo, e o RH aprova ou recusa em um fluxo rastreável. Toda decisão fica gravada na trilha de auditoria.', 'Resposta 5', 'FAQ', 'textarea'],
            ['faq6_q', 'O Cronos funciona para empresas com várias filiais?', 'Pergunta 6', 'FAQ', 'text'],
            ['faq6_a', 'Sim. As Unidades de Trabalho permitem organizar filiais e departamentos com feriados locais, gestores próprios e delegação de permissões por unidade.', 'Resposta 6', 'FAQ', 'textarea'],

            ['cta_final_title', 'Veja o Cronos apurando o ponto da sua equipe', 'Título', 'CTA Final', 'text'],
            ['cta_final_body', 'Agende uma demonstração com nosso time e traga um caso real da sua operação — escalas, banco de horas, o que for. Mostramos o fluxo completo funcionando.', 'Texto', 'CTA Final', 'textarea'],

            ['contact_email', 'contato@pontocronos.com.br', 'E-mail de contato (footer)', 'Contato', 'text'],
            ['contact_phone', '(31) 0000-0000', 'Telefone de contato (footer)', 'Contato', 'text'],
            ['footer_note', 'Cronos — Sistema de Gestão de Ponto, Escalas e Controle de Jornada.', 'Footer — descrição curta', 'Contato', 'text'],

            ['privacy_meta_title', 'Política de Privacidade — Cronos', 'Meta title da página', 'Privacidade', 'text'],
            ['privacy_meta_desc', 'Política de privacidade do Cronos: como coletamos, usamos e protegemos dados pessoais, em conformidade com a LGPD.', 'Meta description da página', 'Privacidade', 'textarea'],
        ];
        $ins = $pdo->prepare('INSERT INTO content (key, value, label, section, type) VALUES (?,?,?,?,?)');
        foreach ($seed as $row) $ins->execute($row);

        $msg = 'ok';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Instalação — Cronos</title>
<style>
body{font-family:system-ui,sans-serif;background:#F4F6F5;color:#14232E;display:grid;place-items:center;min-height:100vh;margin:0}
.box{background:#fff;border:1px solid #D9DFDC;border-radius:8px;padding:2rem;max-width:420px;width:90%}
h1{font-size:1.2rem;margin-top:0}
label{display:block;margin:.8rem 0 .3rem;font-size:.9rem}
input{width:100%;padding:.6rem;border:1px solid #D9DFDC;border-radius:6px;box-sizing:border-box}
button{margin-top:1.2rem;width:100%;padding:.7rem;background:#0E5A54;color:#fff;border:0;border-radius:6px;font-size:1rem;cursor:pointer}
.ok{color:#0E5A54}.err{color:#B3382C}
code{background:#F4F6F5;padding:.15rem .35rem;border-radius:4px}
</style>
</head>
<body>
<div class="box">
<?php if ($alreadyInstalled): ?>
    <h1>Instalação já concluída</h1>
    <p>O banco de dados já existe. Por segurança, <strong>apague o arquivo <code>install.php</code></strong> do servidor agora.</p>
    <p><a href="admin/">Ir para o painel admin</a></p>
<?php elseif ($msg === 'ok'): ?>
    <h1 class="ok">Instalado com sucesso ✔</h1>
    <p>Agora <strong>apague o arquivo <code>install.php</code></strong> do servidor.</p>
    <p><a href="admin/">Acessar o painel admin</a> · <a href="/">Ver o site</a></p>
<?php else: ?>
    <h1>Instalação do Cronos</h1>
    <p>Defina o acesso do painel administrativo.</p>
    <?php if ($msg): ?><p class="err"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
    <form method="post">
        <label for="user">Usuário</label>
        <input id="user" name="user" required minlength="3" autocomplete="username">
        <label for="pass">Senha (mín. 10 caracteres)</label>
        <input id="pass" name="pass" type="password" required minlength="10" autocomplete="new-password">
        <button type="submit">Instalar</button>
    </form>
<?php endif; ?>
</div>
</body>
</html>
