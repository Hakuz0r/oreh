<?php
if (!defined('ABSPATH')) exit;

/**
 * Дополнительные слои защиты от спама — работают независимо от
 * reCAPTCHA (она не 100% барьер, боты её иногда решают через платные
 * сервисы разгадывания). Три проверки:
 *  1. Honeypot — невидимое человеку поле, простые боты его заполняют.
 *  2. Временная ловушка — форма не может быть отправлена за < 3 сек.
 *  3. Лимит по IP — не больше 3 заявок за 10 минут с одного адреса.
 */

function oreh_antispam_widget() {
    ?>
    <div class="oreh-hp" aria-hidden="true">
      <label>
        <?php esc_html_e('Оставьте это поле пустым', 'oreh'); ?>
        <input type="text" name="oreh_website" tabindex="-1" autocomplete="off" />
      </label>
    </div>
    <input type="hidden" name="oreh_ts" value="<?php echo esc_attr(time()); ?>" />
    <?php
}

/**
 * true — похоже на человека, false — похоже на бота (тихо отклоняем,
 * без объяснений в интерфейсе, чтобы не подсказывать боту, что именно
 * его выдало).
 */
function oreh_antispam_check() {
    // 1. Honeypot: у настоящего пользователя это поле всегда пустое.
    if (!empty($_POST['oreh_website'])) {
        return false;
    }

    // 2. Время: меньше 3 секунд от загрузки страницы до отправки формы.
    $ts = isset($_POST['oreh_ts']) ? (int) $_POST['oreh_ts'] : 0;
    if ($ts <= 0 || (time() - $ts) < 3) {
        return false;
    }

    // 3. Лимит по IP — общий для обеих форм, чтобы бот не обходил его,
    // просто переключаясь между формой заявки и корзиной.
    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
    if ($ip !== '') {
        $key   = 'oreh_rl_' . md5($ip);
        $count = (int) get_transient($key);
        if ($count >= 3) {
            return false;
        }
        set_transient($key, $count + 1, 10 * MINUTE_IN_SECONDS);
    }

    return true;
}
