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
 * Сайт-каталог без корзины и оплаты: убираем все точки входа в корзину
 * и оформление заказа, которые обычно добавляет WooCommerce из коробки.
 */
add_action('init', function () {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
});

add_action('template_redirect', function () {
    $is_unused_page = (function_exists('is_cart') && is_cart())
        || (function_exists('is_checkout') && is_checkout())
        || (function_exists('is_account_page') && is_account_page());

    if ($is_unused_page) {
        wp_safe_redirect(home_url('/'));
        exit;
    }
});

add_action('wp_enqueue_scripts', function () {
    wp_dequeue_script('wc-cart-fragments');
}, 20);

add_filter('loop_shop_columns', function () {
    return 2;
});
