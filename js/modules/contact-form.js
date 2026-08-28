export function initContactForm() {
  const form = document.querySelector('[data-contact-form]');
  const success = document.querySelector('[data-contact-success]');
  const resetBtn = document.querySelector('[data-contact-reset]');

  if (!form || !success) return;

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    form.classList.add('is-hidden');
    success.classList.remove('is-hidden');
  });

  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      form.reset();
      success.classList.add('is-hidden');
      form.classList.remove('is-hidden');
    });
  }
}
