<?php require __DIR__ . '/cronossite/cronos/config.php'; require_installed(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= c('support_meta_title', 'Suporte — Cronos') ?></title>
<meta name="description" content="<?= c('support_meta_desc', 'Central de suporte do Cronos e do aplicativo Cronos: contato, dúvidas frequentes sobre login, registro de ponto, justificativas e atestados.') ?>">
<link rel="canonical" href="https://pontocronos.com.br/suporte.php">
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
  <h1>Suporte</h1>
  <p>Central de ajuda do <strong>Cronos</strong>, o sistema de gestão de ponto, e do aplicativo Cronos para iPhone e Android.</p>

  <h2>Fale com a gente</h2>
  <ul>
    <li><strong>E-mail:</strong> <a href="mailto:<?= c('contact_email', 'contato@pontocronos.com.br') ?>?subject=Suporte — Cronos"><?= c('contact_email', 'contato@pontocronos.com.br') ?></a></li>
    <li><strong>Telefone:</strong> <?= c('contact_phone', '(11) 5304-3800') ?></li>
  </ul>
  <p>Para agilizar o atendimento, informe o <strong>nome da sua empresa ou órgão</strong>, o seu nome, o modelo do aparelho (iPhone ou Android) e o que aconteceu. Uma foto da tela com o erro ajuda bastante.</p>

  <h2>Antes de nos procurar: fale com o RH ou com o seu gestor</h2>
  <p>O Cronos é contratado pela sua empresa ou órgão público, que administra o seu cadastro. Por isso, dúvidas sobre a <strong>sua jornada</strong>, banco de horas, férias, ajuste de marcação, aprovação de atestado, <strong>liberação de acesso</strong> e <strong>troca de senha</strong> devem ser tratadas primeiro com o RH ou com o administrador do sistema na sua empresa. Eles têm acesso para resolver essas questões. Problemas técnicos do aplicativo ou do sistema são com a gente.</p>

  <h2>Perguntas frequentes</h2>

  <h3>Como entro no aplicativo?</h3>
  <p>Informe o seu <strong>CPF</strong> e a senha cadastrada pela sua empresa. No primeiro acesso, o sistema pode pedir que você crie uma nova senha. Se o CPF não for aceito, o cadastro pode não estar ativo ou não ter o CPF preenchido: fale com o RH.</p>

  <h3>Esqueci minha senha</h3>
  <p>O aplicativo não tem recuperação automática de senha. Peça ao RH ou ao administrador do sistema da sua empresa para redefinir a sua senha; no próximo acesso você escolhe uma nova.</p>

  <h3>Não consigo registrar o ponto</h3>
  <ul>
    <li>Permita o acesso à <strong>localização</strong> e à <strong>câmera</strong> nos ajustes do aparelho. A selfie e a localização fazem parte de cada marcação;</li>
    <li>Mantenha o GPS ligado e aguarde alguns segundos para o aparelho obter a posição;</li>
    <li>Se o aplicativo avisar que você está <strong>fora do perímetro</strong> permitido, a marcação depende da regra definida pela sua empresa. Fale com o seu gestor;</li>
    <li>Feche e abra o aplicativo, e confira se ele está atualizado na loja.</li>
  </ul>

  <h3>Estou sem internet. O que acontece com o meu ponto?</h3>
  <p>A marcação fica guardada no aparelho e é enviada automaticamente quando a conexão voltar. Um aviso aparece no aplicativo enquanto houver registros aguardando envio. Mantenha o aplicativo instalado até que todos sejam enviados.</p>

  <h3>Uma marcação ficou pendente ou foi rejeitada</h3>
  <p>No <strong>Histórico</strong> do aplicativo, as marcações aparecem como pendentes, enviadas ou rejeitadas. Nas rejeitadas, o motivo é mostrado e você pode tentar enviar de novo. Se o problema continuar, procure o RH para solicitar um ajuste de ponto.</p>

  <h3>Como envio um atestado ou uma justificativa?</h3>
  <p>No aplicativo, abra <strong>Justificativas</strong>, escolha o tipo, informe o período e o motivo e anexe uma foto ou um PDF de até 2 MB. O arquivo é armazenado de forma criptografada e a solicitação é analisada pelo gestor ou, no caso de atestado médico, pelo médico responsável. O andamento aparece na própria tela.</p>

  <h3>Quero saber como meus dados são tratados</h3>
  <p>Consulte a nossa <a href="privacidade.php">Política de Privacidade</a>. Solicitações de titulares de dados, nos termos da LGPD, também podem ser enviadas para o e-mail acima.</p>

  <h2>Sua empresa quer conhecer ou contratar o Cronos?</h2>
  <p>Escreva para <a href="mailto:<?= c('contact_email', 'contato@pontocronos.com.br') ?>?subject=Solicitação de demonstração — Cronos"><?= c('contact_email', 'contato@pontocronos.com.br') ?></a> e agendamos uma demonstração.</p>
</main>

<footer class="site-footer">
  <div class="wrap footer-bottom">
    <p>© <?= date('Y') ?> Cronos · pontocronos.com.br · Todos os direitos reservados.</p>
    <p>Desenvolvido por <a href="https://qitec.com.br" rel="noopener">QItec</a></p>
  </div>
</footer>

</body>
</html>
