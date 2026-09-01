const AUTOPLAY_DELAY = 6000;

export function initHeroSlider() {
  const root = document.querySelector('[data-hero-slider]');
  if (!root) return;

  const slides = Array.from(root.querySelectorAll('[data-slide]'));
  const dots = Array.from(root.querySelectorAll('[data-slide-dot]'));
  const prevBtn = root.querySelector('[data-slide-prev]');
  const nextBtn = root.querySelector('[data-slide-next]');

  if (slides.length <= 1) {
    if (prevBtn) prevBtn.hidden = true;
    if (nextBtn) nextBtn.hidden = true;
    return;
  }

  let active = slides.findIndex((slide) => slide.classList.contains('is-active'));
  if (active < 0) active = 0;
  let timer = null;

  function show(index) {
    active = (index + slides.length) % slides.length;
    slides.forEach((slide, i) => slide.classList.toggle('is-active', i === active));
    dots.forEach((dot, i) => dot.classList.toggle('is-active', i === active));
  }

  function restartAutoplay() {
    if (timer) clearInterval(timer);
    timer = setInterval(() => show(active + 1), AUTOPLAY_DELAY);
  }

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      show(i);
      restartAutoplay();
    });
  });

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      show(active - 1);
      restartAutoplay();
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      show(active + 1);
      restartAutoplay();
    });
  }

  root.addEventListener('mouseenter', () => timer && clearInterval(timer));
  root.addEventListener('mouseleave', restartAutoplay);

  restartAutoplay();
}
