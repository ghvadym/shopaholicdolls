<?php
/**
 * webtec-theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package webtec-theme
 */

if (!function_exists('webtec_theme_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function webtec_theme_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on webtec-theme, use a find and replace
         * to change 'webtec-theme' to the name of your theme in all the template files.
         */
        load_theme_textdomain('webtec-theme', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        // register_nav_menus( array(
        //   'menu-1' => esc_html__( 'Primary', 'webtec-theme' ),
        // ) );

        add_theme_support('custom-logo');  //własne logo

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ]);

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');
    }
endif;
add_action('after_setup_theme', 'webtec_theme_setup');

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function webtec_theme_pingback_header()
{
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
    }
}

add_action('wp_head', 'webtec_theme_pingback_header');

/* Kompresja zdjęć dodawanych przez Media - 100 oznacza że zdjęcia nie są kompresowane*/
add_filter('jpeg_quality', function ($arg) {
    return 100;
});

/* show also the same variation price */
add_filter('woocommerce_show_variation_price', function () {
    return true;
});

add_filter('woocommerce_product_variation_title_include_attributes', 'custom_product_variation_title', 10, 2);
function custom_product_variation_title($should_include_attributes, $product)
{
    $should_include_attributes = false;
    return $should_include_attributes;
}

/* security */
remove_action('wp_head', 'wp_generator');

add_filter('xmlrpc_enabled', '__return_false');

function wpb_disable_feed()
{
    wp_die(__('Wróć na stronę główną!'));
}

add_action('do_feed', 'wpb_disable_feed', 1);
add_action('do_feed_rdf', 'wpb_disable_feed', 1);
add_action('do_feed_rss', 'wpb_disable_feed', 1);
add_action('do_feed_rss2', 'wpb_disable_feed', 1);
add_action('do_feed_atom', 'wpb_disable_feed', 1);
add_action('do_feed_rss2_comments', 'wpb_disable_feed', 1);
add_action('do_feed_atom_comments', 'wpb_disable_feed', 1);

// Poczatek: Obsługa dodatkowego pola dot. zwracanej ilosci do magazynow produktow z wariantami ilościowymi

/**
 * Simple product setting.
 */
function ace_add_stock_inventory_multiplier_setting()
{

    ?>
    <div class='options_group'><?php

    woocommerce_wp_text_input([
        'id'                => '_stock_multiplier',
        'label'             => __('Zmniejszenie zapasów w stosunku do ilości sprzedanej', DOMAIN),
        'desc_tip'          => 'true',
        'description'       => __('Wpisz ilość mnożnik stosowany do redukcji poziomu zapasów przy zakupie.', DOMAIN),
        'type'              => 'number',
        'custom_attributes' => [
            'min'  => '1',
            'step' => '1',
        ],
    ]);

    ?></div><?php

}

add_action('woocommerce_product_options_inventory_product_data', 'ace_add_stock_inventory_multiplier_setting');

/**
 * Add variable setting.
 *
 * @param $loop
 * @param $variation_data
 * @param $variation
 */
function ace_add_variation_stock_inventory_multiplier_setting($loop, $variation_data, $variation)
{

    $variation = wc_get_product($variation);
    woocommerce_wp_text_input([
        'id'                => "stock_multiplier{$loop}",
        'name'              => "stock_multiplier[{$loop}]",
        'value'             => $variation->get_meta('_stock_multiplier'),
        'label'             => __('Zmniejszenie zapasów w stosunku do ilości sprzedanej', DOMAIN),
        'desc_tip'          => 'true',
        'description'       => __('Wpisz ilość mnożnik stosowany do redukcji poziomu zapasów przy zakupie.', DOMAIN),
        'type'              => 'number',
        'custom_attributes' => [
            'min'  => '1',
            'step' => '1',
        ],
    ]);

}

add_action('woocommerce_variation_options_pricing', 'ace_add_variation_stock_inventory_multiplier_setting', 50, 3);

/**
 * Save the custom fields.
 *
 * @param WC_Product $product
 */
function ace_save_custom_stock_reduction_setting($product)
{

    if (!empty($_POST['_stock_multiplier'])) {
        $product->update_meta_data('_stock_multiplier', absint($_POST['_stock_multiplier']));
    }
}

add_action('woocommerce_admin_process_product_object', 'ace_save_custom_stock_reduction_setting');

/**
 * Save custom variable fields.
 *
 * @param int $variation_id
 * @param $i
 */
function ace_save_variable_custom_stock_reduction_setting($variation_id, $i)
{
    $variation = wc_get_product($variation_id);
    if (!empty($_POST['stock_multiplier']) && !empty($_POST['stock_multiplier'][$i])) {
        $variation->update_meta_data('_stock_multiplier', absint($_POST['stock_multiplier'][$i]));
        $variation->save();
    }
}

