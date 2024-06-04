<?php
$show_sample = get_field('show_samples', 'options');

if ($show_sample) {
    $samples = get_field('product_samples', 'options');
}

if (empty($samples)) {
    return;
}

$selected_sample = WC()->session->get('product_sample') ?: '';
?>

<div class="cart-sidebar__item cart-sidebar__samples">
    <div class="cart-sidebar__top">
        <p>
            <?php _e('Choose samples', DOMAIN); ?>
        </p>
        <?php get_svg('arrow-down'); ?>
    </div>
    <div class="cart-sidebar__content">
        <?php foreach ($samples as $sample):
            $icon_url = $sample['icon'] ?? '';
            $name = $sample['title'] ?? '';

            $checked = trim($selected_sample) == trim($name);
            ?>

            <div class="cart-sidebar__samples--item<?php echo $checked ? ' active' : ''; ?>">
                <?php if ($icon_url) : ?>
                    <div class="cart-sidebar__samples--image">
                        <img src="<?php echo $icon_url; ?>"
                             alt="<?php echo esc_attr($name); ?>"
                             title="<?php echo esc_attr($name); ?>">
                    </div>
                <?php endif; ?>
                <div class="cart-sidebar__samples--title">
                    <?php echo esc_html($name); ?>
                </div>
                <div class="cart-sidebar__samples--radio">
                    <input type="radio" name="sample" data-key="<?php echo esc_attr($name); ?>" <?php checked($checked) ?>>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
</div>