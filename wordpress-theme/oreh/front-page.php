<?php
if (!defined('ABSPATH')) exit;

get_header();

$oreh_sent   = isset($_GET['sent']) ? sanitize_text_field(wp_unslash($_GET['sent'])) : '';
$oreh_why_img = get_theme_mod('oreh_why_image', '');
$oreh_slides  = oreh_get_slides();
?>

<section id="top" class="hero-slider" data-hero-slider>
  <?php if ($oreh_slides) : ?>
    <?php foreach ($oreh_slides as $i => $slide) : ?>
      <div
        class="hero-slider__slide<?php echo $i === 0 ? ' is-active' : ''; ?>"
        data-slide
        <?php if ($slide['image']) : ?>style="background-image: url('<?php echo esc_url($slide['image']); ?>');"<?php endif; ?>
      >
        <div class="hero-slider__overlay"></div>
        <div class="hero-slider__inner">
          <div class="hero-slider__content">
            <h1 class="hero-slider__title"><?php echo esc_html($slide['title']); ?></h1>
            <?php if ($slide['subtitle']) : ?>
              <p class="hero-slider__subtitle"><?php echo esc_html($slide['subtitle']); ?></p>
            <?php endif; ?>
            <div class="hero-slider__actions">
              <a href="<?php echo esc_url($slide['btn_url']); ?>" class="btn btn--primary"><?php echo esc_html($slide['btn_text']); ?></a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (count($oreh_slides) > 1) : ?>
      <button type="button" class="hero-slider__arrow hero-slider__arrow--prev" aria-label="<?php esc_attr_e('Предыдущий слайд', 'oreh'); ?>" data-slide-prev>
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
      <button type="button" class="hero-slider__arrow hero-slider__arrow--next" aria-label="<?php esc_attr_e('Следующий слайд', 'oreh'); ?>" data-slide-next>
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
      <div class="hero-slider__dots">
        <?php foreach ($oreh_slides as $i => $slide) : ?>
          <button type="button" class="hero-slider__dot<?php echo $i === 0 ? ' is-active' : ''; ?>" aria-label="<?php echo esc_attr(sprintf(/* translators: %d: номер слайда */ __('Слайд %d', 'oreh'), $i + 1)); ?>" data-slide-dot></button>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php else : ?>
    <div class="hero-slider__slide is-active">
      <div class="hero-slider__overlay"></div>
      <div class="hero-slider__inner">
        <div class="hero-slider__content">
          <h1 class="hero-slider__title"><?php bloginfo('name'); ?></h1>
          <p class="hero-slider__subtitle"><?php esc_html_e('Добавьте слайды в разделе «Слайды баннера»', 'oreh'); ?></p>
        </div>
      </div>
    </div>
  <?php endif; ?>
</section>

<section id="equipment" class="catalog">
  <div class="container">
    <div class="catalog__grid">
      <?php if (class_exists('WooCommerce')) :
        $oreh_products = new WP_Query([
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ]);

        if ($oreh_products->have_posts()) :
            while ($oreh_products->have_posts()) : $oreh_products->the_post();
                wc_get_template_part('content', 'product');
            endwhile;
            wp_reset_postdata();
        else :
            ?>
            <p class="catalog__empty"><?php esc_html_e('Пока нет добавленных товаров. Добавьте первый товар в разделе «Товары».', 'oreh'); ?></p>
            <?php
        endif;
      else :
        ?>
        <p class="catalog__empty"><?php esc_html_e('Для отображения каталога установите и активируйте плагин WooCommerce.', 'oreh'); ?></p>
        <?php
      endif; ?>
    </div>
  </div>
</section>

