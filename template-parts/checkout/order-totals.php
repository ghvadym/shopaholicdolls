<?php

$free_shipping_amount = get_free_shipping_amount();
$free_shipping_amount_label = '';
if ($free_shipping_amount) {
    $free_shipping_amount_label = sprintf(
        '<span class="note">('.__('free from', DOMAIN).' %s)</span>',
        wc_price((int) $free_shipping_amount)
    );
}

$cart_totals = WC()->cart->get_totals();
?>

<div class="order_summary__totals_list">
    <div class="order_summary__row">
        <div class="order_summary__row_title">
            <?php esc_html_e('Subtotal', DOMAIN); ?>
        </div>
        <div class="order_summary__row_value">
            <?php echo wc_price($cart_totals['subtotal']); ?>
        </div>
    </div>

    <div class="order_summary__row">
        <div class="order_summary__row_title">
            <?php esc_html_e('Shipping', DOMAIN); ?>
            <?php if ($cart_totals['shipping_total'] > 0):
                echo $free_shipping_amount_label;
            endif; ?>
        </div>
        <div class="order_summary__row_value">
            <?php if ($cart_totals['shipping_total'] == 0):
                _e('Free', DOMAIN);
            else:
                echo wc_price($cart_totals['shipping_total']);
            endif; ?>
        </div>
    </div>

    <?php foreach (WC()->cart->get_fees() as $fee) : ?>
        <div class="order_summary__row">
            <div class="order_summary__row_title">
                <?php echo esc_html($fee->name); ?>
            </div>
            <div class="order_summary__row_value">
                <?php wc_cart_totals_fee_html($fee); ?>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if ($cart_totals['discount_total'] > 0): ?>
        <div class="order_summary__row">
            <div class="order_summary__row_title">
                <?php esc_html_e('Discount', DOMAIN); ?>
            </div>
            <div class="order_summary__row_value">
                <?php echo wc_price($cart_totals['discount_total'] * -1); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
        <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
            <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                <div class="order_summary__row tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                    <div class="order_summary__row_title">
                        <?php echo esc_html($tax->label); ?>
                    </div>
                    <div class="order_summary__row_value">
                        <?php echo wp_kses_post($tax->formatted_amount); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="order_summary__row tax-total">
                <div class="order_summary__row_title">
                    <?php echo esc_html(WC()->countries->tax_or_vat()); ?>
                </div>
                <div class="order_summary__row_value">
                    <?php wc_cart_totals_taxes_total_html(); ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($gifts_total = gift_cards_total()): ?>
        <div class="order_summary__row">
            <div class="order_summary__row_title">
                <?php _e('Gift card'); ?>
            </div>
            <div class="order_summary__row_value">
                <?php echo wc_price($gifts_total * -1); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="content-divider"></div>

<div class="order_summary__row row-total">
    <div class="order_summary__row_title">
        <?php esc_html_e('Total', DOMAIN); ?>
    </div>
    <div class="order_summary__row_value">
        <?php echo wc_price($cart_totals['total']); ?>
    </div>
</div>

<div class="order_summary__row coins">
    <div class="order_summary__row_title">
        <?php if (USER_ID):
            esc_html_e('Points you will earn', DOMAIN);
        else:
            echo sprintf(__('Points you can earn %s', DOMAIN), '<span>(<div class="show_login_form">'.__('SIGN UP', DOMAIN).'</div>)</span>');
        endif; ?>
    </div>
    <div class="order_summary__row_value">
        <img src="<?php echo get_template_directory_uri() . '/dest/img/coin.svg' ?>"
             alt="<?php _e('Coin', DOMAIN); ?>"
             title="<?php _e('Coin', DOMAIN); ?>">
        <?php echo sprintf('%s PKT', points_amount()); ?>
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