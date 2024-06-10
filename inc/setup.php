<?php

//add_filter('show_admin_bar', '__return_false');
add_filter('gutenberg_use_widgets_block_editor', '__return_false');
add_filter('use_widgets_block_editor', '__return_false');
add_filter('woocommerce_enqueue_styles', '__return_empty_array');
add_filter('automatic_updater_disabled', '__return_true');

add_filter('site_transient_update_plugins', function ($value) {
    unset($value->response[plugin_basename(__FILE__)]);
    return $value;
});

add_action('wp_enqueue_scripts', 'wp_enqueue_scripts_call');
function wp_enqueue_scripts_call()
{
    wp_enqueue_style('global', WOP_THEME_URL . '/dest/css/global.css');
    wp_enqueue_style('swiper', WOP_THEME_URL . '/dest/lib/swiper-slider/swiper.css');
    wp_enqueue_script('swiper', WOP_THEME_URL . '/dest/lib/swiper-slider/swiper.js', [], time());

    if (is_checkout() || is_account_page() || is_wc_endpoint_url()) {
        $google_api_key = get_field('google_map_api_key', 'options');
        if ($google_api_key) {
            wp_enqueue_script('google-places', "https://maps.googleapis.com/maps/api/js?key=$google_api_key&loading=async&libraries=places&language=" . CURRENT_LANG);
        }
    }

    if (is_home() || is_front_page()) {
        wp_enqueue_style('home', WOP_THEME_URL . '/dest/css/home.css');
    }

    if (is_tax() || is_archive() || is_page_template('templates/template-archive.php')) {
        wp_enqueue_style('archive', WOP_THEME_URL . '/dest/css/archive.css');
        wp_enqueue_script('archive-script', WOP_THEME_URL . '/dest/js/archive2.js', ['jquery'], time());

        wp_localize_script('archive-script', 'archivesetting', [
            'cat_slug' => product_category_base()
        ]);
    }

    if ( is_wc_endpoint_url( 'lost-password') || is_page_template('templates/reset-password.php' )) {
        wp_enqueue_style('forgot-password', WOP_THEME_URL . '/dest/css/forgot-password.css');
        wp_enqueue_script('forgot-script', WOP_THEME_URL . '/dest/js/forgot-password2.js', ['jquery'], time());
    }

    if (is_checkout()) {
        wp_enqueue_style('checkout', WOP_THEME_URL . '/dest/css/checkout.css');
        wp_enqueue_script('checkout-script', WOP_THEME_URL . '/dest/js/checkout-script.js', ['jquery'], time());

        wp_localize_script('checkout-script', 'ajaxcheckout', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('checkout-nonce')
        ]);
    }

    if (is_order_received_page()) {
        wp_enqueue_style('checkout', WOP_THEME_URL . '/dest/css/checkout.css');
        wp_enqueue_style('confirmation', WOP_THEME_URL . '/dest/css/thankyou.css');
    }

    if (is_account_page()) {
        wp_enqueue_style('checkout', WOP_THEME_URL . '/dest/css/checkout.css');
        wp_enqueue_style('myaccount', WOP_THEME_URL . '/dest/css/myaccount.css');

        wp_enqueue_script('pagination', WOP_THEME_URL . '/dest/lib/pagination/pagination.js', [], time());

        wp_enqueue_script('myaccount-script', WOP_THEME_URL . '/dest/js/myaccount-script.js', ['jquery', 'pagination'], time());

        wp_localize_script('myaccount-script', 'ajaxmyaccount', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('my-account-nonce')
        ]);
    }

    if (is_cart()) {
        wp_enqueue_style('cart', WOP_THEME_URL . '/dest/css/cart.css');
        wp_enqueue_script('cart-script', WOP_THEME_URL . '/dest/js/cart-script.js', ['jquery'], time());

        wp_localize_script('cart-script', 'ajaxcart', [
            'ajaxurl'              => admin_url('admin-ajax.php'),
            'update_cart_nonce'    => wp_create_nonce('update-cart'),
            'restore_item_message' => __('Oops! Get it back!', DOMAIN),
        ]);
    }

    wp_enqueue_script('jquery-script', WOP_THEME_URL . '/dest/js/jquery-script.js', ['jquery'], time());
    wp_enqueue_script('vanilla-script', WOP_THEME_URL . '/dest/js/vanilla-script.js', ['swiper'], time());

    if (is_product()) {
        wp_enqueue_script('wheelzoom', 'https://cdn.jsdelivr.net/npm/vanilla-js-wheel-zoom@8.0.0/dist/wheel-zoom.min.js');

        wp_enqueue_style('slick', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
        wp_enqueue_style('slick-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css');
        wp_enqueue_script('slick', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js');

        wp_enqueue_style('single-product', WOP_THEME_URL . '/dest/css/product.css');
        wp_enqueue_script('single-product', WOP_THEME_URL . '/dest/js/single-product.js', ['jquery', 'wheelzoom', 'slick', 'swiper'], time());
        wp_enqueue_style('gift-cards', WOP_THEME_URL . '/dest/css/gift-card.css');
    }

    if (is_page_template('templates/about-us.php')) {
        wp_enqueue_style('about-us', WOP_THEME_URL . '/dest/css/about-us.css');
    }

    if (is_page_template('templates/whishlist.php')) {
        wp_enqueue_style('whishlist', WOP_THEME_URL . '/dest/css/whishlist.css');
    }

    if (is_page_template('templates/contact-us.php')) {
        wp_enqueue_style('contact-us', WOP_THEME_URL . '/dest/css/contact-us.css');
        wp_enqueue_script('contact-us', WOP_THEME_URL . '/dest/js/contact-us2.js', ['jquery'], time(), true);

        $map_api_key = get_field('google_map_api_key', 'options');
        wp_enqueue_script('google-map-api', add_query_arg(
            [
                'key'      => $map_api_key,
                'loading'  => 'async',
                'language' => CURRENT_LANG,
                'callback' =>'mapInit'
            ],'https://maps.googleapis.com/maps/api/js'), ['contact-us'], time(), true);
    }

    wp_localize_script('jquery-script', 'wopajax', [
        'ajaxurl'        => admin_url('admin-ajax.php'),
        'nonce'          => wp_create_nonce('wop-nonce'),
        'required_field' => __('This field is required', DOMAIN),
        'my_account'     => is_account_page(),
        'checkout'       => is_checkout()
    ]);

    if (is_page_template('templates/text-page.php')) {
        wp_enqueue_style('tabs', WOP_THEME_URL . '/dest/css/text-page.css');
    }

    if (is_page_template('templates/brands.php')) {
        wp_enqueue_style('brands', WOP_THEME_URL . '/dest/css/brands.css');
        wp_enqueue_script('brands-script', WOP_THEME_URL . '/dest/js/brands-script.js', ['jquery'], '', true);
    }
}

add_action('template_redirect', 'template_redirect_call');
function template_redirect_call()
{
    if (is_singular('product_advantages')) {
        wp_redirect(home_url());
        exit;
    }
}

add_action('init', 'disable_wp_blocks', 100);
function disable_wp_blocks()
{
    $wc_styles = [
        'wp-block-library',
        'wc-blocks-style',
        'wc-blocks-style-active-filters',
        'wc-blocks-style-add-to-cart-form',
        'wc-blocks-packages-style',
        'wc-blocks-style-all-products',
        'wc-blocks-style-all-reviews',
        'wc-blocks-style-attribute-filter',
        'wc-blocks-style-breadcrumbs',
        'wc-blocks-style-catalog-sorting',
        'wc-blocks-style-customer-account',
        'wc-blocks-style-featured-category',
        'wc-blocks-style-featured-product',
        'wc-blocks-style-mini-cart',
        'wc-blocks-style-price-filter',
        'wc-blocks-style-product-add-to-cart',
        'wc-blocks-style-product-button',
        'wc-blocks-style-product-categories',
        'wc-blocks-style-product-image',
        'wc-blocks-style-product-image-gallery',
        'wc-blocks-style-product-query',
        'wc-blocks-style-product-results-count',
        'wc-blocks-style-product-reviews',
        'wc-blocks-style-product-sale-badge',
        'wc-blocks-style-product-search',
        'wc-blocks-style-product-sku',
        'wc-blocks-style-product-stock-indicator',
        'wc-blocks-style-product-summary',
        'wc-blocks-style-product-title',
        'wc-blocks-style-rating-filter',
        'wc-blocks-style-reviews-by-category',
        'wc-blocks-style-reviews-by-product',
        'wc-blocks-style-product-details',
        'wc-blocks-style-single-product',
        'wc-blocks-style-stock-filter',
        'wc-blocks-style-cart',
        'wc-blocks-style-checkout',
        'wc-blocks-style-mini-cart-contents',
        'classic-theme-styles-inline',
    ];

    foreach ($wc_styles as $wc_style) {
        wp_deregister_style($wc_style);
    }

    $wc_scripts = [
        'wc-blocks-middleware',
        'wc-blocks-data-store',
    ];

    foreach ($wc_scripts as $wc_script) {
        wp_deregister_script($wc_script);
    }
}

add_action('after_setup_theme', 'after_setup_theme_call');
function after_setup_theme_call()
{
    register_nav_menus([
        'main_header'          => 'Main Header',
        'header_mobile_bottom' => 'Header Mobile Bottom',
        'menu_item_1'          => 'Menu item 1',
        'menu_item_2'          => 'Menu item 2',
        'menu_item_3'          => 'Menu item 3',
        'menu_item_4'          => 'Menu item 4',
        'menu_item_5'          => 'Menu item 5',
        'menu_item_6'          => 'Menu item 6',
        'menu_item_7'          => 'Menu item 7',
        'menu_item_8'          => 'Menu item 8',
        'footer_menu_1'        => 'Footer Menu 1',
        'footer_menu_2'        => 'Footer Menu 2',
        'footer_menu_3'        => 'Footer Menu 3'
    ]);

    add_post_type_support('page', 'excerpt');

    add_theme_support('woocommerce');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'unlink-homepage-logo' => false
    ]);

    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => __('Options', DOMAIN),
            'menu_title' => __('Options', DOMAIN),
            'menu_slug'  => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect'   => false,
        ]);

        acf_add_options_sub_page([
            'page_title'  => __('Header', DOMAIN),
            'menu_title'  => __('Header', DOMAIN),
            'parent_slug' => 'theme-general-settings',
            'menu_slug'   => 'acf-settings-header',
        ]);
        acf_add_options_sub_page([
            'page_title'  => __('Footer', DOMAIN),
            'menu_title'  => __('Footer', DOMAIN),
            'parent_slug' => 'theme-general-settings',
            'menu_slug'   => 'acf-settings-footer',
        ]);
    }
}

