<?php
/**
 * Checkout terms and conditions area.
 *
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

if (apply_filters('woocommerce_checkout_show_terms', true) && function_exists('wc_terms_and_conditions_checkbox_enabled')) { ?>
    <span class="payment-method__checkbox checkbox_item">
        <?php if (wc_terms_and_conditions_checkbox_enabled()) : ?>
            <p class="form-row form-row__checkbox validate-required">
                <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked(apply_filters('woocommerce_terms_is_checked_default', isset($_POST['terms'])), true); // WPCS: input var ok, csrf ok. ?> id="terms">
                <label for="terms" class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<?php wc_terms_and_conditions_checkbox_text(); ?>
                </label>
                <input type="hidden" name="terms-field" value="1">
            </p>
        <?php endif; ?>
    </span>
    <?php
}
