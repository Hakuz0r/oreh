<?php
if (!defined('ABSPATH')) exit;

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

    $to      = get_option('admin_email');
    $subject = sprintf(/* translators: %s: имя из формы заявки */ __('Новая заявка с сайта OREH — %s', 'oreh'), $name);
    $body    = "Имя: {$name}\nТелефон: {$phone}\nИнтересует: {$product}\nКомментарий: {$comment}";

    wp_mail($to, $subject, $body);

    wp_safe_redirect(add_query_arg('sent', '1', home_url('/#contacts')));
    exit;
}
add_action('admin_post_oreh_contact_submit', 'oreh_handle_contact_form');
add_action('admin_post_nopriv_oreh_contact_submit', 'oreh_handle_contact_form');