add_filter('get_custom_logo_image_attributes', 'get_custom_logo_image_attributes_call');
function get_custom_logo_image_attributes_call($attr)
{
    $attr['title'] = $attr['alt'] ?? get_option('blogname');

    return $attr;
}

add_action('admin_menu', 'remove_default_post_types');
function remove_default_post_types()
{
    remove_menu_page('edit-comments.php');
}


add_filter('woocommerce_login_redirect', 'woocommerce_login_auth_call', 10, 1);
add_filter('woocommerce_registration_redirect', 'woocommerce_login_auth_call', 10, 1);
function woocommerce_login_auth_call($redirect_to)
{
    return $_COOKIE['remember_place'] ?? $redirect_to;
}


add_filter('login_redirect', 'custom_login_redirect', 10, 3);
function custom_login_redirect($redirect_to, $request, $user)
{
    return admin_url('edit.php?post_type=product');
}


add_action('wp_login', 'wp_login_call', 10, 2);
function wp_login_call($user_login, $user)
{
    $product_id = $_COOKIE['add_product_id'] ?? 0;

    if ($product_id) {
        add_product_to_wishlist($product_id, $user->ID);

        setcookie('add_product_id', null, strtotime('-1 day'));
        unset($_COOKIE['add_product_id']);
    }
}


