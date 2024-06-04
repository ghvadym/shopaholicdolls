<?php
if (WC()->cart->is_empty()) {
    return;
}

$backorder_items = 0;
$summary_opened = WC()->session->get('summary_opened');
?>

<div class="order_summary__head">
    <h2 class="order_summary__title">
        <?php esc_html_e('Order Summary', DOMAIN); ?>
    </h2>
    <div class="mobile_version">
        <div class="order_summary__total">
            <?php wc_cart_totals_order_total_html(); ?>
        </div>
        <div class="order_summary__arrow"></div>
    </div>
</div>

<div class="order_summary__note mobile_version" <?php echo $summary_opened ? 'style="display: none;"' : ''; ?>>
    <?php get_template_part_var('checkout/free-gift-note'); ?>
</div>

<div class="order_summary__body" <?php echo !$summary_opened ? 'style="display: none;"' : ''; ?>>
    <div class="order_summary__items">
        <div class="order_summary__items_list">
            <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $is_product_variable = $_product->is_type('variable') || $_product->is_type('variation');

                if (empty($_product) || !$_product->exists() || $cart_item['quantity'] < 1 || !apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                    continue;
                }

                $quantity = apply_filters('woocommerce_checkout_cart_item_quantity', ' <p class="product-quantity">' . sprintf('&times;%s', $cart_item['quantity']) . '</p>', $cart_item, $cart_item_key);

                $is_product_backordered = $_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity']);
                if ($is_product_backordered) {
                    $backorder_items++;
                }

                $free_gift_note_id = get_field('free_gift_note', 'options');
                $gift_wrap_id = get_field('gift_wrap', 'options');
                $gift_card_id = get_gift_product();

                $title = wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key));
                $title_parts = explode(' - ', $title);

                $attributes = $_product->get_attributes();
                $attribute_names = [];

                if (!empty($attributes)) {
                    foreach ($attributes as $attribute_name => $attribute_value) {
                        if (!$attribute_value) {
                            continue;
                        }

                        $attribute_names[] = $_product->get_attribute($attribute_name);
                    }
                }
                ?>

                <div class="order_summary__item">
                    <div class="order_summary__item_img">
                        <?php echo $_product->get_image(); ?>
                    </div>
                    <div class="order_summary__item_title">
                        <span>
                             <?php esc_html_e($title_parts[0]); ?>
                        </span>
                        <?php if ($gift_card_id == $product_id && !empty($cart_item['pw_gift_card_message'])) { ?>
                            <p>
                                <?php echo esc_html($cart_item['pw_gift_card_message']); ?>
                            </p>
                        <?php } else if ($is_product_variable && $product_id != $gift_card_id && !empty($attribute_names)) { ?>
                            <p>
                                <?php echo implode(', ', array_reverse($attribute_names)); ?>
                            </p>
                        <?php } else if ($product_id == $free_gift_note_id && $message = WC()->session->get('gift_message')) { ?>
                            <p>
                                <small>
                                    <?php echo esc_html($message); ?>
                                </small>
                            </p>
                        <?php } else if (!empty($title_parts[1]) && in_array($product_id, get_free_gift_products())) { ?>
                            <p>
                                <?php echo esc_html($title_parts[1]); ?>
                            </p>
                        <?php } ?>
                        <?php echo $quantity; ?>
                    </div>
                    <div class="order_summary__item_quantity">
                        <?php echo $quantity; ?>
                    </div>
                    <div class="order_summary__item_subtotal">
                        <?php if (isset($cart_item['custom_price']) && $cart_item['custom_price'] !== ''):
                            echo wc_price($cart_item['custom_price']);
                        else:
                            echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                        endif;
                        if ($is_product_backordered): ?>
                            <div class="order_summary__item_icon">
                                <img src="<?php echo get_template_directory_uri() . '/dest/img/calendar-bell.svg' ?>"
                                     alt="<?php _e('Item with late availability', DOMAIN); ?>"
                                     title="<?php _e('Item with late availability', DOMAIN); ?>">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php get_template_part_var('checkout/free-gift-note'); ?>

    <?php if ($message = WC()->session->get('order_note')) { ?>
        <div class="order_message">
            <div class="order_message__body">
                <div class="order_message__title">
                    <?php esc_html_e('Special request', DOMAIN); ?>:
                </div>
                <div class="order_message__text">
                    <?php echo trim(str_replace("\n", '<br>', $message)); ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="content-divider"></div>

    <div class="order_summary__totals">
        <?php get_template_part_var('checkout/order-totals', [
            'backorder_items' => $backorder_items
        ]); ?>
    </div>

</div>
