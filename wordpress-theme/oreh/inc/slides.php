<?php
if (!defined('ABSPATH')) exit;

/**
 * Слайды главного баннера — свой тип записей вместо ACF: та же удобная
 * админка (заголовок, картинка записи, порядок перетаскиванием), но
 * без лишнего плагина на сервере.
 */
add_action('init', function () {
    register_post_type('oreh_slide', [
        'labels' => [
            'name'               => __('Слайды', 'oreh'),
            'singular_name'      => __('Слайд', 'oreh'),
            'add_new_item'       => __('Добавить слайд', 'oreh'),
            'edit_item'          => __('Редактировать слайд', 'oreh'),
            'all_items'          => __('Слайды баннера', 'oreh'),
            'featured_image'     => __('Фон слайда', 'oreh'),
            'set_featured_image' => __('Задать фон слайда', 'oreh'),
        ],
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-images-alt2',
        'menu_position'      => 26,
        'supports'           => ['title', 'thumbnail', 'page-attributes'],
        'capability_type'    => 'page',
        'map_meta_cap'       => true,
        'hierarchical'       => false,
    ]);
});

add_action('add_meta_boxes', function () {
    add_meta_box(
        'oreh_slide_fields',
        __('Текст и кнопка слайда', 'oreh'),
        'oreh_render_slide_meta_box',
        'oreh_slide',
        'normal',
        'high'
    );
});

function oreh_slide_overlay_options() {
    return [
        'off'    => __('Выключено', 'oreh'),
        'light'  => __('Слабое', 'oreh'),
        'normal' => __('Обычное (по умолчанию)', 'oreh'),
        'strong' => __('Сильное', 'oreh'),
    ];
}

function oreh_slide_fit_options() {
    return [
        'auto'    => __('Авто (по пропорциям фото)', 'oreh'),
        'cover'   => __('Заполнить блок — края обрезаются', 'oreh'),
        'contain' => __('Показать фото целиком', 'oreh'),
    ];
}

