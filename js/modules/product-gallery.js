export function initProductGallery() {
  const mainImages = document.querySelectorAll('[data-gallery-image]');
  const thumbs = document.querySelectorAll('[data-gallery-thumb]');

  if (!mainImages.length || !thumbs.length) return;

  thumbs.forEach((thumb) => {
    thumb.addEventListener('click', () => {
      const index = thumb.getAttribute('data-gallery-thumb');

      mainImages.forEach((img) => {
        img.classList.toggle('is-active', img.getAttribute('data-gallery-image') === index);
      });

      thumbs.forEach((t) => {
        t.classList.toggle('is-active', t === thumb);
      });
    });
  });
}
