<?php
if (!defined('ABSPATH')) exit;

add_action('after_setup_theme', function () {
    load_theme_textdomain('oreh', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 46,
        'width'       => 46,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('automatic-feed-links');

    register_nav_menus([
        'primary' => __('Главное меню', 'oreh'),
    ]);
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('oreh-fonts', 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap', [], null);
    wp_enqueue_style('oreh-style', get_stylesheet_uri(), [], OREH_THEME_VERSION);
    wp_enqueue_style('oreh-adaptive', get_template_directory_uri() . '/assets/css/adaptive.css', ['oreh-style'], OREH_THEME_VERSION);

    if (function_exists('wp_enqueue_script_module')) {
        wp_enqueue_script_module('oreh-main', get_template_directory_uri() . '/assets/js/main.js', [], OREH_THEME_VERSION);
    } else {
        // Фолбэк для WP < 6.5 (Script Modules API): грузим как обычный module-скрипт.
        wp_enqueue_script('oreh-main', get_template_directory_uri() . '/assets/js/main.js', [], OREH_THEME_VERSION, true);
        add_filter('script_loader_tag', function ($tag, $handle) {
            if ($handle === 'oreh-main') {
                $tag = str_replace(' src', ' type="module" src', $tag);
            }
            return $tag;
        }, 10, 2);
    }
});

/**
 * Фолбэк-меню на случай, если в Appearance → Menus ещё не назначено
 * меню на позицию "primary" — те же три якорных ссылки, что и в макете.
 */
function oreh_default_menu($args) {
    $menu_class = isset($args['menu_class']) ? $args['menu_class'] : 'header__nav';
    $link_map = [
        'header__nav'       => 'header__nav-link',
        'mobile-menu__nav'  => 'mobile-menu__nav-link',
        'footer__nav'       => 'footer__nav-link',
    ];
    $link_class = isset($link_map[$menu_class]) ? $link_map[$menu_class] : '';
    $items = [
        'equipment' => __('Оборудование', 'oreh'),
        'why'       => __('Почему мы', 'oreh'),
        'contacts'  => __('Контакты', 'oreh'),
    ];

    echo '<ul class="' . esc_attr($menu_class) . '">';
    foreach ($items as $anchor => $label) {
        printf(
            '<li><a class="%1$s" href="%2$s">%3$s</a></li>',
            esc_attr($link_class),
            esc_url(home_url('/#' . $anchor)),
            esc_html($label)
        );
    }
    echo '</ul>';
}

/**
 * Классы для ссылок реального меню (Appearance → Menus) — WP по умолчанию
 * навешивает классы на <li>, а в вёрстке они нужны на <a>.
 */
add_filter('nav_menu_link_attributes', function ($atts, $item, $args) {
    $link_map = [
        'header__nav'      => 'header__nav-link',
        'mobile-menu__nav' => 'mobile-menu__nav-link',
        'footer__nav'      => 'footer__nav-link',
    ];
    $menu_class = isset($args->menu_class) ? $args->menu_class : '';
    if (isset($link_map[$menu_class])) {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' ' . $link_map[$menu_class] : $link_map[$menu_class];
    }
    return $atts;
}, 10, 3);

/**
 * Ленивая загрузка для картинок в медиатеке — WordPress ядро уже добавляет
 * loading="lazy" автоматически с 5.9. Тут только добираем автогенерацию
 * WebP для всех размеров, которые WP создаёт при загрузке в медиатеку
 * (миниатюры товаров, галерея и т.д.) — исходный файл не трогаем.
 */
add_filter('image_editor_output_format', function ($formats) {
    $formats['image/jpeg'] = 'image/webp';
    $formats['image/png']  = 'image/webp';
    return $formats;
});
