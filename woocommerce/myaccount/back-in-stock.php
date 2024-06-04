<?php
/**
 * Back in Stock notifications list.
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/notifications.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce Back In Stock Notifications
 * @version 1.6.0
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Split two paginations using the "|" symbol.
$current_pages              = ! empty( $current_page ) ? explode( '|', urldecode( $current_page ) ) : array();
$current_pages              = array_map( 'absint', $current_pages );
$notifications_current_page = isset( $current_pages[ 0 ] ) ? $current_pages[ 0 ] : 1;
$activities_current_page    = isset( $current_pages[ 1 ] ) ? $current_pages[ 1 ] : 1;

/**
 * `woocommerce_bis_account_notifications_per_page` filter.
 * How many notifications to show per page.
 *
 * @since 1.1.2
 *
 * @param  int  $per_page
 * @return int
 */
$notifications_per_page = (int) apply_filters( 'woocommerce_bis_account_notifications_per_page', 10 );
$query_args        = array(
    'is_active'      => 'on',
    'product_exists' => true,
    'product_status' => 'publish',
    'user_id'        => get_current_user_id(),
    'order_by'       => array( 'id' => 'DESC' ),
    'limit'          => $notifications_per_page,
    'offset'         => ( $notifications_current_page - 1 ) * $notifications_per_page
);

$user                                  = wp_get_current_user();
$allowed_roles                         = array( 'shop_manager', 'administrator' );
$is_user_eligible_for_private_products = is_a( $user, 'WP_User' ) && array_intersect( $allowed_roles, $user->roles );

if ( $is_user_eligible_for_private_products ) {
    unset( $query_args[ 'product_status' ] );
}

$notifications     = wc_bis_get_notifications( $query_args );
$has_notifications = ! empty( $notifications ) ? true : false;

$template_args             = array(
    'has_notifications'          => $has_notifications,
    'notifications'              => $notifications
);


do_action( 'woocommerce_bis_before_account_backinstock', $has_notifications ); 
?>

<div class="account__tab_title">
    <?php _e('STOCK ALERT', DOMAIN); ?>
</div>

