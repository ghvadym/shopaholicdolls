<?php

/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 6.1.0
 */

defined('ABSPATH') || exit;

global $product;

$id = $product->get_id();
$product_types = wp_get_post_terms($id, 'product_type');
$product_type_name = $product_types[0]->slug;

$category_exists = check_category_exists_for_current_page('gift-cards');

if ($product_type_name == "pw-gift-card") {
    $gift_card_class = 'gift-cards-select';
    $gift_card_variations_wrap = 'gift-cards__variations-wrap';
} else {
    $gift_card_class = '';
    $gift_card_variations_wrap = '';
}

$default_attributes = $product->get_default_attributes();

$attribute_keys = array_keys($attributes);
$variations_json = wp_json_encode($available_variations);
$variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json)
    : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);
$possible_variations = get_all_possible_variations($id);
do_action('woocommerce_before_add_to_cart_form');

?>

    <form class="variations_form" action="<?php echo esc_url(apply_filters(
        'woocommerce_add_to_cart_form_action',
        $product->get_permalink()
    )); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint($product->get_id()); ?>"
          data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok.
          ?>">

        <?php if (empty($available_variations) && false !== $available_variations) : ?>
            <p class="stock out-of-stock"><?php echo esc_html(apply_filters(
                    'woocommerce_out_of_stock_message',
                    __('This product is currently out of stock and unavailable.', DOMAIN)
                )); ?></p>
        <?php else : ?>

            <div class="product-options full <?php echo $gift_card_class ?>">
                <div class="product-options__select variations">
                    <?php if (!empty($possible_variations)) :
                        foreach ($possible_variations as $attribute_name => $options) :

                            custom_wc_dropdown_variation_attribute_options(
                                [
                                    'options'          => $options,
                                    'attribute'        => $attribute_name,
                                    'product'          => $product,
                                    'show_option_none' => wc_attribute_label($attribute_name),
                                    'id'               => '',
                                    'class'            => count($options) == 1 ? 'single-select' : '',
                                ]
                            );

                            $default_attribute = '';

                            if (!empty($options)) {
                                if (empty($default_attributes)) {
                                    if (preg_match('~[0-9]+~', $options[0] ?? '')) {
                                        sort($options, SORT_NUMERIC);
                                        $default_attribute = end($options);
                                    } else {
                                        foreach ($available_variations as $available_variation) {
                                            if (wc_get_product($available_variation['variation_id'])->is_in_stock()) {
                                                $filtered_variations[] = $available_variation;
                                            }
                                        }

                                        if (!empty($filtered_variations)) {
                                            usort($filtered_variations, function ($a, $b) {
                                                return $a['display_price'] - $b['display_price'];
                                            });

                                            $default_attribute = $filtered_variations[0]['attributes']['attribute_' . $attribute_name];
                                        } else {
                                            $prices = array_column($available_variations, 'display_price');
                                            $min_price_index = array_search(min($prices), $prices);
                                            $default_attribute = $available_variations[$min_price_index]['attributes']['attribute_' . $attribute_name];
                                        }
                                    }
                                } else {
                                    $default_attribute = $default_attributes[$attribute_name];
                                }
                            }

                            echo '<div class="product-default-variation" data-name="attribute_' . $attribute_name . '" data-value="' . $default_attribute . '" style="display:none;"></div>';
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>

            <div class="single_variation_wrap product-actions <?php echo $gift_card_variations_wrap; ?>">
                <div class="product_price">
                    <div class="woocommerce-variation single_variation product-price"></div>
                </div>
                <?php wc_get_template('single-product/add-to-cart/variation-add-to-cart-button.php'); ?>
                <?php get_template_part('template-parts/product/gifts'); ?>
                <div class="product_availability">
                    <div class="woocommerce-variation single_variation"></div>
                </div>
            </div>

        <?php endif; ?>
    </form>

<?php
do_action('woocommerce_after_add_to_cart_form');