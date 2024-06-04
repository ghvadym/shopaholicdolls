<?php
/*
* Template name: Wishlist
*/
if (!USER_ID) {
    wp_redirect(MY_ACC_LINK);
    exit;
}
get_header();
$page_title   = get_the_title();
$user_favorites = get_user_meta(USER_ID, 'favorites_posts', true);
get_template_part_var('archive/head-section');

?>

<section class="favorites">
    <div class="container <?php if (!empty($user_favorites)) : ?> favorites__wrapper <?php endif; ?>">
        <?php
        $posts_per_page = 12;
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $start_index = ($paged - 1) * $posts_per_page;
        $end_index = $start_index + $posts_per_page;

        $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'menu_order';
        $order = isset($_GET['order']) ? strtoupper(sanitize_text_field($_GET['order'])) : 'ASC';

        if (!empty($user_favorites) && is_array($user_favorites)) {
            $counter = 0;

            usort($user_favorites, function($a, $b) use ($orderby, $order) {
                $product_a = wc_get_product($a);
                $product_b = wc_get_product($b);

                switch ($orderby) {
                    case 'popularity':
                    if ($product_a instanceof WC_Product_Variable && $product_b instanceof WC_Product_Variable) {
                        return $product_a->get_popularity() - $product_b->get_popularity();
                    } else {
                        return $product_a->get_id() - $product_b->get_id();
                    } 
                    case 'rating':
                        return $product_a->get_average_rating() - $product_b->get_average_rating();
                    case 'price':
                        return $product_a->get_price() - $product_b->get_price();
                    case 'price-desc':
                        return $product_b->get_price() - $product_a->get_price();
                    case 'date':
                        return strtotime($product_b->get_date_created()) - strtotime($product_a->get_date_created());
                    default:
                        return 0;
                }
            });

            foreach ($user_favorites as $index => $product_id) {
                if ($index < $start_index) {
                    continue;
                }

                $product = wc_get_product($product_id);

                if ($product && $product->get_status() === 'publish') {
                    get_template_part_var('cards/product-card', [
                        'product_id' => $product_id
                    ]);

                    $counter++;

                    if ($counter >= $posts_per_page) {
                        break;
                    }
                }

                if ($index + 1 === $end_index) {
                    break;
                }
            }
        } else { ?>
        <div class="empty-wrapper">
            <div class="wc-empty-cart-message"><?php _e('Your whishlist is currently empty.', DOMAIN) ;?></div>
            <a class="button wc-backward<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', home_url())); ?>">
                <?php
                echo esc_html(apply_filters('woocommerce_return_to_shop_text', __('Return to home', DOMAIN)));
                ?>
            </a>
        </div>
        <?php } ?>
    </div>
</section>


<?php   
get_template_part_var('archive/pagination');

get_footer();

?>