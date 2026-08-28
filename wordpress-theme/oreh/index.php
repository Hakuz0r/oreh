<?php
if (!defined('ABSPATH')) exit;

get_header();
?>

<section class="catalog">
  <div class="container">
    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('product-card'); ?>>
          <div class="product-card__body">
            <h2 class="product-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="product-card__desc"><?php the_excerpt(); ?></div>
          </div>
        </article>
      <?php endwhile; ?>
      <?php the_posts_pagination(['class' => 'pagination']); ?>
    <?php else : ?>
      <p class="catalog__empty"><?php esc_html_e('Ничего не найдено.', 'oreh'); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
