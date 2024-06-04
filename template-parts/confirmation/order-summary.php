<?php
if (empty($order)) {
    return;
}

$backorder_items = 0;
$order_id = $order->get_id();
$order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));

if (empty($order_items)) {
    return;
}

$gift_wrap_product_id = get_field('gift_wrap', 'options');
?>

<div class="order_summary__head">
    <h2 class="order_summary__title">
        <?php esc_html_e('Order Summary', DOMAIN); ?>
    </h2>
</div>

<div class="order_summary__body">
    <div class="order_summary__items">
        <div class="order_summary__items_list">
            <?php foreach ($order_items as $item_id => $item) {
                $product_id = apply_filters('woocommerce_cart_item_product_id', $item['product_id'], $item, $item_id);
                $product = $item->get_product();
                $is_product_variable = false;
                if (!empty($product) && is_a($product, 'WC_Product')) {
                    $is_product_variable = $product->is_type('variable') || $product->is_type('variation');
                }
                if (empty($product) || !apply_filters('woocommerce_checkout_cart_item_visible', true, $item, $item_id)) {
                    continue;
                }

                if ($gift_wrap_product_id && $product_id == $gift_wrap_product_id) {
                    $gift_wrap_price = $order->get_line_subtotal($item);
                }

                $quantity = apply_filters('woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf('&times; %s', $item->get_quantity()) . '</strong>', $item);

                $is_product_backordered = $product->backorders_require_notification() && $product->is_on_backorder($item['quantity']);
                if ($is_product_backordered) {
                    $backorder_items++;
                }

                $free_gift_note_id = get_field('free_gift_note', 'options');
                $gift_wrap_id = get_field('gift_wrap', 'options');
                $gift_card_id = get_gift_product();

                $title = wp_kses_post(apply_filters('woocommerce_cart_item_name', $product->get_name(), $item, $item_id));
                $title_parts = explode(' - ', $title);

                $attributes = $product->get_attributes();
                $attribute_names = [];

                if (!empty($attributes)) {
                    foreach ($attributes as $attribute_name => $attribute_value) {
                        if (!$attribute_value) {
                            continue;
                        }

                        $attribute_names[] = $product->get_attribute($attribute_name);
                    }
                }
                ?>

                <div class="order_summary__item">
                    <div class="order_summary__item_img">
                        <?php echo $product->get_image(); ?>
                    </div>
                    <div class="order_summary__item_title">
                        <span>
                             <?php esc_html_e($title_parts[0]); ?>
                        </span>
                        <?php if ($gift_card_id == $product_id && !empty($item['pw_gift_card_message'])) { ?>
                            <p>
                                <?php echo esc_html($item['pw_gift_card_message']); ?>
                            </p>
                        <?php } else if ($is_product_variable && $product_id != $gift_card_id && !empty($attribute_names)) { ?>
                            <p>
                                <?php echo implode(', ', array_reverse($attribute_names)); ?>
                            </p>
                        <?php } else if ($product_id == $free_gift_note_id && $message = get_post_meta($order_id, 'gift_message', true)) { ?>
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
                        <?php echo $order->get_formatted_line_subtotal($item); ?>
                        <?php if ($is_product_backordered) { ?>
                            <div class="order_summary__item_icon">
                                <img src="<?php echo get_template_directory_uri() . '/dest/img/calendar-bell.svg' ?>"
                                     alt="<?php _e('Item with late availability', DOMAIN); ?>"
                                     title="<?php _e('Item with late availability', DOMAIN); ?>">
                            </div>
                        <?php } ?>
                    </div>
                </div>

            <?php } ?>
        </div>
    </div>

    <?php if ($message = get_post_meta($order_id, 'order_note', true)) { ?>
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
        <?php get_template_part_var('confirmation/order-totals', [
            'order'           => $order,
            'backorder_items' => $backorder_items,
            'gift_wrap_price' => $gift_wrap_price ?? ''
        ]); ?>
    </div>

</div>
