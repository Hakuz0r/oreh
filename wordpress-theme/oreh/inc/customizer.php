<?php
if (!defined('ABSPATH')) exit;

/**
 * Все тексты главной страницы вынесены в Внешний вид → Настроить,
 * чтобы клиент правил их сам, без залезания в код.
 */

function oreh_customizer_defaults() {
    return [
        // Контакты
        'oreh_phone'  => '+7 999 123-45-67',
        'oreh_email'  => 'info@oreh.ru',
        'oreh_hours'  => 'Пн — Сб, 10:00 — 19:00',

        // Верхний блок
        'oreh_intro_title'    => 'TriaMotion 3 In 1',
        'oreh_intro_subtitle' => 'Твое идеальное пространство для тренировок',

        // Баннер
        'oreh_hero_title'    => 'TriaMotion',
        'oreh_hero_subtitle' => 'Меняй тренировку одним движением',
        'oreh_hero_btn1'     => 'Выбрать оборудование',
        'oreh_hero_btn2'     => 'Почему мы',

        // Почему мы
        'oreh_why_title' => 'Почему мы',
        'oreh_why_text'  => 'Оборудование собирается вручную из массива ясеня и натуральной кожи. Никакого пластика, никаких лишних деталей.',
        'oreh_why_1_title' => 'Массив ясеня',
        'oreh_why_1_text'  => 'Рама из цельного дерева с покрытием маслом. Каждая деталь шлифуется вручную.',
        'oreh_why_2_title' => 'Натуральная кожа',
        'oreh_why_2_text'  => 'Обивка из плотной кожи на упругом наполнителе — держит форму под нагрузкой.',
        'oreh_why_3_title' => 'Три положения',
        'oreh_why_3_text'  => 'Скамья, наклон и станок для пресса — рама меняет конфигурацию одним движением.',
        'oreh_why_4_title' => 'Складывается без инструментов',
        'oreh_why_4_text'  => 'Стальные фиксаторы и шарниры на подшипниках. Хранится в квартире, не занимая места.',

        // Заявка
        'oreh_contact_title' => 'Оставить заявку',
        'oreh_contact_text'  => 'Расскажем про наличие, сроки изготовления и доставку. Отвечаем в течение рабочего дня.',
    ];
}

/**
 * Значение поля из кастомайзера с дефолтом из макета.
 */
function oreh_text($key) {
    $defaults = oreh_customizer_defaults();
    $default  = isset($defaults[$key]) ? $defaults[$key] : '';
    return get_theme_mod($key, $default);
}

add_action('customize_register', function ($wp_customize) {
    $defaults = oreh_customizer_defaults();

    $add = function ($wp_customize, $id, $label, $section, $type = 'text') use ($defaults) {
        $sanitize = ($type === 'textarea') ? 'sanitize_textarea_field' : 'sanitize_text_field';
        if (strpos($id, 'email') !== false) {
            $sanitize = 'sanitize_email';
        }
        $wp_customize->add_setting($id, [
            'default'           => isset($defaults[$id]) ? $defaults[$id] : '',
            'sanitize_callback' => $sanitize,
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control($id, [
            'label'   => $label,
            'section' => $section,
            'type'    => $type,
        ]);
    };

    // --- Контакты ---
    $wp_customize->add_section('oreh_contacts', [
        'title'    => __('OREH: Контакты', 'oreh'),
        'priority' => 30,
    ]);
    $add($wp_customize, 'oreh_phone', __('Телефон', 'oreh'), 'oreh_contacts');
    $add($wp_customize, 'oreh_email', __('Email', 'oreh'), 'oreh_contacts');
    $add($wp_customize, 'oreh_hours', __('Часы работы', 'oreh'), 'oreh_contacts');

    // --- Главный экран ---
    $wp_customize->add_section('oreh_hero', [
        'title'       => __('OREH: Главный экран', 'oreh'),
        'priority'    => 31,
        'description' => __('Верхний блок и баннер на главной странице.', 'oreh'),
    ]);
    $add($wp_customize, 'oreh_intro_title', __('Заголовок вверху', 'oreh'), 'oreh_hero');
    $add($wp_customize, 'oreh_intro_subtitle', __('Подзаголовок вверху', 'oreh'), 'oreh_hero', 'textarea');
    $add($wp_customize, 'oreh_hero_title', __('Заголовок на баннере', 'oreh'), 'oreh_hero');
    $add($wp_customize, 'oreh_hero_subtitle', __('Подзаголовок на баннере', 'oreh'), 'oreh_hero', 'textarea');
    $add($wp_customize, 'oreh_hero_btn1', __('Кнопка 1', 'oreh'), 'oreh_hero');
    $add($wp_customize, 'oreh_hero_btn2', __('Кнопка 2', 'oreh'), 'oreh_hero');

    // Фон баннера — картинкой из медиатеки
    $wp_customize->add_setting('oreh_hero_image', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'oreh_hero_image', [
        'label'       => __('Фон баннера', 'oreh'),
        'description' => __('Если не задан — используется фото из темы.', 'oreh'),
        'section'     => 'oreh_hero',
    ]));

    // --- Почему мы ---
    $wp_customize->add_section('oreh_why', [
        'title'    => __('OREH: Почему мы', 'oreh'),
        'priority' => 32,
    ]);
    $add($wp_customize, 'oreh_why_title', __('Заголовок блока', 'oreh'), 'oreh_why');
    $add($wp_customize, 'oreh_why_text', __('Текст под заголовком', 'oreh'), 'oreh_why', 'textarea');
    for ($i = 1; $i <= 4; $i++) {
        $add($wp_customize, "oreh_why_{$i}_title", sprintf(__('Пункт %d — заголовок', 'oreh'), $i), 'oreh_why');
        $add($wp_customize, "oreh_why_{$i}_text", sprintf(__('Пункт %d — текст', 'oreh'), $i), 'oreh_why', 'textarea');
    }

    $wp_customize->add_setting('oreh_why_image', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'oreh_why_image', [
        'label'   => __('Фото в блоке', 'oreh'),
        'section' => 'oreh_why',
    ]));

    // --- Блок заявки ---
    $wp_customize->add_section('oreh_contact_block', [
        'title'    => __('OREH: Блок заявки', 'oreh'),
        'priority' => 33,
    ]);
    $add($wp_customize, 'oreh_contact_title', __('Заголовок', 'oreh'), 'oreh_contact_block');
    $add($wp_customize, 'oreh_contact_text', __('Текст', 'oreh'), 'oreh_contact_block', 'textarea');
});

/**
 * Телефон в виде, пригодном для href="tel:".
 */
function oreh_phone_href() {
    return preg_replace('/[^0-9+]/', '', oreh_text('oreh_phone'));
}
