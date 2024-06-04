<?php

/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.1
 */

defined('ABSPATH') || exit;

if (!function_exists('wc_get_gallery_image_html')) {
    return;
}

global $product;

$columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
$post_thumbnail_id = $product->get_image_id();
$gallery_images_ids = $product->get_gallery_image_ids();

$variation_gallery = [];
$variation_thumbs_arr = [];

$placeholder_image = wc_placeholder_img_src('woocommerce_single');
$user_favorites = get_user_meta(USER_ID, 'favorites_posts', true);
$is_product_in_favorites = in_array($product->get_id(), (array)$user_favorites);

if ($product->is_type('variable')) {
    foreach ($gallery_images_ids as $key => $val) {
        array_push($variation_thumbs_arr, [
            'id'           => $val,
            'variant_name' => ''
        ]);
    }

    $variation_ids = $product->get_children();
    foreach ($variation_ids as $variation_id) {

        $variation = wc_get_product($variation_id);
        // Get the variation image gallery.
        $variation_image_gallery = $variation->get_gallery_image_ids();
        $images_meta = get_post_meta($variation_id);

        $attributes = $variation->get_attributes();
        $attribute_names = [];

        if (!empty($attributes)) {
            foreach ($attributes as $attribute_name => $attribute_value) {
                $attribute_names[] = $attribute_value;
            }
        }

        $variation_value = implode(',', $attribute_names);

        $variation_thumbs_arr[] = [
            'id'           => $images_meta['_thumbnail_id'][0] ?? '',
            'variant_name' => $variation_value
        ];
    }
}

$category_exists = check_category_exists_for_current_page('gift-cards');
$id = $product->get_id();
$product_types = wp_get_post_terms($id, 'product_type');
$product_type_name = $product_types[0]->slug;
$gift_card = $product_type_name === 'pw-gift-card';

if ($gift_card) {
    $gift_cards_slider_class = 'gift-cards__no-slider';
    $gift_cards_image_wrap = 'gift-cards__image-wrap';
} else {
    $gift_cards_slider_class = '';
    $gift_cards_image_wrap = '';
}

$is_product_on_sale = $product->is_on_sale();
$product_id = get_default_product_variation_id($product);
$product_permalink = get_permalink($product_id);

$user_favorites = get_user_meta(USER_ID, 'favorites_posts', true);
$in_favorites = in_array($product_id, (array)$user_favorites);
?>


