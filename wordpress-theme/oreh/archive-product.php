<?php
if (!defined('ABSPATH')) exit;

get_header();
?>

<section class="catalog">
  <div class="container">
    <h1 class="archive-title"><?php woocommerce_page_title(); ?></h1>

    <div class="catalog__grid">
      <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post();
            wc_get_template_part('content', 'product');
        endwhile; ?>
      <?php else : ?>
        <p class="catalog__empty"><?php esc_html_e('Товары не найдены.', 'oreh'); ?></p>
      <?php endif; ?>
    </div>

    <?php
    the_posts_pagination([
        'prev_text'  => __('←', 'oreh'),
        'next_text'  => __('→', 'oreh'),
        'class'      => 'pagination',
    ]);
    ?>
  </div>
</section>

<?php get_footer(); ?>
