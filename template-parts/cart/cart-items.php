<?php

do_action('woocommerce_before_cart_table');

$cart_items = WC()->cart->get_cart();

if (empty($cart_items)) {
    wp_safe_redirect(CART_LINK);
    exit;
}

$gifts_data = gifts_data();
$gifts_data_ids = $gifts_data['ids'] ?? [];

$rewards = get_rewards();

foreach ($cart_items as $cart_item_key => $cart_item) {
    $product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);


    if (!$product || !$product->exists() || $cart_item['quantity'] < 1 || !apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
        continue;
    }

    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
    $is_product_variable = $product->is_type('variable') || $product->is_type('variation');

    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $product->is_visible() ? $product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
    $gift_card_id = get_gift_product();
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $price = $cart_item['data']->get_price();
   
    ?>

    <div class="cart-table-row cart_item" data-item-key="<?php echo esc_attr($cart_item_key); ?>">
        <div class="cart-table-cell product-thumbnail">
            <?php
            $thumbnail = $product->get_image();

            if (!$product_permalink) {
                echo $thumbnail;
            } else {
                printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
            }
            ?>
            <div class="product-bottom cart-table-cell mobile_version">
                <?php
                echo apply_filters(
                    'woocommerce_cart_item_remove_link',
                    sprintf(
                        '<a href="#" class="remove" aria-label="%s" data-cart_item_key="%s" data-id="%s">%s %s</a>',
                        esc_html__('Remove this item', DOMAIN),
                        esc_attr($cart_item_key),
                        $product_id,
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                <path d="M6.11328 3.16683C6.38784 2.39003 7.12866 1.8335 7.99948 1.8335C8.87029 1.8335 9.61112 2.39003 9.88567 3.16683" stroke="#999999" stroke-linecap="round"/>
                                <path d="M13.6654 4.5H2.33203" stroke="#999999" stroke-linecap="round"/>
                                <path d="M12.5564 6.1665L12.2498 10.7659C12.1318 12.5359 12.0728 13.4208 11.4961 13.9603C10.9195 14.4998 10.0325 14.4998 8.25866 14.4998H7.74308C5.96921 14.4998 5.08228 14.4998 4.50561 13.9603C3.92893 13.4208 3.86994 12.5359 3.75194 10.7659L3.44531 6.1665" stroke="#999999" stroke-linecap="round"/>
                                <path d="M6.33203 7.8335L6.66536 11.1668" stroke="#999999" stroke-linecap="round"/>
                                <path d="M9.66536 7.8335L9.33203 11.1668" stroke="#999999" stroke-linecap="round"/>
                            </svg>',
                        esc_html__('Remove', DOMAIN)
                    ),
                    $cart_item_key
                );
                ?>
            </div>
        </div>
        <div class="cart-table-cell product-info">
            <div class="cart-table-cell product-name" data-title="<?php esc_attr_e('Product', DOMAIN); ?>">
                <?php
                $product_permalink = $product->is_visible() ? esc_url($product->get_permalink()) : '';

                if ($product_permalink) {
                    $product_name_parts = explode(' - ', $product->get_name());
                    $output_product_name = $product_name_parts[0] ?? $product->get_name();

                    echo '<a href="' . $product_permalink . '">' . wp_kses_post(apply_filters('woocommerce_cart_item_name', $output_product_name, $cart_item, $cart_item_key)) . '</a>';
                } else {
                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $product->get_name(), $cart_item, $cart_item_key));
                }
                ?>
                <?php if ($product->backorders_require_notification() && $product->is_on_backorder($cart_item['quantity'])) : ?>
                    <div class="cart-table-cell product-backorder mobile_version">
                        <?php backorder_message(false); ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="cart-table-cell product-quantity mobile_version" data-title="Quantity" bis_skin_checked="1">
                <?php if (!in_array($cart_item['product_id'], exclude_product_ids()) && !array_key_exists($cart_item['product_id'], $rewards_ids) && $product_id != $gift_card_id && !in_array($product_id, $gifts_data_ids)):
                    get_template_part_var('cart/product-quantity', [
                        'cart_item'     => $cart_item,
                        'cart_item_key' => $cart_item_key,
                        'product'       => $product,
                    ]);
                endif; ?>
                <div class="cart-table-cell product-subtotal" data-title="<?php esc_attr_e('Subtotal', DOMAIN); ?>">
                    <?php if ($sale_price && $sale_price < $regular_price) {
                        echo '<span class="sale-subtotal">' . wc_price($sale_price * $cart_item['quantity']) . '</span>';
                        echo '<span class="regular-subtotal">' . wc_price($regular_price * $cart_item['quantity']) . '</span>';
                    } else {
                        echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($product, $cart_item['quantity']), $cart_item, $cart_item_key);
                    }
                    ?>
                </div>
            </div>

            <?php
            $item_data = $cart_item['data']->get_data();
            if (!array_key_exists($cart_item['product_id'], $rewards_ids) && $is_product_variable && !empty($item_data['attributes']) && $product_id != $gift_card_id && !in_array($product_id, $gifts_data_ids)) :
                $attributes = $item_data['attributes'];
                
                if (is_array($attributes)) : ?>
                    <div class="cart-table-cell product-variations">

                        <?php foreach ($attributes as $attribute_name => $attribute) :
                            $attribute_label = wc_attribute_label($attribute_name);
                            $attribute_value = $attribute;
                            
                            if (is_a($attribute, 'WC_Product_Attribute')) {
                                $attribute_value = implode(', ', $attribute->get_options());
                            } else if (taxonomy_exists($attribute_name)) {
                                $term = get_term_by('slug', $attribute, $attribute_name);
                                if ($term && !is_wp_error($term)) {
                                    $attribute_value = $term->name;
                                }
                            }
                            ?>
                            <p><?php echo esc_html($attribute_label) . ': <span>' . esc_html($attribute_value) . '</span>'; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php
                endif;
            endif;
            ?>

            <div class="product-bottom cart-table-cell">
                <?php
                if (!array_key_exists($cart_item['product_id'], $rewards_ids) && $is_product_variable && !empty($item_data['attributes']) && $product_id != $gift_card_id && !in_array($product_id, $gifts_data_ids)) {
                    echo apply_filters(
                        'woocommerce_cart_item_edit_link',
                        sprintf(
                            '<a href="#" class="edit-variation" aria-label="%s" data-cart_item_key="%s">%s %s</a>',
                            esc_html__('Edit this item', DOMAIN),
                            esc_attr($cart_item_key),
                            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                    <path d="M7.33203 3.1665H2.66536C2.31174 3.1665 1.9726 3.30698 1.72256 3.55703C1.47251 3.80708 1.33203 4.14622 1.33203 4.49984V13.8332C1.33203 14.1868 1.47251 14.5259 1.72256 14.776C1.9726 15.026 2.31174 15.1665 2.66536 15.1665H11.9987C12.3523 15.1665 12.6915 15.026 12.9415 14.776C13.1916 14.5259 13.332 14.1868 13.332 13.8332V9.1665" stroke="#999999" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.332 2.16665C12.5972 1.90144 12.957 1.75244 13.332 1.75244C13.7071 1.75244 14.0668 1.90144 14.332 2.16665C14.5972 2.43187 14.7462 2.79158 14.7462 3.16665C14.7462 3.54173 14.5972 3.90144 14.332 4.16665L7.9987 10.5L5.33203 11.1667L5.9987 8.49999L12.332 2.16665Z" stroke="#999999" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>',
                            esc_html__('Edit', DOMAIN)
                        ),
                        $cart_item_key
                    );
                }

                echo apply_filters(
                    'woocommerce_cart_item_remove_link',
                    sprintf(
                        '<a href="#" class="remove desktop_version" aria-label="%s" data-cart_item_key="%s" data-id="%s">%s %s</a>',
                        esc_html__('Remove this item', DOMAIN),
                        esc_attr($cart_item_key),
                        $product_id,
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                <path d="M6.11328 3.16683C6.38784 2.39003 7.12866 1.8335 7.99948 1.8335C8.87029 1.8335 9.61112 2.39003 9.88567 3.16683" stroke="#999999" stroke-linecap="round"/>
                                <path d="M13.6654 4.5H2.33203" stroke="#999999" stroke-linecap="round"/>
                                <path d="M12.5564 6.1665L12.2498 10.7659C12.1318 12.5359 12.0728 13.4208 11.4961 13.9603C10.9195 14.4998 10.0325 14.4998 8.25866 14.4998H7.74308C5.96921 14.4998 5.08228 14.4998 4.50561 13.9603C3.92893 13.4208 3.86994 12.5359 3.75194 10.7659L3.44531 6.1665" stroke="#999999" stroke-linecap="round"/>
                                <path d="M6.33203 7.8335L6.66536 11.1668" stroke="#999999" stroke-linecap="round"/>
                                <path d="M9.66536 7.8335L9.33203 11.1668" stroke="#999999" stroke-linecap="round"/>
                            </svg>',
                        esc_html__('Remove', DOMAIN)
                    ),
                    $cart_item_key
                );
                ?>

            </div>

            <?php if (!array_key_exists($cart_item['product_id'], $rewards_ids) && $is_product_variable && !empty($item_data['attributes']) && $product_id != $gift_card_id && !in_array($product_id, $gifts_data_ids)):
                $possible_variations = get_all_possible_variations($product_id);
                $cart_item_attributes = $item_data['attributes'];
                ?>

                <div class="variation-wrapper" style="display:none;">
                    <div class="variation-select-wrapper">
                        <?php if (!empty($possible_variations)): ?>
                            <?php foreach ($possible_variations as $attribute_name => $attribute_values) :
                               
                                custom_wc_dropdown_variation_attribute_options(
                                    [
                                        'options'          => $attribute_values,
                                        'attribute'        => $attribute_name,
                                        'product'          => wc_get_product($product_id),
                                        'show_option_none' => wc_attribute_label($attribute_name),
                                        'id'               => '',
                                        'selected'         => $cart_item_attributes[$attribute_name],
                                    ]
                                );

                            endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="button-wrapper">
                        <div class="btn-transparent cancel-button">
                            <?php _e('Cancel', DOMAIN); ?>
                        </div>
                        <div class="button confirm-button" data-cart_item_key="<?php echo esc_attr($cart_item_key); ?>">
                            <?php _e('Confirm', DOMAIN); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="cart-table-cell product-price desktop_version" data-title="<?php esc_attr_e('Price', DOMAIN); ?>">
            <?php if ($sale_price && $sale_price < $regular_price) {
                echo '<span class="sale-price">' . wc_price($sale_price) . '</span>';
                echo '<span class="regular-price">' . wc_price($regular_price) . '</span>';
            } else {
                if ($price !== '0' && $price != 'Free') {
                    echo WC()->cart->get_product_price($product);
                }
            }
            ?>
        </div>
        <div class="cart-table-cell product-quantity desktop_version" data-title="Quantity" bis_skin_checked="1">
            <?php if (!in_array($cart_item['product_id'], exclude_product_ids()) && !array_key_exists($cart_item['product_id'], $rewards_ids) && $product_id != $gift_card_id && !in_array($product_id, $gifts_data_ids)):
                get_template_part_var('cart/product-quantity', [
                    'cart_item'     => $cart_item,
                    'cart_item_key' => $cart_item_key,
                    'product'       => $product,
                ]);
            endif; ?>
        </div>
        <div class="cart-table-cell product-subtotal desktop_version" data-title="<?php esc_attr_e('Subtotal', DOMAIN); ?>">
            <?php if ($sale_price && $sale_price < $regular_price) {
                echo '<span class="sale-subtotal">' . wc_price($sale_price * $cart_item['quantity']) . '</span>';
                echo '<span class="regular-subtotal">' . wc_price($regular_price * $cart_item['quantity']) . '</span>';
            } else {
                echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($product, $cart_item['quantity']), $cart_item, $cart_item_key);
            }
            ?>
        </div>
        <?php if ($product->backorders_require_notification() && $product->is_on_backorder($cart_item['quantity'])) : ?>
            <div class="cart-table-cell product-backorder desktop_version">
                <?php backorder_message(); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php
    
}

do_action('woocommerce_cart_contents');

do_action('woocommerce_cart_actions');

wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce');

do_action('woocommerce_after_cart_table');