add_action('user_register', 'user_register_call');
function user_register_call($user_id)
{
    $product_id = $_COOKIE['add_product_id'] ?? 0;

    if ($product_id) {
        add_product_to_wishlist($product_id, $user_id);

        setcookie('add_product_id', null, strtotime('-1 day'));
        unset($_COOKIE['add_product_id']);
    }
}


add_filter('upload_mimes', 'upload_mimes_types');
function upload_mimes_types($types)
{
    $types['svg'] = 'image/svg+xml';
    $types['webp'] = 'image/webp';

    return $types;
}

add_action('after_setup_theme', 'my_theme_setup');
function my_theme_setup()
{
    load_theme_textdomain(DOMAIN, get_template_directory() . '/languages');
}

//add_filter('script_loader_tag', 'add_type_attribute', 10, 3);
function add_type_attribute($tag, $handle, $src)
{
    //Module script handle names which have imports
    $scripts = [
        'jquery-script',
        'vanilla-script',
    ];

    if (in_array($handle, $scripts)) {
        return '<script type="module" src="' . esc_url($src) . '" id="' . esc_attr($handle) . '-id"></script>';
    }

    return $tag;
}

add_filter('walker_nav_menu_start_el', 'add_mega_menu', 10, 2);
function add_mega_menu($item_output, $item)
{
    $fields = get_fields($item->ID);
    $menu_options = $fields['menu_options'] ?? '';

    if (!$menu_options) {
        return $item_output;
    }

    $fields = get_field($menu_options, 'options');
    $header_location = $fields['submenu'] ?? '';

    if (!$header_location) {
        return $item_output;
    }

    ob_start();
    if ($header_location === 'brands') {
        $most_wanted_brands = best_selling_products_sort_by_language();

        get_template_part_var("mega-menu/mobile/submenu-brands", [
            'title'              => $item_output,
            'fields'             => $fields,
            'most_wanted_brands' => $most_wanted_brands
        ]);
        get_template_part_var("mega-menu/desktop/submenu-brands", [
            'title'              => $item_output,
            'fields'             => $fields,
            'most_wanted_brands' => $most_wanted_brands
        ]);
    } else {
        get_template_part_var("mega-menu/mobile/submenu", [
            'header_location' => $header_location,
            'title'           => $item_output,
            'fields'          => $fields
        ]);
        get_template_part_var("mega-menu/desktop/submenu", [
            'header_location' => $header_location,
            'title'           => $item_output,
            'fields'          => $fields
        ]);
    }

    $link_type = $fields['link_type'] ?? '';
    if ($link_type === 'category') {
        $category = $fields['category'] ?? '';

        if (!empty($category)) {
            $term_id = is_int($category) ? $category : $category->term_id;

            $link = get_term_link($term_id, 'product_cat');
        }
    } else if ($link_type === 'custom_link') {
        $link_array = $fields['link'] ?? '';

        if (!empty($link_array)) {
            $link = $link_array['url'] ?? '';
        }
    }

    if (!empty($link)) {
        $item_output .= '<a class="header_modal__view-more" href="'.$link.'">' . __('View more', DOMAIN) . '</a>';
    }

    $mega_menu = ob_get_clean();

    return $item_output . $mega_menu;

}


