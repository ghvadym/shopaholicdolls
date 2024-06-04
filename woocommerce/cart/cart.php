<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.4.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');
?>

<section class="cart">
    <h1 class="cart-title">
        <?php esc_html_e('Cart', DOMAIN); ?>
    </h1>
    <div class="cart-table-row cart-table-header">
        <div class="cart-table-cell product-name"><?php esc_html_e('Product', DOMAIN); ?></div>
        <div class="cart-table-cell product-price"><?php esc_html_e('Price', DOMAIN); ?></div>
        <div class="cart-table-cell product-quantity"><?php esc_html_e('QTY', DOMAIN); ?></div>
        <div class="cart-table-cell product-subtotal"><?php esc_html_e('Subtotal', DOMAIN); ?></div>
    </div>
    <div class="cart-container">

        <form class="woocommerce-cart-form" action="<?php echo esc_url(CART_LINK); ?>" method="post">
            <div class="cart-table">
                <?php get_template_part_var('cart/cart-items'); ?>
            </div>
        </form>

        <div class="cart-sidebar">
            <a href="<?php echo home_url(); ?>" class="cart-sidebar__return">Continue shopping</a>
            <?php get_template_part_var('cart/cart-accordions'); ?>
            <div class="cart_totals cart-sidebar__total">
                <?php get_template_part_var('cart/cart-totals'); ?>
            </div>
        </div>

    </div>
</section>

<?php do_action('woocommerce_before_cart_collaterals'); ?>

<div class="cart-collaterals">
    <?php
    /**
     * Cart collaterals hook.
     *
     * @hooked woocommerce_cross_sell_display
     * @hooked woocommerce_cart_totals - 10
     */
    do_action('woocommerce_cart_collaterals');
    ?>
</div>

<?php do_action('woocommerce_after_cart'); ?>
