<?php

if (empty($fields) || empty($category_field_name)) {
    return;
}

$categories = $fields[$category_field_name . '_categories'] ?? '';

if (empty($categories)) {
    return;
}

$title = $fields[$category_field_name . '_cat_title'];
$link = $fields[$category_field_name . '_cat_link'] ?? '';

$mobile_version = !empty($mobile) ? ' mobile_version' : '';
?>

<section class="section categories_slider<?php echo $mobile_version; ?>">
    <div class="container">
        <?php _get_field($title, 'cat_slider__title', 'h2'); ?>
        <div class="cat_slider__content">
            <div class="cat_slider__list swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($categories as $category_item):
                        $category = $category_item['category'] ?? '';

                        if (empty($category)):
                            continue;
                        endif;

                        $title = $category_item['title'] ?? '';
                        $image_url = '';
                        $image_id = $category_item['img'] ?? '';

                        if ($image_id) {
                            $image_url = wp_get_attachment_image_url($image_id, 'medium');
                        }

                        if (!$image_url) {
                            $image_url = get_term_thumbnail_url($category->term_id);
                        }

                        $cat_link = get_term_link($category->term_id); ?>

                        <a href="<?php echo esc_url($cat_link); ?>"
                           class="cat_slider__item swiper-slide">
                            <div class="cat_slider__body">
                                <div class="cat_slider__body-content">
                                    <p class="cat_slider__item_title big_title"><?php echo esc_html($title ?: $category->name); ?></p>
                                    <span class="cat_slider__link btn-plain"><?php _e('View more', DOMAIN); ?></span>
                                </div>
                                <div class="cat_slider__item_img">
                                    <?php if ($image_url): ?>
                                        <img src="<?php echo esc_url($image_url); ?>"
                                             alt="<?php echo esc_attr($category->name); ?>"
                                             title="<?php echo esc_attr($category->name); ?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php if (!empty($link['url'])): ?>
            <a href="<?php echo esc_url($link['url'] ?? ''); ?>"
               target="<?php echo !empty($link['target']) ? esc_attr($link['target']) : '_self'; ?>"
               class="btn-plain-wrapper">
                    <div class="btn-plain">
                        <?php echo !empty($link['title']) ? esc_html($link['title']) : __('View more', DOMAIN); ?>
                    </div>
            </a>
        <?php endif; ?>
    </div>
</section>
