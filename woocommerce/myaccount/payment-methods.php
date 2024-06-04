<?php

if (empty($args['show'])) {
    wp_safe_redirect(wc_get_account_endpoint_url('add-payment-method'));
    return;
}

if (empty($args['show'])) {
    return;
}

$available_payment_methods = WC()->payment_gateways->get_available_payment_gateways();

$saved_methods = wc_get_customer_saved_methods_list(USER_ID);
$has_methods = (bool)$saved_methods;
$types = wc_get_account_payment_methods_types();

do_action('woocommerce_before_account_payment_methods', $has_methods); ?>

<?php foreach ($saved_methods as $type => $methods):
    foreach ($methods as $method) : ?>
        <div class="content__block payment-method<?php echo !empty($method['is_default']) ? ' default-payment-method' : ''; ?>">
            <div class="content__head">
                <div class="content__head_title">
                    <?php echo get_local_img_html('myaccount/credit-card.svg', 'content__head_img', __('Credit card', DOMAIN)); ?>
                    <?php _e('Credit card', DOMAIN); ?>
                </div>
                <div class="content__head_actions">
                    <div class="content__head_action red" data-action="remove">
                        <div class="content__head_action_row">
                            <?php echo get_local_img_html('myaccount/bin.svg', 'content__head_img', __('Credit card remove', DOMAIN)); ?>
                            <span>
                                <?php esc_html_e('Remove', DOMAIN); ?>
                            </span>
                        </div>
                    </div>
                    <?php if (empty($method['is_default']) && !empty($method['actions']['default'])): ?>
                        <div class="content__head_action" data-action="default">
                            <div class="content__head_action_row">
                                <span>
                                    <?php esc_html_e('Make default', DOMAIN); ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="content__body">
                <div class="content__body_val">
                    <div class="content__body_row">
                        <span>
                            <?php _e('Card vendor', DOMAIN); ?>:
                        </span>
                        <span>
                            <?php echo esc_html(wc_get_credit_card_type_label($method['method']['brand'])); ?>
                        </span>
                    </div>
                    <?php if (!empty($method['method']['last4'])): ?>
                        <div class="content__body_row">
                            <span>
                                <?php _e('Card number', DOMAIN); ?>:
                            </span>
                            <span>
                                <?php echo sprintf('**** **** **** %1$s', esc_html($method['method']['last4'])); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <div class="content__body_row">
                        <span>
                            <?php _e('Expires', DOMAIN); ?>:
                        </span>
                        <span>
                            <?php echo esc_html($method['expires']); ?>
                        </span>
                    </div>
                </div>
                <div class="content__body_edit">
                    <?php if (!empty($method['actions']['delete'])): ?>
                        <div class="content__body_remove">
                            <p>
                                <b>
                                    <?php echo sprintf('**** **** **** %1$s', esc_html($method['method']['last4'])); ?>
                                </b>
                            </p>
                            <p class="red">
                                <?php esc_html_e('This payment method will be removed.', DOMAIN); ?>
                            </p>

                            <div class="content__body_buttons">
                                <div class="content__body_btn content__body_cancel btn-transparent">
                                    <?php esc_html_e('Cancel', DOMAIN); ?>
                                </div>
                                <a href="<?php echo esc_url($method['actions']['delete']['url'] ?? ''); ?>" class="content__body_btn btn">
                                    <?php esc_html_e('Confirm', DOMAIN); ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($method['actions']['default'])): ?>
                        <div class="content__body_default">
                            <p>
                                <b>
                                    <?php echo sprintf('**** **** **** %1$s', esc_html($method['method']['last4'])); ?>
                                </b>
                            </p>
                            <p class="grey">
                                <?php esc_html_e('This payment method will be default.', DOMAIN); ?>
                            </p>

                            <div class="content__body_buttons">
                                <div class="content__body_btn content__body_cancel btn-transparent">
                                    <?php esc_html_e('Cancel', DOMAIN); ?>
                                </div>
                                <a href="<?php echo esc_url($method['actions']['default']['url'] ?? ''); ?>" class="content__body_btn btn">
                                    <?php esc_html_e('Confirm', DOMAIN); ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>

<?php do_action('woocommerce_after_account_payment_methods', $has_methods); ?>