add_filter('terms_clauses', 'get_terms_fields', 10, 3);
function get_terms_fields($clauses, $taxonomies, $args)
{
    if (!empty($args['letter'])) {
        global $wpdb;

        $letter_like = $wpdb->esc_like($args['letter']);

        if (!isset($clauses['where'])) {
            $clauses['where'] = '1=1';
        }

        $clauses['where'] .= $wpdb->prepare(" AND t.name LIKE %s", "$letter_like%");
    }

    return $clauses;
}


add_filter('woocommerce_add_to_cart_redirect', function ($url) {
    return wp_get_current_url();
});


add_filter( 'woocommerce_get_price_html', 'custom_price_html', 11, 2 );
function custom_price_html($price_html, $product) {

    global $woocommerce_loop;

    if (is_product() && $product->is_on_sale()) {
        $regular_price = (float) $product->get_regular_price();
        $sale_price    = (float) $product->get_sale_price();
        $percentage = round(100 - ($sale_price / $regular_price * 100)) . '% OFF';

        $price_html = sprintf(
            '<span class="price_discount">%s</span>',
            $percentage
        ) . $price_html;
    }

    return $price_html;
}


add_filter('woocommerce_available_variation', function($available_variations, \WC_Product_Variable $variable, \WC_Product_Variation $variation) {
    if (empty($available_variations['price_html'])) {
        $available_variations['price_html'] = '<span class="price">' . $variation->get_price_html() . '</span>';
    }

    return $available_variations;
}, 10, 3);


add_filter('woocommerce_enable_order_notes_field', '__return_false');

