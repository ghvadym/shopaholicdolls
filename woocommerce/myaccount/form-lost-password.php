<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
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
do_action( 'woocommerce_before_lost_password_form' );
?>

<form method="post" class="woocommerce-ResetPassword lost_reset_password">

    <h2><?php echo get_field('first_title', 'options') ? : ''; ?></h2>

    <?php if (get_field('first_text', 'options')) : ?>
        <p class="desktop_version"><?php echo get_field('first_text', 'options'); ?></p>
    <?php endif; ?>

    <?php if (get_field('first_mobile_bold_text', 'options') && get_field('first_mobile_text', 'options')) : ?>
        <div class="content mobile_version">
            <span><?php echo get_field('first_mobile_bold_text', 'options'); ?></span>
            <p><?php echo get_field('first_mobile_text', 'options'); ?></p>
        </div>
    <?php endif; ?>

    <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
        <label for="user_login"><?php esc_html_e( 'Email', DOMAIN ); ?></label>
        <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="email" />
    </p>

    <?php do_action( 'woocommerce_lostpassword_form' ); ?>

    <?php
    $default_button_text = 'Reset Password';
    $button_text = get_field('first_button_text', 'options');
    ?>

    <p class="woocommerce-form-row form-row">
        <input type="hidden" name="wc_reset_password" value="true" />
        <button type="submit" class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" value="<?php echo esc_attr( $button_text ?: $default_button_text ); ?>"><?php echo esc_html( $button_text ?: $default_button_text ); ?></button>
    </p>

    <div class="woocommerce-form-row form-text">
        <?php echo get_field('first_bottom_text', 'options') ?: __('Forgot an email connected with account?', DOMAIN); ?>

        <?php $link = get_field('first_bottom_link', 'options');?>

        <?php if (!empty($link)): ?>
            <a href="<?php echo esc_url($link['url']); ?>">
                <?php echo esc_html($link['title']); ?>
            </a>
        <?php endif; ?>
    </div>

    <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

</form>

<?php
do_action( 'woocommerce_after_lost_password_form' );
