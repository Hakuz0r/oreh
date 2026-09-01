<?php
if (!defined('ABSPATH')) exit;

/**
 * Общий хелпер отправки в Telegram-бота — используется формой заявки
 * и оформлением заказа из корзины.
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
