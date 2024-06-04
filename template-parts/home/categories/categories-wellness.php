<?php
$pageID = get_the_ID();
$fields = get_fields($pageID);

$wellness_section_title = $fields['wellness_cat_title'] ?? '';
$wellness_categories = $fields['wellness_categories'] ?? [];

if (empty($wellness_categories)) {
    return;
}
?>

<section class="section wellness">
    <div class="container">
        <?php if (!empty($wellness_section_title)) : ?>
            <h2 class="wellness__title"><?php echo $wellness_section_title; ?></h2>
        <?php endif; ?>

        <div class="wellness__list">
            <?php foreach ($wellness_categories as $cat_item):
                $wellness_category = $cat_item['category'] ?? '';

                if (empty($wellness_category)):
                    continue;
                endif;

                $title = $category_item['title'] ?? '';
                $cat_link = get_term_link($wellness_category->term_id, 'product_cat');
                $image_url = '';
                $image_id = $cat_item['img'] ?? '';
                $custom_image = wp_get_attachment_image_url($image_id, 'large');
                $category_image = get_term_thumbnail_url($wellness_category->term_id);

                if ($image_id) {
                    $image_url = $custom_image;
                } else  {
                    $image_url = $category_image;
                }
                ?>

                <a href="<?php echo esc_url($cat_link); ?>" class="wellness__item">
                    <div class="wellness__item-gradient">
                    </div>
                    <div class="wellness__item_img">
                        <?php if ($image_url): ?>
                            <img src="<?php echo esc_url($image_url); ?>"
                                 alt="<?php echo esc_attr($wellness_category->name); ?>"
                                 title="<?php echo esc_attr($wellness_category->name); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="wellness__item-info-wrap">
                        <h3 class="wellness__item_title big_title">
                            <?php echo esc_html($title ?: $wellness_category->name); ?>
                        </h3>
                        <div class="wellness__item_link btn-plain">
                            <?php echo !empty($link['title']) ? esc_html($link['title']) : __('View more', DOMAIN); ?>
                        </div>
                    </div>
                </a>

            <?php endforeach; ?>
        </div>
    </div>
</section>

