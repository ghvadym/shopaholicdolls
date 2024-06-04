<?php
$social_auth_providers = social_auth_providers();
?>

<div class="checkout__auth_wrap">
    <div class="checkout__auth_top">
        <div class="btn-plain auth-tab checkout__auth_guest">
            <?php esc_html_e('Checkout as a guest', DOMAIN); ?>
        </div>
    </div>
    <div class="content-divider">
        <span>
            <?php esc_html_e('OR', DOMAIN); ?>
        </span>
    </div>
    <div class="checkout__auth_tabs">
        <div class="checkout__auth_tab tab-active" data-tab="0">
            <div class="btn-plain">
                <?php esc_html_e('Log in'); ?>
            </div>
        </div>
        <div class="checkout__auth_tab" data-tab="1">
            <div class="btn-plain">
                <?php esc_html_e('Sign up'); ?>
            </div>
        </div>
    </div>
    <div class="checkout__auth_body">
        <?php do_action( 'woocommerce_before_customer_login_form' ); ?>

        <div class="checkout__auth_form checkout__auth_login tab-active" data-tab="0">
            <?php get_template_part('template-parts/checkout/form-login'); ?>
        </div>

        <div class="checkout__auth_form checkout__auth_register" data-tab="1">
            <?php get_template_part('template-parts/checkout/form-registration'); ?>
        </div>

        <?php do_action( 'woocommerce_after_customer_login_form' ); ?>
    </div>
    <div class="content-divider">
        <span>
            <?php esc_html_e('OR CONTINUE WITH', DOMAIN); ?>
        </span>
    </div>
    <?php if (!empty($social_auth_providers)): ?>
        <div class="checkout__auth_bottom">
            <?php get_template_part_var('general/auth-social', [
                'providers' => $social_auth_providers
            ]); ?>
        </div>
    <?php endif; ?>
</div>