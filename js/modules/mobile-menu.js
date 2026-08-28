export function initMobileMenu() {
  const openBtn = document.querySelector('[data-menu-open]');
  const closeBtn = document.querySelector('[data-menu-close]');
  const menu = document.querySelector('[data-mobile-menu]');

  if (!menu) return;

  function open() {
    menu.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function close() {
    menu.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  if (openBtn) openBtn.addEventListener('click', open);
  if (closeBtn) closeBtn.addEventListener('click', close);

  menu.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', close);
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth >= 980) close();
  });
}
