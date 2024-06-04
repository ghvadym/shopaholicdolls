<?php

if (empty($fields) || empty($category_field_name)) {
    return;
}

$categories = $fields[$category_field_name . '_categories'] ?? '';

if (empty($categories)) {
    return;
}

$title = $fields[$category_field_name . '_cat_title'] ?? '';
$link = $fields[$category_field_name . '_cat_link'] ?? '';

$desktop_version = !empty($desktop) ? ' desktop_version' : '';
?>

<section class="section categories_list<?php echo $desktop_version; ?>">
    <div class="container">
        <?php _get_field($title, 'cat__title', 'h2'); ?>
        <div class="cat__content">
            <div class="cat__list">
                <?php foreach ($categories as $category_item):
                    $category = $category_item['category'] ?? '';

                    if (empty($category)):
                        continue;
                    endif;

                    $title = $category_item['title'] ?? '';
                    $image_url = '';
                    $image_id = $category_item['img'] ?? '';

                    if ($image_id) {
                        $image_url = wp_get_attachment_image_url($image_id, 'large');
                    }

                    if (!$image_url) {
                        $image_url = get_term_thumbnail_url($category->term_id);
                    }

                    $cat_link = get_term_link($category->term_id, 'product_cat'); ?>

                    <a href="<?php echo esc_url($cat_link); ?>"
                       class="cat__item">
                        <div class="cat__item_img">
                            <?php if ($image_url): ?>
                                <img src="<?php echo esc_url($image_url); ?>"
                                     alt="<?php echo esc_attr($category->name); ?>"
                                     title="<?php echo esc_attr($category->name); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="cat__item-info-wrap">
                            <h3 class="cat__item_title big_title">
                                <?php echo esc_html($title ?: $category->name); ?>
                            </h3>
                            <div class="cat__item_link btn-plain">
                                <?php echo !empty($link['title']) ? esc_html($link['title']) : __('View more', DOMAIN); ?>
                            </div>
                        </div>
                    </a>

                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
