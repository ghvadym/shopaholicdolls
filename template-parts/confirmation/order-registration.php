<?php
if (empty($email)) {
    return;
}
?>

<?php do_action('woocommerce_before_customer_login_form'); ?>

    <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?> >

        <?php do_action('woocommerce_register_form_start'); ?>

        <?php if (!empty($order_id)): ?>
            <input type="hidden" name="order_id" value="<?php echo esc_attr($order_id); ?>">
        <?php endif; ?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide" style="display:none;">
            <label for="reg_email"><?php esc_html_e('Email address', DOMAIN); ?></label>
            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email"
                   value="<?php echo esc_attr(wp_unslash($email)); ?>"><?php // @codingStandardsIgnoreLine ?>
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="reg_password"><?php esc_html_e('Password', DOMAIN); ?></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text password-requirements" minlength="8" name="password" id="reg_password" autocomplete="new-password" required>
            <span class="form-row-small">
                <span class="min_length_span"><?php _e('Min. 8 characters', DOMAIN); ?></span>,
                <span class="has_upper_case_span"><?php _e('big letter', DOMAIN); ?></span>,
                <span class="has_lower_case_span"><?php _e('small letter', DOMAIN); ?></span>,
                <span class="has_digit_or_symbol_span"><?php _e('digit/symbol', DOMAIN); ?></span>
            </span>
        </p>

        <?php do_action('woocommerce_register_form'); ?>

        <p class="woocommerce-form-row btn-wrapper form-row">
            <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
            <button type="submit" class="woocommerce-Button btn-transparent woocommerce-button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Register', DOMAIN); ?>"><?php esc_html_e('Sign up', DOMAIN); ?></button>
        </p>

        <div class="form_error_message"></div>

        <?php do_action('woocommerce_register_form_end'); ?>

    </form>

<?php do_action('woocommerce_after_customer_login_form'); ?>