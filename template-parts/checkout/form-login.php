<form class="woocommerce-form woocommerce-form-login login" method="post">

    <?php do_action('woocommerce_login_form_start'); ?>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="username"><?php esc_html_e('Username or email address', DOMAIN); ?>&nbsp;<span class="required">*</span></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username"
               value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"><?php // @codingStandardsIgnoreLine ?>
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="password"><?php esc_html_e('Password', DOMAIN); ?>&nbsp;<span class="required">*</span></label>
        <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password">
    </p>

    <?php do_action('woocommerce_login_form'); ?>

    <p class="form-row">
        <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
        <button type="submit"
                class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
                name="login" value="<?php esc_attr_e('Log in', DOMAIN); ?>">
            <?php esc_html_e('Log in', DOMAIN); ?>
        </button>
    </p>
    <p class="woocommerce-LostPassword lost_password remember_place">
        <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="btn-plain remember_place" <?php echo current_url_attr(); ?>>
            <?php esc_html_e('Forgot your password?', DOMAIN); ?>
        </a>
    </p>

    <?php do_action('woocommerce_login_form_end'); ?>

</form>