<section id="why" class="why">
  <div class="why__grid">
    <div>
      <h2 class="why__title"><?php echo esc_html(oreh_text('oreh_why_title')); ?></h2>
      <p class="why__text"><?php echo esc_html(oreh_text('oreh_why_text')); ?></p>
      <div class="why__image">
        <?php if ($oreh_why_img) : ?>
          <img src="<?php echo esc_url($oreh_why_img); ?>" alt="<?php echo esc_attr(oreh_text('oreh_why_title')); ?>" loading="lazy" />
        <?php else : ?>
          <picture>
            <source srcset="<?php echo esc_url(get_template_directory_uri() . '/assets/images/detail-hinge.webp'); ?>" type="image/webp" />
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/detail-hinge.jpg'); ?>" alt="<?php esc_attr_e('Скамья OREH', 'oreh'); ?>" loading="lazy" />
          </picture>
        <?php endif; ?>
      </div>
    </div>
    <div class="why__list">
      <?php for ($i = 1; $i <= 4; $i++) :
          $title = oreh_text("oreh_why_{$i}_title");
          $text  = oreh_text("oreh_why_{$i}_text");
          if ($title === '' && $text === '') continue;
      ?>
        <div class="why__item">
          <span class="why__item-number"><?php echo esc_html(sprintf('%02d', $i)); ?></span>
          <h3 class="why__item-title"><?php echo esc_html($title); ?></h3>
          <p class="why__item-text"><?php echo esc_html($text); ?></p>
        </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<section id="contacts" class="contact">
  <div class="contact__grid">
    <div>
      <h2 class="contact__title"><?php echo esc_html(oreh_text('oreh_contact_title')); ?></h2>
      <p class="contact__text"><?php echo esc_html(oreh_text('oreh_contact_text')); ?></p>
      <div class="contact__details">
        <a href="tel:<?php echo esc_attr(oreh_phone_href()); ?>" class="contact__phone"><?php echo esc_html(oreh_text('oreh_phone')); ?></a>
        <a href="mailto:<?php echo esc_attr(oreh_text('oreh_email')); ?>" class="contact__email"><?php echo esc_html(oreh_text('oreh_email')); ?></a>
        <span class="contact__hours"><?php echo esc_html(oreh_text('oreh_hours')); ?></span>
      </div>
    </div>

    <div class="contact__panel">
      <div class="form-success<?php echo $oreh_sent === '1' ? '' : ' is-hidden'; ?>">
        <h3 class="form-success__title"><?php esc_html_e('Заявка отправлена', 'oreh'); ?></h3>
        <p class="form-success__text"><?php esc_html_e('Свяжемся с вами в течение рабочего дня.', 'oreh'); ?></p>
        <a href="<?php echo esc_url(home_url('/#contacts')); ?>" class="form-success__btn"><?php esc_html_e('Отправить ещё одну', 'oreh'); ?></a>
      </div>

      <form class="form<?php echo $oreh_sent === '1' ? ' is-hidden' : ''; ?>" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="oreh_contact_submit" />
        <?php wp_nonce_field('oreh_contact_submit', 'oreh_contact_nonce'); ?>

        <?php if ($oreh_sent === 'error') : ?>
          <p class="catalog__empty"><?php esc_html_e('Заполните имя и телефон.', 'oreh'); ?></p>
        <?php endif; ?>

        <label class="form__field">
          <span class="form__label"><?php esc_html_e('ИМЯ', 'oreh'); ?></span>
          <input type="text" name="name" required placeholder="<?php esc_attr_e('Как к вам обращаться', 'oreh'); ?>" class="form__input" />
        </label>
        <label class="form__field">
          <span class="form__label"><?php esc_html_e('ТЕЛЕФОН', 'oreh'); ?></span>
          <input type="tel" name="phone" required placeholder="+7 ___ ___-__-__" class="form__input" />
        </label>
        <label class="form__field">
          <span class="form__label"><?php esc_html_e('ИНТЕРЕСУЕТ', 'oreh'); ?></span>
          <select name="product" class="form__select">
            <?php
            if (class_exists('WooCommerce')) {
                $oreh_form_products = get_posts([
                    'post_type'      => 'product',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                ]);
                foreach ($oreh_form_products as $oreh_form_product) {
                    echo '<option>' . esc_html(get_the_title($oreh_form_product)) . '</option>';
                }
            }
            ?>
            <option><?php esc_html_e('Другое', 'oreh'); ?></option>
          </select>
        </label>
        <label class="form__field">
          <span class="form__label"><?php esc_html_e('КОММЕНТАРИЙ', 'oreh'); ?></span>
          <textarea name="comment" rows="3" placeholder="<?php esc_attr_e('Город, вопросы по доставке', 'oreh'); ?>" class="form__textarea"></textarea>
        </label>
        <label class="form__consent">
          <input type="checkbox" required class="form__checkbox" />
          <span><?php esc_html_e('Согласен с обработкой персональных данных', 'oreh'); ?></span>
        </label>
        <button type="submit" class="form__submit"><?php esc_html_e('Отправить заявку', 'oreh'); ?></button>
      </form>
    </div>
  </div>
</section>

<?php get_footer(); ?>
