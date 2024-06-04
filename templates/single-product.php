<?php

/**
 * @var $product \WC_Product|\WC_Product_Variable
 */
global $product;

get_header('', ['class_wrap' => 'page-product']);
woocommerce_breadcrumb(['home' => '']);
woocommerce_output_all_notices();

$id = $product->get_id();
$product_id = get_default_product_variation_id($product);
$product_permalink = get_permalink($product_id);
$fields = get_fields($id);
$product_advantages = $fields['product_advantages'] ?? [];
$gift_card_advantages = $fields['gift_card_about'] ?? '';

$cross_sell_ids = $product->get_cross_sell_ids();
$up_sell_ids = $product->get_upsell_ids();

$about_title = get_field('product_about_title', 'options') ?: __('About the product', DOMAIN);

$category_exists = check_category_exists_for_current_page('gift-cards');

$is_product_on_sale = $product->is_on_sale();

$user_favorites = get_user_meta(USER_ID, 'favorites_posts', true);
$in_favorites = in_array($product_id, (array)$user_favorites);

$added_date = $product->get_date_created();
if ($added_date) {
    $current_date = new DateTime();
    $interval = $current_date->diff($added_date);
    $days_diff = $interval->days ?? 0;
}
$product_types = wp_get_post_terms($id, 'product_type');
$product_type_name = $product_types[0]->slug;

$gift_card = $product_type_name === 'pw-gift-card';

