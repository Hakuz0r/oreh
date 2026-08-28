<?php
if (!defined('ABSPATH')) exit;

get_header();

while (have_posts()) : the_post();
    global $product;
    $product = wc_get_product(get_the_ID());
    if (!$product) continue;

    $gallery_ids = $product->get_gallery_image_ids();
    $featured_id = $product->get_image_id();
    $all_ids     = $featured_id ? array_merge([$featured_id], $gallery_ids) : $gallery_ids;
    $all_ids     = array_values(array_unique(array_filter($all_ids)));

    $main_ids  = array_slice($all_ids, 0, 6);
    $extra_ids = array_slice($all_ids, 6);
    ?>

    <div class="breadcrumbs">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="breadcrumbs__link"><?php esc_html_e('Главная', 'oreh'); ?></a>
      <span>/</span>
      <a href="<?php echo esc_url(home_url('/#equipment')); ?>" class="breadcrumbs__link"><?php esc_html_e('Оборудование', 'oreh'); ?></a>
      <span>/</span>
      <span class="breadcrumbs__current"><?php the_title(); ?></span>
    </div>

    <section class="product-section">
      <div class="gallery">
        <div class="gallery__main">
          <?php if ($main_ids) : ?>
            <?php foreach ($main_ids as $i => $id) : ?>
              <?php echo wp_get_attachment_image($id, 'large', false, [
                  'class'              => 'gallery__image' . ($i === 0 ? ' is-active' : ''),
                  'data-gallery-image' => $i,
                  'alt'                => esc_attr(get_the_title()),
                  'loading'            => $i === 0 ? 'eager' : 'lazy',
              ]); ?>
            <?php endforeach; ?>
          <?php else : ?>
            <?php echo wc_placeholder_img('large', ['class' => 'gallery__image is-active', 'data-gallery-image' => 0]); ?>
          <?php endif; ?>
        </div>
        <?php if (count($main_ids) > 1) : ?>
          <div class="gallery__thumbs">
            <?php foreach ($main_ids as $i => $id) : ?>
              <button type="button" class="gallery__thumb<?php echo $i === 0 ? ' is-active' : ''; ?>" aria-label="<?php echo esc_attr(sprintf(/* translators: %d: номер фото */ __('Фото %d', 'oreh'), $i + 1)); ?>" data-gallery-thumb="<?php echo esc_attr($i); ?>">
                <?php echo wp_get_attachment_image($id, 'thumbnail', false, ['class' => 'gallery__thumb-image', 'loading' => 'lazy']); ?>
                <span class="gallery__thumb-ring"></span>
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="product">
        <h1 class="product__title"><?php the_title(); ?></h1>
        <?php $short = $product->get_short_description(); ?>
        <?php if ($short) : ?><p class="product__subtitle"><?php echo wp_kses_post($short); ?></p><?php endif; ?>

        <div class="product__price"><?php echo wp_kses_post($product->get_price_html()); ?></div>

        <?php
        $description = $product->get_description();
        if ($description) :
        ?>
          <div class="product__description"><?php echo wp_kses_post(wpautop($description)); ?></div>
        <?php endif; ?>

        <div class="product__actions">
          <a href="<?php echo esc_url(home_url('/#contacts')); ?>" class="btn btn--primary"><?php esc_html_e('Оставить заявку', 'oreh'); ?></a>
          <a href="tel:<?php echo esc_attr(oreh_phone_href()); ?>" class="btn btn--outline"><?php echo esc_html(get_theme_mod('oreh_phone', '+7 999 123-45-67')); ?></a>
        </div>

        <?php
        $attributes = $product->get_attributes();
        if ($attributes) :
        ?>
          <div class="product__specs">
            <?php foreach ($attributes as $attribute) :
                $label = wc_attribute_label($attribute->get_name());
                $value = $product->get_attribute($attribute->get_name());
                if (!$value) continue;
            ?>
              <div class="product__spec-row">
                <span class="product__spec-label"><?php echo esc_html($label); ?></span>
                <span class="product__spec-value"><?php echo wp_kses_post($value); ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <?php if ($extra_ids) : ?>
      <section class="about">
        <div class="about__grid">
          <div class="about__gallery">
            <?php foreach ($extra_ids as $id) : ?>
              <div class="about__gallery-item"><?php echo wp_get_attachment_image($id, 'medium_large', false, ['loading' => 'lazy']); ?></div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <section class="cta">
      <div class="cta__inner">
        <div>
          <h2 class="cta__title"><?php esc_html_e('Нужна консультация?', 'oreh'); ?></h2>
          <p class="cta__text"><?php esc_html_e('Расскажем про наличие, сроки изготовления и доставку.', 'oreh'); ?></p>
        </div>
        <a href="<?php echo esc_url(home_url('/#contacts')); ?>" class="btn btn--primary"><?php esc_html_e('Оставить заявку', 'oreh'); ?></a>
      </div>
    </section>

<?php endwhile;

get_footer();
