<?php require __DIR__ . '/cronossite/cronos/config.php'; require_installed(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= c('meta_title', 'Cronos — Sistema de Ponto Eletrônico, Escalas e Controle de Jornada') ?></title>
<meta name="description" content="<?= c('meta_desc', 'Sistema de ponto eletrônico online com integração Hikvision, apuração automática, banco de horas e gestão de escalas.') ?>">
<link rel="canonical" href="https://pontocronos.com.br/">
<meta property="og:type" content="website">
<meta property="og:locale" content="pt_BR">
<meta property="og:title" content="<?= c('meta_title') ?>">
<meta property="og:description" content="<?= c('meta_desc') ?>">
<meta property="og:url" content="https://pontocronos.com.br/">
<meta property="og:site_name" content="Cronos">
<meta name="twitter:card" content="summary_large_image">
<link rel="stylesheet" href="styles.css">
<?php
// JSON-LD montado via array + json_encode (não interpolação de string) para escapar
// corretamente qualquer aspas/acento que o admin venha a digitar no conteúdo editável.
$faqPairs = [
    ['faq1_q', 'faq1_a'], ['faq2_q', 'faq2_a'], ['faq3_q', 'faq3_a'],
    ['faq4_q', 'faq4_a'], ['faq5_q', 'faq5_a'], ['faq6_q', 'faq6_a'],
];
$faqEntities = [];
foreach ($faqPairs as [$qKey, $aKey]) {
    $q = c_val($qKey);
    $a = c_val($aKey);
    if ($q === '' || $a === '') continue;
    $faqEntities[] = [
        '@type' => 'Question',
        'name' => $q,
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $a],
    ];
}
$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Organization',
            'name' => 'Cronos',
            'url' => 'https://pontocronos.com.br',
            'email' => c_val('contact_email', 'contato@pontocronos.com.br'),
            'description' => 'Sistema de gestão de ponto eletrônico, escalas e controle de jornada de trabalho.',
        ],
        [
            '@type' => 'SoftwareApplication',
            'name' => 'Cronos',
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web',
            'url' => 'https://pontocronos.com.br',
            'description' => 'Sistema de ponto eletrônico online com apuração automática, banco de horas, gestão de escalas e integração com dispositivos Hikvision.',
            'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'BRL', 'description' => 'Demonstração gratuita sob solicitação'],
        ],
        ['@type' => 'FAQPage', 'mainEntity' => $faqEntities],
    ],
];
?>
<script type="application/ld+json"><?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?></script>
</head>
<body>

<header class="site-header">
  <div class="wrap header-inner">
    <a class="logo" href="/" aria-label="Cronos — página inicial">
      <span class="logo-mark" aria-hidden="true">◷</span>Cronos
    </a>
    <nav aria-label="Navegação principal">
      <button class="menu-toggle" aria-expanded="false" aria-controls="main-menu">
        <span class="sr-only">Abrir menu</span>
        <span class="menu-bars" aria-hidden="true"></span>
      </button>
      <ul id="main-menu" class="menu">
        <li><a href="#funcionalidades">Funcionalidades</a></li>
        <li><a href="#beneficios">Benefícios</a></li>
        <li><a href="#seguranca">Integrações</a></li>
        <li><a href="#faq">Perguntas frequentes</a></li>
        <li><a class="btn btn-primary btn-nav" href="#demo"><?= c('cta_primary', 'Solicitar demonstração') ?></a></li>
      </ul>
    </nav>
  </div>
</header>

<main>

