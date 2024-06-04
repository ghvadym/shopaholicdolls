<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$is_user_logged_in = is_user_logged_in() || WC()->session->get('customer_hide_login');
$summary_opened = WC()->session->get('summary_opened');
?>

<div class="checkout__head">
    <div class="page_title">
        <h1>
            <?php _e('shipping & payment', DOMAIN); ?>
        </h1>
    </div>
    <div class="checkout-btn-back">
        <a class="btn-plain" href="<?php echo esc_url(CART_LINK); ?>">
            <?php _e('back to cart', DOMAIN); ?>
        </a>
    </div>
</div>
<div class="checkout__wrap">
    <div class="checkout__row">
        <div class="checkout__col checkout__auth<?php echo $is_user_logged_in ? ' d-none' : ''; ?>">
            <?php get_template_part('template-parts/checkout/form-auth'); ?>
        </div>
        <div class="checkout__col checkout__fields<?php echo !$is_user_logged_in ? ' d-none' : ''; ?>">
            <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(CHECKOUT_LINK); ?>" enctype="multipart/form-data">
                <?php if ($checkout->get_checkout_fields()) : ?>
                    <div class="checkout__block">
                        <?php do_action('woocommerce_checkout_billing'); ?>
                    </div>
                    <?php do_action('woocommerce_checkout_shipping'); ?>
                <?php endif; ?>

                <div class="checkout__block">
                    <div class="checkout_shipping_methods">
                        <div class="checkout_field__label">
                            <?php _e('Shipping method', DOMAIN); ?>
                        </div>
                        <?php woocommerce_order_review(); ?>
                    </div>

                    <div class="gift_options">
                        <?php get_template_part('template-parts/checkout/gift-options'); ?>
                    </div>
                </div>

                <div class="checkout__block">
                    <?php woocommerce_checkout_payment(); ?>

                    <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
                </div>
            </form>
        </div>

        <div class="checkout__col checkout__summary<?php echo !$is_user_logged_in ? ' d-none' : ''; ?>">
            <div class="order_summary<?php echo $summary_opened ? ' summary_opened' : ''; ?>">
                <?php get_template_part('template-parts/checkout/order-summary'); ?>
            </div>
        </div>
    </div>
</div>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>