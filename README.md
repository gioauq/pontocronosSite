# Cronos — Landing Page + Painel Admin

Site institucional do Cronos (pontocronos.com.br) com painel administrativo
para edição de conteúdo. Backend próprio em PHP 8 + SQLite — funciona em
qualquer hospedagem compartilhada (Hostinger, Locaweb, HostGator etc.)
sem instalar nada além do PHP.

Estrutura em duas partes de propósito: as páginas públicas ficam na raiz do
domínio (URL curta, boa para SEO); o backend do CMS (config, instalador,
banco e painel admin) fica um nível mais escondido em `cronossite/cronos/`
— o mesmo tipo de obscuridade recomendada para a pasta `admin/` isolada, só
que aplicada à árvore inteira do backend.

## Estrutura

```
/                                 pontocronos.com.br/
├── index.php                     Landing page (lê conteúdo do banco)
├── privacidade.php               Política de privacidade (LGPD)
├── styles.css                    Estilos
├── script.js                     JS (menu mobile)
├── sitemap.xml                   Sitemap para o Google
├── robots.txt                    Regras para buscadores
├── .htaccess                     Proteções e cache (protege também data/ abaixo)
├── fonts/                        Bricolage Grotesque, IBM Plex Sans/Mono (auto-hospedadas, .woff2)
└── cronossite/cronos/            pontocronos.com.br/cronossite/cronos/
    ├── config.php                 Configuração e funções compartilhadas
    ├── install.php                Instalador (usar UMA vez e apagar)
    ├── admin/
    │   └── index.php              Painel: pontocronos.com.br/cronossite/cronos/admin
    └── data/                      Banco SQLite (criado pelo instalador; protegido)
```

`index.php` e `privacidade.php` (na raiz) carregam `config.php` de dentro de
`cronossite/cronos/` — é a única ponte entre as duas partes; tudo o mais em
cada uma delas é independente.

## Instalação

1. Envie a pasta inteira (mantendo a estrutura acima) para a raiz do seu
   domínio (public_html).
2. Confirme que o PHP é 8.0+ com a extensão `pdo_sqlite` (padrão na
   maioria das hospedagens).
3. Acesse `https://pontocronos.com.br/cronossite/cronos/install.php`,
   defina usuário e senha do admin (mínimo 10 caracteres).
4. **Apague o arquivo `install.php` do servidor.**
5. Pronto: site em `/` e painel em `/cronossite/cronos/admin/`.

## Painel administrativo (/cronossite/cronos/admin/)

- Login com senha criptografada (bcrypt) e sessão segura (httponly, samesite).
- Bloqueio automático após 5 tentativas erradas em 15 minutos.
- Proteção CSRF em todos os formulários.
- Edita: títulos, subtítulos, textos, CTAs, contatos, meta title e
  meta description (SEO) de todas as seções do site.
- Troca de senha dentro do próprio painel.

## SEO — o que já está no código

- Meta title/description editáveis, canonical, Open Graph, Twitter Card.
- Dados estruturados JSON-LD: Organization, SoftwareApplication e
  FAQPage (gera rich snippets de perguntas no Google) — as perguntas vêm do
  mesmo conteúdo editável exibido na seção de FAQ, sem duplicação.
- HTML semântico, hierarquia de headings, sitemap.xml e robots.txt.
- Fontes auto-hospedadas (`fonts/*.woff2`), sem CDN de terceiro: carregamento
  leve, boa nota no Lighthouse, sem dependência externa.

## SEO — o que fazer depois de publicar (fora do código)

1. Ativar HTTPS na hospedagem e descomentar o redirect no `.htaccess`.
2. Cadastrar o site no **Google Search Console** e enviar o sitemap.
3. Criar o **Google Business Profile** da empresa.
4. Publicar conteúdo periódico (blog): Portaria 671, banco de horas,
   escala 12x36 — termos de cauda longa onde é possível rankear rápido.
5. Buscar backlinks de parceiros, diretórios de software e imprensa local.

## Segurança

- Nunca deixe o `install.php` no servidor após a instalação.
- O banco em `cronossite/cronos/data/` é bloqueado pelo `.htaccess` da raiz
  (a regra de bloqueio de `.sqlite` vale para toda a árvore abaixo dela,
  Apache/LiteSpeed). Em Nginx, adicione: `location ~ \.sqlite$ { deny all; }`.
- Faça backup periódico do arquivo `cronossite/cronos/data/cronos.sqlite`.

## Relação com o sistema Cronos (Laravel)

Este site institucional é uma aplicação **separada** do sistema de gestão de
ponto (o produto Cronos em si, feito em Laravel). Na configuração local/de
produção combinada:

- `pontocronos.com.br/` → este site (institucional + CMS)
- `pontocronos.com.br/brumadinho` → PWA de autoatendimento do servidor
  (painel Filament do Laravel, cliente "Brumadinho")
- `pontocronos.com.br/brumadinhoadmin` → painel administrativo do sistema
  (gestão de ponto, escalas, folha — cliente "Brumadinho")

As duas últimas rotas são servidas pela aplicação Laravel (outro projeto,
`Cronos/public/`), mapeadas via `Alias` do Apache — ver
`httpd-vhosts.conf`. Para um novo cliente futuro, replique os dois
`Alias` com o novo prefixo (ex.: `/novocliente`, `/novoclienteadmin`)
apontando para a mesma (ou uma nova) instância do Laravel.
