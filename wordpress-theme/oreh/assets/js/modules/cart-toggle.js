export function initCartToggle() {
  const buttons = document.querySelectorAll('[data-cart-toggle]');
  if (!buttons.length || typeof window.orehCartToggle === 'undefined') return;

  const { ajaxUrl, nonce } = window.orehCartToggle;

  function setCartCount(count) {
    document.querySelectorAll('[data-cart-count]').forEach((el) => {
      el.textContent = String(count);
      el.classList.toggle('is-empty', count === 0);
    });
  }

  buttons.forEach((btn) => {
    btn.addEventListener('click', async () => {
      if (btn.disabled) return;
      btn.disabled = true;

      try {
        const body = new URLSearchParams({
          action: 'oreh_toggle_cart',
          nonce,
          product_id: btn.dataset.productId,
        });

        const response = await fetch(ajaxUrl, { method: 'POST', body });
        const result = await response.json();

        if (result.success) {
          const inCart = result.data.in_cart;
          btn.classList.toggle('is-in-cart', inCart);
          btn.textContent = inCart ? btn.dataset.labelRemove : btn.dataset.labelAdd;
          setCartCount(result.data.count);
        }
      } catch (e) {
        // тихо игнорируем сетевые сбои — кнопка просто разблокируется
      } finally {
        btn.disabled = false;
      }
    });
  });
}
