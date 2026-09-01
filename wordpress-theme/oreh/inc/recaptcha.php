<?php
if (!defined('ABSPATH')) exit;

/**
 * Google reCAPTCHA v2 ("я не робот") на форме заявки и оформлении
 * заказа из корзины. Ключи задаются константами в wp-config.php:
 *   define('OREH_RECAPTCHA_SITE_KEY', '...');
 *   define('OREH_RECAPTCHA_SECRET_KEY', '...');
 * Пока константы не заданы — виджет просто не появляется и проверка
 * не требуется, формы работают как раньше.
 */

function oreh_recaptcha_enabled() {
    return defined('OREH_RECAPTCHA_SITE_KEY') && defined('OREH_RECAPTCHA_SECRET_KEY');
}

add_action('wp_enqueue_scripts', function () {
    if (!oreh_recaptcha_enabled()) return;
    if (!is_front_page() && !(function_exists('is_cart') && is_cart())) return;

    wp_enqueue_script('oreh-recaptcha', 'https://www.google.com/recaptcha/api.js', [], null, true);
});

/**
 * Виджет — вставляется в форму перед кнопкой отправки.
 */
function oreh_recaptcha_widget() {
    if (!oreh_recaptcha_enabled()) return;
    ?>
    <div class="g-recaptcha" data-sitekey="<?php echo esc_attr(OREH_RECAPTCHA_SITE_KEY); ?>"></div>
    <?php
}

/**
 * Проверка ответа виджета на сервере. true — либо капча не настроена
 * (тогда проверять нечего), либо человек прошёл проверку.
 */
function oreh_recaptcha_verify() {
    if (!oreh_recaptcha_enabled()) return true;

    $response = isset($_POST['g-recaptcha-response']) ? sanitize_text_field(wp_unslash($_POST['g-recaptcha-response'])) : '';
    if ($response === '') return false;

    $result = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
        'timeout' => 10,
        'body'    => [
            'secret'   => OREH_RECAPTCHA_SECRET_KEY,
            'response' => $response,
            'remoteip' => isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '',
        ],
    ]);

    if (is_wp_error($result)) {
        error_log('OREH reCAPTCHA error: ' . $result->get_error_message());
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($result), true);
    return !empty($body['success']);
}
