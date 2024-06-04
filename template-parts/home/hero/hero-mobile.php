<?php
if (empty($fields) || empty($fields['hero_items'])) {
    return;
}

$categories = !empty($fields['main_categories']) ? $fields['main_categories'] : [];
?>

<section class="section section_hero hero_mobile">
    <div class="hero__wrapper">
        <div class="hero__items swiper hero-slider">
            <div class="swiper-pagination hero-slider-pagination"></div>
            <div class="swiper-wrapper">
                <?php foreach ($fields['hero_items'] as $item):
                    $link = $item['link'] ?? [];
                    if (empty($link)) continue; ?>

                    <a href="<?php echo esc_url($link['url'] ?? ''); ?>"
                       target="<?php echo !empty($link['target']) ? esc_attr($link['target']) : '_self'; ?>"
                       class="hero__item swiper-slide">
                        <div class="hero__item_image">
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?php echo esc_url($item['image']); ?>"
                                     alt="<?php _e('Hero image', DOMAIN); ?>"
                                     title="<?php _e('Hero image', DOMAIN); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="hero__item_body"
                             style="color: <?php echo !empty($item['text_color']) ? $item['text_color'] : '#ffffff'; ?>">
                            <?php _get_field($item['hash'], 'hero__item_hash'); ?>
                            <div class="hero__item_content">
                                <?php
                                _get_field($item['subtitle'], 'hero__item_subtitle');
                                _get_field($item['title'], 'hero__item_title big_title');
                                _get_field($link['title'], 'hero__item_link');
                                ?>
                            </div>
                        </div>
                    </a>

                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>