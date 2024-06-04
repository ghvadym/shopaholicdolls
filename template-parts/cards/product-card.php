<?php
if (empty($product_id)) {
    return;
}

$product = wc_get_product($product_id);

if (empty($product) || $product->get_status() !== 'publish') {
    return;
}

$title = !empty($custom_title) ? $custom_title : $product->get_title();
$title_parts = preg_split('/ (-|–|—) /', $title);
$url = esc_url(get_the_permalink($product_id));
$show_one_price = get_field('show_one_price', 'options');
$single_variable_product = $product->is_type('variable') && $show_one_price;
$price = '';
$is_product_on_sale = $product->is_on_sale();

if ($product->is_type('variable') && !$show_one_price) {
    $min_price = $product->get_variation_price('min');
    $max_price = $product->get_variation_price('max');

    if ($min_price == $max_price) {
        $price = wc_price($min_price);
    } else {
        $price = sprintf(
            '%1$s - %2$s',
            str_replace(CURRENCY_SYMBOL, '', wc_price($min_price)),
            wc_price($max_price)
        );
    }
} else if ($product->is_type('simple') || $single_variable_product) {
    if ($is_product_on_sale) {
        $regular_price = (float)$product->get_regular_price();
        $formatted_price = number_format($regular_price, 2, ',', '');
        $sale_price = (float)$product->get_sale_price();
        $percentage = round(100 - ($sale_price / $regular_price * 100)) . '% OFF';

        $before_sale_price = wc_price($regular_price);
        $price = wc_price($sale_price);
    } else {
        $price = wc_price($product->get_price());
    }
} else {
    $price = wc_price($product->get_price());
}

$thumbnail = !empty($image) ? esc_url($image) : esc_url(get_thumbnail_url($product_id));

$slider_class = !empty($slider) ? ' swiper-slide' : '';

$add_to_cart_link = site_url(add_query_arg([
    'add-to-cart' => $product_id,
]));
$user_favorites = get_user_meta(USER_ID, 'favorites_posts', true);
$in_favorites = in_array($product_id, (array)$user_favorites);

$added_date = $product->get_date_created();
$current_date = new DateTime();
$interval = $current_date->diff($added_date);
$days_diff = $interval->days ?? 0;
?>

<div class="post_card<?php echo $slider_class; ?>">
    <div class="post_card__content">
        <a href="<?php echo $url; ?>" class="post_card__header">
            <div class="post_card__image <?php echo (!$product->is_in_stock() && !$product->is_on_backorder(1)) ? 'hidden' : ''; ?>">
                <img src="<?php echo $thumbnail; ?>"
                     alt="<?php echo esc_attr($title); ?>"
                     title="<?php echo esc_attr($title); ?>">
                <div class="product-info__sale-new-wrapper">

                    <?php if ($days_diff <= new_products_time_interval()) : ?>
                        <div class="post_card__new new-info-icon">
                            <?php _e('NEW', DOMAIN); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($is_product_on_sale)): ?>
                        <div class="post_card__sale sale-info-icon">
                            <?php _e('% SALE', DOMAIN); ?>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </a>
        <div class="post_card__body">
            <a href="<?php echo $url; ?>" class="post_card__title">
                <?php echo esc_html($title_parts[0] ?? $title); ?>
            </a>
            <?php if (CURRENT_LANG === 'pl' && !empty($title_parts[1])): ?>
                <a href="<?php echo $url; ?>" class="post_card__text">
                    <?php echo esc_html($title_parts[1]); ?>
                </a>
            <?php endif; ?>
            <div class="post_card__prices">
                <?php if (!empty($is_product_on_sale) && !empty($before_sale_price) && $product->is_in_stock() && !empty($formatted_price)): ?>
                    <div class="post_card__price_regular">
                        <?php echo $formatted_price; ?>
                    </div>
                    <div class="post_card__price sale">
                        <?php echo $price; ?>
                    </div>
                <?php else : ?>
                    <?php if ($product->is_in_stock()) : ?>
                        <div class="post_card__price">
                            <?php echo $price; ?>
                        </div>
                    <?php else : ?>
                        <div class="out"><?php _e('Out of stock', DOMAIN); ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="post_card__footer">
            <?php if (USER_ID) : ?>
                <div class="post_card__action add_item_to_wishlist <?php echo $in_favorites ? 'favorite' : ''; ?>" data-id="<?php echo $product_id; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 24 22" fill="none"><path d="M21.3036 2.76815C20.766 2.20759 20.1278 1.76292 19.4253 1.45954C18.7228 1.15615 17.9699 1 17.2095 1C16.4491 1 15.6961 1.15615 14.9936 1.45954C14.2912 1.76292 13.6529 2.20759 13.1153 2.76815L11.9997 3.93095L10.8841 2.76815C9.79827 1.6364 8.32556 1.00059 6.78997 1.00059C5.25437 1.00059 3.78167 1.6364 2.69584 2.76815C1.61001 3.89989 1 5.43487 1 7.03541C1 8.63594 1.61001 10.1709 2.69584 11.3027L3.81147 12.4655L11.9997 21L20.188 12.4655L21.3036 11.3027C21.8414 10.7424 22.268 10.0771 22.5591 9.34495C22.8502 8.61276 23 7.82796 23 7.03541C23 6.24285 22.8502 5.45806 22.5591 4.72587C22.268 3.99368 21.8414 3.32844 21.3036 2.76815Z" fill="#FBFBFB" stroke="#1B1B1B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
            <?php else: ?>
                <a href="<?php echo MY_ACC_LINK; ?>" class="post_card__action remember_place"
                    <?php echo current_url_attr(); ?>
                    data-id="<?php echo $product_id; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 24 22" fill="none"><path d="M21.3036 2.76815C20.766 2.20759 20.1278 1.76292 19.4253 1.45954C18.7228 1.15615 17.9699 1 17.2095 1C16.4491 1 15.6961 1.15615 14.9936 1.45954C14.2912 1.76292 13.6529 2.20759 13.1153 2.76815L11.9997 3.93095L10.8841 2.76815C9.79827 1.6364 8.32556 1.00059 6.78997 1.00059C5.25437 1.00059 3.78167 1.6364 2.69584 2.76815C1.61001 3.89989 1 5.43487 1 7.03541C1 8.63594 1.61001 10.1709 2.69584 11.3027L3.81147 12.4655L11.9997 21L20.188 12.4655L21.3036 11.3027C21.8414 10.7424 22.268 10.0771 22.5591 9.34495C22.8502 8.61276 23 7.82796 23 7.03541C23 6.24285 22.8502 5.45806 22.5591 4.72587C22.268 3.99368 21.8414 3.32844 21.3036 2.76815Z" fill="#FBFBFB" stroke="#1B1B1B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            <?php endif; ?>
            <?php if (($product->is_type('variable') || $product->is_type('variation')) && ($product->is_in_stock() || $product->is_on_backorder(1))) : ?>
                <a href="<?php echo $url; ?>" class="post_card__action">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/dest/img/variable.svg'); ?>"
                         alt="<?php _e('Variable product', DOMAIN); ?>"
                         title="<?php _e('Variable product', DOMAIN); ?>">
                </a>
            <?php elseif ($product->is_in_stock() || $product->is_on_backorder(1)) : ?>
                <div class="post_card__action add_product_to_cart<?php echo is_cart() ? ' cart_page' : ''; ?>" data-id="<?php echo $product_id; ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/dest/img/cart-empty.svg'); ?>"
                         alt="<?php _e('Add to cart', DOMAIN); ?>"
                         title="<?php _e('Add to cart', DOMAIN); ?>">
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
