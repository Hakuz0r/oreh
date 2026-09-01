<?php
if (!defined('ABSPATH')) exit;

/**
 * Это НЕ отдельная страница, а фрагмент — WooCommerce включает его
 * прямо внутри the_content() шорткодом [woocommerce_cart] на странице
 * «Корзина». get_header()/get_footer() здесь не нужны и ломают
 * разметку (страница их уже вызвала один раз через page.php).
 */

$oreh_sent  = isset($_GET['sent']) ? sanitize_text_field(wp_unslash($_GET['sent'])) : '';
$cart_items = WC()->cart->get_cart();
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

<?php elseif (empty($cart_items)) : ?>

  <div class="cart__empty">
    <p><?php esc_html_e('Корзина пуста.', 'oreh'); ?></p>
    <a href="<?php echo esc_url(home_url('/#equipment')); ?>" class="btn btn--primary"><?php esc_html_e('Перейти в каталог', 'oreh'); ?></a>
  </div>

<?php else : ?>

  <form class="cart__items" method="post" action="<?php echo esc_url(wc_get_cart_url()); ?>">
    <?php foreach ($cart_items as $cart_item_key => $cart_item) :
        $product = $cart_item['data'];
        if (!$product) continue;
    ?>
      <div class="cart__item">
        <div class="cart__item-media">
          <?php
          $image_id = $product->get_image_id();
          if ($image_id) {
              echo wp_get_attachment_image($image_id, 'thumbnail', false, ['class' => 'cart__item-image']);
          }
          ?>
        </div>
        <div class="cart__item-info">
          <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="cart__item-title"><?php echo esc_html($product->get_name()); ?></a>
          <span class="cart__item-price"><?php echo wp_kses_post(wc_price($product->get_price())); ?></span>
        </div>
        <input
          type="number"
          min="1"
          class="cart__item-qty"
          name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]"
          value="<?php echo esc_attr($cart_item['quantity']); ?>"
          aria-label="<?php esc_attr_e('Количество', 'oreh'); ?>"
        />
        <span class="cart__item-subtotal"><?php echo wp_kses_post(wc_price($product->get_price() * $cart_item['quantity'])); ?></span>
        <a href="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)); ?>" class="cart__item-remove" aria-label="<?php esc_attr_e('Удалить', 'oreh'); ?>">&times;</a>
      </div>
    <?php endforeach; ?>

    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
    <div class="cart__update-row">
      <button type="submit" name="update_cart" value="<?php esc_attr_e('Обновить корзину', 'oreh'); ?>" class="btn btn--outline btn--sm"><?php esc_html_e('Обновить корзину', 'oreh'); ?></button>
      <span class="cart__subtotal"><?php esc_html_e('Итого:', 'oreh'); ?> <?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?></span>
    </div>
  </form>

  <div class="cart__checkout contact__panel">
    <h2 class="cart__checkout-title"><?php esc_html_e('Оформить заказ', 'oreh'); ?></h2>
    <p class="cart__checkout-text"><?php esc_html_e('Без предоплаты — менеджер свяжется, чтобы уточнить детали.', 'oreh'); ?></p>

    <form class="form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
      <input type="hidden" name="action" value="oreh_cart_submit" />
      <?php wp_nonce_field('oreh_cart_submit', 'oreh_cart_nonce'); ?>

      <?php if ($oreh_sent === 'error') : ?>
        <p class="catalog__empty"><?php esc_html_e('Заполните имя и телефон.', 'oreh'); ?></p>
      <?php endif; ?>

      <label class="form__field">
        <span class="form__label"><?php esc_html_e('ИМЯ', 'oreh'); ?></span>
        <input type="text" name="name" required placeholder="<?php esc_attr_e('Как к вам обращаться', 'oreh'); ?>" class="form__input" />
      </label>
      <label class="form__field">
        <span class="form__label"><?php esc_html_e('ТЕЛЕФОН', 'oreh'); ?></span>
        <input type="tel" name="phone" required placeholder="+7 ___ ___-__-__" class="form__input" />
      </label>
      <label class="form__field">
        <span class="form__label"><?php esc_html_e('КОММЕНТАРИЙ', 'oreh'); ?></span>
        <textarea name="comment" rows="3" placeholder="<?php esc_attr_e('Город, вопросы по доставке', 'oreh'); ?>" class="form__textarea"></textarea>
      </label>
      <label class="form__consent">
        <input type="checkbox" required class="form__checkbox" />
        <span><?php esc_html_e('Согласен с обработкой персональных данных', 'oreh'); ?></span>
      </label>
      <button type="submit" class="form__submit"><?php esc_html_e('Отправить заказ', 'oreh'); ?></button>
    </form>
  </div>

<?php endif; ?>
