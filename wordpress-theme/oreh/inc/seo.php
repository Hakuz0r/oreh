<?php
if (!defined('ABSPATH')) exit;

/**
 * Базовые SEO-теги без плагина: meta description, Open Graph,
 * canonical, JSON-LD. Достаточно для лендинга/каталога такого
 * размера, ничего лишнего на сервер не ставим.
 */
add_action('wp_head', function () {
    $title       = wp_get_document_title();
    $description = '';
    $image       = '';
    $canonical   = is_front_page() ? home_url('/') : get_permalink();

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

    echo "\n" . '<link rel="canonical" href="' . esc_url($canonical) . '" />' . "\n";
    echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta property="og:type" content="' . (is_front_page() ? 'website' : 'article') . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($canonical) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
    if ($image) {
        echo '<meta property="og:image" content="' . esc_url($image) . '" />' . "\n";
    }
    echo '<meta name="twitter:card" content="' . ($image ? 'summary_large_image' : 'summary') . '" />' . "\n";
}, 1);

/**
 * JSON-LD: Organization на главной, Product на странице товара —
 * даёт Google материал для расширенных сниппетов (цена, наличие).
 */
add_action('wp_head', function () {
    if (is_front_page()) {
        $logo_id = get_theme_mod('custom_logo');

        $data = [
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => get_bloginfo('name'),
            'url'      => home_url('/'),
        ];
        if ($logo_id) {
            $data['logo'] = wp_get_attachment_image_url($logo_id, 'large');
        }
        $phone = oreh_text('oreh_phone');
        if ($phone) {
            $data['telephone'] = $phone;
        }
        $email = oreh_text('oreh_email');
        if ($email) {
            $data['email'] = $email;
        }

        echo '<script type="application/ld+json">' . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
        return;
    }

    if (!function_exists('is_product') || !is_product()) {
        return;
    }

    $wc_product = wc_get_product(get_the_ID());
    if (!$wc_product) {
        return;
    }

    $images = [];
    $image_id = $wc_product->get_image_id();
    if ($image_id) {
        $images[] = wp_get_attachment_image_url($image_id, 'large');
    }
    foreach ($wc_product->get_gallery_image_ids() as $gallery_id) {
        $images[] = wp_get_attachment_image_url($gallery_id, 'large');
    }

    $data = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        'name'        => $wc_product->get_name(),
        'description' => wp_strip_all_tags($wc_product->get_short_description() ?: $wc_product->get_description()),
        'image'       => $images,
        'offers'      => [
            '@type'         => 'Offer',
            'url'           => get_permalink($wc_product->get_id()),
            'priceCurrency' => get_woocommerce_currency(),
            'price'         => $wc_product->get_price(),
            'availability'  => $wc_product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        ],
    ];
    if ($wc_product->get_sku()) {
        $data['sku'] = $wc_product->get_sku();
    }

    echo '<script type="application/ld+json">' . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}, 2);

/**
 * Примечание: /wp-sitemap.xml WordPress генерирует сам с версии 5.5
 * и сам же добавляет ссылку на него в robots.txt — отдельно делать
 * это не нужно (и даже вредно, задвоит строку).
 */
