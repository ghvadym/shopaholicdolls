<?php
$is_user_logged_in = is_user_logged_in();
?>

<div class="order__buttons">
    <a href="<?php echo home_url(); ?>" class="order__button btn-transparent">
        <?php _e('Continue shopping', DOMAIN); ?>
    </a>
    <?php if ($is_user_logged_in): ?>
        <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')) ?>" class="order__button btn-plain">
            <?php esc_html_e('Check your orders', DOMAIN); ?>
        </a>
    <?php endif; ?>
</div>
<?php if (!$is_user_logged_in): ?>
    <div class="order__form">
        <h3 class="order__form_title">
            <?php esc_html_e('Create account', DOMAIN); ?>
        </h3>
        <?php if (!empty($order)): ?>
            <div class="order__form_subtitle">
                <?php echo sprintf('You will receive <span>%s</span> reward points for this order', $order->get_subtotal()) ?>
            </div>
        <?php endif; ?>
        <div class="checkout__auth_form">
            <?php get_template_part_var('confirmation/order-registration', [
                'email'    => $email ?? '',
                'order_id' => $order_id ?? ''
            ]); ?>
        </div>
    </div>
<?php endif; ?>