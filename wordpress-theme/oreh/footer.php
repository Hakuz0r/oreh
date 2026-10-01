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

<?php
$oreh_tg  = oreh_text('oreh_telegram');
$oreh_max = oreh_text('oreh_max');
?>
<?php if ($oreh_tg || $oreh_max) : ?>
  <div class="messengers">
    <?php if ($oreh_max) : ?>
      <a href="<?php echo esc_url($oreh_max); ?>" class="messengers__btn messengers__btn--max" target="_blank" rel="noopener" aria-label="<?php esc_attr_e('Написать в MAX', 'oreh'); ?>">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#fff" d="M12 4.2c-4.4 0-7.9 3.2-7.9 7.3 0 2.1.9 3.9 2.4 5.3l-.6 2.9c-.1.4.3.7.7.5l3-1.4c.8.2 1.6.3 2.4.3 4.4 0 7.9-3.2 7.9-7.3S16.4 4.2 12 4.2Zm-3.6 9.9V9.2h1.3l2.3 3 2.3-3h1.3v4.9h-1.4v-2.8l-2.2 2.8-2.2-2.8v2.8H8.4Z"/></svg>
      </a>
    <?php endif; ?>
    <?php if ($oreh_tg) : ?>
      <a href="<?php echo esc_url($oreh_tg); ?>" class="messengers__btn messengers__btn--tg" target="_blank" rel="noopener" aria-label="<?php esc_attr_e('Написать в Telegram', 'oreh'); ?>">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#fff" d="M9.8 15.3 9.6 18.6c.3 0 .5-.1.6-.3l1.5-1.4 3.1 2.3c.6.3 1 .2 1.1-.5l2.1-9.7c.2-.9-.3-1.2-.9-1L4.9 12.4c-.8.3-.8.8-.1 1l3.1 1 7.3-4.6c.3-.2.7-.1.4.1L9.8 15.3Z"/></svg>
      </a>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
