import { initMobileMenu } from './modules/mobile-menu.js';
import { initProductGallery } from './modules/product-gallery.js';
import { initHeroSlider } from './modules/hero-slider.js';
import { initCartToggle } from './modules/cart-toggle.js';

document.addEventListener('DOMContentLoaded', () => {
  initMobileMenu();
  initProductGallery();
  initHeroSlider();
  initCartToggle();
});
