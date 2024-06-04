<?php
$padeID = get_the_ID();
$fields = get_fields($padeID);
$skin_types_title = $fields['skin_types_section_title'] ?? '';
$skin_types = $fields['skin_types'] ?? [];

if (empty($skin_types)) {
    return;
}
?>

<section class="section skin-types">
    <div class="container">
        <?php if (!empty($skin_types_title)) : ?>
            <h2 class="skin-types__title"><?php echo $skin_types_title; ?></h2>
        <?php endif; ?>

        <div class="skin-types__list">
            <?php foreach ($skin_types as $item) :
                $image_id = $item['image'] ?? '';
                $item_title = $item['title'] ?? '';
                $link_arr = $item['link'] ?? [];
                $link_url = $link_arr['url'] ?? '';
                $link_title = $link_arr['title'] ?? '';
                $image_url = wp_get_attachment_image_url($image_id, 'large');
                ?>
                <div class="skin-types__item">
                    <a href="<?php echo $link_url; ?>" class="skin-types__item-link">
                        <div class="skin-types__item-image">
                            <div class="skin-types__item-gradient"></div>
                            <?php if ($image_url): ?>
                                <img src="<?php echo $image_url; ?>"
                                     alt="<?php echo __('Skin', DOMAIN) . ' ' . $item_title; ?>"
                                     title="<?php echo __('Skin', DOMAIN) . ' ' . $item_title; ?>">
                            <?php endif; ?>
                        </div>

                        <div class="skin-types__item-content">
                            <h3 class="skin-types__item-title big_title"><?php echo $item_title; ?></h3>
                            <div class="skin-types__item-link-text">
                                <?php echo !empty($link_arr['title']) ? esc_html($link_arr['title']) : __('View more', DOMAIN); ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