function oreh_render_slide_meta_box($post) {
    wp_nonce_field('oreh_slide_save', 'oreh_slide_nonce');

    $subtitle = get_post_meta($post->ID, '_oreh_slide_subtitle', true);
    $btn_text = get_post_meta($post->ID, '_oreh_slide_btn_text', true);
    $btn_url  = get_post_meta($post->ID, '_oreh_slide_btn_url', true);
    $overlay  = get_post_meta($post->ID, '_oreh_slide_overlay', true);
    if ($overlay === '') {
        $overlay = 'normal';
    }
    $fit = get_post_meta($post->ID, '_oreh_slide_fit', true);
    if (!array_key_exists($fit, oreh_slide_fit_options())) {
        $fit = 'auto';
    }
    $pos_x = get_post_meta($post->ID, '_oreh_slide_pos_x', true);
    $pos_y = get_post_meta($post->ID, '_oreh_slide_pos_y', true);
    ?>
    <p>
        <label for="oreh_slide_subtitle"><strong><?php esc_html_e('Подзаголовок', 'oreh'); ?></strong></label><br />
        <input type="text" id="oreh_slide_subtitle" name="oreh_slide_subtitle" value="<?php echo esc_attr($subtitle); ?>" class="widefat" />
    </p>
    <p>
        <label for="oreh_slide_btn_text"><strong><?php esc_html_e('Текст кнопки', 'oreh'); ?></strong></label><br />
        <input type="text" id="oreh_slide_btn_text" name="oreh_slide_btn_text" value="<?php echo esc_attr($btn_text); ?>" class="widefat" placeholder="<?php esc_attr_e('Выбрать оборудование', 'oreh'); ?>" />
    </p>
    <p>
        <label for="oreh_slide_btn_url"><strong><?php esc_html_e('Ссылка кнопки', 'oreh'); ?></strong></label><br />
        <input type="text" id="oreh_slide_btn_url" name="oreh_slide_btn_url" value="<?php echo esc_attr($btn_url); ?>" class="widefat" placeholder="#equipment" />
    </p>
    <p>
        <label for="oreh_slide_overlay"><strong><?php esc_html_e('Затемнение фона', 'oreh'); ?></strong></label><br />
        <select id="oreh_slide_overlay" name="oreh_slide_overlay">
            <?php foreach (oreh_slide_overlay_options() as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($overlay, $value); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php esc_html_e('Затемняет фото под текстом, чтобы заголовок и кнопка оставались читаемыми. Если фото и так тёмное или спокойное — можно ослабить или выключить.', 'oreh'); ?></p>
    </p>
    <p>
        <label for="oreh_slide_fit"><strong><?php esc_html_e('Масштаб фото', 'oreh'); ?></strong></label><br />
        <select id="oreh_slide_fit" name="oreh_slide_fit">
            <?php foreach (oreh_slide_fit_options() as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($fit, $value); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php esc_html_e('«Авто» — вертикальные фото на широком экране показываются целиком и прижимаются вправо, горизонтальные растягиваются на весь баннер. Если фото на десктопе выглядит слишком приближенным — поставьте «Показать фото целиком».', 'oreh'); ?></p>
    </p>
    <p>
        <label for="oreh_slide_pos_x"><strong><?php esc_html_e('Положение фото по горизонтали, %', 'oreh'); ?></strong></label><br />
        <input type="number" min="0" max="100" step="1" id="oreh_slide_pos_x" name="oreh_slide_pos_x" value="<?php echo esc_attr($pos_x); ?>" placeholder="50" />
        <p class="description"><?php esc_html_e('0 — прижать к левому краю, 50 — по центру, 100 — к правому. Пусто — как решит «Авто».', 'oreh'); ?></p>
    </p>
    <p>
        <label for="oreh_slide_pos_y"><strong><?php esc_html_e('Положение фото по вертикали, %', 'oreh'); ?></strong></label><br />
        <input type="number" min="0" max="100" step="1" id="oreh_slide_pos_y" name="oreh_slide_pos_y" value="<?php echo esc_attr($pos_y); ?>" placeholder="36" />
        <p class="description"><?php esc_html_e('Какая часть фото остаётся видимой при обрезке: 0 — верх, 50 — центр, 100 — низ. Если обрезает головы — поставьте 0–20.', 'oreh'); ?></p>
    </p>
    <p class="description"><?php esc_html_e('Не забудьте задать «Фон слайда» справа (изображение записи) — это фото на слайде. Порядок слайдов задаётся перетаскиванием в списке «Слайды баннера».', 'oreh'); ?></p>
    <?php
}

add_action('save_post_oreh_slide', function ($post_id) {
    if (!isset($_POST['oreh_slide_nonce']) || !wp_verify_nonce($_POST['oreh_slide_nonce'], 'oreh_slide_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['oreh_slide_subtitle'])) {
        update_post_meta($post_id, '_oreh_slide_subtitle', sanitize_text_field(wp_unslash($_POST['oreh_slide_subtitle'])));
    }
    if (isset($_POST['oreh_slide_btn_text'])) {
        update_post_meta($post_id, '_oreh_slide_btn_text', sanitize_text_field(wp_unslash($_POST['oreh_slide_btn_text'])));
    }
    if (isset($_POST['oreh_slide_btn_url'])) {
        update_post_meta($post_id, '_oreh_slide_btn_url', sanitize_text_field(wp_unslash($_POST['oreh_slide_btn_url'])));
    }
    if (isset($_POST['oreh_slide_overlay']) && array_key_exists($_POST['oreh_slide_overlay'], oreh_slide_overlay_options())) {
        update_post_meta($post_id, '_oreh_slide_overlay', sanitize_key($_POST['oreh_slide_overlay']));
    }
    if (isset($_POST['oreh_slide_fit']) && array_key_exists($_POST['oreh_slide_fit'], oreh_slide_fit_options())) {
        update_post_meta($post_id, '_oreh_slide_fit', sanitize_key($_POST['oreh_slide_fit']));
    }
    foreach (['pos_x', 'pos_y'] as $axis) {
        $field = 'oreh_slide_' . $axis;
        if (!isset($_POST[$field])) {
            continue;
        }
        $value = trim(wp_unslash($_POST[$field]));
        if ($value === '') {
            delete_post_meta($post_id, '_oreh_slide_' . $axis);
        } else {
            update_post_meta($post_id, '_oreh_slide_' . $axis, max(0, min(100, (int) $value)));
        }
    }
});

/**
 * Достаём слайды для главной — упорядочены как в списке в админке.
 */
function oreh_get_slides() {
    $slides = get_posts([
        'post_type'      => 'oreh_slide',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ]);

    return array_map(function ($slide) {
        $btn_text = get_post_meta($slide->ID, '_oreh_slide_btn_text', true);
        $btn_url  = get_post_meta($slide->ID, '_oreh_slide_btn_url', true);
        $overlay  = get_post_meta($slide->ID, '_oreh_slide_overlay', true);
        if (!array_key_exists($overlay, oreh_slide_overlay_options())) {
            $overlay = 'normal';
        }

        $fit = get_post_meta($slide->ID, '_oreh_slide_fit', true);
        if (!array_key_exists($fit, oreh_slide_fit_options())) {
            $fit = 'auto';
        }

        // Вертикальное фото, растянутое на всю ширину десктопного баннера,
        // превращается в бессмысленный кроп, поэтому в «Авто» показываем его целиком.
        $thumb_meta = wp_get_attachment_metadata(get_post_thumbnail_id($slide->ID));
        $is_tall    = !empty($thumb_meta['width']) && !empty($thumb_meta['height'])
            && $thumb_meta['height'] >= $thumb_meta['width'];

        $fit_desktop = $fit === 'auto' ? ($is_tall ? 'contain' : 'cover') : $fit;
        $fit_mobile  = $fit === 'auto' ? 'cover' : $fit;

        $subtitle = get_post_meta($slide->ID, '_oreh_slide_subtitle', true);
        $has_text = get_the_title($slide) !== '' || $subtitle !== '';

        $pos_x_raw = get_post_meta($slide->ID, '_oreh_slide_pos_x', true);
        $pos_y_raw = get_post_meta($slide->ID, '_oreh_slide_pos_y', true);
        $pos_x = $pos_x_raw === '' ? 50 : max(0, min(100, (int) $pos_x_raw));
        $pos_y = $pos_y_raw === '' ? 36 : max(0, min(100, (int) $pos_y_raw));
        // Фото целиком на десктопе прижимаем вправо, чтобы оно не лезло под текст;
        // на слайде без текста освобождать левый край не от чего — оставляем по центру.
        $pos_x_desktop = ($pos_x_raw === '' && $fit_desktop === 'contain' && $has_text) ? 100 : $pos_x;

        return [
            'id'          => $slide->ID,
            'title'       => get_the_title($slide),
            'subtitle'    => $subtitle,
            'has_button'  => $btn_text !== '' || $btn_url !== '',
            'btn_text'    => $btn_text !== '' ? $btn_text : __('Выбрать оборудование', 'oreh'),
            'btn_url'     => $btn_url !== '' ? $btn_url : '#equipment',
            'overlay'     => $overlay,
            'image'       => get_the_post_thumbnail_url($slide->ID, 'full'),
            'fit_desktop' => $fit_desktop,
            'fit_mobile'  => $fit_mobile,
            'pos_desktop' => $pos_x_desktop . '% ' . $pos_y . '%',
            'pos_mobile'  => $pos_x . '% ' . $pos_y . '%',
        ];
    }, $slides);
}

/**
 * Стартовый слайд из старого макета, чтобы главная не была пустой,
 * пока admin не добавит свои. Вызывается при активации темы; если
 * тема уже была активна на момент обновления кода — можно вызвать
 * вручную: wp eval "oreh_maybe_seed_default_slide();"
 */
add_action('after_switch_theme', 'oreh_maybe_seed_default_slide');

function oreh_maybe_seed_default_slide() {
    $existing = get_posts(['post_type' => 'oreh_slide', 'posts_per_page' => 1, 'post_status' => 'any']);
    if ($existing) {
        return;
    }

    $slide_id = wp_insert_post([
        'post_type'   => 'oreh_slide',
        'post_title'  => 'TriaMotion',
        'post_status' => 'publish',
        'menu_order'  => 0,
    ]);

    if (!$slide_id || is_wp_error($slide_id)) {
        return;
    }

    update_post_meta($slide_id, '_oreh_slide_subtitle', 'Меняй тренировку одним движением');
    update_post_meta($slide_id, '_oreh_slide_btn_text', 'Выбрать оборудование');
    update_post_meta($slide_id, '_oreh_slide_btn_url', '#equipment');

    $image_path = get_template_directory() . '/assets/images/hero.jpg';
    if (file_exists($image_path)) {
        $upload = wp_upload_bits('hero-slide-1.jpg', null, file_get_contents($image_path));
        if (empty($upload['error'])) {
            $attachment_id = wp_insert_attachment([
                'post_mime_type' => 'image/jpeg',
                'post_title'     => 'TriaMotion',
                'post_status'    => 'inherit',
            ], $upload['file'], $slide_id);
            require_once ABSPATH . 'wp-admin/includes/image.php';
            wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $upload['file']));
            set_post_thumbnail($slide_id, $attachment_id);
        }
    }
}
