<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;

$is_user_logged_in = is_user_logged_in();
?>

<?php if ($order):
    $order_id = $order->get_id();
    if (!$order->has_status('failed')): ?>
        <div class="order__top">
            <div class="order__top_content">
                <img class="order__top_img" src="<?php echo get_local_img_url('confirmed.svg'); ?>"
                     alt="<?php _e('Confirmed', DOMAIN); ?>"
                     title="<?php _e('Confirmed', DOMAIN); ?>">
                <div class="order__top_title">
                    <?php esc_html_e( 'You’ve got great taste!', DOMAIN); ?>
                </div>
                <div class="order__top_subtitle">
                    <?php echo wp_sprintf('Thank you. Your order <b>#%s</b> is confirmed.', $order_id); ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="order__top">
            <div class="order__top_content">
                <img class="order__top_img" src="<?php echo get_local_img_url('failed.svg'); ?>"
                     alt="<?php _e('Failed', DOMAIN); ?>"
                     title="<?php _e('Failed', DOMAIN); ?>">
                <div class="order__top_title">
                    <?php esc_html_e( 'Sorry, your order cannot be processed.', DOMAIN); ?>
                </div>
                <div class="order__top_subtitle">
                    <?php echo wp_sprintf('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.'); ?>
                </div>
                <div class="order__top_actions">
                    <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay">
                        <?php esc_html_e('Pay', DOMAIN); ?>
                    </a>
                    <?php if ($is_user_logged_in) : ?>
                        <a href="<?php echo esc_url(MY_ACC_LINK); ?>" class="button pay">
                            <?php esc_html_e('My account', DOMAIN); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php
if (!$order || $order->has_status('failed')) {
    return;
}

$data = $order->get_data();
$get_countries = WC()->countries;
$countries = $get_countries->get_allowed_countries();
$addresses = [
    'shipping' => __('Shipping address', DOMAIN),
    'billing'  => __('Billing address', DOMAIN)
];
?>

<div class="woocommerce-order">

    <div class="order__row">
        <div class="order__col">
            <div class="order__content">
                <div class="order__info">
                    <h1 class="order__title">
                        <?php esc_html_e('Customer information', DOMAIN); ?>
                    </h1>
                    <div class="order__info_row">
                        <div class="order__info_title">
                            <?php esc_html_e('Contact', DOMAIN); ?>
                        </div>
                        <div class="order__info_value">
                            <p>
                                <?php echo $data['billing']['email']; ?>
                            </p>
                        </div>
                    </div>

                    <?php foreach ($addresses as $key => $title):
                        $fields = $data[$key] ?? []; ?>
                        <div class="order__info_row">
                            <div class="order__info_title">
                                <?php echo $title; ?>
                            </div>
                            <div class="order__info_value">
                                <?php _get_field($fields['first_name'], '', 'p'); ?>
                                <?php if ($fields['address_1']): ?>
                                    <p>
                                        <?php echo $fields['address_1'] ?? ''; ?>
                                        <?php if ($fields['address_2'] ?? ''): ?>
                                            <?php echo ', ' . $fields['address_2']; ?>
                                        <?php endif; ?>
                                    </p>
                                <?php endif; ?>
                                <?php _get_field($fields['city'] ?? '', '', 'p'); ?>
                                <?php _get_field($fields['postcode'] ?? '', '', 'p'); ?>
                                <?php if (!empty($countries) && !empty($fields['country'])): ?>
                                    <p>
                                        <?php echo $countries[$fields['country']]; ?>
                                    </p>
                                <?php endif; ?>
                                <?php _get_field($fields['phone'] ?? '', '', 'p'); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="order__info_row">
                        <div class="order__info_title">
                            <?php esc_html_e( 'Shipping method', DOMAIN ); ?>
                        </div>
                        <div class="order__info_value">
                            <p>
                                <?php echo $order->get_shipping_method(); ?>
                            </p>
                        </div>
                    </div>

                    <div class="order__info_row">
                        <div class="order__info_title">
                            <?php esc_html_e( 'Payment method', DOMAIN ); ?>
                        </div>
                        <div class="order__info_value">
                            <?php _get_field($data['payment_method_title'], '', 'p'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="desktop_version">
                <?php get_template_part_var('confirmation/order-additional-info', [
                    'email'    => $data['billing']['email'] ?? '',
                    'order_id' => $order_id
                ]); ?>
            </div>
        </div>
        <div class="order__col">
            <div class="order__content">
                <div class="order__summary">
                    <?php get_template_part_var('confirmation/order-summary', [
                        'order' => $order
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile_version">
        <?php get_template_part_var('confirmation/order-additional-info', [
            'email'    => $data['billing']['email'] ?? '',
            'order_id' => $order_id
        ]); ?>
    </div>

</div>
