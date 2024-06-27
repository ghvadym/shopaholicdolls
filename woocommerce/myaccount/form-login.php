<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$registration = get_field('login_register', 'options');
$items = $registration['registration_benefits'] ?? [];
$social_auth_providers = social_auth_providers();

do_action('woocommerce_before_customer_login_form'); ?>
<div class="desktop_version">
    <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

        <div class="login_form__row" id="customer_login">

        <div class="login_form__col">

    <?php endif; ?>

    <h2><?php esc_html_e('i have an account', DOMAIN); ?></h2>

    <form class="login_form__item woocommerce-form woocommerce-form-login login" method="post">

        <?php do_action('woocommerce_login_form_start'); ?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="username"><?php esc_html_e('Username or email address', DOMAIN); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username"
                   value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
        </p>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="password"><?php esc_html_e('Password', DOMAIN); ?>&nbsp;<span class="required">*</span></label>
            <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password"/>
        </p>

        <?php do_action('woocommerce_login_form'); ?>

        <div class="form-row">
            <p class="woocommerce-LostPassword lost_password">
                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Forgot your password?', DOMAIN); ?></a>
            </p>
            <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
            <button type="submit"
                    class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
                    name="login" value="<?php esc_attr_e('Log in', DOMAIN); ?>"><?php esc_html_e('Log in', DOMAIN); ?></button>
        </div>
        <?php do_action('woocommerce_login_form_end'); ?>

    </form>

    <div class="content-divider">
        <span>
            <?php esc_html_e('OR CONTINUE WITH', DOMAIN); ?>
        </span>
    </div>

    <?php if (!empty($social_auth_providers)): ?>
        <div class="checkout__auth_bottom">
            <?php get_template_part_var('general/auth-social', [
                'providers' => $social_auth_providers,
            ]); ?>
        </div>
    <?php endif; ?>

    <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

        </div>

        <h2 class="login_form__text">
            <?php _e('OR', DOMAIN); ?>
        </h2>
        <div class="login_form__col reg_form__col">
            <div class="reg__form--wrapper">
                <h3><?php esc_html_e('create free account', DOMAIN); ?></h3>
                <a class="btn-plain" href="#"><?php _e('sign up', DOMAIN); ?></a>
                <form method="post" class="login_form__item woocommerce-form woocommerce-form-register register hidden" <?php do_action('woocommerce_register_form_tag'); ?> >

                    <?php do_action('woocommerce_register_form_start'); ?>

                    <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="reg_username"><?php esc_html_e('Username', DOMAIN); ?>&nbsp;<span class="required">*</span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username"
                                   value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
                        </p>

                    <?php endif; ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_email"><?php esc_html_e('Email address', DOMAIN); ?>&nbsp;<span class="required">*</span></label>
                        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text check-reg-email" name="email" id="reg_email" autocomplete="email"
                               value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>

                        <?php if ('no' !== get_option('woocommerce_registration_generate_password')) : ?>

                            <small class="form-row-small">
                                <?php esc_html_e('We will send an email with link to set a password to your new Shopaholic Dolls account.', DOMAIN); ?>
                            </small>

                        <?php endif; ?>
                    </p>

                    <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="reg_password"><?php esc_html_e('Password', DOMAIN); ?>&nbsp;<span class="required">*</span></label>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password"/>
                        </p>

                    <?php endif; ?>

                    <?php do_action('woocommerce_register_form'); ?>

                    <p class="woocommerce-form-row form-row">
                        <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                        <button type="submit"
                                class="woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit"
                                name="register" value="<?php esc_attr_e('Register', DOMAIN); ?>"><?php esc_html_e('SIGN UP', DOMAIN); ?></button>
                    </p>

                    <?php do_action('woocommerce_register_form_end'); ?>

                </form>
            </div>
            <?php
            if (!empty($registration)) : ?>
                <div class="register__benefits"
                     style="background: linear-gradient(180deg, rgba(251, 251, 251, 0.85) 0%, rgba(251, 251, 251, 0.65) 35.42%, #FBFBFB 86.46%), url('<?php echo $registration['background_image']; ?>'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
                    <?php if (!empty($registration['register_benefits_title'])) : ?>
                        <div class="register__benefits--title">
                            <?php echo $registration['register_benefits_title']; ?>
                        </div>
                    <?php endif;
                    if ($items) : ?>
                        <div class="register__benefits--items">
                            <?php foreach ($items as $item) :
                                if (!empty($item['image'] && $item['text'])) : ?>

                                    <div class="register__benefits--item">
                                        <?php if (!empty($item['image'])) : ?>
                                            <img src="<?php echo $item['image']; ?>"
                                                 alt="<?php _e('Benefit item', DOMAIN); ?>"
                                                 title="<?php _e('Benefit item', DOMAIN); ?>">
                                        <?php endif; ?>
                                        <?php if (!empty($item['text'])) : ?>
                                            <p>
                                                <?php echo $item['text']; ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif;
                            endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        </div>
    <?php endif; ?>
</div>

