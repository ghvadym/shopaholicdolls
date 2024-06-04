<?php

$padeID = get_the_ID();
$fields = get_fields($padeID);
$sale_fields = $fields['sale'] ?? [];

$sale_title = $sale_fields['section_title'] ?? '';
$sale_image_desktop_id = $sale_fields['image_desktop'] ?? '';
$sale_image_mobile_id = $sale_fields['image_mobile'] ?? '';
$sale_item_title = $sale_fields['image_title'] ?? '';
$sale_item_subtitle = $sale_fields['image_subtitle'] ?? '';
$sale_item_link = $sale_fields['link'] ?? [];
$sale_link_url = $sale_fields['url'] ?? '';
$sale_link_title = $sale_fields['title'] ?? '';
$sale_item_text_color = $sale_fields['text_color'] ?? '';

if ($sale_image_desktop_id) {
    $image = wp_get_attachment_image($sale_image_desktop_id, 'full', '', [
        'alt'   => $sale_title,
        'title' => $sale_title,
        'class' => 'sale__image'
    ]);
}

if ($sale_image_mobile_id) {
    $image_mob = wp_get_attachment_image($sale_image_mobile_id, 'full', '', [
        'alt'   => $sale_title,
        'title' => $sale_title,
        'class' => 'sale__image'
    ]);
}

if (empty($image) && empty($image_mob)) {
    return;
}
?>

<section class="section sale">
    <div class="container">
        <?php if (!empty($sale_title)) : ?>
            <h2 class="sale__title" style="color: #1b1b1b"><?php echo $sale_title; ?></h2>
        <?php endif; ?>

        <a href="<?php echo $sale_item_link; ?>" class="sale__item">
            <div class="sale__image-wrap">
                <?php if (!empty($image)): ?>
                    <div class="desktop_version">
                        <?php echo $image; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($image_mob)): ?>
                    <div class="mobile_version">
                        <?php echo $image_mob; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="sale__content-wrap">
                <?php if (!empty($sale_item_subtitle)) : ?>
                    <span class="sale__item-subtitle" style="color: <?php echo $sale_item_text_color ?>"><?php echo $sale_item_subtitle; ?></span>
                <?php endif; ?>

                <?php if (!empty($sale_item_title)) : ?>
                    <p class="sale__item-title big_title" style="color: <?php echo $sale_item_text_color ?>"><?php echo $sale_item_title; ?></p>
                <?php endif; ?>

                <?php if (!empty($sale_link_title)) : ?>
                    <span class="sale__item-link-text btn-plain" style="color: <?php echo $sale_item_text_color ?>"><?php echo $sale_link_title; ?></span>
                <?php endif; ?>
            </div>
        </a>
    </div>
</section>