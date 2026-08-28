<?php
if (!defined('ABSPATH')) exit;

/**
 * Отправка заявки в Telegram-бота вместо почты.
 * Токен бота и ID чата задаются константами в wp-config.php:
 *   define('OREH_TG_BOT_TOKEN', '123456:AA...');
 *   define('OREH_TG_CHAT_ID', '123456789');
 */
function oreh_send_to_telegram($text) {
    if (!defined('OREH_TG_BOT_TOKEN') || !defined('OREH_TG_CHAT_ID')) {
        error_log('OREH: не заданы OREH_TG_BOT_TOKEN / OREH_TG_CHAT_ID в wp-config.php');
        return false;
    }

    $url = 'https://api.telegram.org/bot' . OREH_TG_BOT_TOKEN . '/sendMessage';

    $response = wp_remote_post($url, [
        'timeout' => 10,
        'body'    => [
            'chat_id'    => OREH_TG_CHAT_ID,
            'text'       => $text,
            'parse_mode' => 'HTML',
        ],
    ]);

    if (is_wp_error($response)) {
        error_log('OREH Telegram error: ' . $response->get_error_message());
        return false;
    }

    return true;
}

function oreh_handle_contact_form() {
    $redirect_error = add_query_arg('sent', 'error', home_url('/#contacts'));

    if (!isset($_POST['oreh_contact_nonce']) || !wp_verify_nonce($_POST['oreh_contact_nonce'], 'oreh_contact_submit')) {
        wp_safe_redirect($redirect_error);
        exit;
    }

    $name    = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $phone   = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $product = isset($_POST['product']) ? sanitize_text_field(wp_unslash($_POST['product'])) : '';
    $comment = isset($_POST['comment']) ? sanitize_textarea_field(wp_unslash($_POST['comment'])) : '';

    if ($name === '' || $phone === '') {
        wp_safe_redirect($redirect_error);
        exit;
    }

    $text = sprintf(
        "<b>Новая заявка с сайта OREH</b>\n\n<b>Имя:</b> %s\n<b>Телефон:</b> %s\n<b>Интересует:</b> %s\n<b>Комментарий:</b> %s",
        esc_html($name),
        esc_html($phone),
        esc_html($product !== '' ? $product : '—'),
        esc_html($comment !== '' ? $comment : '—')
    );

    oreh_send_to_telegram($text);

    wp_safe_redirect(add_query_arg('sent', '1', home_url('/#contacts')));
    exit;
}
add_action('admin_post_oreh_contact_submit', 'oreh_handle_contact_form');
add_action('admin_post_nopriv_oreh_contact_submit', 'oreh_handle_contact_form');
