// Cronos — interações mínimas e intencionais.
// A única animação orquestrada da página (régua de jornada) é feita em CSS puro.

(function () {
  const toggle = document.querySelector('.menu-toggle');
  const menu = document.getElementById('main-menu');
  if (!toggle || !menu) return;

  toggle.addEventListener('click', function () {
    const open = menu.classList.toggle('open');
    toggle.setAttribute('aria-expanded', String(open));
  });

  // Fecha o menu ao clicar em um link (navegação por âncora)
  menu.addEventListener('click', function (e) {
    if (e.target.tagName === 'A' && menu.classList.contains('open')) {
      menu.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
    }
  });

  // Fecha com Esc
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && menu.classList.contains('open')) {
      menu.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.focus();
    }
  });
})();
