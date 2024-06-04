<?php

$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
?>

<div class="account__tab_title">
    <?php _e('Wallet', DOMAIN); ?>
</div>

<?php if ($available_gateways):
    foreach ($available_gateways as $gateway): ?>
        <div class="content__block">
            <div class="content__head">
                <div class="content__head_title">
                    <?php echo get_local_img_html('myaccount/credit-card.svg', 'content__head_img', __('Credit card', DOMAIN)); ?>
                    <?php _e('Credit card', DOMAIN); ?>
                </div>

                <div class="content__head_actions">
                    <div class="content__head_action active" data-action="add" style="display: block">
                        <div class="content__head_action_row">
                            <?php echo get_local_img_html('myaccount/add.svg', 'content__head_img', __('Add payment method', DOMAIN)); ?>
                            <span>
                                <?php esc_html_e('Add', DOMAIN); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content__body">
                <div class="content__body_edit">
                    <form id="add_payment_method" method="post">
                        <div id="payment" class="woocommerce-Payment">
                            <ul class="woocommerce-PaymentMethods payment_methods methods">
                                <li class="woocommerce-PaymentMethod woocommerce-PaymentMethod--<?php echo esc_attr($gateway->id); ?> payment_method_<?php echo esc_attr($gateway->id); ?>">
                                    <input id="payment_method_<?php echo esc_attr($gateway->id); ?>" type="radio" class="input-radio" name="payment_method"
                                           value="<?php echo esc_attr($gateway->id); ?>" checked
                                           style="display:none;">
                                    <?php if ($gateway->has_fields() || $gateway->get_description()):
                                        echo '<div class="woocommerce-PaymentBox woocommerce-PaymentBox--' . esc_attr($gateway->id) . ' payment_box payment_method_' . esc_attr($gateway->id) . '" style="display: none;">';
                                        $gateway->payment_fields();
                                        echo '</div>';
                                    endif;
                                    ?>
                                </li>
                            </ul>
                        </div>
                        <?php do_action('woocommerce_add_payment_method_form_bottom'); ?>
                        <div class="content__body_buttons">
                            <div class="content__body_btn content__body_cancel btn-transparent">
                                <?php esc_html_e('Cancel', DOMAIN); ?>
                            </div>
                            <?php wp_nonce_field('woocommerce-add-payment-method', 'woocommerce-add-payment-method-nonce'); ?>
                            <button type="submit"
                                    class="content__body_btn btn woocommerce-Button woocommerce-Button--alt button alt<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
                                    id="place_order" value="<?php esc_attr_e('Add payment method', DOMAIN); ?>"><?php esc_html_e('Confirm', DOMAIN); ?></button>
                            <input type="hidden" name="woocommerce_add_payment_method" id="woocommerce_add_payment_method" value="1">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <?php wc_print_notice( esc_html__( 'New payment methods can only be added during checkout. Please contact us if you require assistance.', DOMAIN ), 'notice' ); ?>
<?php endif; ?>

<?php wc_get_template('myaccount/payment-methods.php', [
    'show' => true
]); ?>