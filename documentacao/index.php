<?php require __DIR__ . '/../cronossite/cronos/config.php'; require_installed(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= c('docs_meta_title', 'Documentação da API — Cronos') ?></title>
<meta name="description" content="<?= c('docs_meta_desc', 'Referência da API de integração de folha de pagamento do Cronos: autenticação, escopos e endpoints para o sistema parceiro consumir dados de ponto e apuração.') ?>">
<link rel="canonical" href="https://pontocronos.com.br/documentacao/">
<meta name="robots" content="noindex,follow">
<link rel="stylesheet" href="/styles.css">
</head>
<body>

<header class="site-header">
  <div class="wrap header-inner">
    <a class="logo" href="/" aria-label="Cronos — página inicial">
      <span class="logo-mark" aria-hidden="true">◷</span>Cronos
    </a>
    <nav aria-label="Navegação principal">
      <ul class="menu" style="display:flex">
        <li><a href="/">← Voltar ao site</a></li>
      </ul>
    </nav>
  </div>
</header>

<section class="docs-hero">
  <div class="wrap">
    <p class="eyebrow">Documentação técnica</p>
    <h1>API de integração de folha de pagamento</h1>
    <p class="hero-sub">Referência para o sistema de folha parceiro ler, direto do Cronos, os dados de ponto, jornada, banco de horas, afastamentos e apuração — sem exportação manual de planilha.</p>

    <dl class="docs-quickref">
      <div><dt>URL base</dt><dd>pontocronos.com.br/api/v1</dd></div>
      <div><dt>Autenticação</dt><dd>Header X-API-Key</dd></div>
      <div><dt>Formato</dt><dd>JSON</dd></div>
      <div><dt>Limite</dt><dd>120 req/min por chave</dd></div>
    </dl>
  </div>
</section>

<div class="wrap docs-body">
  <div class="docs-toc-wrap">
    <button class="docs-toc-toggle" id="tocToggle" type="button" aria-expanded="false" aria-controls="tocNav">
      <span>Sumário</span>
      <span aria-hidden="true">▾</span>
    </button>
    <nav class="docs-toc" id="tocNav" aria-label="Sumário da documentação">
      <a href="#visao-geral">Visão geral</a>
      <a href="#autenticacao">Autenticação</a>
      <a href="#escopos">Escopos</a>
      <a href="#paginacao">Paginação e filtros</a>
      <a href="#erros">Erros</a>
      <a href="#limites">Limite de requisições</a>
      <span class="toc-label">Endpoints</span>
      <a class="ep" href="#ep-company"><span class="m">GET</span>Empresa</a>
      <a class="ep" href="#ep-work-units"><span class="m">GET</span>Unidades</a>
      <a class="ep" href="#ep-employees"><span class="m">GET</span>Funcionários</a>
      <a class="ep" href="#ep-employee-show"><span class="m">GET</span>Funcionário</a>
      <a class="ep" href="#ep-work-schedules"><span class="m">GET</span>Jornadas</a>
      <a class="ep" href="#ep-time-records"><span class="m">GET</span>Marcações</a>
      <a class="ep" href="#ep-time-adjustments"><span class="m">GET</span>Ajustes</a>
      <a class="ep" href="#ep-absences"><span class="m">GET</span>Afastamentos</a>
      <a class="ep" href="#ep-time-bank"><span class="m">GET</span>Banco de horas</a>
      <a class="ep" href="#ep-daily-apurations"><span class="m">GET</span>Apuração diária</a>
      <a class="ep" href="#ep-payroll-summary"><span class="m">GET</span>Resumo de folha</a>
      <a class="ep" href="#ep-sync-logs"><span class="m">GET</span>Sincronização</a>
    </nav>
  </div>

  <div class="docs-content">

    <section class="docs-section" id="visao-geral">
      <h2>Visão geral</h2>
      <p class="section-intro">Todos os endpoints são de leitura (<code class="mono">GET</code>) e devolvem JSON. A API é multi-tenant: cada chave enxerga só os dados da prefeitura ou empresa a que pertence — nunca de outro cliente Cronos.</p>

      <h3 class="sub">Como começar</h3>
      <ol class="docs-steps">
        <li>
          <span class="func-num">1</span>
          <p>Peça ao administrador do Cronos pra criar uma chave em <strong>Integrações → Chaves de API</strong>, com os escopos que sua integração precisa.</p>
        </li>
        <li>
          <span class="func-num">2</span>
          <p>Guarde a chave com segurança assim que ela for gerada — ela só é exibida em texto puro naquele momento, uma única vez.</p>
        </li>
        <li>
          <span class="func-num">3</span>
          <p>Envie a chave no header <code class="mono">X-API-Key</code> em toda requisição.</p>
        </li>
        <li>
          <span class="func-num">4</span>
          <p>Teste com uma chamada simples: <code class="mono">GET /company</code>.</p>
        </li>
      </ol>
    </section>

    <section class="docs-section" id="autenticacao">
      <h2>Autenticação</h2>
      <p class="section-intro">Toda requisição precisa do header <code class="mono">X-API-Key</code> com a chave completa. Não existe login e senha — a chave já identifica a integração e a empresa.</p>

      <div class="code-panel">
        <span class="lbl">Requisição</span>
        <pre>curl https://pontocronos.com.br/api/v1/company \
  -H "X-API-Key: cronos_live_••••••••••••••••••••••••••••••••••••••••"</pre>
      </div>

      <div class="callout">
        <strong>A chave nunca é reemitida em texto puro.</strong> Se for perdida, peça ao administrador pra <em>rotacionar</em> a chave — uma nova é gerada e a antiga segue válida durante a transição, até ser revogada manualmente. Assim sua integração não para no meio da troca.
      </div>
    </section>

    <section class="docs-section" id="escopos">
      <h2>Escopos</h2>
      <p class="section-intro">Cada chave é liberada só para os escopos que ela precisa. Um pedido a um endpoint fora do escopo da chave recebe <code class="mono">403</code>.</p>

      <div class="docs-table-wrap">
        <table class="docs-table">
          <thead><tr><th>Escopo</th><th>Libera acesso a</th></tr></thead>
          <tbody>
            <tr><td><code>company:read</code></td><td>Empresa e unidades de trabalho</td></tr>
            <tr><td><code>employees:read</code></td><td>Funcionários (cadastro completo)</td></tr>
            <tr><td><code>work_schedules:read</code></td><td>Jornadas e escalas</td></tr>
            <tr><td><code>time_records:read</code></td><td>Marcações de ponto</td></tr>
            <tr><td><code>time_adjustments:read</code></td><td>Ajustes e justificativas de ponto</td></tr>
            <tr><td><code>absences:read</code></td><td>Afastamentos, férias e ocorrências</td></tr>
            <tr><td><code>time_bank:read</code></td><td>Lançamentos de banco de horas</td></tr>
            <tr><td><code>daily_apurations:read</code></td><td>Apuração diária (extras, faltas, atrasos)</td></tr>
            <tr><td><code>payroll:read</code></td><td>Resumo consolidado de folha</td></tr>
            <tr><td><code>sync:read</code></td><td>Histórico de uso da própria chave</td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <section class="docs-section" id="paginacao">
      <h2>Paginação e filtros</h2>
      <p class="section-intro">Toda listagem devolve <code class="mono">data</code> (os registros), <code class="mono">links</code> (navegação) e <code class="mono">meta</code> (contagens). Controle com <code class="mono">page</code> e <code class="mono">per_page</code> (máximo 100, padrão 25).</p>

      <div class="code-panel">
        <span class="lbl">Resposta paginada</span>
        <pre>{
  "data": [ ... ],
  "links": { "first": "…", "last": "…", "prev": null, "next": "…" },
  "meta": { "current_page": 1, "per_page": 25, "total": 143, "last_page": 6 }
}</pre>
      </div>

      <p>Endpoints com histórico (marcações, ajustes, afastamentos, apuração diária e resumo de folha) exigem <code class="mono">date_from</code> e <code class="mono">date_to</code> (formato <code class="mono">AAAA-MM-DD</code>). A maioria também aceita <code class="mono">employee_id</code> pra filtrar um único funcionário — os parâmetros de cada endpoint estão na referência abaixo.</p>
    </section>

    <section class="docs-section" id="erros">
      <h2>Erros</h2>
      <p class="section-intro">Todo erro segue o mesmo formato, independente da causa:</p>

      <div class="code-panel">
        <span class="lbl">Envelope de erro</span>
        <pre>{
  "error": {
    "code": "validation_error",
    "message": "The date from field is required.",
    "fields": { "date_from": ["The date from field is required."] }
  }
}</pre>
      </div>
      <p class="note" style="margin-top:-.6rem;"><code class="mono">fields</code> só aparece em erros de validação (422).</p>

      <div class="docs-table-wrap">
        <table class="docs-table">
          <thead><tr><th>HTTP</th><th>Código</th><th>Motivo</th></tr></thead>
          <tbody>
            <tr><td>401</td><td><code>unauthorized</code></td><td>Chave ausente, inválida, revogada ou expirada</td></tr>
            <tr><td>403</td><td><code>missing_scope</code></td><td>A chave não tem o escopo exigido por este endpoint</td></tr>
            <tr><td>404</td><td><code>not_found</code></td><td>Registro inexistente, ou de outra empresa</td></tr>
            <tr><td>422</td><td><code>validation_error</code></td><td>Parâmetro obrigatório ausente ou inválido</td></tr>
            <tr><td>429</td><td><code>too_many_requests</code></td><td>Limite de requisições excedido</td></tr>
            <tr><td>500</td><td><code>server_error</code></td><td>Erro interno — se persistir, entre em contato</td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <section class="docs-section" id="limites">
      <h2>Limite de requisições</h2>
      <p class="section-intro"><strong>120 requisições por minuto</strong>, contadas por chave — não por IP. Acima disso, a resposta é <code class="mono">429 too_many_requests</code> até a janela renovar. Pensado pra uma rotina em lote ler o volume de um fechamento de folha inteiro sem esbarrar no limite; não é voltado a tráfego interativo de usuário.</p>
    </section>

    <section class="docs-section" id="referencia">
      <h2>Referência de endpoints</h2>
      <p class="section-intro">Todos abaixo aceitam <code class="mono">page</code>/<code class="mono">per_page</code>, exceto onde indicado. Os exemplos de resposta usam dados fictícios.</p>

      <article class="endpoint" id="ep-company">
        <div class="endpoint-head">
          <span class="pill pill-get">GET</span>
          <span class="endpoint-path">/company</span>
          <span class="pill pill-scope">company:read</span>
        </div>
        <p class="desc">Dados cadastrais da empresa e as configurações que explicam como a folha é calculada: códigos de verba, multiplicadores de hora extra e adicional noturno.</p>
        <div class="code-panel">
          <span class="lbl">Resposta</span>
          <pre>{
  "data": {
    "name": "Prefeitura Municipal de Exemplo",
    "cnpj": "12.345.678/0001-90",
    "address": "Praça Central, 100",
    "collective_agreement_number": "SIND-2026-004",
    "tolerance_minutes": 5,
    "day_break_time": "00:00:00",
    "payroll_codes": {
      "overtime_50": "101", "overtime_100": "102", "absence": "103",
      "delay": "104", "night_shift": "105", "dsr_loss": "106"
    },
    "overtime_multipliers": { "weekday": 1.5, "saturday": 1.5, "sunday": 2, "holiday": 2, "rest_day": 2 },
    "night_shift": { "start_time": "22:00:00", "end_time": "05:00:00", "additional_percent": 20 },
    "interjornada_minimum_hours": 11
  }
}</pre>
        </div>
      </article>

      <article class="endpoint" id="ep-work-units">
        <div class="endpoint-head">
          <span class="pill pill-get">GET</span>
          <span class="endpoint-path">/work-units</span>
          <span class="pill pill-scope">company:read</span>
        </div>
        <p class="desc">Unidades de lotação — sedes, postos, secretarias. Coordenadas de geofence não são expostas; não têm utilidade pra folha.</p>
        <div class="code-panel">
          <span class="lbl">Resposta (item de data[])</span>
          <pre>{ "id": 4, "name": "Secretaria de Obras", "type": "Sede", "address": "Rua das Flores, 55" }</pre>
        </div>
      </article>

      <article class="endpoint" id="ep-employees">
        <div class="endpoint-head">
          <span class="pill pill-get">GET</span>
          <span class="endpoint-path">/employees</span>
          <span class="pill pill-scope">employees:read</span>
        </div>
        <p class="desc">Cadastro dos funcionários — os dados necessários pra identificá-los na folha, CPF, RG, CTPS e PIS inclusos.</p>
        <div class="docs-table-wrap">
          <table class="docs-table">
            <thead><tr><th>Parâmetro</th><th></th><th>Descrição</th></tr></thead>
            <tbody>
              <tr><td><code>is_active</code></td><td><span class="pill pill-opt">opcional</span></td><td>Filtra por ativo (<code>true</code>/<code>false</code>)</td></tr>
              <tr><td><code>updated_since</code></td><td><span class="pill pill-opt">opcional</span></td><td>Só cadastros alterados a partir desta data</td></tr>
            </tbody>
          </table>
        </div>
        <div class="code-panel">
          <span class="lbl">Resposta (item de data[])</span>
          <pre>{
  "id": 1042, "name": "Marcos Andrade Silva", "social_name": null,
  "registration_number": "MAT-1042",
  "cpf": "123.456.789-00", "rg": "12.345.678-9", "ctps": "123456", "pis": "12345678900",
  "email": "marcos.silva@exemplo.gov.br",
  "job_title": "Analista Administrativo", "department": "Secretaria de Obras",
  "cost_center": "SEC-OBRAS-01", "work_regime": "CLT",
  "admission_date": "2019-03-01", "termination_date": null, "is_active": true,
  "hourly_rate": 22.5,
  "work_unit": { "id": 4, "name": "Secretaria de Obras" },
  "work_schedule": { "id": 2, "name": "Escala 8h-17h" },
  "updated_at": "2026-09-10T14:22:00-03:00"
}</pre>
        </div>
      </article>

      <article class="endpoint" id="ep-employee-show">
        <div class="endpoint-head">
          <span class="pill pill-get">GET</span>
          <span class="endpoint-path">/employees/{id}</span>
          <span class="pill pill-scope">employees:read</span>
        </div>
        <p class="desc">Um único funcionário, mesmos campos da listagem. <code>404</code> se o id não existir ou for de outra empresa.</p>
      </article>

      <article class="endpoint" id="ep-work-schedules">
        <div class="endpoint-head">
          <span class="pill pill-get">GET</span>
          <span class="endpoint-path">/work-schedules</span>
          <span class="pill pill-scope">work_schedules:read</span>
        </div>
        <p class="desc">Jornadas e escalas cadastradas, incluindo turno duplo e revezamento (12x36, 24x72 etc).</p>
        <div class="code-panel">
          <span class="lbl">Resposta (item de data[])</span>
          <pre>{
  "id": 2, "name": "Escala 8h-17h", "days_of_week": [1, 2, 3, 4, 5],
  "start_time": "08:00:00", "end_time": "17:00:00",
  "break_start": "12:00:00", "break_end": "13:00:00",
  "flexible": false, "is_double_shift": false,
  "second_start_time": null, "second_end_time": null, "is_mobile": false,
  "rotation_cycle": null, "cycle_work_days": null, "cycle_rest_days": null
}</pre>
        </div>
      </article>

      <article class="endpoint" id="ep-time-records">
        <div class="endpoint-head">
          <span class="pill pill-get">GET</span>
          <span class="endpoint-path">/time-records</span>
          <span class="pill pill-scope">time_records:read</span>
        </div>
        <p class="desc">Marcações de ponto batidas — app, portal web ou relógio facial.</p>
        <div class="docs-table-wrap">
          <table class="docs-table">
            <thead><tr><th>Parâmetro</th><th></th><th>Descrição</th></tr></thead>
            <tbody>
              <tr><td><code>date_from</code></td><td><span class="pill pill-req">obrigatório</span></td><td>Início do período (AAAA-MM-DD)</td></tr>
              <tr><td><code>date_to</code></td><td><span class="pill pill-req">obrigatório</span></td><td>Fim do período</td></tr>
              <tr><td><code>employee_id</code></td><td><span class="pill pill-opt">opcional</span></td><td>Restringe a um funcionário</td></tr>
            </tbody>
          </table>
        </div>
        <div class="code-panel">
          <span class="lbl">Resposta (item de data[])</span>
          <pre>{
  "id": 88213, "employee_id": 1042, "type": "entrada",
  "recorded_at": "2026-07-21T08:02:11-03:00",
  "latitude": -19.9227, "longitude": -43.9451,
  "source": "hikvision", "is_offline": false
}</pre>
        </div>
        <p class="note">
          <code>type</code>: entrada, saida, pausa_entrada (saída pro intervalo), pausa_saida (retorno do intervalo).
          <code>source</code>: mobile, portal, hikvision, manual, system.
        </p>
      </article>

      <article class="endpoint" id="ep-time-adjustments">
        <div class="endpoint-head">
          <span class="pill pill-get">GET</span>
          <span class="endpoint-path">/time-adjustments</span>
          <span class="pill pill-scope">time_adjustments:read</span>
        </div>
        <p class="desc">Correções manuais de marcação (esquecimento de bater ponto etc), com o fluxo de aprovação.</p>
        <div class="docs-table-wrap">
          <table class="docs-table">
            <thead><tr><th>Parâmetro</th><th></th><th>Descrição</th></tr></thead>
            <tbody>
              <tr><td><code>date_from</code> / <code>date_to</code></td><td><span class="pill pill-req">obrigatório</span></td><td>Período de <code>requested_at</code></td></tr>
              <tr><td><code>employee_id</code></td><td><span class="pill pill-opt">opcional</span></td><td>Restringe a um funcionário</td></tr>
              <tr><td><code>status</code></td><td><span class="pill pill-opt">opcional</span></td><td>pending, approved ou rejected</td></tr>
            </tbody>
          </table>
        </div>
        <div class="code-panel">
          <span class="lbl">Resposta (item de data[])</span>
          <pre>{
  "id": 512, "employee_id": 1042, "type": "entrada",
  "original_time": null, "new_time": "2026-07-15T08:00:00-03:00",
  "reason": "Esqueceu de bater o ponto na chegada",
  "status": "approved", "requested_at": "2026-07-15T10:30:00-03:00",
  "approver": { "id": 3, "name": "Ana Paula Reis" }
}</pre>
        </div>
      </article>

      <article class="endpoint" id="ep-absences">
        <div class="endpoint-head">
          <span class="pill pill-get">GET</span>
          <span class="endpoint-path">/absences</span>
          <span class="pill pill-scope">absences:read</span>
        </div>
        <p class="desc">Férias, licenças médicas, abonos e demais afastamentos. Devolve quem se sobrepõe ao período pedido — inclusive um afastamento que começou antes de <code>date_from</code> mas segue em andamento.</p>
        <div class="docs-table-wrap">
          <table class="docs-table">
            <thead><tr><th>Parâmetro</th><th></th><th>Descrição</th></tr></thead>
            <tbody>
              <tr><td><code>date_from</code> / <code>date_to</code></td><td><span class="pill pill-req">obrigatório</span></td><td>Período de sobreposição</td></tr>
              <tr><td><code>employee_id</code></td><td><span class="pill pill-opt">opcional</span></td><td>Restringe a um funcionário</td></tr>
              <tr><td><code>type</code></td><td><span class="pill pill-opt">opcional</span></td><td>vacation, medical, business_trip, breastfeeding, course, leave, allowance, justification, other</td></tr>
              <tr><td><code>status</code></td><td><span class="pill pill-opt">opcional</span></td><td>pending, approved ou rejected</td></tr>
            </tbody>
          </table>
        </div>
        <div class="code-panel">
          <span class="lbl">Resposta (item de data[])</span>
          <pre>{
  "id": 77, "employee_id": 1042, "type": "vacation", "status": "approved",
  "start_date": "2026-07-10", "end_date": "2026-07-24",
  "start_time": null, "end_time": null,
  "reason": null, "approver": { "id": 3, "name": "Ana Paula Reis" }
}</pre>
        </div>
      </article>

      <article class="endpoint" id="ep-time-bank">
        <div class="endpoint-head">
          <span class="pill pill-get">GET</span>
          <span class="endpoint-path">/time-bank</span>
          <span class="pill pill-scope">time_bank:read</span>
        </div>
        <p class="desc">Lançamentos do banco de horas — cada linha é um lançamento (crédito, débito ou compensação automática), não um saldo único. Some <code>balance_minutes</code> dos lançamentos de um funcionário pra obter o saldo consolidado.</p>
        <div class="docs-table-wrap">
          <table class="docs-table">
            <thead><tr><th>Parâmetro</th><th></th><th>Descrição</th></tr></thead>
            <tbody>
              <tr><td><code>employee_id</code></td><td><span class="pill pill-opt">opcional</span></td><td>Restringe a um funcionário</td></tr>
              <tr><td><code>date_from</code> / <code>date_to</code></td><td><span class="pill pill-opt">opcional</span></td><td>Filtra por <code>validity_date</code></td></tr>
            </tbody>
          </table>
        </div>
        <div class="code-panel">
          <span class="lbl">Resposta (item de data[])</span>
          <pre>{
  "id": 3301, "employee_id": 1042, "balance_minutes": 120,
  "validity_date": "2026-07-31", "compensated_at": null, "note": null
}</pre>
        </div>
      </article>

      <article class="endpoint" id="ep-daily-apurations">
        <div class="endpoint-head">
          <span class="pill pill-get">GET</span>
          <span class="endpoint-path">/daily-apurations</span>
          <span class="pill pill-scope">daily_apurations:read</span>
        </div>
        <p class="desc">Apuração diária consolidada por funcionário: minutos trabalhados, extras, atraso, falta, intervalo irregular e adicional noturno — dia a dia.</p>
        <div class="docs-table-wrap">
          <table class="docs-table">
            <thead><tr><th>Parâmetro</th><th></th><th>Descrição</th></tr></thead>
            <tbody>
              <tr><td><code>date_from</code> / <code>date_to</code></td><td><span class="pill pill-req">obrigatório</span></td><td>Período</td></tr>
              <tr><td><code>employee_id</code></td><td><span class="pill pill-opt">opcional</span></td><td>Restringe a um funcionário</td></tr>
            </tbody>
          </table>
        </div>
        <div class="code-panel">
          <span class="lbl">Resposta (item de data[])</span>
          <pre>{
  "id": 60142, "employee_id": 1042, "date": "2026-07-21",
  "is_scheduled_workday": true, "is_holiday": false,
  "is_absence": false, "is_falta": false,
  "expected_minutes": 480, "worked_minutes": 540,
  "late_minutes": 0, "early_leave_minutes": 0, "irregular_break_minutes": 0,
  "night_minutes": 0, "interjornada_violation_minutes": 0,
  "has_inconsistency": false, "notes": [],
  "trigger_source": "scheduled", "calculated_at": "2026-07-22T03:00:04-03:00"
}</pre>
        </div>
        <div class="callout">
          <strong>Não é um registro fechado.</strong> A apuração é recalculada sempre que algo muda no dia — por exemplo, a aprovação retroativa de um ajuste. Dois pedidos pro mesmo dia podem trazer valores diferentes se algo mudou entre eles. <code>calculated_at</code> mostra quando esta versão foi gerada.
        </div>
      </article>

      <article class="endpoint" id="ep-payroll-summary">
        <div class="endpoint-head">
          <span class="pill pill-get">GET</span>
          <span class="endpoint-path">/payroll-summary</span>
          <span class="pill pill-scope">payroll:read</span>
        </div>
        <p class="desc">O endpoint principal pra fechar a folha: resumo por funcionário já pronto pra lançar — horas extras (50%/100%), atraso, adicional noturno, faltas e DSR perdido, em horas e, quando o funcionário tem valor-hora cadastrado, também em reais.</p>
        <div class="docs-table-wrap">
          <table class="docs-table">
            <thead><tr><th>Parâmetro</th><th></th><th>Descrição</th></tr></thead>
            <tbody>
              <tr><td><code>date_from</code> / <code>date_to</code></td><td><span class="pill pill-req">obrigatório</span></td><td>Período de apuração</td></tr>
              <tr><td><code>employee_id</code></td><td><span class="pill pill-opt">opcional</span></td><td>Sem isso, devolve todos os ativos — paginado, nunca tudo de uma vez</td></tr>
            </tbody>
          </table>
        </div>
        <div class="code-panel">
          <span class="lbl">Resposta</span>
          <pre>{
  "data": [
    {
      "employee": { "id": 1042, "name": "Marcos Andrade Silva", "registration_number": "MAT-1042" },
      "hourly_rate": 22.5,
      "overtime_50_hours": 6.5, "overtime_50_value": 219.38,
      "overtime_100_hours": 0, "overtime_100_value": 0,
      "delay_hours": 0.25, "delay_value": 5.63,
      "night_hours": 0, "night_value": 0,
      "absence_days": 1, "dsr_lost_days": 0
    }
  ],
  "period": { "date_from": "2026-07-01", "date_to": "2026-07-31" },
  "meta": { "current_page": 1, "per_page": 25, "total": 118, "last_page": 5 }
}</pre>
        </div>
        <div class="callout">
          <strong>Valores em reais só quando há valor-hora cadastrado.</strong> Sem <code>hourly_rate</code> no funcionário, os campos <code>*_value</code> vêm <code>null</code> — use <code>*_hours</code> nesse caso.
        </div>
      </article>

      <article class="endpoint" id="ep-sync-logs">
        <div class="endpoint-head">
          <span class="pill pill-get">GET</span>
          <span class="endpoint-path">/sync-logs</span>
          <span class="pill pill-scope">sync:read</span>
        </div>
        <p class="desc">Histórico de uso da própria chave que está autenticando a chamada — útil pra auditar e depurar sua integração. Nunca mostra o uso de outras chaves.</p>
        <div class="docs-table-wrap">
          <table class="docs-table">
            <thead><tr><th>Parâmetro</th><th></th><th>Descrição</th></tr></thead>
            <tbody>
              <tr><td><code>date_from</code> / <code>date_to</code></td><td><span class="pill pill-opt">opcional</span></td><td>Filtra pela data da requisição</td></tr>
            </tbody>
          </table>
        </div>
        <div class="code-panel">
          <span class="lbl">Resposta (item de data[])</span>
          <pre>{
  "id": 90311, "method": "GET", "path": "/api/v1/payroll-summary",
  "status_code": 200, "ip_address": "203.0.113.42",
  "user_agent": "FolhaXYZ-Integracao/1.0",
  "duration_ms": 184, "created_at": "2026-07-31T22:05:11-03:00"
}</pre>
        </div>
      </article>

    </section>
  </div>
</div>

<footer class="site-footer">
  <div class="wrap footer-grid">
    <div>
      <p class="logo footer-logo"><span class="logo-mark" aria-hidden="true">◷</span>Cronos</p>
      <p class="footer-note"><?= c('footer_note', 'Sistema de ponto eletrônico, escalas e controle de jornada.') ?></p>
    </div>
    <nav aria-label="Links do rodapé">
      <ul>
        <li><a href="/#funcionalidades">Funcionalidades</a></li>
        <li><a href="/#beneficios">Benefícios</a></li>
        <li><a href="/privacidade.php">Política de privacidade</a></li>
      </ul>
    </nav>
    <div>
      <p class="footer-contact-title">Contato</p>
      <p><a href="mailto:<?= c('contact_email') ?>"><?= c('contact_email', 'contato@pontocronos.com.br') ?></a></p>
      <p><?= c('contact_phone', '(31) 0000-0000') ?></p>
    </div>
  </div>
  <div class="wrap footer-bottom">
    <p>© <?= date('Y') ?> Cronos · pontocronos.com.br · Todos os direitos reservados. · CNPJ: <?= c('contact_cnpj', '67.223.417/0001-94') ?></p>
    <p>Desenvolvido por <a href="https://qitec.com.br" rel="noopener">QItec</a></p>
  </div>
</footer>

<script>
(function () {
  var toggle = document.getElementById('tocToggle');
  var nav = document.getElementById('tocNav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  var links = Array.prototype.slice.call(document.querySelectorAll('.docs-toc a'));
  var targets = links.map(function (a) {
    var el = document.getElementById(a.getAttribute('href').slice(1));
    return el ? { link: a, el: el } : null;
  }).filter(Boolean);

  if ('IntersectionObserver' in window && targets.length) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        var match = targets.find(function (t) { return t.el === entry.target; });
        if (match && entry.isIntersecting) {
          links.forEach(function (l) { l.classList.remove('active'); });
          match.link.classList.add('active');
        }
      });
    }, { rootMargin: '-10% 0px -70% 0px', threshold: 0 });
    targets.forEach(function (t) { observer.observe(t.el); });
  }
})();
</script>
</body>
</html>