<div class="woocommerce-orders-wrapper woocommerce-MyAccount-orders backinstock__wrapper">
    <?php if ( $has_notifications && ! empty( $notifications ) ) : ?>
        <?php
        $has_in_stock = false;
        $has_out_of_stock = false;

        foreach ( $notifications as $notification ) {
            $product = $notification->get_product();
            if ( $product && $product->is_in_stock() ) {
                $has_in_stock = true;
            } else {
                $has_out_of_stock = true;
            }
        }
        ?>
        <?php if ( $has_in_stock ) : ?>
            <div class="in-stock-section">
                <div class="in-stock--title">
                    <p>
                        <?php esc_html_e( 'BACK IN STOCK ', DOMAIN ); ?>
                        <span><?php echo esc_html__( '(ready for your order)', DOMAIN ); ?></span>
                    </p>
                </div>
                <div class="in-stock--items">
                    <?php foreach ( $notifications as $notification ) : ?>
                        <?php
                        $product = $notification->get_product();
                        $product_id = $product->get_id();
                        if ( ! $product || ! $product->is_in_stock() ) {
                            continue;
                        }
                        ?>
                        <div class="woocommerce-order woocommerce-order-<?php echo esc_attr( $notification->get_id() ); ?>">
                            <div class="order-details">
                                <a href="<?php echo esc_url( $notification->get_product_permalink() ); ?>" class="order-image">
                                    <?php echo get_thumbnail_html( $product_id, __('Product image', DOMAIN),'thumbnail' );?>
                                </a>
                                <div class="order-detail">
                                    <?php 
                                    $product_name = $notification->get_product_name();
                                    $exploded_name = explode('-', $product_name, 2);
                                    $first_part = isset($exploded_name[0]) ? $exploded_name[0] : '';
                                    $second_part = isset($exploded_name[1]) ? $exploded_name[1] : '';
                                    ?>
                                    <?php if (!empty($first_part)) : ?>
                                        <?php echo wp_kses_post( sprintf( '<a class="order-title" href="%s">%s</a>', $notification->get_product_permalink(), $first_part ) ); ?>
                                    <?php endif; ?>
                                    <?php if (!empty($second_part)) : ?>
                                        <span class="order-description"><?php echo esc_html( $second_part ); ?></span>
                                    <?php endif; ?>
                                </div>
        
                                <div class="order-actions">
                                    <?php
                                    echo wp_kses_post(
                                        sprintf(
                                            '<div class="add-to-cart add_product_to_cart" data-id="%2$d">%1$s</div>',
                                            get_local_img_html('cart-empty.svg', '', __('Add to cart', DOMAIN)),
                                            $product->get_id()
                                        )
                                    );
                                    echo wp_kses_post(
                                        sprintf(
                                            '<a class="remove-item" href="%1$s">%2$s</a>',
                                            wp_nonce_url( add_query_arg( array( 'wc_bis_deactivate' => $notification->get_id() ), WC_BIS()->account->get_endpoint_url() ), 'deactivate_notification_account_nonce' ),
                                            get_local_img_html('trash.svg', '', __('Remove back in stock item', DOMAIN))
                                        )
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif ;?>
        <?php if ( $has_out_of_stock ) : ?>
            <div class="out-of-stock-section">
                <div class="out-of-stock--title">
                    <p>
                        <?php esc_html_e( 'OUT OF STOCK', DOMAIN ); ?>
                    </p>
                </div>
                <div class="out-of-stock--items">
                    <?php foreach ( $notifications as $notification ) : ?>
                        <?php
                        $product = $notification->get_product();
                        $product_id = $product->get_id();
        
                        if ( ! $product || $product->is_in_stock() ) {
                            continue;
                        }
                        ?>
                        <div class="woocommerce-order woocommerce-order-<?php echo esc_attr( $notification->get_id() ); ?>">
                            <div class="order-details">
                                <a href="<?php echo esc_url( $notification->get_product_permalink() ); ?>" class="order-image">
                                    <?php echo get_thumbnail_html( $product_id, __('Product image', DOMAIN), 'thumbnail' );?>
                                </a>
                                <div class="order-detail">
                                    <?php 
                                    $product_name = $notification->get_product_name();
                                    $exploded_name = explode('-', $product_name, 2);
                                    $first_part = isset($exploded_name[0]) ? $exploded_name[0] : '';
                                    $second_part = isset($exploded_name[1]) ? $exploded_name[1] : '';
                                    ?>
                                    <?php if (!empty($first_part)) : ?>
                                        <?php echo wp_kses_post( sprintf( '<a href="%s">%s</a>', $notification->get_product_permalink(), $first_part ) ); ?>
                                    <?php endif; ?>
                                    <?php if (!empty($second_part)) : ?>
                                        <span class="order-description"><?php echo esc_html( $second_part ); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="order-actions">
                                    <?php
                                    echo wp_kses_post(
                                        sprintf(
                                            '<a class="remove-item" href="%1$s">%2$s</a>',
                                            wp_nonce_url( add_query_arg( array( 'wc_bis_deactivate' => $notification->get_id() ), WC_BIS()->account->get_endpoint_url() ), 'deactivate_notification_account_nonce' ),
                                            get_local_img_html('trash.svg', '', __('Remove back in stock item', DOMAIN))
                                        )
                                    );
                                    ?>
                                </div>
        
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif ;?>
    <?php else : ?>
        <div class="woocommerce-order">
            <div class="order-details">
                <div class="order-detail"><?php esc_html_e( 'No active notifications found.', DOMAIN ); ?></div>
            </div>
        </div>
    <?php endif; ?>
</div>


<?php do_action( 'woocommerce_bis_after_account_backinstock', $has_notifications ); ?>

