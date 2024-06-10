<?php

$reward_items = get_rewards();

if (empty($reward_items)) {
    return;
}

$users_points = get_user_meta(USER_ID, 'order_reward_points', true) ?: 0;
$used_points = get_used_reward_points();
$points_total = (int) $users_points - (int) $used_points;
?>

<div class="cart-sidebar__item">
    <div class="cart-sidebar__top">
        <p>
            <?php _e('REWARD POINTS', DOMAIN); ?>
            ( <span><?php echo $points_total; ?></span> pkt )
        </p>
        <?php get_svg('arrow-down'); ?>
    </div>
    <div class="cart-sidebar__content<?php echo !empty($open) ? ' open' : ''; ?>">
        <form id="reward_products">
            <?php foreach ($reward_items as $product_id => $price):
                $product_available = ($points_total - $price) > 0;
                $product_title = get_the_title($product_id);

                if (function_exists('pll_get_post_translations')) {
                    $translations = pll_get_post_translations($product_id);
                    $is_product_in_cart = false;

                    foreach ($translations as $translation_product_id) {
                        if (is_product_in_cart($translation_product_id)) {
                            $is_product_in_cart = true;
                            break;
                        }
                    }
                } else {
                    $is_product_in_cart = is_product_in_cart($product_id);
                }
                ?>

                <div class="cart-sidebar__points--item<?php echo !$is_product_in_cart && !$product_available ? ' hidden' : ''; ?>">
                    <div class="cart-sidebar__points--image">
                        <img src="<?php echo esc_url(get_thumbnail_url($product_id)); ?> "
                             alt="<?php echo esc_attr($product_title); ?>"
                             title="<?php echo esc_attr($product_title); ?>">
                    </div>
                    <div class="cart-sidebar__points--title">
                        <div class="point_title">
                            <?php echo esc_html($product_title); ?>
                        </div>
                        <span><?php echo sprintf('%s pkt', $price); ?></span>
                    </div>
                    <?php if ($is_product_in_cart || $product_available): ?>
                        <div class="cart-sidebar__points--check">
                            <input type="checkbox" class="reward_product_item" name="reward_items[]" value="<?php echo $product_id; ?>" <?php checked($is_product_in_cart); ?>>
                            <span></span>
                        </div>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>
            <input type="hidden" name="lang" value="<?php echo CURRENT_LANG; ?>">
        </form>
        <?php if ($users_points): ?>
            <div class="cart-sidebar__points--text">
                <?php _e('* If you don’t choose a reward, your points will be saved', DOMAIN); ?>
            </div>
        <?php endif; ?>
    </div>
</div>