<div class="mobile_version">
    <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

        <div class="login_form__row" id="customer_login">

        <div class="login_form__col login-mobile" >

    <?php endif; ?>

    <h2><?php esc_html_e('i have an account', DOMAIN); ?></h2>
    <div class="login-wrapper"
         style="background: linear-gradient(180deg, rgba(251, 251, 251, 0.60) 0%, rgba(251, 251, 251, 0.85) 35.42%, #FBFBFB 86.46%)   , url('<?php echo $registration['background_image']; ?>'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
        <form class="login_form__item woocommerce-form woocommerce-form-login login" method="post">

            <?php do_action('woocommerce_login_form_start'); ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="username"><?php esc_html_e('Username or email address', DOMAIN); ?>&nbsp;<span class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username"
                       value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password"><?php esc_html_e('Password', DOMAIN); ?>&nbsp;<span class="required">*</span></label>
                <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password"/>
            </p>

            <?php do_action('woocommerce_login_form'); ?>

            <div class="form-row">
                <p class="woocommerce-LostPassword lost_password">
                    <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Forgot your password?', DOMAIN); ?></a>
                </p>
                <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                <button type="submit"
                        class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
                        name="login" value="<?php esc_attr_e('Log in', DOMAIN); ?>"><?php esc_html_e('Log in', DOMAIN); ?></button>
            </div>
            <?php do_action('woocommerce_login_form_end'); ?>

        </form>

        <div class="content-divider">
                    <span>
                        <?php esc_html_e('OR CONTINUE WITH', DOMAIN); ?>
                    </span>
        </div>

        <?php if (!empty($social_auth_providers)): ?>
            <div class="checkout__auth_bottom">
                <div class="auth__social_links">
                    <?php foreach ($social_auth_providers as $social_auth_provider): ?>
                        <a href="<?php echo $social_auth_provider['url']; ?>"
                           class="auth__social_link">
                            <img src="<?php echo get_local_img_url('social-' . $social_auth_provider['id'] . '.svg'); ?>"
                                 alt="<?php echo __('Auth with', DOMAIN) . ' ' . $social_auth_provider['id']; ?>"
                                 title="<?php echo __('Auth with', DOMAIN) . ' ' . $social_auth_provider['id']; ?>">
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="form-switcher">
            <h3><?php esc_html_e('create free account', DOMAIN); ?></h3>
            <a class="btn-plain" href="#"><?php _e('sign up', DOMAIN); ?></a>
        </div>
    </div>
    <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

            </div>
        <div class="login_form__col reg_form__col hidden">
            <h2><?php esc_html_e('create free account', DOMAIN); ?></h2>
            <div class="login-wrapper"
                 style="background: linear-gradient(180deg, rgba(251, 251, 251, 0.85) 0%, rgba(251, 251, 251, 0.65) 35.42%, #FBFBFB 86.46%), url('<?php echo $registration['background_image']; ?>'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
                <form method="post" class="login_form__item woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?> >

                    <?php do_action('woocommerce_register_form_start'); ?>

                    <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="reg_username"><?php esc_html_e('Username', DOMAIN); ?>&nbsp;<span class="required">*</span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username"
                                   value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
                        </p>

                    <?php endif; ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_email"><?php esc_html_e('Email address', DOMAIN); ?>&nbsp;<span class="required">*</span></label>
                        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text check-reg-email" name="email" id="reg_email" autocomplete="email"
                               value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
                    </p>

                    <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="reg_password"><?php esc_html_e('Password', DOMAIN); ?>&nbsp;<span class="required">*</span></label>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password"/>
                        </p>

                    <?php endif; ?>

                    <?php do_action('woocommerce_register_form'); ?>

                    <p class="woocommerce-form-row form-row">
                        <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                        <button type="submit"
                                class="woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit"
                                name="register" value="<?php esc_attr_e('Register', DOMAIN); ?>"><?php esc_html_e('SIGN UP', DOMAIN); ?></button>
                    </p>
                    <?php if ('no' !== get_option('woocommerce_registration_generate_password')) : ?>

                        <small class="form-row-small">
                            <?php esc_html_e('We will send an email with link to set a password to your new Shopaholic Dolls account.', DOMAIN); ?>
                        </small>

                    <?php endif; ?>
                    <?php do_action('woocommerce_register_form_end'); ?>

                </form>
                <?php if (!empty($registration)) : ?>
                    <div class="register__benefits">
                        <?php if (!empty($registration['register_benefits_title'])) : ?>
                            <div class="register__benefits--title">
                                <?php echo $registration['register_benefits_title']; ?>
                            </div>
                        <?php endif;
                        if ($items) : ?>
                            <div class="register__benefits--items">
                                <?php foreach ($items as $item) :
                                    if (!empty($item['image'] && $item['text'])) : ?>

                                        <div class="register__benefits--item">
                                            <?php if (!empty($item['image'])) : ?>
                                                <img src="<?php echo $item['image']; ?>"
                                                     alt="<?php _e('Benefit item', DOMAIN); ?>"
                                                     title="<?php _e('Benefit item', DOMAIN); ?>">
                                            <?php endif; ?>
                                            <?php if (!empty($item['text'])) : ?>
                                                <p>
                                                    <?php echo $item['text']; ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif;
                                endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="content-divider">
                    <span>
                        <?php esc_html_e('OR CONTINUE WITH', DOMAIN); ?>
                    </span>
                </div>
                <?php if (!empty($social_auth_providers)): ?>
                    <div class="checkout__auth_bottom">
                        <div class="auth__social_links">
                            <?php foreach ($social_auth_providers as $social_auth_provider): ?>
                                <a href="<?php echo $social_auth_provider['url']; ?>"
                                   class="auth__social_link">
                                    <img src="<?php echo get_local_img_url('social-' . $social_auth_provider['id'] . '.svg'); ?>"
                                         alt="<?php echo $social_auth_provider['id']; ?>"
                                         title="<?php echo $social_auth_provider['id']; ?>">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-switcher">
                <h3><?php esc_html_e('I have an account', DOMAIN); ?></h3>
                <a class="btn-plain" href="#"><?php _e('LOG IN', DOMAIN); ?></a>
            </div>
        </div>

        </div>
    <?php endif; ?>
</div>
<?php do_action('woocommerce_after_customer_login_form'); ?>