<div class="product-thumb product_loading variable_item <?php echo $gift_cards_image_wrap; ?>">
    <div class="product-thumb__img product_loading swiper">
        <?php if (!empty($gallery_images_ids) || !empty($variation_thumbs_arr)) :
            $thumbnail_src = $post_thumbnail_id ? wp_get_attachment_image_url($post_thumbnail_id, 'full') : $placeholder_image;
            $thumbnail_alt = $post_thumbnail_id ? trim(wp_strip_all_tags(get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true))) : $product->get_name();
            ?>
            <div class="product-thumb__slider swiper-wrapper">
                <div class="zoom-wrapp f-panzoom swiper-slide">
                    <div class="swiper-zoom-container">
                        <?php echo sprintf('<img class="f-panzoom__content" src="%s" width="500" height="492" alt="%s" title="%s">',
                            esc_url($thumbnail_src),
                            esc_attr($thumbnail_alt),
                            esc_attr($thumbnail_alt)
                        );
                        ?>
                    </div>
                    <!-- /.swiper-zoom-container -->
                </div>
                <!-- /.swiper-slide -->

                <?php if ($product->is_type('variable') || !empty($variation_image_gallery)) : ?>
                    <?php foreach ($variation_thumbs_arr as $key => $val) :
                        $image_src = wp_get_attachment_image_url($val['id'], 'full');
                        $image_alt = trim(wp_strip_all_tags(get_post_meta($val['id'], '_wp_attachment_image_alt', true)));
                        ?>

                        <?php if (!empty($image_src)) : ?>
                        <div class="zoom-wrapp f-panzoom swiper-slide product-thumb__variation-slide" data-variation="<?php echo $val['id']; ?>">
                            <div class="swiper-zoom-container">
                                <?php echo sprintf('<img class="f-panzoom__content" src="%s" width="500" height="492" alt="%s" title="%s" data-slide="' . $val['variant_name'] . '">',
                                    esc_url($image_src),
                                    esc_attr($image_alt),
                                    esc_attr($image_alt)
                                ); ?>
                            </div>
                            <!-- /.swiper-zoom-container -->
                        </div>
                        <!-- /.product-thumb__variation-slide -->
                    <?php endif; ?>

                    <?php endforeach; ?>

                <?php else : ?>
                    <?php foreach ($gallery_images_ids as $imagesId) :
                        $image_src = wp_get_attachment_image_url($imagesId, 'full');
                        $image_alt = trim(wp_strip_all_tags(get_post_meta($imagesId, '_wp_attachment_image_alt', true)));
                        ?>

                        <div class="zoom-wrapp f-panzoom swiper-slide">
                            <div class="swiper-zoom-container">
                                <?php echo sprintf('<img class="f-panzoom__content" src="%s" width="500" height="492" alt="%s" title="%s">',
                                    esc_url($image_src),
                                    esc_attr($image_alt),
                                    esc_attr($image_alt)
                                );
                                ?>
                            </div>
                            <!-- /.swiper-zoom-container -->
                        </div>
                        <!-- /.swiper-slide -->
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        <?php else : ?>
            <?php $thumbnail_src = $post_thumbnail_id ? wp_get_attachment_image_url($post_thumbnail_id, 'full') : $placeholder_image; ?>
            <?php $thumbnail_alt = $post_thumbnail_id ? trim(wp_strip_all_tags(get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true))) : $product->get_name(); ?>

            <div class="product-thumb__slider gift-card__slider swiper-wrapper">
                <div class="zoom-wrapp f-panzoom swiper-slide">
                    <div class="swiper-zoom-container">
                        <?php echo sprintf('<img class="f-panzoom__content" src="%s" width="500" height="492" alt="%s" title="%s">',
                            esc_url($thumbnail_src),
                            esc_attr($thumbnail_alt),
                            esc_attr($thumbnail_alt)
                        ); ?>
                    </div>
                    <!-- /.swiper-zoom-container -->
                </div>
                <!-- /.swiper-slide -->
            </div>
            <!-- /.swiper-wrapper -->
        <?php endif; ?>

        <?php if (!empty($gallery_images_ids) || !empty($variation_thumbs_arr)) : ?>
            <div class="product-thumb__actions">
                <div class="swiper-pagination product-thumb__actions-pagination"></div>
                <div class="swiper-button-prev product-thumb__actions-button-prev desktop_version">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="-0.625" y="-0.625" width="26.75" height="26.75" rx="13.375" transform="matrix(0 -1 -1 0 26.75 26.75)" stroke="#585858" stroke-width="1.25"/>
                        <path d="M16.6546 9.92896C16.8415 9.7543 16.8418 9.45794 16.6551 9.283C16.4852 9.12375 16.2209 9.12353 16.0507 9.28249L11.7824 13.2692C11.3592 13.6645 11.3592 14.3355 11.7824 14.7308L16.0522 18.7189C16.2217 18.8772 16.4847 18.8773 16.6543 18.7192C16.8413 18.5449 16.8415 18.2487 16.6547 18.0741L12.2969 14L16.6546 9.92896Z"
                              fill="#585858" stroke="#585858" stroke-width="0.5" stroke-linejoin="round"/>
                    </svg>
                </div>

                <div class="swiper-button-next product-thumb__actions-button-next desktop_version">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.625" y="27.375" width="26.75" height="26.75" rx="13.375" transform="rotate(-90 0.625 27.375)" stroke="#585858" stroke-width="1.25"/>
                        <path d="M11.3454 9.92896C11.1585 9.7543 11.1582 9.45794 11.3449 9.283C11.5148 9.12375 11.7791 9.12353 11.9493 9.28249L16.2176 13.2692C16.6408 13.6645 16.6408 14.3355 16.2176 14.7308L11.9478 18.7189C11.7783 18.8772 11.5153 18.8773 11.3457 18.7192C11.1587 18.5449 11.1585 18.2487 11.3453 18.0741L15.7031 14L11.3454 9.92896Z"
                              fill="#585858" stroke="#585858" stroke-width="0.5" stroke-linejoin="round"/>
                    </svg>
                </div>

                <?php if (!$gift_card) : ?>
                    <div class="product-action__copy-item-url mobile_version" data-copy="<?php echo esc_html($product_permalink); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M10.7734 6.68722L13.096 4.35609C13.9643 3.4878 15.1419 3 16.3699 3C17.5978 3 18.7755 3.4878 19.6438 4.35609C20.5121 5.22437 20.9999 6.40202 20.9999 7.62996C20.9999 8.85791 20.5121 10.0356 19.6438 10.9038L17.3126 13.2264"
                                  stroke="#585858" stroke-width="1.5" stroke-miterlimit="10"/>
                            <path d="M6.68722 10.7773L4.35608 13.0999C3.4878 13.9682 3 15.1458 3 16.3738C3 17.6017 3.4878 18.7794 4.35609 19.6477C5.22437 20.516 6.40202 21.0038 7.62996 21.0038C8.85791 21.0038 10.0356 20.516 10.9038 19.6477L13.2264 17.3165"
                                  stroke="#585858" stroke-width="1.5" stroke-miterlimit="10"/>
                            <path d="M16.0902 7.91211L7.91406 16.0882" stroke="#585858" stroke-width="1.5"
                                  stroke-miterlimit="10"/>
                        </svg>
                        <span class="product-action__copy-url-info copy-url-info-hidden"><?php _e('Product address copied', DOMAIN); ?></span>
                    </div>

                    <?php if (USER_ID) : ?>
                        <div class="product-action__add-to-wishlist add_item_to_wishlist mobile_version <?php echo $in_favorites ? 'favorite' : ''; ?>"
                             data-id="<?php echo $product_id; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 24 22"
                                 fill="none">
                                <path d="M21.3036 2.76815C20.766 2.20759 20.1278 1.76292 19.4253 1.45954C18.7228 1.15615 17.9699 1 17.2095 1C16.4491 1 15.6961 1.15615 14.9936 1.45954C14.2912 1.76292 13.6529 2.20759 13.1153 2.76815L11.9997 3.93095L10.8841 2.76815C9.79827 1.6364 8.32556 1.00059 6.78997 1.00059C5.25437 1.00059 3.78167 1.6364 2.69584 2.76815C1.61001 3.89989 1 5.43487 1 7.03541C1 8.63594 1.61001 10.1709 2.69584 11.3027L3.81147 12.4655L11.9997 21L20.188 12.4655L21.3036 11.3027C21.8414 10.7424 22.268 10.0771 22.5591 9.34495C22.8502 8.61276 23 7.82796 23 7.03541C23 6.24285 22.8502 5.45806 22.5591 4.72587C22.268 3.99368 21.8414 3.32844 21.3036 2.76815Z"
                                      fill="#FBFBFB" stroke="#1B1B1B" stroke-width="1.5" stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo MY_ACC_LINK; ?>" class="product-action__add-to-wishlist remember_place mobile_version"
                           data-id="<?php echo $product_id; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 24 22"
                                 fill="none">
                                <path d="M21.3036 2.76815C20.766 2.20759 20.1278 1.76292 19.4253 1.45954C18.7228 1.15615 17.9699 1 17.2095 1C16.4491 1 15.6961 1.15615 14.9936 1.45954C14.2912 1.76292 13.6529 2.20759 13.1153 2.76815L11.9997 3.93095L10.8841 2.76815C9.79827 1.6364 8.32556 1.00059 6.78997 1.00059C5.25437 1.00059 3.78167 1.6364 2.69584 2.76815C1.61001 3.89989 1 5.43487 1 7.03541C1 8.63594 1.61001 10.1709 2.69584 11.3027L3.81147 12.4655L11.9997 21L20.188 12.4655L21.3036 11.3027C21.8414 10.7424 22.268 10.0771 22.5591 9.34495C22.8502 8.61276 23 7.82796 23 7.03541C23 6.24285 22.8502 5.45806 22.5591 4.72587C22.268 3.99368 21.8414 3.32844 21.3036 2.76815Z"
                                      fill="#FBFBFB" stroke="#1B1B1B" stroke-width="1.5" stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <!-- /.product-thumb__actions -->
        <?php elseif (!$gift_card) : ?>
            <div class="product-thumb__actions mobile_version">
                <div class="product-action__copy-item-url" data-copy="<?php echo esc_html($product_permalink); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M10.7734 6.68722L13.096 4.35609C13.9643 3.4878 15.1419 3 16.3699 3C17.5978 3 18.7755 3.4878 19.6438 4.35609C20.5121 5.22437 20.9999 6.40202 20.9999 7.62996C20.9999 8.85791 20.5121 10.0356 19.6438 10.9038L17.3126 13.2264"
                              stroke="#585858" stroke-width="1.5" stroke-miterlimit="10"/>
                        <path d="M6.68722 10.7773L4.35608 13.0999C3.4878 13.9682 3 15.1458 3 16.3738C3 17.6017 3.4878 18.7794 4.35609 19.6477C5.22437 20.516 6.40202 21.0038 7.62996 21.0038C8.85791 21.0038 10.0356 20.516 10.9038 19.6477L13.2264 17.3165"
                              stroke="#585858" stroke-width="1.5" stroke-miterlimit="10"/>
                        <path d="M16.0902 7.91211L7.91406 16.0882" stroke="#585858" stroke-width="1.5"
                              stroke-miterlimit="10"/>
                    </svg>
                    <span class="product-action__copy-url-info copy-url-info-hidden"><?php _e('Product address copied', DOMAIN); ?></span>
                </div>

                <?php if (USER_ID) : ?>
                    <div class="product-action__add-to-wishlist add_item_to_wishlist <?php echo $in_favorites ? 'favorite' : ''; ?>"
                         data-id="<?php echo $product_id; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 24 22"
                             fill="none">
                            <path d="M21.3036 2.76815C20.766 2.20759 20.1278 1.76292 19.4253 1.45954C18.7228 1.15615 17.9699 1 17.2095 1C16.4491 1 15.6961 1.15615 14.9936 1.45954C14.2912 1.76292 13.6529 2.20759 13.1153 2.76815L11.9997 3.93095L10.8841 2.76815C9.79827 1.6364 8.32556 1.00059 6.78997 1.00059C5.25437 1.00059 3.78167 1.6364 2.69584 2.76815C1.61001 3.89989 1 5.43487 1 7.03541C1 8.63594 1.61001 10.1709 2.69584 11.3027L3.81147 12.4655L11.9997 21L20.188 12.4655L21.3036 11.3027C21.8414 10.7424 22.268 10.0771 22.5591 9.34495C22.8502 8.61276 23 7.82796 23 7.03541C23 6.24285 22.8502 5.45806 22.5591 4.72587C22.268 3.99368 21.8414 3.32844 21.3036 2.76815Z"
                                  fill="#FBFBFB" stroke="#1B1B1B" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </div>
                <?php else: ?>
                    <a href="<?php echo MY_ACC_LINK; ?>" class="product-action__add-to-wishlist remember_place"
                       data-id="<?php echo $product_id; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 24 22"
                             fill="none">
                            <path d="M21.3036 2.76815C20.766 2.20759 20.1278 1.76292 19.4253 1.45954C18.7228 1.15615 17.9699 1 17.2095 1C16.4491 1 15.6961 1.15615 14.9936 1.45954C14.2912 1.76292 13.6529 2.20759 13.1153 2.76815L11.9997 3.93095L10.8841 2.76815C9.79827 1.6364 8.32556 1.00059 6.78997 1.00059C5.25437 1.00059 3.78167 1.6364 2.69584 2.76815C1.61001 3.89989 1 5.43487 1 7.03541C1 8.63594 1.61001 10.1709 2.69584 11.3027L3.81147 12.4655L11.9997 21L20.188 12.4655L21.3036 11.3027C21.8414 10.7424 22.268 10.0771 22.5591 9.34495C22.8502 8.61276 23 7.82796 23 7.03541C23 6.24285 22.8502 5.45806 22.5591 4.72587C22.268 3.99368 21.8414 3.32844 21.3036 2.76815Z"
                                  fill="#FBFBFB" stroke="#1B1B1B" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
            <!-- /.product-thumb__actions -->
        <?php endif; ?>

    </div>
    <!-- /.product-thumb__img -->

    <div class="product-thumb__nav desktop_version <?php echo $gift_cards_slider_class; ?> swiper">
        <?php if (!empty($gallery_images_ids) || !empty($variation_thumbs_arr)) :
            $thumbnail_src = $post_thumbnail_id ? wp_get_attachment_image_url($post_thumbnail_id, 'full') : $placeholder_image;
            $thumbnail_alt = $post_thumbnail_id ? trim(wp_strip_all_tags(get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true))) : $product->get_name();
            ?>
            <div class="product-thumb__nav-slider-wrapper swiper-wrapper">
                <div class="product-thumb__nav-item swiper-slide">
                    <?php echo sprintf('<img class="f-panzoom__content" src="%s" width="100" height="100" alt="%s" title="%s">',
                        esc_url($thumbnail_src),
                        esc_attr($thumbnail_alt),
                        esc_attr($thumbnail_alt)
                    );
                    ?>
                </div>
                <!-- /.swiper-slide -->

                <?php if ($product->is_type('variable') || !empty($variation_image_gallery)) : ?>
                    <?php foreach ($variation_thumbs_arr as $key => $val) :
                        $image_src = wp_get_attachment_image_url($val['id'], 'full');
                        $image_alt = trim(wp_strip_all_tags(get_post_meta($val['id'], '_wp_attachment_image_alt', true)));
                        ?>
                        <div class="product-thumb__nav-item swiper-slide" data-variation="<?php echo $val['id']; ?>">
                            <?php echo sprintf('<img class="f-panzoom__content" src="%s" width="100" height="100" alt="%s" title="%s" data-slide="' . $val['variant_name'] . '">',
                                esc_url($image_src),
                                esc_attr($image_alt),
                                esc_attr($image_alt)
                            ); ?>
                        </div>
                        <!-- /.swiper-slide -->

                    <?php endforeach; ?>

                <?php else : ?>
                    <?php foreach ($gallery_images_ids as $imagesId) :
                        $image_src = wp_get_attachment_image_url($imagesId, 'full');
                        $image_alt = trim(wp_strip_all_tags(get_post_meta($imagesId, '_wp_attachment_image_alt', true)));
                        ?>

                        <div class="product-thumb__nav-item swiper-slide">
                            <?php echo sprintf('<img class="f-panzoom__content" src="%s" width="100" height="100" alt="%s" title="%s">',
                                esc_url($image_src),
                                esc_attr($image_alt),
                                esc_attr($image_alt)
                            );
                            ?>
                        </div>
                        <!-- /.swiper-slide -->
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <!-- /.product-thumb__img -->
</div>
<!-- /.product-thumb -->