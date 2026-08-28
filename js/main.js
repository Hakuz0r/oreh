document.addEventListener('DOMContentLoaded', function () {
  initMobileMenu();
  initContactForm();
  initProductGallery();
});

function initMobileMenu() {
  var openBtn = document.querySelector('[data-menu-open]');
  var closeBtn = document.querySelector('[data-menu-close]');
  var menu = document.querySelector('[data-mobile-menu]');

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

  menu.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', close);
  });

  window.addEventListener('resize', function () {
    if (window.innerWidth >= 980) close();
  });
}

function initContactForm() {
  var form = document.querySelector('[data-contact-form]');
  var success = document.querySelector('[data-contact-success]');
  var resetBtn = document.querySelector('[data-contact-reset]');

  if (!form || !success) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    form.classList.add('is-hidden');
    success.classList.remove('is-hidden');
  });

  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      form.reset();
      success.classList.add('is-hidden');
      form.classList.remove('is-hidden');
    });
  }
}

function initProductGallery() {
  var mainImages = document.querySelectorAll('[data-gallery-image]');
  var thumbs = document.querySelectorAll('[data-gallery-thumb]');

  if (!mainImages.length || !thumbs.length) return;

  thumbs.forEach(function (thumb) {
    thumb.addEventListener('click', function () {
      var index = thumb.getAttribute('data-gallery-thumb');

      mainImages.forEach(function (img) {
        img.classList.toggle('is-active', img.getAttribute('data-gallery-image') === index);
      });

      thumbs.forEach(function (t) {
        t.classList.toggle('is-active', t === thumb);
      });
    });
  });
}
