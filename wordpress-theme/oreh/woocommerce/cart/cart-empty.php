<?php
if (!defined('ABSPATH')) exit;

/**
 * Фрагмент для пустой корзины — WooCommerce вызывает именно этот файл
 * (не cart.php!), когда корзина пуста, ещё до того, как доходит очередь
 * до основного шаблона. Тоже фрагмент внутри the_content(), без
 * get_header()/get_footer().
 *
 * Важно: после успешной отправки заказа (oreh_handle_cart_submit)
 * корзина тоже становится пустой, поэтому сообщение "Заказ отправлен"
 * обрабатывается именно здесь, а не в cart.php — иначе оно никогда
 * бы не показалось.
 */

$oreh_sent = isset($_GET['sent']) ? sanitize_text_field(wp_unslash($_GET['sent'])) : '';
?>

<h1 class="cart__title"><?php esc_html_e('Корзина', 'oreh'); ?></h1>

<?php if ($oreh_sent === '1') : ?>

  <div class="cart__checkout contact__panel">
    <div class="form-success">
      <h3 class="form-success__title"><?php esc_html_e('Заказ отправлен', 'oreh'); ?></h3>
      <p class="form-success__text"><?php esc_html_e('Свяжемся с вами в течение рабочего дня.', 'oreh'); ?></p>
      <a href="<?php echo esc_url(home_url('/#equipment')); ?>" class="form-success__btn"><?php esc_html_e('Вернуться в каталог', 'oreh'); ?></a>
    </div>
  </div>

<?php else : ?>

  <div class="cart__empty">
    <p><?php esc_html_e('Корзина пуста.', 'oreh'); ?></p>
    <a href="<?php echo esc_url(home_url('/#equipment')); ?>" class="btn btn--primary"><?php esc_html_e('Перейти в каталог', 'oreh'); ?></a>
  </div>

<?php endif; ?>
