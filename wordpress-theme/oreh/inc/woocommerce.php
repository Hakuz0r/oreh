<?php
if (!defined('ABSPATH')) exit;

add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
});

add_action('admin_notices', function () {
    if (!class_exists('WooCommerce')) {
        echo '<div class="notice notice-warning"><p>';
        esc_html_e('Тема OREH использует WooCommerce для каталога товаров. Установите и активируйте плагин WooCommerce.', 'oreh');
        echo '</p></div>';
    }
});

/**
 * Каталог с лёгкой корзиной (без оплаты): своя вёрстка карточек и
 * страницы товара, поэтому дефолтные шаблонные кнопки/стили WooCommerce
 * не подключаем — добавляем свои "В корзину" прямо в шаблонах.
 */
add_action('init', function () {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
});

/**
 * Из корзины/оформления у нас нет: логина, адресов доставки и оплаты.
 * /checkout/ ведёт туда же, где происходит оформление, — на страницу
 * корзины (там внизу форма). /my-account/ не используется вовсе.
 */
add_action('template_redirect', function () {
    if (function_exists('is_checkout') && is_checkout()) {
        wp_safe_redirect(wc_get_cart_url());
        exit;
    }
    if (function_exists('is_account_page') && is_account_page()) {
        wp_safe_redirect(home_url('/'));
        exit;
    }
});

/**
 * Иконка корзины с счётчиком — используется в шапке (десктоп и мобилка).
 */
function oreh_cart_icon() {
    if (!function_exists('WC')) return;

    $count = (int) WC()->cart->get_cart_contents_count();
    ?>
    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header__cart" aria-label="<?php esc_attr_e('Корзина', 'oreh'); ?>">
      <svg class="header__cart-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M3 4h2l2.4 12.2a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 8H6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
        <circle cx="10" cy="21" r="1.4" fill="currentColor"/>
        <circle cx="17.5" cy="21" r="1.4" fill="currentColor"/>
      </svg>
      <span class="header__cart-count<?php echo $count === 0 ? ' is-empty' : ''; ?>" data-cart-count><?php echo esc_html($count); ?></span>
    </a>
    <?php
}

add_filter('loop_shop_columns', function () {
    return 2;
});
