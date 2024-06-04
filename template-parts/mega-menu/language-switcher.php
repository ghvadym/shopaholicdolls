<?php
if (!empty(get_field('hide_language_switcher', 'options'))) {
    return;
}

$languages = get_languages(true);

if (empty($languages)) {
    return;
}
?>

<div class="header__lang_switcher">
    <div class="lang_switcher__head">
        <div class="lang_switcher__icon">
            <?php echo get_local_img_html('Globe.svg', '', __('Language switcher', DOMAIN)); ?>
        </div>
        <div class="lang_switcher__selected">
            <?php echo CURRENT_LANG; ?>
        </div>
        <div class="lang_switcher__arrow">
            <?php echo get_local_img_html('Arrow.svg', '', __('Language switcher', DOMAIN)); ?>
        </div>
    </div>
    <div class="lang_switcher__dropdown">
        <div class="lang_switcher__list">
            <?php foreach ($languages as $code => $data):
                if ($code == CURRENT_LANG) continue; ?>
                <a href="<?php echo esc_attr($data['url']); ?>" class="lang_switcher__item">
                    <img class="lang_switcher__item_flag contain"
                         src="<?php echo get_local_img_url("flag-$code.svg"); ?>"
                         alt="<?php _e('Language ' . $code, DOMAIN); ?>"
                         title="<?php _e('Language ' . $code, DOMAIN); ?>">
                    <div class="lang_switcher__item_name">
                    <?php
                        if ($data['name'] === 'PL') {
                            echo 'POLSKI';
                        } elseif ($data['name'] === 'ENG') {
                            echo 'ENGLISH';
                        } else {
                            echo $data['name'];
                        }
                    ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>