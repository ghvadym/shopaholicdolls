<?php

$cart_totals = WC()->cart->get_totals();

if (empty($cart_totals)) {
    wp_safe_redirect(CART_LINK);
    exit;
}
?>

<div class="cart-sidebar__total--title">
    <?php _e('Total', DOMAIN); ?>
</div>

<div class="cart-sidebar__total--item cart-sidebar__subtotal">
    <p>
        <?php _e('Subtotal', DOMAIN); ?>
        <span class="woocommerce-Price-amount amount">
            <?php echo wc_price($cart_totals['subtotal']); ?>
        </span>
    </p>
</div>

<div class="cart-sidebar__total--item cart-sidebar__delivery">
    <p>
        <?php _e('Delivery', DOMAIN); ?>
        <span>
            <?php if ($cart_totals['shipping_total'] == 0):
                _e('Free', DOMAIN);
            else:
                echo wc_price($cart_totals['shipping_total']);
            endif; ?>
        </span>
    </p>
</div>

<?php foreach (WC()->cart->get_fees() as $fee) : ?>
    <div class="cart-sidebar__total--item">
        <p>
            <?php echo esc_html($fee->name); ?>
            <span>
                <?php wc_cart_totals_fee_html($fee); ?>
            </span>
        </p>
    </div>
<?php endforeach; ?>

<?php if ($cart_totals['discount_total'] > 0): ?>
    <div class="cart-sidebar__total--item">
        <p>
            <?php esc_html_e('Discount', DOMAIN); ?>
            <span>
                <?php echo wc_price($cart_totals['discount_total'] * -1); ?>
            </span>
        </p>
    </div>
<?php endif; ?>

<?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
    <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
        <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
            <div class="cart-sidebar__total--item tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                <p>
                    <?php echo esc_html($tax->label); ?>
                    <span>
                        <?php echo wp_kses_post($tax->formatted_amount); ?>
                    </span>
                </p>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="cart-sidebar__total--item tax-total">
            <p>
                <?php echo esc_html(WC()->countries->tax_or_vat()); ?>
                <span>
                     <?php wc_cart_totals_taxes_total_html(); ?>
                </span>
            </p>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if ($gifts_total = gift_cards_total()): ?>
    <div class="cart-sidebar__total--item">
        <p>
            <?php _e('Gift card'); ?>
            <span>
                <?php echo wc_price($gifts_total * -1); ?>
            </span>
        </p>
    </div>
<?php endif; ?>

<span class="cart-sidebar__line"></span>

<div class="cart-sidebar__total--item cart-sidebar__total--total">
    <p>
        <?php _e('Total', DOMAIN); ?>
        <span>
            <?php echo wc_price($cart_totals['total']); ?>
        </span>
    </p>
</div>

<a href="<?php echo esc_url(CHECKOUT_LINK); ?>" class="cart-sidebar__link">
    <?php _e('SHIPPING & PAYMENT', DOMAIN); ?>
</a>

<div class="cart-sidebar__bottom">
    <div class="cart-sidebar__earn">
        <p>
            <?php if (USER_ID):
                esc_html_e('Points you will earn', DOMAIN);
            else:
                echo sprintf(__('Points you can earn %s', DOMAIN), '<span>(<a href="'.MY_ACC_LINK.'" class="show_login_form remember_place" '.current_url_attr().'>'.__('SIGN UP', DOMAIN).'</a>)</span>');
            endif; ?>
        </p>
        <div class="cart-sidebar__earn--count">
            <img src="<?php echo get_template_directory_uri() . '/dest/img/coin.svg'; ?>"
                 alt="<?php _e('Coin', DOMAIN); ?>"
                 title="<?php _e('Coin', DOMAIN); ?>">
            <span>
                <?php echo sprintf('%s PKT', points_amount()); ?>
            </span>
        </div>
    </div>

    <span class="cart-sidebar__bottom--line"></span>

    <div class="cart-sidebar__availability">
        <?php
        $backordered_items_count = 0;

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = $cart_item['data'];

            if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                $backordered_items_count++;
            }
        }

        if ($backordered_items_count > 0) : ?>
            <div class="cart-sidebar__availability--left">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/dest/img/calendar-bell.svg'); ?>"
                     alt="<?php _e('Backorder Notification Image', DOMAIN); ?>"
                     title="<?php _e('Backorder Notification Image', DOMAIN); ?>"
                     class="backorder-notification-image">
                <p class="backorder_notification">
                    <?php esc_html_e('Items with availability: 5-15 days', DOMAIN); ?>
                </p>
            </div>
            <p>
                <?php $backorder_label = $backordered_items_count === 1 ? __('Item', DOMAIN) : __('Items', DOMAIN); ?>
                <?php echo $backordered_items_count . ' ' . $backorder_label; ?>
            </p>
        <?php endif; ?>
    </div>
</div>