<!-- Promo Code Block -->
<div class="cart-sidebar__promo">
    <form id="apply_coupon">
        <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e('Promo code', DOMAIN); ?>" required>
        <button class="button">
            <?php esc_attr_e('Apply', DOMAIN); ?>
        </button>
    </form>
    <div class="form_error_message"></div>
</div>

<!-- Gift Card Block -->
<div class="cart-sidebar__item cart-sidebar__gift">
    <div class="cart-sidebar__top">
        <p>
            <?php _e('I have a GIFT CARD', DOMAIN); ?>
        </p>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none"><path d="M3.04122 5.92499C2.84249 5.69937 2.49095 5.69909 2.29186 5.92439C2.12509 6.11312 2.12486 6.39646 2.29131 6.58547L6.74953 11.6478C7.1477 12.1 7.8523 12.1 8.25047 11.6478L12.7101 6.58383C12.8759 6.39564 12.876 6.11363 12.7104 5.92529C12.5121 5.69968 12.1608 5.69945 11.9621 5.92479L7.5 10.987L3.04122 5.92499Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></svg>
    </div>
    <div class="cart-sidebar__content gift_card_content">
        <form id="apply_gift_card">
            <input type="text" name="gift_card_code" class="input-text" id="gift_card_code" value="" placeholder="<?php esc_attr_e('GIFT CARD CODE', DOMAIN); ?>" required>
            <button class="button">
                <?php esc_attr_e('Apply', DOMAIN); ?>
            </button>
        </form>
    </div>
    <div class="form_error_message"></div>
</div>

<!-- Reward Points Block -->
<div class="cart-sidebar__points">
    <?php get_template_part_var('cart/reward-points'); ?>
</div>

<!-- Choose Samples Block -->
<?php get_template_part_var('cart/samples'); ?>

<!-- Leave a Message Block -->
<div class="cart-sidebar__item cart-sidebar__message">
    <div class="cart-sidebar__top">
        <p>
            <?php _e('LEAVE A MESSAGE', DOMAIN); ?>
        </p>
        <?php get_svg('arrow-down'); ?>
        </svg>
    </div>
    <div class="cart-sidebar__content">
        <textarea id="order_note" name="leave_message" class="input-text" placeholder=""><?php echo WC()->session->get('order_note'); ?></textarea>
    </div>
</div>