add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields', 1);
function custom_override_checkout_fields($fields)
{
    $field_names = [
        'email'      => __('Email', DOMAIN),
        'country'    => __('Country', DOMAIN),
        'first_name' => __('Name', DOMAIN),
        'last_name'  => __('Last name', DOMAIN),
        'address_1'  => __('Street', DOMAIN),
        'address_2'  => __('Number', DOMAIN),
        'city'       => __('City / Region', DOMAIN),
        'postcode'   => __('ZIP Code', DOMAIN),
        'phone'      => __('Phone Number', DOMAIN)
    ];

    $i = 1;
    foreach ($field_names as $name => $label) {
        $fields['billing']['billing_' . $name]['placeholder'] = $label;
        $fields['billing']['billing_' . $name]['label'] = $label;
        $fields['billing']['billing_' . $name]['priority'] = $i;
        $i++;
    }

    return $fields;
}


add_filter('woocommerce_default_address_fields' , 'override_default_address_fields', 1, 1);
function override_default_address_fields($fields)
{
    unset($fields['company']);
    unset($fields['state']);
    unset($fields['createaccount']);

    $field_names = [
        'email'      => __('Email', DOMAIN),
        'country'    => __('Country', DOMAIN),
        'first_name' => __('Name', DOMAIN),
        'last_name'  => __('Last name', DOMAIN),
        'address_1'  => __('Street', DOMAIN),
        'address_2'  => __('Number', DOMAIN),
        'city'       => __('City / Region', DOMAIN),
        'postcode'   => __('ZIP Code', DOMAIN),
        'phone'      => __('Phone Number', DOMAIN)
    ];

    $i = 1;
    foreach ($field_names as $name => $title) {
        $fields[$name]['priority'] = $i;
        $fields[$name]['placeholder'] = $title;
        $fields[$name]['label'] = $title;
        $fields[$name]['required'] = true;
        $i++;
    }

    return $fields;
}


add_filter('woocommerce_form_field_email', 'filter_woocommerce_form_field_email', 10, 4);
function filter_woocommerce_form_field_email($field, $key, $args, $value) {
    if ($key === 'billing_email') {
        $text = __('* Please enter your email to receive order confirmation', DOMAIN);
        $field = str_replace(
            '</span>',
            '</span><small class="checkout-note">'.$text.'</small>',
            $field
        );
    }

    return $field;
}


add_filter('woocommerce_form_field_country', 'filter_woocommerce_form_field_country', 10, 4);
function filter_woocommerce_form_field_country($field, $key, $args, $value) {
    if ($key === 'billing_country') {
        $title = __('Shipping address', DOMAIN);
        return str_replace(
            '</label>',
            '</label><span class="checkout_field__label">'.$title.'</span>',
            $field
        );
    }

    return $field;
}


//add_filter('woocommerce_currency_symbol', 'custom_currency_symbol', 10, 2);
function custom_currency_symbol($currency_symbol, $currency)
{
    switch( $currency ) {
        case 'PLN': $currency_symbol = 'PLN'; break;
    }
    return $currency_symbol;
}


add_action('woocommerce_init', 'shipping_instance_form_fields_filters');
function shipping_instance_form_fields_filters()
{
    $shipping_methods = WC()->shipping->get_shipping_methods();
    if (!empty($shipping_methods)) {
        foreach ($shipping_methods as $shipping_method) {
            add_filter('woocommerce_shipping_instance_form_fields_' . $shipping_method->id, 'shipping_instance_form_add_extra_fields');
        }
    }
}


function shipping_instance_form_add_extra_fields($settings)
{
    $settings['shipping_extra_image'] = [
        'title'       => esc_html__('Shipping method image ID', DOMAIN),
        'type'        => 'text',
        'placeholder' => esc_html__('Please add image ID', DOMAIN),
        'description' => '',
        'default'     => ''
    ];

    return $settings;
}


add_filter('woocommerce_cart_shipping_method_full_label', 'woocommerce_cart_shipping_method_full_label_call', 10, 2);
function woocommerce_cart_shipping_method_full_label_call($label, $method)
{
    return str_replace(':', '' , $label);
}


add_filter('woocommerce_cart_item_subtotal', 'woocommerce_cart_item_subtotal_call', 99999, 1);
function woocommerce_cart_item_subtotal_call($subtotal)
{
    return str_replace(['Free', 'free'], wc_price(0) , $subtotal);
}


