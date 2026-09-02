<?php
if (!defined('ABSPATH')) exit;
?>
</main>

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
      <a href="tel:<?php echo esc_attr(oreh_phone_href()); ?>" class="footer__phone"><?php echo esc_html(oreh_text('oreh_phone')); ?></a>
      <a href="mailto:<?php echo esc_attr(oreh_text('oreh_email')); ?>" class="footer__email"><?php echo esc_html(oreh_text('oreh_email')); ?></a>
    </div>
  </div>
  <div class="footer__bottom">
    <span>© <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?>. Sport for life.</span>
    <?php $oreh_privacy_url = get_privacy_policy_url(); ?>
    <?php if ($oreh_privacy_url) : ?>
      <a href="<?php echo esc_url($oreh_privacy_url); ?>" class="footer__bottom-link"><?php esc_html_e('Политика конфиденциальности', 'oreh'); ?></a>
    <?php else : ?>
      <span><?php esc_html_e('Политика конфиденциальности', 'oreh'); ?></span>
    <?php endif; ?>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
