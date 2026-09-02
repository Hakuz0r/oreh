<?php
/**
 * Header
 */
if (!defined('ABSPATH')) exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="header">
  <div class="header__inner">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="header__logo">
      <?php if (has_custom_logo()) : ?>
        <?php the_custom_logo(); ?>
      <?php else : ?>
        <picture>
          <source srcset="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-dark.webp'); ?>" type="image/webp" />
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-dark.png'); ?>" alt="<?php bloginfo('name'); ?>" class="header__logo-icon" />
        </picture>
      <?php endif; ?>
      <span class="header__logo-text"><?php bloginfo('name'); ?></span>
    </a>

    <?php
    wp_nav_menu([
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'header__nav',
        'fallback_cb'    => 'oreh_default_menu',
    ]);
    ?>

    <div class="header__actions">
      <a href="tel:<?php echo esc_attr(oreh_phone_href()); ?>" class="header__phone"><?php echo esc_html(oreh_text('oreh_phone')); ?></a>
      <?php oreh_cart_icon(); ?>
    </div>

    <div class="header__mobile-actions">
      <?php oreh_cart_icon(); ?>
      <a href="tel:<?php echo esc_attr(oreh_phone_href()); ?>" class="header__phone--mobile"><?php echo esc_html(oreh_text('oreh_phone')); ?></a>
      <button type="button" class="header__burger" aria-label="<?php esc_attr_e('Меню', 'oreh'); ?>" data-menu-open>
        <span class="header__burger-line"></span>
        <span class="header__burger-line"></span>
        <span class="header__burger-line"></span>
      </button>
    </div>
  </div>
</header>

<div class="mobile-menu" data-mobile-menu>
  <div class="mobile-menu__top">
    <span class="mobile-menu__logo"><?php bloginfo('name'); ?></span>
    <button type="button" class="mobile-menu__close" aria-label="<?php esc_attr_e('Закрыть', 'oreh'); ?>" data-menu-close>&times;</button>
  </div>
  <?php
  wp_nav_menu([
      'theme_location' => 'primary',
      'container'      => false,
      'menu_class'     => 'mobile-menu__nav',
      'fallback_cb'    => 'oreh_default_menu',
  ]);
  ?>
  <a href="tel:<?php echo esc_attr(oreh_phone_href()); ?>" class="mobile-menu__phone"><?php echo esc_html(oreh_text('oreh_phone')); ?></a>
</div>

<main>
