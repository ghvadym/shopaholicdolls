<?php
if (empty($order)) {
    return;
}

$data = $order->get_data();
$billing_fields = $data['billing'] ?? [];
$shipping_fields = $data['shipping'] ?? [];
$get_countries = WC()->countries;
$countries = $get_countries->get_allowed_countries();
$addresses = [
    'shipping' => __('Shipping address', DOMAIN),
    'billing'  => __('Billing address', DOMAIN)
];

$shippings_data = get_order_shipping_data($order->get_id());
?>

<div class="order__info">
    <div class="order__info_row">
        <div class="order__info_col">
            <div class="order__info_title">
                <?php esc_html_e('Contact', DOMAIN); ?>
            </div>
            <div class="order__info_value">
                <p>
                    <?php echo $data['billing']['email']; ?>
                </p>
            </div>
        </div>
    </div>
    <div class="order__info_row">
        <?php foreach ($addresses as $key => $title):
            $fields = $data[$key] ?? []; ?>
            <div class="order__info_col">
                <div class="order__info_title">
                    <?php echo $title; ?>
                </div>
                <div class="order__info_value">
                    <?php _get_field($fields['first_name'], '', 'p'); ?>
                    <?php if ($fields['address_1']): ?>
                        <p>
                            <?php echo $fields['address_1']; ?>
                            <?php if ($fields['address_2']): ?>
                                <?php echo ', ' . $fields['address_2']; ?>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                    <?php _get_field($fields['city'], '', 'p'); ?>
                    <?php _get_field($fields['postcode'], '', 'p'); ?>
                    <?php if (!empty($countries) && $fields['country']): ?>
                        <p>
                            <?php echo $countries[$fields['country']] ?? ''; ?>
                        </p>
                    <?php endif; ?>
                    <?php _get_field($fields['phone'], '', 'p'); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="order__info_row">
        <div class="order__info_col">
            <div class="order__info_title">
                <?php esc_html_e('Shipping method', DOMAIN); ?>
            </div>
            <div class="order__info_value">
                <p>
                    <?php echo $order->get_shipping_method(); ?>
                </p>
                <?php if (!empty($shippings_data)): ?>
                    <?php foreach ($shippings_data as $shipping):
                        if (empty($shipping['tracking_url'])) {
                            continue;
                        }
                        ?>
                        <div class="shipping_tracking">
                            <?php echo get_local_img_html('myaccount/point.svg', '', __('Track shipping', DOMAIN)); ?>
                            <a href="<?php echo esc_attr($shipping['tracking_url']); ?>" target="_blank">
                                <?php _e('Tracking', DOMAIN); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <p>

                </p>
            </div>
        </div>
        <div class="order__info_col">
            <div class="order__info_title">
                <?php esc_html_e('Payment method', DOMAIN); ?>
            </div>
            <div class="order__info_value">
                <?php _get_field($data['payment_method_title'], '', 'p'); ?>
            </div>
        </div>
    </div>
</div>