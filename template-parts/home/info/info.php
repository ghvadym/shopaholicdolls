<?php
$padeID = get_the_ID();
$fields = get_fields($padeID);
$info_items = $fields['info_items'] ?? [];

if (empty($info_items)) {
    return;
}
?>

<section class="section info">
    <div class="container">
        <div class="info__list">
            <?php foreach ($info_items as $item) :
                $image_desktop_id = $item['image_desktop'] ?? '';
                $image_mobile_id = $item['image_mobile'] ?? '';
                $title = $item['title'] ?? '';
                $subtitle = $item['subtitle'] ?? '';
                $link_type = $item['link_type'] ?? [];
                $link_arr = $item['link'] ?? [];
                $category = $item['category'] ?? [];
                $text_color = $item['text_color'] ?? '';

                $link = '';
                $image_url = '';
                $image_mob_url = '';
                $image_alt = '';

                if ($image_desktop_id) {
                    $image_url = wp_get_attachment_image_url($image_desktop_id, 'large');
                }

                if ($image_mobile_id) {
                    $image_mob_url = wp_get_attachment_image_url($image_mobile_id, 'large');
                }

                if ($link_type == 'category' && $category) {
                    $link = get_term_link($category->term_id);

                    if (!$image_url) {
                        $image_url = get_term_thumbnail_url($category->term_id);
                    }

                    $image_alt = $category->name;
                } elseif ($link_type == 'custom_link') {
                    $link = $link_arr['url'] ?? '';
                    $image_alt = $title;
                }

                ?>
                <div class="info__item">
                    <a href="<?php echo $link; ?>" class="info__item-link">
                        <div class="info__item-image">
                            <?php if ($image_url): ?>
                                <img src="<?php echo esc_url($image_url); ?>"
                                     alt="<?php echo esc_attr($image_alt); ?>"
                                     title="<?php echo esc_attr($image_alt); ?>"
                                     class="desktop_version">
                            <?php endif; ?>

                            <?php if ($image_mob_url): ?>
                                <img src="<?php echo esc_url($image_mob_url); ?>"
                                     alt="<?php echo esc_attr($image_alt); ?>"
                                     title="<?php echo esc_attr($image_alt); ?>"
                                     class="mobile_version">
                            <?php endif; ?>
                        </div>

                        <div class="info__item-content">
                            <?php if (!empty($subtitle)) : ?>
                                <p class="info__item-subtitle" style="color: <?php echo $text_color; ?>"><?php echo $subtitle; ?></p>
                            <?php endif; ?>

                            <?php if (!empty($title)) : ?>
                                <h3 class="info__item-title big_title" style="color: <?php echo $text_color; ?>"><?php echo $title; ?></h3>
                            <?php endif; ?>

                            <div class="info__link" style="color: <?php echo $text_color; ?>">
                                <?php echo !empty($link_arr['title']) ? esc_html($link_arr['title']) : __('View more', DOMAIN); ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

