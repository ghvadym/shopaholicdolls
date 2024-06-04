<?php
if (!defined('PWGC_SESSION_KEY')) {
    return;
}

$gift_product_note = left_for_gift_product();

if (empty($gift_product_note)) {
    return;
}

$session_data = WC()->session->get(PWGC_SESSION_KEY);
?>

<div class="order_free_gift_message">
    <div class="order_free_gift_message_img">
        <img src="<?php echo get_local_img_url('gift-pink.svg'); ?>"
             alt="<?php _e('Gift', DOMAIN); ?>"
             title="<?php _e('Gift', DOMAIN); ?>">
    </div>
    <div class="order_free_gift_message__body">
        <div class="order_free_gift_message__title">
            <?php esc_html_e('FREE GIFT - You are so close!', DOMAIN); ?>
        </div>
        <div class="order_free_gift_message__text">
            <?php echo sprintf(
                'Only %s more to get a free %s+ GIFT',
                '<strong>+'.wc_price($gift_product_note['left_to_reach_gift']).'</strong>',
                $gift_product_note['next_step_price']
            ); ?>
        </div>
        <div class="order_free_gift_message__progress">
            <span style="width: <?php echo abs(100 - (100 * $gift_product_note['left_to_reach_gift'] / $gift_product_note['next_step_price'])) . '%;'; ?>"></span>
            <span style="width: <?php echo abs(100 * $gift_product_note['left_to_reach_gift'] / $gift_product_note['next_step_price']) . '%;'; ?>"></span>
        </div>
    </div>
</div>
