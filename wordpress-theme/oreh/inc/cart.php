<?php
if (!defined('ABSPATH')) exit;

/**
 * "Оформить заказ" на странице корзины — та же логика, что и форма
 * заявки на главной (никакой оплаты), только вместо одного товара
 * из выпадающего списка в Telegram уходит весь список из корзины.
 */
function oreh_handle_cart_submit() {
    $redirect_error = add_query_arg('sent', 'error', wc_get_cart_url());

    if (!isset($_POST['oreh_cart_nonce']) || !wp_verify_nonce($_POST['oreh_cart_nonce'], 'oreh_cart_submit')) {
        wp_safe_redirect($redirect_error);
        exit;
    }

    if (!oreh_recaptcha_verify()) {
        wp_safe_redirect($redirect_error);
        exit;
    }

    // admin-post.php живёт под /wp-admin/, поэтому WooCommerce по
    // умолчанию не инициализирует WC()->cart на этом запросе — просим
    // явно, иначе будет обращение к методу null-объекта.
    if (function_exists('WC') && is_null(WC()->cart)) {
        wc_load_cart();
    }

    if (!function_exists('WC') || !WC()->cart || WC()->cart->is_empty()) {
        wp_safe_redirect($redirect_error);
        exit;
    }

    $name    = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $phone   = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $comment = isset($_POST['comment']) ? sanitize_textarea_field(wp_unslash($_POST['comment'])) : '';

    if ($name === '' || $phone === '') {
        wp_safe_redirect($redirect_error);
        exit;
    }

    $items = [];
    foreach (WC()->cart->get_cart() as $cart_item) {
        $product = isset($cart_item['data']) ? $cart_item['data'] : null;
        if (!$product) continue;
        $items[] = sprintf('— %s × %d', $product->get_name(), (int) $cart_item['quantity']);
    }

    $text = sprintf(
        "<b>Заказ из корзины OREH</b>\n\n<b>Имя:</b> %s\n<b>Телефон:</b> %s\n<b>Товары:</b>\n%s\n\n<b>Комментарий:</b> %s",
        esc_html($name),
        esc_html($phone),
        esc_html(implode("\n", $items)),
        esc_html($comment !== '' ? $comment : '—')
    );

    oreh_send_to_telegram($text);

    $email_body = sprintf(
        "Заказ из корзины OREH\n\nИмя: %s\nТелефон: %s\nТовары:\n%s\n\nКомментарий: %s",
        $name,
        $phone,
        implode("\n", $items),
        $comment !== '' ? $comment : '—'
    );
    wp_mail(oreh_leads_email(), 'Заказ из корзины OREH', $email_body);

    WC()->cart->empty_cart();

    wp_safe_redirect(add_query_arg('sent', '1', wc_get_cart_url()));
    exit;
}
add_action('admin_post_oreh_cart_submit', 'oreh_handle_cart_submit');
add_action('admin_post_nopriv_oreh_cart_submit', 'oreh_handle_cart_submit');

/**
 * Проверка "товар уже в корзине?" — используется при рендере кнопки,
 * чтобы сразу показать правильное состояние без ожидания JS.
 */
function oreh_product_in_cart($product_id) {
    if (!function_exists('WC') || !WC()->cart) {
        return false;
    }
    $cart_id = WC()->cart->generate_cart_id($product_id);
    return (bool) WC()->cart->find_product_in_cart($cart_id);
}

/**
 * Кнопка "В корзину" / "Убрать из корзины" — общая для карточки
 * каталога и страницы товара.
 */
function oreh_cart_toggle_button($product, $size_class = '', $variant = 'outline') {
    if (!$product->is_purchasable()) {
        return;
    }

    $in_cart = oreh_product_in_cart($product->get_id());
    $label_add    = __('В корзину', 'oreh');
    $label_remove = __('Убрать из корзины', 'oreh');
    $variant_class = $variant === 'primary' ? 'btn--primary' : 'btn--outline';
    ?>
    <button
      type="button"
      class="btn <?php echo esc_attr($variant_class); ?> oreh-cart-toggle<?php echo $size_class ? ' ' . esc_attr($size_class) : ''; ?><?php echo $in_cart ? ' is-in-cart' : ''; ?>"
      data-cart-toggle
      data-product-id="<?php echo esc_attr($product->get_id()); ?>"
      data-label-add="<?php echo esc_attr($label_add); ?>"
      data-label-remove="<?php echo esc_attr($label_remove); ?>"
    ><?php echo esc_html($in_cart ? $label_remove : $label_add); ?></button>
    <?php
}

/**
 * Кнопка "В корзину" ⇄ "Убрать из корзины" — свой лёгкий AJAX вместо
 * стандартного wc-add-to-cart.js: не даёт всплывающую ссылку
 * "Просмотреть корзину" сбоку, вместо этого сама кнопка меняет
 * подпись и состояние.
 */
/**
 * main.js грузится через Script Modules API (type="module"), а не
 * через classic wp_enqueue_script — wp_localize_script() с модулями
 * не работает. Поэтому передаём данные простой глобальной переменной.
 */
add_action('wp_head', function () {
    ?>
    <script>
      window.orehCartToggle = {
        ajaxUrl: <?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>,
        nonce: <?php echo wp_json_encode(wp_create_nonce('oreh_toggle_cart')); ?>
      };
    </script>
    <?php
}, 5);

function oreh_ajax_toggle_cart() {
    check_ajax_referer('oreh_toggle_cart', 'nonce');

    if (function_exists('WC') && is_null(WC()->cart)) {
        wc_load_cart();
    }

    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

    if (!$product_id || !function_exists('WC') || !WC()->cart) {
        wp_send_json_error();
    }

    $cart_id      = WC()->cart->generate_cart_id($product_id);
    $existing_key = WC()->cart->find_product_in_cart($cart_id);

    if ($existing_key) {
        WC()->cart->remove_cart_item($existing_key);
        $in_cart = false;
    } else {
        $added   = WC()->cart->add_to_cart($product_id);
        $in_cart = (bool) $added;
    }

    wp_send_json_success([
        'in_cart' => $in_cart,
        'count'   => WC()->cart->get_cart_contents_count(),
    ]);
}
add_action('wp_ajax_oreh_toggle_cart', 'oreh_ajax_toggle_cart');
add_action('wp_ajax_nopriv_oreh_toggle_cart', 'oreh_ajax_toggle_cart');
