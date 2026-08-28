<?php
if (!defined('ABSPATH')) exit;

get_header();

$oreh_sent = isset($_GET['sent']) ? sanitize_text_field(wp_unslash($_GET['sent'])) : '';
?>

<section id="top" class="intro">
  <h1 class="intro__title">TriaMotion 3 In 1</h1>
  <p class="intro__subtitle"><?php esc_html_e('Твое идеальное пространство для тренировок', 'oreh'); ?></p>
</section>

<section class="hero">
  <div class="hero__overlay"></div>
  <div class="hero__inner">
    <div class="hero__content">
      <h2 class="hero__title">TriaMotion</h2>
      <p class="hero__subtitle"><?php esc_html_e('Меняй тренировку одним движением', 'oreh'); ?></p>
      <div class="hero__actions">
        <a href="#equipment" class="btn btn--primary"><?php esc_html_e('Выбрать оборудование', 'oreh'); ?></a>
        <a href="#why" class="btn btn--outline"><?php esc_html_e('Почему мы', 'oreh'); ?></a>
      </div>
    </div>
  </div>
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
      <h2 class="why__title"><?php esc_html_e('Почему мы', 'oreh'); ?></h2>
      <p class="why__text"><?php esc_html_e('Оборудование собирается вручную из массива ясеня и натуральной кожи. Никакого пластика, никаких лишних деталей.', 'oreh'); ?></p>
      <div class="why__image">
        <picture>
          <source srcset="<?php echo esc_url(get_template_directory_uri() . '/assets/images/detail-hinge.webp'); ?>" type="image/webp" />
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/detail-hinge.jpg'); ?>" alt="<?php esc_attr_e('Скамья OREH', 'oreh'); ?>" loading="lazy" />
        </picture>
      </div>
    </div>
    <div class="why__list">
      <div class="why__item">
        <span class="why__item-number">01</span>
        <h3 class="why__item-title"><?php esc_html_e('Массив ясеня', 'oreh'); ?></h3>
        <p class="why__item-text"><?php esc_html_e('Рама из цельного дерева с покрытием маслом. Каждая деталь шлифуется вручную.', 'oreh'); ?></p>
      </div>
      <div class="why__item">
        <span class="why__item-number">02</span>
        <h3 class="why__item-title"><?php esc_html_e('Натуральная кожа', 'oreh'); ?></h3>
        <p class="why__item-text"><?php esc_html_e('Обивка из плотной кожи на упругом наполнителе — держит форму под нагрузкой.', 'oreh'); ?></p>
      </div>
      <div class="why__item">
        <span class="why__item-number">03</span>
        <h3 class="why__item-title"><?php esc_html_e('Три положения', 'oreh'); ?></h3>
        <p class="why__item-text"><?php esc_html_e('Скамья, наклон и станок для пресса — рама меняет конфигурацию одним движением.', 'oreh'); ?></p>
      </div>
      <div class="why__item">
        <span class="why__item-number">04</span>
        <h3 class="why__item-title"><?php esc_html_e('Складывается без инструментов', 'oreh'); ?></h3>
        <p class="why__item-text"><?php esc_html_e('Стальные фиксаторы и шарниры на подшипниках. Хранится в квартире, не занимая места.', 'oreh'); ?></p>
      </div>
    </div>
  </div>
</section>

<section id="contacts" class="contact">
  <div class="contact__grid">
    <div>
      <h2 class="contact__title"><?php esc_html_e('Оставить заявку', 'oreh'); ?></h2>
      <p class="contact__text"><?php esc_html_e('Расскажем про наличие, сроки изготовления и доставку. Отвечаем в течение рабочего дня.', 'oreh'); ?></p>
      <div class="contact__details">
        <a href="tel:<?php echo esc_attr(oreh_phone_href()); ?>" class="contact__phone"><?php echo esc_html(get_theme_mod('oreh_phone', '+7 999 123-45-67')); ?></a>
        <a href="mailto:<?php echo esc_attr(get_theme_mod('oreh_email', 'info@oreh.ru')); ?>" class="contact__email"><?php echo esc_html(get_theme_mod('oreh_email', 'info@oreh.ru')); ?></a>
        <span class="contact__hours"><?php echo esc_html(get_theme_mod('oreh_hours', 'Пн — Сб, 10:00 — 19:00')); ?></span>
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