<!-- ============ HERO ============ -->
<section class="hero" aria-labelledby="hero-title">
  <div class="wrap">
    <p class="eyebrow"><?= c('hero_eyebrow', 'Gestão de ponto, escalas e jornada') ?></p>
    <h1 id="hero-title"><?= c('hero_title', 'Cada batida registrada. Cada hora apurada. Sem retrabalho.') ?></h1>
    <p class="hero-sub"><?= c('hero_sub') ?></p>
    <div class="hero-ctas">
      <a class="btn btn-primary" href="#demo"><?= c('cta_primary', 'Solicitar demonstração') ?></a>
      <a class="btn btn-ghost" href="#funcionalidades"><?= c('cta_secondary', 'Ver funcionalidades') ?></a>
    </div>

    <!-- Elemento-assinatura: régua de jornada -->
    <figure class="journey" aria-label="Exemplo de jornada de trabalho apurada pelo Cronos">
      <div class="journey-card">
        <div class="journey-head">
          <span class="j-name">Maria C. Santos <span class="j-role">· Analista fiscal · Filial BH</span></span>
          <span class="j-date mono">seg, 20/07</span>
        </div>
        <div class="journey-ruler" role="img" aria-label="Linha do tempo das 6h às 20h com quatro batidas de ponto registradas">
          <div class="ruler-track">
            <?php for ($h = 6; $h <= 20; $h += 2): ?>
              <span class="ruler-hour mono" style="--pos: <?= (($h - 6) / 14) * 100 ?>%"><?= sprintf('%02d', $h) ?>h</span>
            <?php endfor; ?>
            <span class="work-span" style="--start: <?= ((8 + 2/60 - 6) / 14) * 100 ?>%; --end: <?= ((12 - 6) / 14) * 100 ?>%"></span>
            <span class="work-span" style="--start: <?= ((13 - 6) / 14) * 100 ?>%; --end: <?= ((17 + 34/60 - 6) / 14) * 100 ?>%"></span>
            <span class="punch" style="--pos: <?= ((8 + 2/60 - 6) / 14) * 100 ?>%; --d: .2s"><i class="stamp mono">08:02</i><b>entrada</b></span>
            <span class="punch" style="--pos: <?= ((12 - 6) / 14) * 100 ?>%; --d: .5s"><i class="stamp mono">12:00</i><b>intervalo</b></span>
            <span class="punch" style="--pos: <?= ((13 - 6) / 14) * 100 ?>%; --d: .8s"><i class="stamp mono">13:00</i><b>retorno</b></span>
            <span class="punch" style="--pos: <?= ((17 + 34/60 - 6) / 14) * 100 ?>%; --d: 1.1s"><i class="stamp mono">17:34</i><b>saída</b></span>
          </div>
        </div>
        <dl class="journey-totals">
          <div><dt>Trabalhadas</dt><dd class="mono">8:32</dd></div>
          <div><dt>Jornada prevista</dt><dd class="mono">8:00</dd></div>
          <div><dt>Saldo do dia</dt><dd class="mono positivo">+0:32</dd></div>
          <div><dt>Origem</dt><dd>Facial · Hikvision</dd></div>
          <div class="j-status"><dt>Status</dt><dd><span class="tag-ok">Apurado automaticamente</span></dd></div>
        </dl>
      </div>
      <figcaption class="sr-only">Exemplo de apuração automática de um dia de trabalho no Cronos.</figcaption>
    </figure>
  </div>
</section>

<!-- ============ FUNCIONALIDADES ============ -->
<section id="funcionalidades" class="funcs" aria-labelledby="funcs-title">
  <div class="wrap">
    <h2 id="funcs-title"><?= c('func_title', 'Tudo que o espelho de ponto exige. Nada que atrase o RH.') ?></h2>
    <p class="section-intro"><?= c('func_intro') ?></p>

    <div class="func-main">
      <?php $stampCodes = ['1' => 'REG', '2' => 'HIK', '3' => 'BCO', '4' => 'ESC', '5' => 'AUS']; ?>
      <?php foreach ($stampCodes as $n => $code): ?>
      <article class="func-row">
        <div class="func-num mono" aria-hidden="true"><?= $code ?></div>
        <div class="func-body">
          <h3><?= c("feat{$n}_title") ?></h3>
          <p><?= c("feat{$n}_body") ?></p>
        </div>
        <div class="func-detail mono" aria-hidden="true"><?= c("feat{$n}_tags") ?></div>
      </article>
      <?php endforeach; ?>
    </div>

    <div class="func-compact" aria-label="Demais funcionalidades">
      <h3 class="compact-title"><?= c('compact_title', 'E ainda:') ?></h3>
      <ul class="compact-list">
        <?php for ($n = 1; $n <= 6; $n++): ?>
        <li><strong><?= c("compact{$n}_title") ?></strong> — <?= c("compact{$n}_body") ?></li>
        <?php endfor; ?>
      </ul>
    </div>
  </div>
</section>

