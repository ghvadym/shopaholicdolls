<?php

if (!defined('PWGC_SESSION_KEY')) {
    return;
}

$session_data = WC()->session->get(PWGC_SESSION_KEY);

if (empty($session_data['gift_cards'])) {
    return;
}

foreach ($session_data['gift_cards'] as $card_number => $discount_amount) {
    if ((int) $discount_amount === 0) {
        continue;
    }
    $pw_gift_card = new PW_Gift_Card($card_number);
    if ($pw_gift_card->get_id()) {
        $balance = apply_filters('pwgc_to_current_currency', $pw_gift_card->get_balance());
        $balance -= $discount_amount;
        $balance = apply_filters('pwgc_remaining_balance_checkout', $balance, $pw_gift_card);
        ?>
        <div class="cart-sidebar__total--item cart-discount coupon-<?php echo esc_attr(sanitize_title($card_number)); ?>">
            <p>
                <?php _e('Gift card', DOMAIN); ?>
                <?php if ($pw_gift_card->has_expired()) { ?>
                    <small style="color: red;">
                        <?php _e('Expired', DOMAIN); ?>
                    </small>
                <?php } ?>
                <span>
                    <?php echo wc_price($discount_amount * -1); ?>
                </span>
            </p>
        </div>
        <?php
    }
}