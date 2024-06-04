<?php

$brands_taxonomy = get_taxonomy('pwb-brand');

if (empty($brands_taxonomy)) {
    return;
}

$brands_image_url = get_field('brands_image', 'options');

$image_id = $fields['image'] ?? '';
$link_array = $fields['link'] ?? '';
$image_url = '';
$link = '';

if ($image_id) {
    $image_url = wp_get_attachment_image_url($image_id, 'medium');
}

if (!empty($link_array)) {
    $link = $link_array['url'] ?? '';
}
?>

<div class="mega_menu__item menu-item-brands brands-desktop">
    <div class="mega_menu__body">
        <?php if (!empty($image_url)): ?>
            <div class="mega_menu__media">
                <div class="mega_menu__image" style="background-image: url('<?php echo $image_url; ?>')">
                    <?php if (!empty($title)): ?>
                        <div class="mega_menu__image_title big_title">
                            <?php echo $title; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="mega_menu__content">
            <div class="mega_menu__head">
                <?php if (!empty($title)): ?>
                    <div class="mega_menu__title">
                        <?php echo $title; ?>
                    </div>
                <?php endif; ?>
                <div class="mega_menu__label">
                    <strong>
                        <?php esc_html_e('Most wanted', DOMAIN); ?>
                    </strong>
                </div>
                <div class="mega_menu__filter">
                    <?php alphabet('mega_menu__alphabet', 'mega_menu__letter'); ?>
                </div>
            </div>
            <div class="mega_menu__links mega_menu__most_wanted_brands">
                <?php if (!empty($most_wanted_brands)):
                    $most_wanted_brands = array_chunk($most_wanted_brands, 4, true);
                    foreach ($most_wanted_brands as $brand): ?>
                        <div class="menu-item">
                            <?php foreach ($brand as $brand_id => $total_sales):
                                $brand_term = get_term_by('id', $brand_id, 'pwb-brand'); ?>
                                <a href="<?php echo get_term_link($brand_id); ?>">
                                    <?php echo $brand_term->name; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="mega_menu__links mega_menu__brand_filter_results d-none"></div>
        </div>
    </div>
</div>