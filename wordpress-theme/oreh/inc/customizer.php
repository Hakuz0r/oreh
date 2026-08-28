<?php
if (!defined('ABSPATH')) exit;

add_action('customize_register', function ($wp_customize) {
    $wp_customize->add_section('oreh_contacts', [
        'title'    => __('Контакты OREH', 'oreh'),
        'priority' => 30,
    ]);

    $wp_customize->add_setting('oreh_phone', [
        'default'           => '+7 999 123-45-67',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('oreh_phone', [
        'label'   => __('Телефон', 'oreh'),
        'section' => 'oreh_contacts',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('oreh_email', [
        'default'           => 'info@oreh.ru',
        'sanitize_callback' => 'sanitize_email',
    ]);
    $wp_customize->add_control('oreh_email', [
        'label'   => __('Email', 'oreh'),
        'section' => 'oreh_contacts',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('oreh_hours', [
        'default'           => 'Пн — Сб, 10:00 — 19:00',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('oreh_hours', [
        'label'   => __('Часы работы', 'oreh'),
        'section' => 'oreh_contacts',
        'type'    => 'text',
    ]);
});

function oreh_phone_href() {
    $phone = get_theme_mod('oreh_phone', '+7 999 123-45-67');
    return preg_replace('/[^0-9+]/', '', $phone);
}
