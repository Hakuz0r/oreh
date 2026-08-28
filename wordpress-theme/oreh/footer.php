<?php
if (!defined('ABSPATH')) exit;
?>
<footer class="footer">
  <div class="footer__inner">
    <div class="footer__brand">
      <picture>
        <source srcset="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-light.webp'); ?>" type="image/webp" />
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-light.png'); ?>" alt="<?php bloginfo('name'); ?>" class="footer__logo" loading="lazy" />
      </picture>
      <span class="footer__brand-text"><?php bloginfo('name'); ?></span>
    </div>
    <?php
    wp_nav_menu([
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'footer__nav',
        'fallback_cb'    => 'oreh_default_menu',
    ]);
    ?>
    <div class="footer__contacts">
      <a href="tel:<?php echo esc_attr(oreh_phone_href()); ?>" class="footer__phone"><?php echo esc_html(get_theme_mod('oreh_phone', '+7 999 123-45-67')); ?></a>
      <a href="mailto:<?php echo esc_attr(get_theme_mod('oreh_email', 'info@oreh.ru')); ?>" class="footer__email"><?php echo esc_html(get_theme_mod('oreh_email', 'info@oreh.ru')); ?></a>
    </div>
  </div>
  <div class="footer__bottom">
    <span>© <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?>. Sport for life.</span>
    <span><?php esc_html_e('Политика конфиденциальности', 'oreh'); ?></span>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
