<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

if (!wp_doing_ajax()) {
    do_action('woocommerce_review_order_before_payment');
}
?>
<?php if (WC()->cart->needs_payment()) : ?>

    <div id="payment" class="payment-methods woocommerce-checkout-payment">
        <div class="checkout_field__label">
            <?php _e('Payment', DOMAIN); ?>
        </div>

        <?php
        if (!empty($available_gateways)) {
            foreach ($available_gateways as $gateway) {
                wc_get_template('checkout/payment-method.php', ['gateway' => $gateway]);
            }
        } else {
            echo '<div class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . WC()
                ->customer->get_billing_country()
                ? esc_html__('Sorry, it looks like there are no payment methods available for your state. Please contact us if you need assistance or would like to make alternative arrangements.',
                    DOMAIN)
                : esc_html__('Please fill in your details above to see available payment methods.',
                    DOMAIN) . '</div>';
        }
        ?>
    </div>

<?php endif; ?>