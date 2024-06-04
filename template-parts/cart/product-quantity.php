<?php

if (empty($cart_item) || empty($cart_item_key) || empty($product)) {
    return;
}

?>

<div class="quantity" bis_skin_checked="1">
    <?php
    $product_quantity = $cart_item['quantity'];
    $min_quantity = 1;
    $max_quantity = apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product);
    $price = $cart_item['data']->get_price();

    if ($max_quantity === -1 || empty($max_quantity)) {
        $max_quantity = 20;
    } else if ($price === '0' || $price == 'Free') {
        $max_quantity = 1;
    }
    ?>
    <select class="input-text qty text quantity_<?php echo esc_attr($cart_item_key); ?>"
            name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]" aria-label="<?php esc_attr_e('Product quantity', DOMAIN); ?>">
        <?php
        for ($i = $min_quantity; $i <= $max_quantity; $i++) {
            echo '<option value="' . esc_attr($i) . '" ' . selected($product_quantity, $i, false) . '>' . esc_html($i) . '</option>';
        }
        ?>
    </select>
</div>