add_action('woocommerce_save_product_variation', 'ace_save_variable_custom_stock_reduction_setting', 10, 2);

/**
 * Reduce with custom stock quantity based on the settings.
 *
 * @param $quantity
 * @param $order
 * @param $item
 * @return mixed
 */
function ace_custom_stock_reduction($quantity, $order, $item)
{

    /** @var WC_Order_Item_Product $product */
    $multiplier = $item->get_product()->get_meta('_stock_multiplier');

    if (empty($multiplier) && $item->get_product()->is_type('variation')) {
        $product = wc_get_product($item->get_product()->get_parent_id());
        $multiplier = $product->get_meta('_stock_multiplier');
    }

    if (!empty($multiplier)) {
        $quantity = $multiplier * $quantity;
    }

    return $quantity;
}

add_filter('woocommerce_order_item_quantity', 'ace_custom_stock_reduction', 10, 3);

function so_42345940_backorder_message($text, $product)
{
    if ($product->managing_stock() && $product->is_on_backorder(1)) {
        $text = __('Dostepny na zamówienie. Czas realizacji zamówienia to 5-10 dni roboczych.', 'your-textdomain');
    }
    return $text;
}

add_filter('woocommerce_get_availability_text', 'so_42345940_backorder_message', 10, 2);


/** Dodana treść pod tabelą z produktami informująca, że w przypadku gdy zamówienie zawiera produkt na zamóienie
 * czas realizacji z powodu potrzeby dostarczenia wynosi 5-10 dni
 */
add_action('woocommerce_email_after_order_table', 'mm_email_after_order_table', 10, 4);
function mm_email_after_order_table($order, $sent_to_admin, $plain_text, $email)
{
    $sign = 0;
    foreach ($order->get_items() as $item_id => $item) {
        $product = $item->get_product();
        if ($product->managing_stock() && $product->is_on_backorder(1)) {
            echo "<p>W przypadku towarów na zamówienie czas realizacji zamówienia to 5-15 dni roboczych</p>";
        }

    }

    echo "<p>Jeśli zauważysz, że przesyłka nosi oznaki uszkodzenia, bardzo prosimy o spisanie protokołu szkody z kurierem oraz poinformowanie nas o tym. </br>
        Reklamacje te będą rozpatrywane wyłącznie na podstawie protokołu szkody.</p>";
}

/* zmiana ikon dla paypala */
function my_new_paypal_icon()
{
    return 'https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppmcvdam.png';
}

add_filter('woocommerce_paypal_icon', 'my_new_paypal_icon');

/* mail o anulowaniu zamówienia do klienta */
add_filter('woocommerce_email_recipient_cancelled_order', 'wc_cancelled_order_add_customer_email', 10, 2);
add_filter('woocommerce_email_recipient_failed_order', 'wc_cancelled_order_add_customer_email', 10, 2);
function wc_cancelled_order_add_customer_email($recipient, $order)
{
    // Avoiding errors in backend (mandatory when using $order argument)
    if (!is_a($order, 'WC_Order')) return $recipient;

    return $recipient .= "," . $order->get_billing_email();
}

/**
 * @snippet       Display &quot;FREE&quot; if WooCommerce Product Price is Zero or Empty - WooCommerce
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @sourcecode    https://businessbloomer.com/?p=73147
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.5.3
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

add_filter('woocommerce_get_price_html', 'bbloomer_price_free_zero_empty', 100, 2);

function bbloomer_price_free_zero_empty($price, $product)
{

    if ('' === $product->get_price() || 0 == $product->get_price()) {
        $price = '<span class="woocommerce-Price-amount amount">FREE</span>';
    }

    return $price;
}

// Ustawienie pozycji ceny i dodania do koszyka zaraz za SKU

add_action('woocommerce_single_product_summary', 'customizing_variable_products', 1);
function customizing_variable_products()
{
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 15);
}


/** WYŁĄCZENIE BLIKA I INNYCH NOWOCZESNYCH METOD PŁATNOŚCI W PAYPAL */

/** add_filter( 'woocommerce_paypal_express_checkout_disable_smart_payment_buttons', '__return_true' );*/

add_filter('woocommerce_paypal_express_checkout_use_legacy_checkout_js', '__return_true');

// First, change the required password strength
add_filter('woocommerce_min_password_strength', 'reduce_min_strength_password_requirement');
function reduce_min_strength_password_requirement($strength)
{
    // 3 => Strong (default) | 2 => Medium | 1 => Weak | 0 => Very Weak (anything).
    return 2;
}

