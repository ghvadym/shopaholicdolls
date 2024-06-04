<?php
if (empty($order)) {
    return;
}

$order_id = $order->get_id();
?>

<div class="order_summary__totals_list">
    <?php foreach ($order->get_order_item_totals() as $key => $total):
        if ($key === 'payment_method' || $key === 'order_total'):
            continue;
        endif;

        if ($key === 'pw_gift_cards'):
            $total['label'] = __('Gift card', DOMAIN);
        endif;

        if ($key === 'shipping'):
            $total['value'] = wc_price($order->get_shipping_total());
        endif;
        ?>
        <div class="order_summary__row">
            <div class="order_summary__row_title">
                <?php echo $total['label']; ?>
            </div>
            <div class="order_summary__row_value">
                <?php echo $total['value']; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="content-divider"></div>
<div class="order_summary__row row-total">
    <div class="order_summary__row_title">
        <?php esc_html_e('Total', DOMAIN); ?>
    </div>
    <div class="order_summary__row_value">
        <?php echo wc_price($order->get_total()); ?>
    </div>
</div>

<div class="order_summary__row coins">
    <div class="order_summary__row_title">
        <?php echo USER_ID ? esc_html__('Points you earned', DOMAIN) : esc_html__('Points you will earn', DOMAIN); ?>
    </div>
    <div class="order_summary__row_value">
        <img src="<?php echo get_template_directory_uri() . '/dest/img/coin.svg' ?>"
             alt="<?php _e('Coin', DOMAIN); ?>"
             title="<?php _e('Coin', DOMAIN); ?>">
        <?php echo sprintf('%s PTS', get_post_meta($order->get_id(), 'order_reward_points', true)); ?>
    </div>
</div>

<?php if (!empty($backorder_items)): ?>
    <div class="content-divider"></div>
    <div class="order_summary__row backorder">
        <div class="order_summary__row_title">
            <img src="<?php echo get_template_directory_uri() . '/dest/img/calendar-bell.svg' ?>"
                 alt="<?php _e('Items with late availability', DOMAIN); ?>"
                 title="<?php _e('Items with late availability', DOMAIN); ?>">
            <p class="backorder_notification">
                <?php esc_html_e('Items with availability: 5-15 days', DOMAIN); ?>
            </p>
        </div>
        <div class="order_summary__row_value">
            <?php $backorder_label = $backorder_items === 1 ? __('Item', DOMAIN) : __('Items', DOMAIN); ?>
            <span>
                <?php echo $backorder_items . ' ' . $backorder_label; ?>
            </span>
        </div>
    </div>
<?php endif; ?>