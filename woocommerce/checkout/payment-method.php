<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$is_blik_active = array_key_exists('stripe_blik', WC()->payment_gateways->get_available_payment_gateways());
$blik_img = file_exists(get_template_directory() . '/dest/img/wc/blik.svg') ? sprintf('<img src="%1$s" alt="%2$s" title="%2$s">', get_template_directory_uri(). '/dest/img/wc/blik.svg', __('Blik', DOMAIN)) : '';

?>

<label class="payment-method wc_payment_method payment_method_<?php echo esc_attr($gateway->id); ?><?php echo $gateway->chosen ? ' payment_chosen' : ''; ?>">

    <input id="payment_method_<?php echo esc_attr($gateway->id); ?>" type="radio" class="input-radio" name="payment_method"
           value="<?php echo esc_attr($gateway->id); ?>" <?php checked($gateway->chosen,
        true); ?> data-order_button_text="<?php echo esc_attr($gateway->order_button_text); ?>">
    <span class="payment-method__heading">
        <span class="payment-method__box"></span>
        <span class="payment-method__label">
            <span>
                <?php echo $gateway->get_title(); ?>
                <?php echo !$is_blik_active && $gateway->id === 'stripe_p24' ? ' | BLIK' : ''; ?>
            </span>
            <?php echo (($is_blik_active && $gateway->id === 'stripe_blik') || (!$is_blik_active && $gateway->id === 'stripe_p24')) && $blik_img ? $blik_img : ''; ?>
        </span>
    </span>

    <?php if ($gateway->has_fields() || $gateway->get_description()) : ?>
        <span class="payment-method__inner">
            <?php $gateway->payment_fields(); ?>

            <?php if ($gateway->chosen): ?>
                <span class="payment-method__checkboxes">
                    <?php wc_get_template('checkout/terms.php'); ?>

                    <?php if (is_plugin_active('mailchimp-for-woocommerce/mailchimp-woocommerce.php')): ?>
                        <span class="payment-method__checkbox checkbox_item">
                            <p class="form-row form-row-wide mailchimp-newsletter woocommerce-validated">
                                <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="mailchimp_woocommerce_newsletter" type="checkbox" name="mailchimp_woocommerce_newsletter" value="1" checked>
                                <label for="mailchimp_woocommerce_newsletter" class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
                                    <?php _e('Send special news and offers', DOMAIN); ?>
                                </label>
                            </p>
                        </span>
                    <?php endif; ?>
                </span>
                <button type="submit"
                        class="button alt btn checkout-content-btn<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
                        name="woocommerce_checkout_place_order"
                        id="place_order"
                        value="<?php echo esc_attr(__('ORDER & PAY', DOMAIN)); ?>"
                        data-value="<?php echo esc_attr(__('ORDER & PAY', DOMAIN)); ?>">
                        <?php echo esc_html(__('ORDER & PAY', DOMAIN)); ?>
                </button>
            <?php endif; ?>

        </span>
    <?php endif; ?>

</label>