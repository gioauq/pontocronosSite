<?php require __DIR__ . '/cronossite/cronos/config.php'; require_installed(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= c('privacy_meta_title', 'Política de Privacidade — Cronos') ?></title>
<meta name="description" content="<?= c('privacy_meta_desc', 'Política de privacidade do Cronos: como coletamos, usamos e protegemos dados pessoais, em conformidade com a LGPD.') ?>">
<link rel="canonical" href="https://pontocronos.com.br/privacidade.php">
<meta name="robots" content="index,follow">
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/assets/brand/favicon/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/assets/brand/favicon/apple-touch-icon.png">
<link rel="manifest" href="/assets/brand/favicon/site.webmanifest">
<meta name="theme-color" content="#0E2A3B">
<meta property="og:image" content="https://pontocronos.com.br/assets/brand/social/og-image.png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:image" content="https://pontocronos.com.br/assets/brand/social/og-image.png">
<link rel="stylesheet" href="/assets/brand/css/cronos-tokens.css">
<link rel="preload" href="/assets/brand/fontes/bricolage-grotesque-latin-700-normal.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/assets/brand/fontes/ibm-plex-sans-latin-400-normal.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="site-header">
  <div class="wrap header-inner">
    <a class="logo" href="/" aria-label="Cronos — página inicial">
      <img src="/assets/brand/logo/logo-horizontal.svg" alt="Cronos" width="128" height="32">
    </a>
    <nav aria-label="Navegação principal">
      <ul class="menu" style="display:flex">
        <li><a href="index.php">← Voltar ao site</a></li>
      </ul>
    </nav>
  </div>
</header>

<main class="wrap legal">
  <h1>Política de Privacidade</h1>
  <p>Última atualização: <?= date('d/m/Y') ?></p>

  <p>Esta Política de Privacidade descreve como o <strong>Cronos</strong> (pontocronos.com.br), sistema de gestão de ponto, escalas e controle de jornada, coleta, utiliza, armazena e protege dados pessoais, em conformidade com a Lei Geral de Proteção de Dados — Lei nº 13.709/2018 (LGPD).</p>

  <h2>1. Dados que coletamos</h2>
  <p>No uso do sistema, podem ser tratados os seguintes dados:</p>
  <ul>
    <li><strong>Dados cadastrais:</strong> nome, e-mail corporativo, matrícula, cargo, unidade e departamento;</li>
    <li><strong>Dados de jornada:</strong> registros de entrada e saída, escalas, banco de horas, ausências, atestados e ajustes de ponto;</li>
    <li><strong>Dados biométricos:</strong> quando a empresa contratante utiliza dispositivos de reconhecimento facial ou biometria, os templates biométricos são tratados pelos dispositivos e pelo sistema exclusivamente para fins de registro de ponto;</li>
    <li><strong>Dados de navegação neste site:</strong> informações técnicas básicas de acesso (endereço IP, navegador), utilizadas apenas para segurança e funcionamento.</li>
  </ul>

  <h2>2. Finalidade do tratamento</h2>
  <p>Os dados são tratados exclusivamente para: (a) registro e apuração da jornada de trabalho, em cumprimento de obrigação legal do empregador; (b) gestão de escalas, banco de horas, ausências e férias; (c) segurança e rastreabilidade das operações no sistema (trilha de auditoria); e (d) contato comercial, quando solicitado pelo visitante deste site.</p>

  <h2>3. Base legal</h2>
  <p>O tratamento de dados de jornada tem como bases legais o cumprimento de obrigação legal e regulatória pelo empregador (art. 7º, II, da LGPD) e a execução do contrato de trabalho. O tratamento de dados biométricos, quando aplicável, observa as hipóteses do art. 11 da LGPD.</p>

  <h2>4. Compartilhamento</h2>
  <p>Os dados não são vendidos nem compartilhados com terceiros para fins comerciais. O compartilhamento ocorre apenas: (a) com a empresa empregadora contratante, controladora dos dados de seus colaboradores; (b) com autoridades públicas, quando exigido por lei ou fiscalização; e (c) com fornecedores de infraestrutura estritamente necessários à operação do serviço, sob contrato de confidencialidade.</p>

  <h2>5. Armazenamento e segurança</h2>
  <p>Adotamos medidas técnicas e administrativas para proteger os dados, incluindo controle de acesso por perfis, registro de auditoria de todas as ações, criptografia de senhas e comunicação segura. Os registros de ponto são mantidos pelos prazos exigidos pela legislação trabalhista.</p>

  <h2>6. Direitos do titular</h2>
  <p>Nos termos da LGPD, o titular pode solicitar confirmação do tratamento, acesso, correção, anonimização, portabilidade ou eliminação de dados, quando aplicável, além de informações sobre compartilhamento. Colaboradores de empresas contratantes devem direcionar solicitações primeiramente ao empregador, controlador dos dados; o Cronos, como operador, apoiará o atendimento.</p>

  <h2>7. Cookies</h2>
  <p>Este site utiliza apenas cookies estritamente necessários ao seu funcionamento. Não utilizamos cookies de publicidade ou rastreamento de terceiros.</p>

  <h2>8. Contato</h2>
  <p>Dúvidas ou solicitações sobre esta política podem ser enviadas para <a href="mailto:<?= c('contact_email', 'contato@pontocronos.com.br') ?>"><?= c('contact_email', 'contato@pontocronos.com.br') ?></a>.</p>

  <h2>9. Alterações</h2>
  <p>Esta política pode ser atualizada periodicamente. A versão vigente estará sempre disponível nesta página, com a data da última atualização.</p>
</main>

<footer class="site-footer">
  <div class="wrap footer-bottom">
    <p>© <?= date('Y') ?> Cronos · pontocronos.com.br · Todos os direitos reservados.</p>
    <p>Desenvolvido por <a href="https://qitec.com.br" rel="noopener">QItec</a></p>
  </div>
</footer>

</body>
</html>
