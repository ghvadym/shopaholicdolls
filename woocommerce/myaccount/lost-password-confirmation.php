<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 */

defined( 'ABSPATH' ) || exit;

// wc_print_notice( esc_html__( 'Password reset email has been sent.', DOMAIN ) );
?>

<?php do_action( 'woocommerce_before_lost_password_confirmation_message' ); ?>

<div class="lost-confirmed">
    <div class="container">
        <?php 
        $second_icon = get_field('second_icon', 'options'); 
        if (!empty($second_icon) && isset($second_icon['url'], $second_icon['alt'])) :?>
            <div class="icon">
                <img src="<?php echo esc_url($second_icon['url']); ?>"
                     alt="<?php echo esc_attr($second_icon['alt']); ?>"
                     title="<?php echo esc_attr($second_icon['alt']); ?>">
            </div>
        <?php endif; ?>
        <div class="content">
            <h4><?php echo get_field('second_title', 'options') ? : ''; ?></h4>
            <p><?php echo get_field('second_text', 'options') ? : ''; ?></p>
        </div>
    </div>
</div>

<?php 
$mail_features = get_field('second_mail_features', 'options');
if (!empty($mail_features) && is_array($mail_features)) : ?>
    <div class="lost-items">
        <div class="container lost-items__wrapper">
            <?php foreach ($mail_features as $feature) : ?>
                <div class="lost-items__item">
                    <?php 
                    if (!empty($feature['icon']) && is_array($feature['icon']) && isset($feature['icon']['url'], $feature['icon']['alt'])) : 
                        $image_url = esc_url($feature['icon']['url']);
                        $image_alt = esc_attr($feature['icon']['alt']);?>
                        <div class="icon">
                            <img src="<?php echo $image_url; ?>"
                                 alt="<?php echo $image_alt; ?>"
                                 title="<?php echo $image_alt; ?>">
                        </div>
                    <?php endif; ?>
                    <p><?php echo esc_html($feature['text']) ? : ''; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_lost_password_confirmation_message' ); ?>
