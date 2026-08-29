<?php
if (!defined('ABSPATH')) exit;

/**
 * Вся вёрстка сайта своя, блочный редактор и дефолтные стили WooCommerce
 * на фронте не используются — отключаем, чтобы не тянуть лишние килобайты.
 */
add_action('wp_enqueue_scripts', function () {
    if (is_admin()) return;

    // CSS блочного редактора (Gutenberg) — тема классическая
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');

    // Дефолтные стили WooCommerce — карточки и страница товара свёрстаны в теме
    wp_dequeue_style('woocommerce-general');
    wp_dequeue_style('woocommerce-layout');
    wp_dequeue_style('woocommerce-smallscreen');
    wp_dequeue_style('woocommerce-inline');
}, 100);

// Скрипт эмодзи — не нужен
add_action('init', function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
});

// Лишние теги в <head>
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