// Second, change the wording of the password hint.
add_filter('password_hint', 'smarter_password_hint');
function smarter_password_hint($hint)
{
    $hint = 'Wskazówka: dłuższe hasło jest silniejsze, rozważ użycie sekwencji losowych słów.';
    return $hint;
}


//zmiana id produktu dla produktów variable przy zdarzeniu dodawania do koszyku na potrzeba Facebook Pixela
function aepc_force_parent_id($params, $event)
{
    if (!empty($params['content_ids'])) {
        foreach ($params['content_ids'] as &$id) {
            if (($product = wc_get_product($id)) && $product->is_type('variation')) {
                $id = (string)$product->get_parent_id();
            }
        }
        $params['content_ids'] = array_unique($params['content_ids']);
    }

    return $params;
}

add_filter('aepc_event_parameters', 'aepc_force_parent_id', 10, 2);
add_filter('aepc_allowed_standard_event_params', 'aepc_force_parent_id', 10, 2);


//nowy filtr widoczności produktów na liście produktów
add_action('restrict_manage_posts', 'product_visibility_sorting');
function product_visibility_sorting()
{
    global $typenow;

    $taxonomy = 'product_visibility';

    if ($typenow == 'product') {


        $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);

        wp_dropdown_categories([
            'show_option_all' => __("Show all {$info_taxonomy->label}"),
            'taxonomy'        => $taxonomy,
            'name'            => $taxonomy,
            'orderby'         => 'name',
            'selected'        => $selected,
            'show_count'      => true,
            'hide_empty'      => true,
        ]);
    };
}

add_action('parse_query', 'product_tags_sorting_query');
function product_tags_sorting_query($query)
{
    global $pagenow;

    $taxonomy = 'product_visibility';

    $q_vars = &$query->query_vars;
    if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == 'product' && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
        $q_vars[$taxonomy] = $term->slug;
    }
}

/**
 * Show sale prices in the cart.
 */
function my_custom_show_sale_price_at_cart($old_display, $cart_item, $cart_item_key)
{
    /** @var WC_Product $product */
    $product = $cart_item['data'];

    if ($product) {
        return $product->get_price_html();
    }

    return $old_display;
}

add_filter('woocommerce_cart_item_price', 'my_custom_show_sale_price_at_cart', 10, 3);

/* procenciki w promotion flash sale wersja rozbudowana */
/* Replace text of Sale! badge with percentage */

add_filter('woocommerce_sale_flash', 'ds_replace_sale_text');

function ds_replace_sale_text($text)
{
    global $product;
    $stock = $product->get_stock_status();
    $product_type = $product->get_type();
    $sale_price = 0;
    $regular_price = 0;
    if ($product_type == 'variable') {
        $product_variations = $product->get_available_variations();
        foreach ($product_variations as $kay => $value) {
            if ($value['display_price'] < $value['display_regular_price']) {
                $sale_price = $value['display_price'];
                $regular_price = $value['display_regular_price'];
            }
        }
        /* oryginalnie było sprawdzanie czy jest na stanie
        if($regular_price > $sale_price && $stock != 'outofstock') {
           */
        if ($regular_price > $sale_price) {
            $product_sale = intval(((intval($regular_price) - floatval($sale_price)) / floatval($regular_price)) * 100);
            if ($product_sale > 5) {
                return '<span class="onsale">' . esc_html($product_sale) . '% OFF</span>';
            }
            if ($product_sale <= 5) {
                return '<span class="onsale"> Sale!</span>';
            }
        } else {
            return '';
        }
    } else {
        $regular_price = get_post_meta(get_the_ID(), '_regular_price', true);
        $sale_price = get_post_meta(get_the_ID(), '_sale_price', true);
        if ($regular_price > 5) {
            $product_sale = intval(((floatval($regular_price) - floatval($sale_price)) / floatval($regular_price)) * 100);
            return '<span class="onsale">  ' . esc_html($product_sale) . '% OFF</span>';
        }
        if ($regular_price >= 0 && $regular_price <= 5) {
            $product_sale = intval(((floatval($regular_price) - floatval($sale_price)) / floatval($regular_price)) * 100);
            return '<span class="onsale"> Sale!</span>';
        } else {
            return '';
        }
    }
}


/*
 * Woocommerce Filter by on sale
 */
function custom_woocommerce_filter_by_onsale($output)
{
    global $wp_query;

    $selected = filter_input(INPUT_GET, 'product_sale', FILTER_VALIDATE_INT);
    if ($selected == false) {
        $selected = 0;
    }

    $output .= '
        <select id="dropdown_product_sale" name="product_sale">
            <option value="">Filtr promocji</option>
            <option value="1" ' . (($selected === 1) ? 'selected="selected"' : '') . '>Na promocji</option>
            <option value="2" ' . (($selected === 2) ? 'selected="selected"' : '') . '>Brak promocji</option>
        </select>
    ';

    return $output;
}

