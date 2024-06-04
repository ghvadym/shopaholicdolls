<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_reset_password_form' );
?>
<div class="container">
    <form method="post" class="woocommerce-form woocommerce-ResetPassword lost_reset_password password-reset">

        <h2><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'new password', DOMAIN ) ); ?></h2><?php // @codingStandardsIgnoreLine ?>

        <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
            <label for="password_1"><?php esc_html_e( 'New Password', DOMAIN ); ?>&nbsp;<span class="required">*</span></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text password-requirements" name="password_1" id="password_1" autocomplete="new-password" />
			<span class="form-row-small">
				<span class="min_length_span"><?php _e('Min. 8 characters', DOMAIN); ?></span>,
				<span class="has_upper_case_span"><?php _e('big letter', DOMAIN); ?></span>,
				<span class="has_lower_case_span"><?php _e('small letter', DOMAIN); ?></span>,
				<span class="has_digit_or_symbol_span"><?php _e('digit/symbol', DOMAIN); ?></span>
			</span>

        </p>
        <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
            <label for="password_2"><?php esc_html_e( 'Repeat New Password', DOMAIN ); ?>&nbsp;<span class="required">*</span></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" />
        </p>

        <input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
        <input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

        <?php do_action( 'woocommerce_resetpassword_form' ); ?>

			<div class="woocommerce-form__notice">
				<div class="password-strength-indicator">
					<div class="password-strength-bar"></div>
					<div class="password-strength-bar"></div>
					<div class="password-strength-bar"></div>
				</div>
				<div class="woocommerce-password-strength" aria-live="polite"></div>
			</div>
            <input type="hidden" name="wc_reset_password" value="true" />
            <button type="submit" class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" value="<?php esc_attr_e( 'Save', DOMAIN ); ?>"><?php esc_html_e( 'reset password', DOMAIN ); ?></button>

        <?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

    </form>
</div>

<?php
do_action( 'woocommerce_after_reset_password_form' );

