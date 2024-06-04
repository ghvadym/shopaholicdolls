<?php
if (!isset($gift_wrap_price)) {
    return;
}
?>

<div class="order_summary__row">
    <div class="order_summary__row_title">
        <?php esc_html_e('Wrap & send as a gift', DOMAIN); ?>
    </div>
    <div class="order_summary__row_value">
        <?php echo wc_price($gift_wrap_price); ?>
    </div>
</div>