add_action('woocommerce_product_filters', 'custom_woocommerce_filter_by_onsale');

/*
 * Woocommerce Filter by on sale where statement
 */
function custom_woocommerce_filter_by_onsale_where_statement($where)
{
    global $wp_query, $wpdb;

    // Get selected value
    $selected = filter_input(INPUT_GET, 'product_sale', FILTER_VALIDATE_INT);

    // Only trigger if required
    if (!is_admin() || get_query_var('post_type') != "product" || !$selected) {
        return $where;
    }

    $querystr = '
            SELECT p.ID, p.post_parent
            FROM ' . $wpdb->posts . ' p
            WHERE p.ID IN (
                SELECT post_id FROM ' . $wpdb->postmeta . ' pm WHERE pm.meta_key = "_sale_price" AND pm.meta_value > \'\'
            )
        ';

    $pageposts = $wpdb->get_results($querystr, OBJECT);

    $productsIDs = array_map(function ($n) {
        return $n->post_parent > 0 ? $n->post_parent : $n->ID;
    }, $pageposts);

    if ($selected == 1) {
        $where .= ' AND ' . $wpdb->posts . '.ID IN (' . implode(",", $productsIDs) . ') ';
    } else if ($selected == 2) {
        $where .= ' AND ' . $wpdb->posts . '.ID NOT IN (' . implode(",", $productsIDs) . ') ';
    }

    return $where;
}

add_filter('posts_where', 'custom_woocommerce_filter_by_onsale_where_statement');

/*
* Automatically add products on-sale to sale category
*/
add_action('woocommerce_update_product', 'update_product_set_sale_cat', 10, 2);

function update_product_set_sale_cat($product_id, $product)
{
    if ($product->is_on_sale()) {
        wp_add_object_terms($product_id, "sale", 'product_cat');
    } else { // this will also remove the sale category when the product in no longer on sale
        wp_remove_object_terms($product_id, "sale", 'product_cat');
    }
}


/*
* Add extra menu up site
*/
function register_my_menu()
{
    register_nav_menu('additional-menu', __('Up-Menu'));
}

add_action('init', 'register_my_menu');


add_filter('woocommerce_rest_check_permissions', 'my_woocommerce_rest_check_permissions', 90, 4);

function my_woocommerce_rest_check_permissions($permission, $context, $object_id, $post_type)
{
    return true;
}


/*
* https://www.zorem.com/hide-sales-data-from-the-woocommerce-shop-manager-role/
*
* Remove WooCommerce Dashboard Status for shop manager
*/
function remove_dashboard_widgets()
{
    if (current_user_can('shop_manager')) {
        // remove WooCommerce Dashboard Status
        remove_meta_box('woocommerce_dashboard_status', 'dashboard', 'normal');
    }
}

add_action('wp_user_dashboard_setup', 'remove_dashboard_widgets', 20);
add_action('wp_dashboard_setup', 'remove_dashboard_widgets', 20);

/*
* Remove WooCommerce reports for shop manager
* Remove analytics for shop manager
*/
function zorem_remove_wc_reports()
{
    if (current_user_can('shop_manager')) {
        remove_submenu_page('woocommerce', 'wc-reports');
        remove_menu_page('wc-admin&path=/analytics/overview');
        remove_submenu_page('woocommerce', 'wc-admin');
    }
}

add_action('admin_menu', 'zorem_remove_wc_reports', 110);


//Email product url
add_filter('woocommerce_order_item_name', 'display_product_title_as_link', 10, 2);
function display_product_title_as_link($item_name, $item)
{

    $_product = wc_get_product($item['variation_id'] ? $item['variation_id'] : $item['product_id']);

    $link = get_permalink($_product->get_id());

    return '<a href="' . $link . '"  rel="nofollow">' . $item_name . '</a>';
}

//Biger image thumbnail for email product image
add_filter('woocommerce_email_order_items_args', 'custom_wc_email_image_size', 30);
function custom_wc_email_image_size($args)
{
    $args['image_size'] = [50, 50];
    return $args;
}

//New menu item in menu My Account - Privacy Tool
add_filter('woocommerce_account_menu_items', function ($items, $endpoints) {
    $items['privacy-tool'] = 'Privacy Tool';
    return $items;
}, 10, 2);

add_filter('woocommerce_get_endpoint_url', function ($url, $endpoint, $value, $permalink) {
    if ($endpoint === 'privacy-tool') {
        $url = home_url('privacy-tool');
    }
    return $url;
}, 10, 4);

add_filter('wc_stripe_apple_pay_domain', function ($domain) {
    $domain = 'shopaholicdolls.com';
    return $domain;
});