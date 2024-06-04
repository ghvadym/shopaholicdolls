<?php
/**
 * Custom Cart Collaterals
 */
do_action( 'woocommerce_before_cart_collaterals' );
?>

<section class="cart-more">
    <h2 class="product_title">
        <?php echo get_field('product_up_sell_title', 'options') ?: __('travel & minis', DOMAIN); ?>
    </h2>
    <div class="post_cards__list_wrapper">
        <div class="products-up-sell__list post_cards__list swiper">
            <div class="swiper-wrapper">
                <?php
                foreach ( WC()->cart->get_cross_sells() as $cross_sell_id ) {
                    get_template_part_var('cards/product-card', [
                        'product_id' => $cross_sell_id,
                        'slider'     => true
                    ]);
                }
                ?>
            </div>
        </div>
        <div class="slider_posts__arrows">
            <div class="products-up-sell__btn_prev swiper-button-prev"></div>
            <div class="products-up-sell__btn_next swiper-button-next"></div>
        </div>
    </div>
</section>

<?php
/**
 * Custom Cart Collaterals
 */
do_action( 'woocommerce_after_cart_collaterals' );
?>