if (MULTI_CURRENCY_ACTIVE) {
    $multi_currency_settings = WOOMULTI_CURRENCY_Data::get_ins();
    $current_currency = $multi_currency_settings->get_current_currency();
    $default_currency = $multi_currency_settings->get_default_currency();
    if ($product_type_name === 'pw-gift-card' && $current_currency !== $default_currency) {
        ?>
        <script>
            (function ($) {
                $(document).ready(function () {
                    var lang_switcher_pln = $('.lang_switcher__dropdown .wmc-currency-redirect[data-currency="<?php echo $default_currency; ?>"]');
                    if (lang_switcher_pln.length) {
                        $(lang_switcher_pln).trigger('click');
                    }
                });
            })(jQuery);
        </script>
        <?php
    }
}
?>

    <section class="section single-product-info <?php echo $product_type_name; ?>">
        <div class="container">
            <div class="product-wrapp">
                <?php if (!$gift_card) : ?>
                    <div class="sale-new-info-wrapper mobile_version">
                        <?php if (!empty($days_diff) && $days_diff <= new_products_time_interval()) : ?>
                            <div class="new-info-icon new-info-icon--mobile">
                                <?php _e('NEW', DOMAIN); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($is_product_on_sale) && $product->is_in_stock()): ?>
                            <div class="sale-info-icon sale-info-icon--mobile">
                                <?php _e('% SALE', DOMAIN); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php woocommerce_show_product_images(); ?>

                <div class="product-info">
                    <div class="product-info__heading">
                        <?php if (!$gift_card) : ?>
                            <div class="product-info__sale-new-wrapper desktop_version">
                                <?php if (!empty($days_diff) && $days_diff <= new_products_time_interval()) : ?>
                                    <div class="new-info-icon">
                                        <?php _e('NEW', DOMAIN); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($is_product_on_sale) && $product->is_in_stock()): ?>
                                    <div class="sale-info-icon">
                                        <?php _e('% SALE', DOMAIN); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <h1 class="product-title">
                            <?php

                            $title = $product->get_name();
                            $title_parts = explode(' - ', $title);

                            echo $title_parts[0];

                            ?>
                        </h1>

                        <?php

                        foreach ($title_parts as $i => $title_part):
                            if ($i === 0):
                                continue;
                            endif;

                            echo '<p class="product-subtitle">' . $title_part . '</p>';
                        endforeach;

                        ?>

                        <?php if (!$gift_card) : ?>
                            <div class="product-actions-wrapper desktop_version">
                                <?php if (USER_ID) : ?>
                                    <div class="product-action__add-to-wishlist add_item_to_wishlist <?php echo $in_favorites ? 'favorite' : ''; ?>"
                                         data-id="<?php echo $product_id; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22"
                                             viewBox="0 0 24 22" fill="none">
                                            <path d="M21.3036 2.76815C20.766 2.20759 20.1278 1.76292 19.4253 1.45954C18.7228 1.15615 17.9699 1 17.2095 1C16.4491 1 15.6961 1.15615 14.9936 1.45954C14.2912 1.76292 13.6529 2.20759 13.1153 2.76815L11.9997 3.93095L10.8841 2.76815C9.79827 1.6364 8.32556 1.00059 6.78997 1.00059C5.25437 1.00059 3.78167 1.6364 2.69584 2.76815C1.61001 3.89989 1 5.43487 1 7.03541C1 8.63594 1.61001 10.1709 2.69584 11.3027L3.81147 12.4655L11.9997 21L20.188 12.4655L21.3036 11.3027C21.8414 10.7424 22.268 10.0771 22.5591 9.34495C22.8502 8.61276 23 7.82796 23 7.03541C23 6.24285 22.8502 5.45806 22.5591 4.72587C22.268 3.99368 21.8414 3.32844 21.3036 2.76815Z"
                                                  fill="#FBFBFB" stroke="#1B1B1B" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>

                                        <span class="product-action__add-to-wishlist-text"><?php _e('Add to Wishlist', DOMAIN); ?></span>
                                    </div>
                                <?php else: ?>
                                    <a href="<?php echo MY_ACC_LINK; ?>" class="product-action__add-to-wishlist remember_place"
                                       data-id="<?php echo $product_id; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22"
                                             viewBox="0 0 24 22" fill="none">
                                            <path d="M21.3036 2.76815C20.766 2.20759 20.1278 1.76292 19.4253 1.45954C18.7228 1.15615 17.9699 1 17.2095 1C16.4491 1 15.6961 1.15615 14.9936 1.45954C14.2912 1.76292 13.6529 2.20759 13.1153 2.76815L11.9997 3.93095L10.8841 2.76815C9.79827 1.6364 8.32556 1.00059 6.78997 1.00059C5.25437 1.00059 3.78167 1.6364 2.69584 2.76815C1.61001 3.89989 1 5.43487 1 7.03541C1 8.63594 1.61001 10.1709 2.69584 11.3027L3.81147 12.4655L11.9997 21L20.188 12.4655L21.3036 11.3027C21.8414 10.7424 22.268 10.0771 22.5591 9.34495C22.8502 8.61276 23 7.82796 23 7.03541C23 6.24285 22.8502 5.45806 22.5591 4.72587C22.268 3.99368 21.8414 3.32844 21.3036 2.76815Z"
                                                  fill="#FBFBFB" stroke="#1B1B1B" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>

                                        <span class="product-action__add-to-wishlist-text"><?php _e('Add to Wishlist', DOMAIN); ?></span>
                                    </a>
                                <?php endif; ?>

                                <div class="product-action__copy-item-url"
                                     data-copy="<?php echo esc_html($product_permalink); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none">
                                        <path d="M10.7734 6.68722L13.096 4.35609C13.9643 3.4878 15.1419 3 16.3699 3C17.5978 3 18.7755 3.4878 19.6438 4.35609C20.5121 5.22437 20.9999 6.40202 20.9999 7.62996C20.9999 8.85791 20.5121 10.0356 19.6438 10.9038L17.3126 13.2264"
                                              stroke="#585858" stroke-width="1.5" stroke-miterlimit="10"/>
                                        <path d="M6.68722 10.7773L4.35608 13.0999C3.4878 13.9682 3 15.1458 3 16.3738C3 17.6017 3.4878 18.7794 4.35609 19.6477C5.22437 20.516 6.40202 21.0038 7.62996 21.0038C8.85791 21.0038 10.0356 20.516 10.9038 19.6477L13.2264 17.3165"
                                              stroke="#585858" stroke-width="1.5" stroke-miterlimit="10"/>
                                        <path d="M16.0902 7.91211L7.91406 16.0882" stroke="#585858" stroke-width="1.5"
                                              stroke-miterlimit="10"/>
                                    </svg>

                                    <span class="product-action__copy-item-url-text"><?php _e('Copy address', DOMAIN); ?></span>
                                    <span class="product-action__copy-url-info copy-url-info-hidden"><?php _e('Product address copied', DOMAIN); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php woocommerce_template_single_add_to_cart(); ?>

                    <?php if (!empty($product_advantages)): ?>
                        <div class="product-advantages">
                            <h2 class="product_title">
                                <?php echo esc_html($about_title); ?>
                            </h2>
                            <div class="product-advantages__list">
                                <?php foreach ($product_advantages as $advantage_id):
                                    $thumbnail_url = has_post_thumbnail($advantage_id) ? get_the_post_thumbnail_url($advantage_id) : '';
                                    if (!$thumbnail_url):
                                        continue;
                                    endif;
                                    $title = get_the_title($advantage_id);
                                    ?>
                                    <div class="product-advantage__item">
                                        <img src="<?php echo esc_url($thumbnail_url); ?>"
                                             alt="<?php echo esc_attr($title); ?>"
                                             title="<?php echo esc_attr($title); ?>">
                                        <span>
                                            <?php echo esc_html($title); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($gift_card && $gift_card_advantages) : ?>
                        <div class="gift-card__info">
                            <?php echo $gift_card_advantages; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

