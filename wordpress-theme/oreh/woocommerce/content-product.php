<?php
if (!defined('ABSPATH')) exit;

global $product;
$product = wc_get_product(get_the_ID());

if (!$product || !$product->is_visible()) {
    return;
}
?>
<article class="product-card">
  <div class="product-card__media">
    <?php
    $image_id = $product->get_image_id();
    if ($image_id) {
        echo wp_get_attachment_image($image_id, 'large', false, [
            'class' => 'product-card__image',
            'alt'   => esc_attr($product->get_name()),
        ]);
    } else {
        echo wc_placeholder_img('large', ['class' => 'product-card__image']);
    }
    ?>
  </div>
  <div class="product-card__body">
    <h3 class="product-card__title"><?php echo esc_html($product->get_name()); ?></h3>
    <?php $short = $product->get_short_description(); ?>
    <?php if ($short) : ?>
      <p class="product-card__desc"><?php echo wp_kses_post($short); ?></p>
    <?php endif; ?>
    <div class="product-card__footer">
      <span class="product-card__price"><?php echo wp_kses_post($product->get_price_html()); ?></span>
      <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="btn btn--primary btn--sm"><?php esc_html_e('Подробнее', 'oreh'); ?></a>
    </div>
  </div>
</article>