add_filter('woocommerce_get_availability_text', 'filter_product_availability_text', 10, 2);
function filter_product_availability_text($availability_text, $product)
{
    if ($product->get_stock_status() === 'onbackorder') {
        $availability_text = __('Availability: 5-15 days', DOMAIN);
    }

    if ($product->get_stock_status() === 'instock') {
        $availability_text = __('In stock', DOMAIN);
    }

    return $availability_text;
}


add_action('woocommerce_checkout_update_order_meta', 'woocommerce_checkout_update_order_meta_call');
function woocommerce_checkout_update_order_meta_call($order_id)
{
    if (!empty($_POST['gift_message'])) {
        update_post_meta($order_id, 'gift_message', sanitize_text_field($_POST['gift_message']));
    }

    $order = wc_get_order($order_id);
    $earned_points = points_amount($order->get_subtotal());
    $used_points = get_used_reward_points();

    update_post_meta($order_id, 'order_reward_points', $earned_points);
    update_post_meta($order_id, 'order_used_points', $used_points);

    if ($user = wp_get_current_user()) {
        $users_points = get_user_meta($user->ID, 'order_reward_points', true) ?: 0;
        $total_points = (int) $users_points - (int) $used_points + (int) $earned_points;

        update_user_meta($user->ID, 'order_reward_points', $total_points);
    }
}


add_action('woocommerce_checkout_create_order', 'woocommerce_checkout_create_order_call');
function woocommerce_checkout_create_order_call($order)
{
    $session_keys = [
        'order_note',
        'product_sample'
    ];

    foreach ($session_keys as $key) {
        $data = WC()->session->get($key);

        if (!$data) {
            continue;
        }

        $order->update_meta_data($key, $data);

        WC()->session->__unset($key);
    }

    WC()->session->__unset('cart_gifts_removed');
}

add_action('woocommerce_email_after_order_table', 'woocommerce_custom_meta', 20);
add_action('woocommerce_admin_order_data_after_billing_address', 'woocommerce_custom_meta');
function woocommerce_custom_meta($order)
{
    $data = [
        'gift_message'        => __('Gift message', DOMAIN),
        'order_note'          => __('User\'s message', DOMAIN),
        'order_reward_points' => __('Reward points', DOMAIN),
        'order_used_points'   => __('Used points', DOMAIN),
        'product_sample'      => __('Product sample', DOMAIN)
    ];

    foreach ($data as $key => $value) {
        $meta = $order->get_meta($key);

        if ($order->get_meta($key)) {
            echo '<p><strong style="display:block;">' . $value . ':</strong>' . $meta . '</p>';
        }
    }
}


remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );

add_action( 'woocommerce_cart_collaterals', 'custom_cart_collaterals', 10 );
function custom_cart_collaterals() {
    wc_get_template( 'cart/cart-collaterals.php' );
}


add_action('woocommerce_created_customer', 'woocommerce_created_customer_call', 10, 3);
function woocommerce_created_customer_call($customer_id)
{
    $order_id = $_POST['order_id'] ?? '';

    if ($order_id) {
        update_post_meta($order_id, '_customer_user', $customer_id);

        $order = wc_get_order($order_id);
        if (!empty($order)) {
            $points = points_amount($order->get_subtotal());

            update_post_meta($order_id, 'order_reward_points', $points);
            update_user_meta($customer_id, 'order_reward_points', $points);
        }
    }
}

/* Custom redirect */
add_action('woocommerce_customer_reset_password', 'custom_reset_password_redirect');
function custom_reset_password_redirect($user)
{
    $redirect_page_id = get_field('success_reset_password', 'options');
    if ($redirect_page_id) {
        $redirect_url = get_permalink($redirect_page_id);
    } else {
        $redirect_url = MY_ACC_LINK;
    }

    wp_safe_redirect($redirect_url);
    exit;
}


//REMOVE CF7 p tags
add_filter('wpcf7_autop_or_not', '__return_false');

//REGISTER GOOGLE MAPS API KEY
function my_acf_init()
{
    $map_api_key = get_field('google_map_api_key', 'options');
    acf_update_setting('google_api_key', $map_api_key);
}

add_action('acf/init', 'my_acf_init');


