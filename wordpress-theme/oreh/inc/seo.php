<?php
if (!defined('ABSPATH')) exit;

/**
 * Базовые SEO-теги без плагина: meta description + Open Graph.
 * Достаточно для лендинга/каталога такого размера, ничего лишнего
 * на сервер не ставим.
 */
add_action('wp_head', function () {
    $title       = wp_get_document_title();
    $description = '';
    $image       = '';

    if (is_front_page()) {
        $description = oreh_text('oreh_intro_subtitle');
        $logo_id      = get_theme_mod('custom_logo');
        $image        = $logo_id ? wp_get_attachment_image_url($logo_id, 'large') : '';
    } elseif (function_exists('is_product') && is_product()) {
        global $product;
        $wc_product  = $product instanceof WC_Product ? $product : wc_get_product(get_the_ID());
        $description = $wc_product ? wp_strip_all_tags($wc_product->get_short_description() ?: $wc_product->get_description()) : '';
        $image_id     = $wc_product ? $wc_product->get_image_id() : 0;
        $image        = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
    } elseif (is_singular()) {
        $description = wp_strip_all_tags(get_the_excerpt());
        $image        = get_the_post_thumbnail_url(get_the_ID(), 'large');
    }

    if (!$description) {
        $description = get_bloginfo('description');
    }
    $description = wp_trim_words($description, 30, '…');

    if (!$image) {
        $logo_id = get_theme_mod('custom_logo');
        $image   = $logo_id ? wp_get_attachment_image_url($logo_id, 'large') : '';
    }

    echo "\n" . '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta property="og:type" content="' . (is_front_page() ? 'website' : 'article') . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url(is_front_page() ? home_url('/') : get_permalink()) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
    if ($image) {
        echo '<meta property="og:image" content="' . esc_url($image) . '" />' . "\n";
    }
    echo '<meta name="twitter:card" content="' . ($image ? 'summary_large_image' : 'summary') . '" />' . "\n";
}, 1);

/**
 * Примечание: /wp-sitemap.xml WordPress генерирует сам с версии 5.5
 * и сам же добавляет ссылку на него в robots.txt — отдельно делать
 * это не нужно (и даже вредно, задвоит строку).
 */
