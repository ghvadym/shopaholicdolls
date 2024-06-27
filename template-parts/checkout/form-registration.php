<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?> >

    <?php do_action('woocommerce_register_form_start'); ?>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="reg_email"><?php esc_html_e('Email address', DOMAIN); ?>&nbsp;<span class="required">*</span></label>
        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text check-reg-email" name="email" id="reg_email" autocomplete="email"
               value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>"><?php // @codingStandardsIgnoreLine ?>
    </p>

    <?php do_action('woocommerce_register_form'); ?>

    <p class="woocommerce-form-row form-row">
        <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
        <button type="submit"
                class="woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit"
                name="register" value="<?php esc_attr_e('Sign up', DOMAIN); ?>">
            <?php esc_html_e('Register', DOMAIN); ?>
        </button>
    </p>

    <?php do_action('woocommerce_register_form_end'); ?>

</form>