<!-- ============ BENEFÍCIOS ============ -->
<section id="beneficios" class="benefs" aria-labelledby="benefs-title">
  <div class="wrap">
    <h2 id="benefs-title"><?= c('benef_title', 'Por que empresas trocam a planilha pelo Cronos') ?></h2>
    <div class="benef-grid">
      <?php for ($n = 1; $n <= 4; $n++): ?>
      <article>
        <h3><?= c("benef{$n}_title") ?></h3>
        <p><?= c("benef{$n}_body") ?></p>
      </article>
      <?php endfor; ?>
    </div>
  </div>
</section>

<!-- ============ INTEGRAÇÕES & SEGURANÇA ============ -->
<section id="seguranca" class="seg" aria-labelledby="seg-title">
  <div class="wrap seg-inner">
    <div class="seg-text">
      <h2 id="seg-title"><?= c('seg_title', 'Integração de hardware e segurança jurídica') ?></h2>
      <p><?= c('seg_text') ?></p>
      <ul class="seg-list">
        <?php for ($n = 1; $n <= 4; $n++): ?>
        <li><?= c("seg_item{$n}") ?></li>
        <?php endfor; ?>
      </ul>
    </div>
    <div class="seg-log" aria-hidden="true">
      <div class="log-line mono"><span class="log-time">09:41:07</span> batida recebida · dispositivo FACIAL-02 · filial BH</div>
      <div class="log-line mono"><span class="log-time">09:41:08</span> apuração atualizada · matrícula 00482</div>
      <div class="log-line mono"><span class="log-time">10:15:33</span> ajuste de ponto solicitado · aguardando gestor</div>
      <div class="log-line mono"><span class="log-time">10:22:51</span> ajuste aprovado por j.almeida · motivo registrado</div>
      <div class="log-line mono"><span class="log-time">11:03:14</span> atestado anexado · fluxo de abono iniciado</div>
    </div>
  </div>
</section>

<!-- ============ FAQ ============ -->
<section id="faq" class="faq" aria-labelledby="faq-title">
  <div class="wrap">
    <h2 id="faq-title">Perguntas frequentes</h2>
    <?php for ($n = 1; $n <= 6; $n++): ?>
    <details>
      <summary><?= c("faq{$n}_q") ?></summary>
      <p><?= c("faq{$n}_a") ?></p>
    </details>
    <?php endfor; ?>
  </div>
</section>

<!-- ============ CTA FINAL ============ -->
<section id="demo" class="cta-final" aria-labelledby="cta-title">
  <div class="wrap">
    <h2 id="cta-title"><?= c('cta_final_title', 'Veja o Cronos apurando o ponto da sua equipe') ?></h2>
    <p><?= c('cta_final_body') ?></p>
    <a class="btn btn-primary btn-lg" href="mailto:<?= c('contact_email', 'contato@pontocronos.com.br') ?>?subject=Solicitação de demonstração — Cronos"><?= c('cta_primary', 'Solicitar demonstração') ?></a>
  </div>
</section>

</main>

<footer class="site-footer">
  <div class="wrap footer-grid">
    <div>
      <p class="logo footer-logo"><span class="logo-mark" aria-hidden="true">◷</span>Cronos</p>
      <p class="footer-note"><?= c('footer_note') ?></p>
    </div>
    <nav aria-label="Links do rodapé">
      <ul>
        <li><a href="#funcionalidades">Funcionalidades</a></li>
        <li><a href="#beneficios">Benefícios</a></li>
        <li><a href="#faq">Perguntas frequentes</a></li>
        <li><a href="privacidade.php">Política de privacidade</a></li>
      </ul>
    </nav>
    <div>
      <p class="footer-contact-title">Contato</p>
      <p><a href="mailto:<?= c('contact_email') ?>"><?= c('contact_email', 'contato@pontocronos.com.br') ?></a></p>
      <p><?= c('contact_phone', '(31) 0000-0000') ?></p>
    </div>
  </div>
  <div class="wrap footer-bottom">
    <p>© <?= date('Y') ?> Cronos · pontocronos.com.br · Todos os direitos reservados.</p>
    <p>Desenvolvido por <a href="https://qitec.com.br" rel="noopener">QItec</a></p>
  </div>
</footer>

<script src="script.js" defer></script>
</body>
</html>