<?php if (!$gift_card) : ?>
    <section class="section product-information <?php echo $product_type_name; ?>">
        <div class="container">
            <h2 class="product_title">
                <?php echo esc_html($about_title); ?>
            </h2>

            <?php
            $about_tabs = [
                'details'     => __('Details', DOMAIN),
                'how_to_use'  => __('How to use', DOMAIN),
                'skin_type'   => __('Skin type', DOMAIN),
                'concern'     => __('Concern / need', DOMAIN),
                'ingredients' => __('Ingredients', DOMAIN),
                'volume'      => __('Volume', DOMAIN),
                'tags'        => __('Tags', DOMAIN),
                'noteworthy'  => __('Noteworthy', DOMAIN)
            ];
            ?>
            <div class="product-about">
                <div class="nav__tabs">
                    <?php if ($product instanceof WC_Product) :
                        $brand_terms = get_the_terms($product->get_id(), 'pwb-brand');

                        if ($brand_terms && !is_wp_error($brand_terms)) :
                            $brand_term = array_shift($brand_terms);
                            $brand_term_link = get_term_link($brand_term, 'pwb-brand');
                            ?>
                            <div class="product-about__brand mobile_version">
                                <?php if ($brand_term_link && !is_wp_error($brand_term_link)) : ?>
                                    <span class="product-about__brand-text"><?php echo __('Brand:', DOMAIN); ?></span>

                                    <a class="product-about__brand-link" href="<?php echo esc_url($brand_term_link); ?>">
                                        <?php echo $brand_term->name; ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php foreach ($about_tabs as $slug => $title):
                        $text = $fields["product_$slug"] ?? '';
                        if (!$text && $slug !== 'tags'):
                            continue;
                        endif; ?>

                        <div class="nav__tab"
                             data-tab="<?php echo esc_attr($slug); ?>">
                            <div class="nav__tab_title">
                                <?php echo esc_html($title); ?>
                            </div>
                            <div class="nav__content">
                                <div class="nav__tab_text">
                                    <?php if ($slug === 'tags'):
                                        get_template_part_var('product/tags', [
                                            'post_id' => $id
                                        ]);
                                    else:
                                        echo apply_filters('the_content', $text);
                                    endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                    <?php if ($product instanceof WC_Product) :
                        $brand_terms = get_the_terms($product->get_id(), 'pwb-brand');

                        if ($brand_terms && !is_wp_error($brand_terms)) :
                            $brand_term = array_shift($brand_terms);
                            $brand_term_link = get_term_link($brand_term, 'pwb-brand');
                            ?>
                            <div class="product-about__brand desktop_version">
                                <?php if ($brand_term_link && !is_wp_error($brand_term_link)) : ?>
                                    <span class="product-about__brand-text"><?php echo __('Brand:', DOMAIN); ?></span>

                                    <a class="product-about__brand-link"
                                       href="<?php echo esc_url($brand_term_link); ?>">
                                        <?php echo $brand_term->name; ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="nav__contents">
                    <?php foreach ($about_tabs as $slug => $title):
                        $text = $fields["product_$slug"] ?? '';
                        if (!$text && $slug !== 'tags'):
                            continue;
                        endif; ?>

                        <div class="nav__content"
                             data-tab="<?php echo esc_attr($slug); ?>">
                            <div class="nav__tab_text">
                                <?php if ($slug === 'tags'):
                                    get_template_part_var('product/tags', [
                                        'post_id' => $id
                                    ]);
                                else:
                                    echo apply_filters('the_content', $text);
                                endif; ?>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if (!$gift_card && !empty($up_sell_ids)) : ?>
    <section class="section products-up-sell">
        <div class="container">
            <h2 class="product_title">
                <?php echo get_field('product_up_sell_title', 'options') ?: __('Works best with', DOMAIN); ?>
            </h2>
            <div class="post_cards__list_wrapper">
                <div class="products-up-sell__list post_cards__list swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($up_sell_ids as $product_id):
                            get_template_part_var('cards/product-card', [
                                'product_id' => $product_id,
                                'slider'     => true
                            ]);
                        endforeach; ?>
                    </div>
                </div>
                <div class="slider_posts__arrows">
                    <div class="products-up-sell__btn_prev swiper-button-prev"></div>
                    <div class="products-up-sell__btn_next swiper-button-next"></div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if (!$gift_card && !empty($cross_sell_ids)) : ?>
    <section class="section products-cross">
        <div class="container">
            <h2 class="product_title">
                <?php echo get_field('product_cross_sell_title', 'options') ?: __('Similar items', DOMAIN); ?>
            </h2>
            <div class="post_cards__list_wrapper">
                <div class="products-cross__list post_cards__list swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($cross_sell_ids as $product_id):
                            get_template_part_var('cards/product-card', [
                                'product_id' => $product_id,
                                'slider'     => true
                            ]);
                        endforeach; ?>
                    </div>
                </div>
                <div class="slider_posts__arrows">
                    <div class="products-cross__btn_prev swiper-button-prev"></div>
                    <div class="products-cross__btn_next swiper-button-next"></div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>