add_filter('woocommerce_return_to_shop_redirect', 'woocommerce_return_to_shop_redirect_call');
function woocommerce_return_to_shop_redirect_call()
{
    return home_url();
}


add_filter('woocommerce_return_to_shop_text', 'woocommerce_return_to_shop_text_call');
function woocommerce_return_to_shop_text_call()
{
    return __('Back to home', DOMAIN);
}


add_filter('woocommerce_account_menu_items', 'woocommerce_account_menu_items_call', 9999);
function woocommerce_account_menu_items_call($items)
{
    $items = [
        'edit-account'    => __('Contact', DOMAIN),
        'edit-address'    => _n('Address', 'Addresses', (1 + (int)wc_shipping_enabled()), DOMAIN),
        'orders'          => __('Orders', DOMAIN),
        'payment-methods' => __('Wallet', DOMAIN),
        'backinstock'     => __('Stock alert', DOMAIN),
    ];

    $extra_items = my_account_additional_endpoints();
    if (!empty($extra_items)) {
        $items = array_merge($items, $extra_items);
    }

    return $items;
}


/* Registration additional endpoints for My account page */
if (!empty(my_account_additional_endpoints())) {

    add_action('init', 'init_endpoints_call');
    function init_endpoints_call()
    {
        foreach (my_account_additional_endpoints() as $endpoint => $label) {
            add_rewrite_endpoint($endpoint, EP_ROOT | EP_PAGES);
        }
    }

    add_filter('woocommerce_get_query_vars', 'endpoints_query_vars_call', 0);
    function endpoints_query_vars_call($vars)
    {
        foreach (my_account_additional_endpoints() as $endpoint => $label) {
            $vars[$endpoint] = $endpoint;
        }

        return $vars;
    }

    foreach (my_account_additional_endpoints() as $endpoint => $label) {
        add_action("woocommerce_account_{$endpoint}_endpoint", function () use ($endpoint) {
            if (is_user_logged_in()) {
                wc_get_template("myaccount/{$endpoint}.php");
            }
        });
    }

}


add_action('woocommerce_before_calculate_totals', 'woocommerce_custom_price_to_cart_item', 99);
function woocommerce_custom_price_to_cart_item($cart_object)
{
    if (!WC()->session->__isset('reload_checkout')) {
        foreach ($cart_object->cart_contents as $key => $value) {
            if (isset($value['custom_price'])) {
                $value['data']->set_price($value['custom_price']);
            }
        }
    }
}


/* Default country */
add_filter('default_checkout_billing_country', 'default_checkout_country');
add_filter('default_checkout_shipping_country', 'default_checkout_country');
function default_checkout_country()
{
    return 'PL';
}


/**
 * Add gift product to cart if cart total is greater than or equal to min cart value.
 *
 * @param WC_Cart $cart
 *
 * @return void
 */
add_filter('woocommerce_before_calculate_totals', 'check_gifts');

