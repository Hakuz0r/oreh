<?php
if (!defined('ABSPATH')) exit;

get_header();
?>

<section class="intro">
  <h1 class="intro__title">404</h1>
  <p class="intro__subtitle"><?php esc_html_e('Такой страницы нет. Возможно, её перенесли или удалили.', 'oreh'); ?></p>
  <div class="intro__actions">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary"><?php esc_html_e('На главную', 'oreh'); ?></a>
    <a href="<?php echo esc_url(home_url('/#equipment')); ?>" class="btn btn--outline"><?php esc_html_e('Оборудование', 'oreh'); ?></a>
  </div>
</section>

<?php get_footer(); ?>
