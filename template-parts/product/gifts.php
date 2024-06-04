<?php
global $product;
$id = $product->get_id();
$product_types = wp_get_post_terms($id, 'product_type');
$product_type_name = $product_types[0]->slug;

if ($product_type_name === 'pw-gift-card') {
    return;
}

$gifts = gifts_data();
$gifts_steps = $gifts['steps'] ?? [];
$gifts_title = get_field('gifts_title', 'options') ?: __('Your gift', DOMAIN);

if (empty($gifts_steps)) {
    return;
}

?>

<div class="free_gifts">
    <div class="free_gifts__select custom_select">
        <div class="custom_select__head">
            <?php get_svg('gift'); ?>
            <div class="custom_select__title">
                <?php echo esc_html($gifts_title); ?>
            </div>
            <?php get_svg('arrow-select'); ?>
        </div>
        <div class="custom_select__list">
            <div class="custom_select__items">
                <?php foreach ($gifts_steps as $step => $gifts):
                    if (empty($gifts)) {
                        continue;
                    }

                    foreach ($gifts as $gift_id):
                        $title = get_the_title($gift_id);
                        $text = strpos($title, '+') !== false ? substr($title, strpos($title, '+') + 2) : '';
                        ?>
                        <div class="custom_select__item">
                            <div class="custom_select__image">
                                <?php echo get_thumbnail_html($gift_id, __('Gift image', DOMAIN)); ?>
                            </div>
                            <div class="custom_select__text">
                                <strong>
                                    <?php echo esc_html($step); ?>+
                                </strong>
                                <p>
                                    <?php echo esc_html($text); ?>
                                </p>
                            </div>
                        </div>
                    <?php
                    endforeach;
                endforeach; ?>
            </div>
            <div class="custom_select__note">
                <?php _e('* Gift added automatically', DOMAIN); ?>
            </div>
        </div>
    </div>
</div>