function check_gifts($cart)
{
    $gifts_data = gifts_data();
    $gifts_in_cart = [];
    $active_step = false;

    if (empty($gifts_data['steps']) || empty($gifts_data['ids'])) {
        return;
    }

    $gift_card_id = get_gift_product();
    $gift_cards = [];
    $reward_items = get_rewards();

    /* Calculate order items total price */
    $cart_total = 0;
    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        if (array_key_exists($cart_item['product_id'], $reward_items)) {
            continue;
        }

        if ($cart_item['product_id'] == $gift_card_id) {
            $gift_cards[] = $cart_item['product_id'];
            continue;
        }

        if (in_array($cart_item['product_id'], $gifts_data['ids'])) {
            $gifts_in_cart[] = $cart_item['product_id'];
            continue;
        }

        $cart_total += $cart_item['data']->get_price() * $cart_item['quantity'];
    }

    if (!$cart_total && empty($gift_cards)) {
        $cart->empty_cart();
        return;
    }

    /* Get active step price */
    foreach ($gifts_data['steps'] as $step_price => $products) {
        if ($cart_total >= $step_price) {
            $active_step = $step_price;
        }

        if ($step_price > $cart_total) {
            break;
        }
    }

    /* If there are not active steps - clear gifts from the cart */
    if ($active_step === false && $gifts_in_cart) {
        foreach ($gifts_in_cart as $gift_in_cart_id) {
            if ($cart_item_key = $cart->generate_cart_id($gift_in_cart_id)) {
                $cart->remove_cart_item($cart_item_key);
            }
        }

        return;
    }

    $active_step_products_ids = $gifts_data['steps'][$active_step] ?? [];

    /* Add active step gift products */
    if (!empty($active_step_products_ids)) {
        foreach ($active_step_products_ids as $active_product_id) {
            if (in_array($active_product_id, $gifts_in_cart)) {
                continue;
            }

            /* If user removed once gift product then not add it */
            $cart_gifts_removed = WC()->session->get('cart_gifts_removed') ?: [];
            if (is_array($cart_gifts_removed) && in_array($active_product_id, $cart_gifts_removed)) {
                if ($active_product_item_key = $cart->generate_cart_id($active_product_id)) {
                    $cart->remove_cart_item($active_product_item_key);
                }

                continue;
            }

            $cart_item_key = $cart->generate_cart_id($active_product_id);

            if ($cart->get_cart_item($cart_item_key)) {
                continue;
            }

            $cart->add_to_cart($active_product_id);
        }
    }

    /* Remove inactive gift products */
    foreach ($gifts_data['steps'] as $price => $products) {
        if ($price == $active_step) {
            continue;
        }

        foreach ($products as $inactive_product_id) {
            if ($cart_item_key = $cart->generate_cart_id($inactive_product_id)) {
                $cart->remove_cart_item($cart_item_key);
            }
        }
    }
}


add_filter('cron_schedules', 'multi_currency_rate_exchange_cron_schedules');
function multi_currency_rate_exchange_cron_schedules($schedules)
{
    $schedules['six_hours'] = [
        'interval' => HOUR_IN_SECONDS * 6,
        'display'  => 'Once every 6 hours',
    ];

    $schedules['twelve_hours'] = [
        'interval' => HOUR_IN_SECONDS * 12,
        'display'  => 'Once every 12 hours',
    ];

    return $schedules;
}


add_action('wp', 'multi_currency_rate_exchange_event');
function multi_currency_rate_exchange_event()
{
    if (!wp_next_scheduled('manage_multi_currency_rate_exchange')) {
        wp_schedule_event(time(), 'six_hours', 'manage_multi_currency_rate_exchange');
    }

    if (!wp_next_scheduled('shopaholic_total_sales_products')) {
        wp_schedule_event(time(), 'twelve_hours', 'shopaholic_total_sales_products');
    }
}


add_action('manage_multi_currency_rate_exchange', 'manage_multi_currency_rate_exchange_call');
function manage_multi_currency_rate_exchange_call()
{
    if (!MULTI_CURRENCY_ACTIVE) {
        return;
    }

    $rates = get_currency_exchange_rates();

    if (empty($rates)) {
        return;
    }

    $selected_rates = [];
    $options = get_option('woo_multi_currency_params');

    if (empty($options['currency'])) {
        return;
    }

    $price_format = get_currency_price_format();

    foreach ($rates as $rate) {
        if (!in_array($rate->code, $options['currency'])) {
            continue;
        }

        if (!isset($rate->{$price_format})) {
            continue;
        }

        $selected_rates[$rate->code] = 1 / $rate->{$price_format};
    }

    if (empty($selected_rates)) {
        return;
    }

    foreach ($selected_rates as $code => $new_rate) {
        $currency_rate_index = array_search($code, $options['currency']);
        $options['currency_rate'][$currency_rate_index] = $new_rate;
    }

    update_option('woo_multi_currency_params', $options);
}


add_action('deactivate_woocommerce-multi-currency', 'multi_currency_rate_exchange_event_deactivation');
function multi_currency_rate_exchange_event_deactivation()
{
    wp_unschedule_hook('manage_multi_currency_rate_exchange');
}

add_action('shopaholic_total_sales_products', 'shopaholic_total_sales_products_call');
function shopaholic_total_sales_products_call()
{
    update_option('_best_selling_brands', gets_best_selling_product_categories());
}

add_action('switch_theme', 'theme_deactivated');
function theme_deactivation_hook()
{
    wp_unschedule_hook('manage_multi_currency_rate_exchange');
    wp_unschedule_hook('shopaholic_total_sales_products');
}
