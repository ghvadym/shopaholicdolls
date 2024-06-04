<?php

register_ajax([
    'header_menu_brands_filter',
    'brands_filter',
    'reset_brands_filter',
    'header_search_results',
    'custom_woocommerce_catalog_orderby',
    'checkout_update_totals',
    'update_order_session',
    'update_cart',
    'update_cart_totals',
    'cart_apply_coupon',
    'cart_apply_gift_card',
    'cart_update_rewards',
    'cart_update_rewards_html',
    'update_cart_variation',
    'update_user_data',
    'change_user_pass',
    'reorder_order',
    'manage_wishlist',
    'add_gift_card',
    'apply_gift_card',
    'newsletter_subscribe',
    'add_product_to_cart',
    'update_mini_cart_items_html'
]);

function header_menu_brands_filter()
{
    $data = sanitize_post($_POST);

    $search = !empty($data['letter']) ? htmlspecialchars($data['letter']) : '';

    if (!$search) {
        wp_send_json([
            'status'  => 'error',
            'message' => 'Search field is empty'
        ]);

        return;
    }

    $args = [
        'taxonomy'   => ['pwb-brand'],
        'hide_empty' => true,
        'fields'     => 'id=>name',
        'letter'     => $search,
        'number'     => 20,
    ];

    $brands = get_terms($args);

    if (empty($brands)) {
        wp_send_json([
            'status'  => 'error',
            'message' => 'There are no brands'
        ]);

        return;
    }

    ob_start();

    $brands_combined = array_chunk($brands, 4, true);
    foreach ($brands_combined as $brands): ?>
        <div class="menu-item">
            <?php foreach ($brands as $brand_id => $brand_name): ?>
                <a href="<?php echo get_term_link($brand_id); ?>">
                    <?php echo $brand_name; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endforeach;

    $html = ob_get_contents();
    ob_end_clean();

    wp_send_json([
        'html' => $html,
    ]);
}

function brands_filter()
{
    $data = sanitize_post($_POST);

    $search = !empty($data['letter']) ? htmlspecialchars($data['letter']) : '';

    if (!$search) {
        wp_send_json([
            'status'  => 'error',
            'message' => 'Search field is empty'
        ]);

        return;
    }

    $args = [
        'taxonomy'   => ['pwb-brand'],
        'hide_empty' => true,
        'fields'     => 'id=>name',
        'letter'     => $search,
        'number'     => 20,
    ];

    $brands = get_terms($args);

    if (empty($brands)) {
        wp_send_json([
            'status'  => 'error',
            'message' => 'There are no brands'
        ]);

        return;
    }

    ob_start();

    ?>
    <div class="brands__result-row">
        <h3><?php echo $search; ?></h3>
        <ul>
            <?php foreach ($brands as $brand_id => $brand_name): ?>
                <li>
                    <a href="<?php echo get_term_link($brand_id); ?>">
                        <?php echo $brand_name; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php

    $html = ob_get_contents();
    ob_end_clean();

    wp_send_json([
        'html' => $html,
    ]);
}

function reset_brands_filter()
{
    $args = [
        'taxonomy'   => 'pwb-brand',
        'hide_empty' => true,
        'fields'     => 'id=>name',
    ];

    $brands = get_terms($args);

    $alphabetical_brands = [];
    foreach ($brands as $brand_id => $brand_name) {
        $first_letter = strtoupper(substr($brand_name, 0, 1));
        $alphabetical_brands[$first_letter][] = ['id' => $brand_id, 'name' => $brand_name];
    }

    ob_start();

    foreach ($alphabetical_brands as $letter => $brand_list) {
        echo '<div class="brands__result-row">';
        echo '<h3>' . $letter . '</h3>';
        echo '<ul>';

        foreach ($brand_list as $brand) {
            echo '<li>';
            echo '<a href="' . get_term_link($brand['id']) . '">' . $brand['name'] . '</a>';
            echo '</li>';
        }

        echo '</ul>';
        echo '</div>';
    }

    $html = ob_get_contents();
    ob_end_clean();

    wp_send_json([
        'html' => $html,
    ]);
    wp_die();
}

function header_search_results()
{
    $data = sanitize_post($_POST);

    $search = !empty($data['search']) ? trim(htmlspecialchars($data['search'])) : '';
    $count = 30;

    if (!$search) {
        wp_send_json([
            'status'  => 'error',
            'message' => 'Search field is empty'
        ]);

        return;
    }

    $taxonomies = [
        'pwb-brand'   => [],
        'product_cat' => [],
    ];

    $terms_args = [
        'taxonomy'   => ['pwb-brand', 'product_cat'],
        'orderby'    => 'count',
        'order'      => 'DESC',
        'hide_empty' => true,
        'name__like' => $search,
    ];

    $terms = get_terms($terms_args);

    $posts_args = [
        'numberposts' => $count,
        'meta_key'    => 'total_sales',
        'orderby'     => 'meta_value_num',
    ];

    if (!empty($terms)) {
        $posts_args['tax_query'] = [
            'relation' => 'OR',
        ];

        foreach ($terms as $term) {
            $taxonomies[$term->taxonomy][] = $term->term_id;
        }

        foreach ($taxonomies as $taxonomy => $terms) {
            if (empty($taxonomy)) {
                continue;
            }

            $posts_args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field'    => 'id',
                'terms'    => $terms,
            ];
        }
    } else {
        $posts_args['s'] = $search;
    }

    $products = _get_posts($posts_args);

    if (!empty($terms) && (empty($products) || count($products) < $count)) {
        unset($posts_args['tax_query']);

        $posts_args['numberposts'] = $count - count($products);
        $posts_args['s'] = $search;

        $search_products = _get_posts($posts_args);

        if (!empty($search_products)) {
            array_push($products, $search_products);
        }
    }

    if (empty($products)) {
        wp_send_json([
            'error'  => true,
            'message' => __('Products not found.', DOMAIN)
        ]);

        return;
    }

    ob_start();

    foreach ($products as $product):
        if (empty($product->post_title)):
            continue;
        endif; ?>

        <a href="<?php echo get_the_permalink($product->ID); ?>" class="search_result__item">
            <div class="search_result__img">
                <img src="<?php echo get_the_post_thumbnail_url($product->ID); ?>"
                     alt="<?php _e('Search result product image', DOMAIN); ?>"
                     title="<?php _e('Search result product image', DOMAIN); ?>">
            </div>
            <div class="search_result__title">
                <?php echo $product->post_title; ?>
            </div>
        </a>
    <?php endforeach;

    $html = ob_get_contents();
    ob_end_clean();

    wp_send_json([
        'html' => $html,
    ]);
}

function custom_woocommerce_catalog_orderby()
{
    $orderby = isset($_POST['orderby']) ? wc_clean($_POST['orderby']) : 'menu_order';
    set_query_var('orderby', $orderby);
    wc_set_loop_prop('orderby', $orderby);
    wp_send_json_success();
}

function checkout_update_totals()
{
    $data = sanitize_post($_POST);

    if (empty($data['action']) || $data['action'] !== 'checkout_update_totals') {
        wp_send_json([
            'status'  => 'error',
            'message' => 'Invalid request'
        ]);

        return;
    }

    if (isset($data['gift_wrap'])) {
        add_gift_wrapper($data['gift_wrap']);
    }

    if (isset($data['gift_message'])) {
        add_free_message($data['gift_message']);
    }

    if (isset($data['summary_opened'])) {
        WC()->session->set('summary_opened', $data['summary_opened']);
    }

    ob_start();

    get_template_part('template-parts/checkout/order-summary');

    $html = ob_get_contents();
    ob_end_clean();

    wp_send_json([
        'html' => $html,
    ]);
}

function update_order_session()
{
    $data = sanitize_post($_POST);

    if (empty($data['action']) || $data['action'] !== 'update_order_session') {
        wp_send_json([
            'status'  => 'error',
            'message' => 'Invalid request'
        ]);

        return;
    }

    unset($data['action']);

    if (empty($data)) {
        wp_send_json([
            'status'  => 'error',
            'message' => 'No data'
        ]);

        return;
    }

    foreach ($data as $data_name => $data_value) {
        if ($data_value = wp_strip_all_tags($data_value)) {
            WC()->session->set($data_name, $data_value);
        } else {
            WC()->session->__unset($data_name);
        }
    }

    wp_send_json_success();
}

function update_cart()
{
    check_ajax_referer('update-cart', 'security');

    $data = sanitize_post($_POST);

    if (empty($data['cart_item_key'])) {
        wp_send_json([
            'status'  => 'error',
            'message' => 'Empty cart item key.'
        ]);

        return;
    }

    $cart_item_key = sanitize_key($data['cart_item_key']);

    if (isset($data['quantity'])) {
        $quantity = wc_stock_amount(wc_clean($data['quantity']));

        WC()->cart->set_quantity($cart_item_key, $quantity);
    } else if (isset($data['variation'])) {
        $variation = wc_clean($data['variation']);
        $product_id = WC()->cart->get_cart()[$cart_item_key]['product_id'];
        $variation_id = find_variation_id_by_attributes($product_id, $variation);

        if ($variation_id) {
            WC()->cart->remove_cart_item($cart_item_key);
            $new_cart_item_key = WC()->cart->add_to_cart($product_id, 1, $variation_id);
        }
    } else if (isset($data['restore_product'])) {
        $cart_item_key = isset($data['cart_item_key']) ? sanitize_key($data['cart_item_key']) : null;

        if ($cart_item_key) {
            $new_cart_item_key = WC()->cart->restore_cart_item($cart_item_key);
        }
    } else if (!empty($data['remove_product'])) {
        $product_id = WC()->cart->get_cart()[$cart_item_key]['product_id'];

        WC()->cart->remove_cart_item($cart_item_key);

        if (!empty($product_id) && in_array($product_id, get_free_gift_products())) {
            $removed_gifts = WC()->session->get('cart_gifts_removed') ?: [];

            if (is_array($removed_gifts)) {
                $products_translations = array_merge(
                    $removed_gifts,
                    array_values(pll_get_post_translations($product_id))
                );

                WC()->session->set('cart_gifts_removed', $products_translations);
            }
        }
    }

    WC()->cart->calculate_totals();

    wp_send_json_success();
}

function update_cart_totals()
{
    ob_start();
    get_template_part_var('cart/cart-totals');
    $cart_total_html = ob_get_clean();

    wp_send_json([
        'success' => true,
        'html'    => $cart_total_html
    ]);
}

function update_cart_variation()
{
    check_ajax_referer('update-cart', 'security');

    $data = sanitize_post($_POST);

    $cart_item_key = $data['cart_item_key'] ?? '';
    $variation = $data['variation'] ?? '';

    if (empty($cart_item_key) || empty($variation)) {
        wp_send_json_error('Provided not full data.');
    }

    $cart_item = WC()->cart->get_cart()[$cart_item_key] ?? [];

    if (empty($cart_item)) {
        wp_send_json_error('Invalid cart item key.');
    }

    $product_id = $cart_item['product_id'];

    $variation_id = find_variation_id_by_attributes($product_id, $variation);

    if (!$variation_id) {
        wp_send_json_error('Invalid variation attributes.');
    }

    $current_quantity = $cart_item['quantity'] ?? 1;

    WC()->cart->remove_cart_item($cart_item_key);

    $new_cart_item_key = WC()->cart->add_to_cart($product_id, $current_quantity, $variation_id);

    wp_send_json_success('Cart variation updated successfully.');
}


function cart_apply_coupon()
{
    check_ajax_referer('update-cart', 'security');

    $data = sanitize_post($_POST);

    $coupon_code = $data['coupon_code'] ?? '';

    if (!$coupon_code) {
        wp_send_json([
            'error' => true,
            'message' => __('Coupon code is required field.', DOMAIN)
        ]);

        return;
    }

    if (WC()->cart->has_discount($coupon_code)) {
        wp_send_json([
            'error' => true,
            'message' => __('Coupon has already been applied.', DOMAIN)
        ]);

        return;
    }

    if (WC()->cart->apply_coupon(sanitize_text_field($coupon_code))) {
        wp_send_json([
            'success' => true,
            'message' => __('Coupon applied successfully.', DOMAIN)
        ]);
    } else {
        wp_send_json([
            'error' => true,
            'message' => __('Coupon application failed.', DOMAIN),
        ]);
    }
}

function cart_apply_gift_card()
{
    check_ajax_referer('update-cart', 'security');

    $data = sanitize_post($_POST);

    if (!is_plugin_active('pw-gift-cards/pw-gift-cards.php')) {
        return;
    }

    $gift_code = $data['gift_code'] ?? '';

    if (!$gift_code) {
        wp_send_json([
            'error'   => true,
            'message' => __('Gift code is required field.', DOMAIN),
        ]);

        return;
    }

    $session_data = (array) WC()->session->get(PWGC_SESSION_KEY);

    if (!empty($session_data)) {
        $gift_cards = $session_data['gift_cards'] ?? [];

        if (!empty($gift_cards) && array_key_exists($gift_code, $gift_cards)) {
            wp_send_json([
                'error'   => true,
                'message' => __('Gift Card has already been applied.', DOMAIN),
            ]);

            return;
        }
    }

    $gift_card = new PW_Gift_Card($gift_code);

    if (!$gift_card->get_active()) {
        wp_send_json([
            'error'   => true,
            'message' => __('Gift Card is not active.', DOMAIN),
        ]);

        return;
    }

    if ($gift_card->get_balance() == 0) {
        wp_send_json([
            'error'   => true,
            'message' => __('This Gift Card has a zero balance.', DOMAIN),
        ]);

        return;
    }

    if ($gift_card->has_expired()) {
        wp_send_json([
            'error'   => true,
            'message' => __('This Gift Card has expired.', DOMAIN),
        ]);

        return;
    }

    $pw_gift_cards_redeeming = new PW_Gift_Cards_Redeeming();

    if ($pw_gift_cards_redeeming->add_gift_card_to_session($gift_code) !== true) {
        wp_send_json([
            'error'   => true,
            'message' => __('Gift Card has not been applied', DOMAIN),
        ]);

        return;
    }

    if (USER_ID) {
        $user_gift_cards = get_user_meta(USER_ID, 'gift_cards', true) ?: [];

        $gift_card_id = $gift_card->get_id();

        if (!in_array($gift_card_id, $user_gift_cards)) {
            $user_gift_cards[] = (int)$gift_card_id;

            update_user_meta(USER_ID, 'gift_cards', $user_gift_cards);
        }
    }
    
    wp_send_json([
        'success' => true,
        'message' => __('Gift Card has been applied', DOMAIN),
    ]);
}

function cart_update_rewards()
{
    check_ajax_referer('update-cart', 'security');

    $data = sanitize_post($_POST);

    $selected_reward_items_ids = $data['reward_items'] ?? [];
    $lang = $data['lang'] ?? CURRENT_LANG;
    $reward_items = get_rewards($lang);

    foreach ($reward_items as $reward_item) {
        $translations = pll_get_post_translations($reward_item->ID);

        foreach ($translations as $product_id) {
            if (is_product_in_cart($product_id)) {
                if (!in_array($product_id, $selected_reward_items_ids)) {
                    remove_cart_item($product_id);
                }
            } else {
                if (in_array($product_id, $selected_reward_items_ids)) {
                    WC()->cart->add_to_cart($product_id, 1, 0, [], [
                        'custom_price' => 0
                    ]);
                }
            }
        }
    }

    WC()->cart->calculate_totals();

    cart_update_rewards_html();
}

function cart_update_rewards_html()
{
    if (WC()->cart->get_cart_contents_count() == 0) {
        wp_send_json([
            'error' => true,
            'redirect' => CART_LINK,
            'message' => 'Cart is empty'
        ]);

        return;
    }

    ob_start();
    get_template_part_var('cart/reward-points', [
        'open' => true
    ]);
    $cart_total_html = ob_get_clean();

    wp_send_json([
        'success' => true,
        'html'    => $cart_total_html
    ]);
}

function update_user_data()
{
    check_ajax_referer('my-account-nonce', 'nonce');

    $data = sanitize_post($_POST);

    unset($data['action']);
    unset($data['ID']);
    unset($data['filter']);
    unset($data['nonce']);

    if (!empty($data['rm_fields'])) {
        foreach ($data as $key => $item) {
            if ($key === 'template_name') {
                continue;
            }

            $data[$key] = '';
        }
    }

    unset($data['rm_fields']);

    foreach ($data as $data_name => $data_value) {
        if ($data_name === 'template_name') {
            continue;
        }

        $data_value = wp_strip_all_tags($data_value);

        if ($data_value) {
            if (str_contains($data_name, 'email')) {
                $email = explode(' ', $data_value);
                if (!empty($email[0])) {
                    $data_value = sanitize_email($email[0]);
                }
            }

            if (str_contains($data_name, 'phone')) {
                $data_value = wc_sanitize_phone_number($data_value);
            }
        }

        update_user_meta(USER_ID, $data_name, $data_value);
    }

    update_user_data_html($data['template_name'] ?? '');
}


function update_user_data_html($template_name = '')
{
    if (!$template_name) {
        wp_send_json_success();
        return;
    }

    ob_start();

    $path = "template-parts/myaccount/$template_name";

    if (!file_exists(get_template_directory() . '/' . $path . '.php')) {
        wp_send_json([
            'status'  => 'error',
            'message' => 'There is no such template as ' . $template_name,
        ]);

        return;
    }

    get_template_part($path);

    $html = ob_get_contents();
    ob_end_clean();

    wp_send_json([
        'html' => $html,
    ]);
}


function change_user_pass()
{
    check_ajax_referer('my-acc-change-pass', 'change-pass-nonce');

    $data = sanitize_post($_POST);

    $old_pass = !empty($data['old_password']) ? trim($data['old_password']) : '';
    $new_pass = !empty($data['new_password']) ? trim($data['new_password']) : '';

    if (!$old_pass) {
        wp_send_json([
            'error'   => true,
            'message' => __('Old password in required field', DOMAIN),
        ]);

        return;
    }

    $user = get_user_by('id', USER_ID);

    if (!$user) {
        wp_send_json([
            'error'   => true,
            'message' => __('User not found', DOMAIN),
        ]);

        return;
    }

    if (!wp_check_password($old_pass, $user->user_pass, $user->ID)) {
        wp_send_json([
            'error'   => true,
            'message' => __('Old password is not correct', DOMAIN),
        ]);

        return;
    }

    if (!$new_pass) {
        wp_send_json([
            'error'   => true,
            'message' => __('New password in required field', DOMAIN),
        ]);

        return;
    }

    if ($old_pass === $new_pass) {
        wp_send_json([
            'error'   => true,
            'message' => __('New password must be different from old password', DOMAIN),
        ]);

        return;
    }

    if (strlen($new_pass) < 8) {
        wp_send_json([
            'error'   => true,
            'message' => __('New password must be 8 symbols at least', DOMAIN),
        ]);

        return;
    }

    wp_set_password($new_pass, $user->ID);

    wp_send_json_success();
}


function reorder_order()
{
    check_ajax_referer('my-account-nonce', 'nonce');

    $data = sanitize_post($_POST);

    $order_id = $data['order_id'] ?? '';

    if (!$order_id) {
        wp_send_json([
            'error'   => true,
            'message' => 'Order ID undefined',
        ]);

        return;
    }

    $order = wc_get_order($order_id);

    if (!$order) {
        wp_send_json([
            'error'   => true,
            'message' => 'Order not found',
        ]);

        return;
    }

    WC()->cart->empty_cart();

    /* Add products to cart */
    $gift_card_id = get_gift_product();
    $rewards = get_rewards();
    $rewards_ids = array_column($rewards, 'ID');
    $gifts_ids = get_free_gift_products();

    foreach ($order->get_items() as $item_id => $item) {
        $product_id = apply_filters('woocommerce_cart_item_product_id', $item['product_id'], $item, $item_id);
        $product = $item->get_product();
        $args = [];

        if (!$product) {
            continue;
        }

        if (in_array($product_id, $rewards_ids) || in_array($product_id, $gifts_ids)) {
            continue;
        }

        if ($gift_card_id === $product_id) {
            $args['custom_price'] = $item->get_total();

            $gift_meta_fields = [
                'pw_gift_card_message',
                'pw_gift_card_to',
                'pw_gift_card_from',
                'pw_gift_card_amount',
                'gift-card-amount',
            ];

            foreach ($gift_meta_fields as $gift_meta_field) {
                $args[$gift_meta_field] = wc_get_order_item_meta($item_id, $gift_meta_field);
            }

            $delivery_date = wc_get_order_item_meta($item_id, 'pw_gift_card_delivery_date');
            if ((isset($item['pw_gift_card_delivery_date']) && $item['pw_gift_card_delivery_date']) < date('Y-m-d')) {
                $delivery_date = date('Y-m-d');
            }

            $args['pw_gift_card_delivery_date'] = $delivery_date;
        }

        WC()->cart->add_to_cart($product_id, $item->get_quantity(), $item->get_variation_id(), [], $args);
    }

    /* Add custom session items to cart */
    $session_keys = [
        'order_note',
        'gift_message',
        'product_sample'
    ];

    foreach ($session_keys as $key) {
        if ($val = $order->get_meta($key)) {
            WC()->session->set($key, $val);
        }
    }

    /* Add addresses to cart */
    $addresses = [
        'billing',
        'shipping',
    ];

    foreach ($addresses as $address) {
        $fields = [
            'first_name',
            'address_1',
            'address_2',
            'city',
            'postcode',
            'country',
            'phone',
        ];

        foreach ($fields as $field) {
            WC()->customer->{"set_{$address}_{$field}"}(wc_clean($order->get_meta("_{$address}_{$field}")));
        }
    }

    /* Add shipping methods to cart */
    $shipping_methods = [];

    foreach ($order->get_items('shipping') as $item_id => $item) {
        $shipping_method_id = $item->get_method_id();
        $shipping_method_instance_id = $item->get_instance_id();

        $shipping_method = $shipping_method_id;
        if ($shipping_method_instance_id) {
            $shipping_method .= ':' . $shipping_method_instance_id;
        }

        $shipping_methods[] = $shipping_method;
    }

    if (!empty($shipping_methods)) {
        WC()->session->set('chosen_shipping_methods', $shipping_methods);

        if ($parcel_id = $order->get_meta('_paczkomat_id')) {
            WC()->session->set('paczkomat_id', $parcel_id);
        }
    }

    /* Add payment method to cart */
    WC()->session->set('chosen_payment_method', $order->get_meta('_payment_method'));

    wp_send_json([
        'success'  => true,
        'message'  =>'Order reordered successfully',
        'redirect' => CART_LINK,
    ]);
}


function manage_wishlist()
{
    $data = sanitize_post($_POST);

    if (empty($data['post_ID']) || empty($data['action'])) {
        wp_send_json_error(['message' => 'Invalid data']);
    }

    $postId = intval($data['post_ID']);
    $action = sanitize_text_field($data['wishlist_action']);

    $user_favorites = get_user_meta(USER_ID, 'favorites_posts', true) ?: [];

    if (empty($user_favorites) && !is_array($user_favorites)) {
        $user_favorites = [];
    }

    if ($action === 'add') {
        $user_favorites[] = $postId;
        $message = 'Post added to wishlist';
    } else if ($action === 'remove') {
        $user_favorites = array_values(array_diff($user_favorites, [$postId]));
        $message = 'Post removed from wishlist';
    } else {
        wp_send_json_error(['message' => 'Invalid action']);
    }

    update_user_meta(USER_ID, 'favorites_posts', $user_favorites);

    wp_send_json_success(['message' => $message, 'in_favorites' => $action === 'add', 'favorites' => $user_favorites]);
}


function add_gift_card()
{
    check_ajax_referer('my-account-nonce', 'nonce');

    $data = sanitize_post($_POST);

    $gift_code = $data['gift_code'] ?? '';

    if (!$gift_code) {
        wp_send_json([
            'error'   => true,
            'message' => __('Gift code is required field.', DOMAIN),
        ]);

        return;
    }

    $gift_card = new PW_Gift_Card($gift_code);

    if (!$gift_card->get_active()) {
        wp_send_json([
            'error'   => true,
            'message' => __('Gift card is not active.', DOMAIN),
        ]);

        return;
    }

    if ($gift_card->get_balance() == 0) {
        wp_send_json([
            'error'   => true,
            'message' => __('This gift card has a zero balance.', DOMAIN),
        ]);

        return;
    }

    if ($gift_card->has_expired()) {
        wp_send_json([
            'error'   => true,
            'message' => __('This gift has expired.', DOMAIN),
        ]);

        return;
    }

    $user_gift_cards = get_user_meta(USER_ID, 'gift_cards', true) ?: [];

    $gift_card_id = $gift_card->get_id();

    if (in_array($gift_card_id, $user_gift_cards)) {
        wp_send_json([
            'error'   => true,
            'message' => __('Gift card already added.', DOMAIN),
        ]);

        return;
    }

    $user_gift_cards[] = (int)$gift_card_id;

    update_user_meta(USER_ID, 'gift_cards', $user_gift_cards);

    ob_start();

    wc_get_template('myaccount/gift-cards.php', [
        'navigation' => true,
    ]);

    $html = ob_get_clean();

    wp_send_json([
        'success' => true,
        'html'    => $html,
    ]);
}


function apply_gift_card()
{
    check_ajax_referer('my-account-nonce', 'nonce');

    $data = sanitize_post($_POST);

    $gift_code = $data['gift_code'] ?? '';

    if (!$gift_code) {
        wp_send_json([
            'error'   => true,
            'message' => __('There is no gift code', DOMAIN),
        ]);

        return;
    }

    $session_data = (array) WC()->session->get(PWGC_SESSION_KEY);

    if (!empty($session_data)) {
        $gift_cards = $session_data['gift_cards'] ?? [];

        if (!empty($gift_cards) && array_key_exists($gift_code, $gift_cards)) {
            wp_send_json([
                'error'   => true,
                'message' => __('Gift Card has already been applied.', DOMAIN),
            ]);

            return;
        }
    }

    $gift_card = new PW_Gift_Card($gift_code);

    if (!$gift_card->get_active()) {
        wp_send_json([
            'error'   => true,
            'message' => __('Gift card is not active.', DOMAIN),
        ]);

        return;
    }

    if ($gift_card->get_balance() == 0) {
        wp_send_json([
            'error'   => true,
            'message' => __('This gift card has a zero balance.', DOMAIN),
        ]);

        return;
    }

    if ($gift_card->has_expired()) {
        wp_send_json([
            'error'   => true,
            'message' => __('This gift has expired.', DOMAIN),
        ]);

        return;
    }

    $pw_gift_cards_redeeming = new PW_Gift_Cards_Redeeming();

    if ($pw_gift_cards_redeeming->add_gift_card_to_session($gift_code) !== true) {
        wp_send_json([
            'error'   => true,
            'message' => __('Gift card has not been applied', DOMAIN),
        ]);

        return;
    }

    ob_start();

    wc_get_template('myaccount/gift-cards.php', [
        'navigation' => true,
    ]);

    $html = ob_get_clean();

    wp_send_json([
        'success' => true,
        'html'    => $html,
    ]);
}


function newsletter_subscribe()
{
    check_ajax_referer('wop-nonce', 'nonce');

    $data = sanitize_post($_POST);

    $email = $data['email'] ?? '';

    if (!$email) {
        wp_send_json([
            'error'   => true,
            'message' => __('Email is required field.', DOMAIN)
        ]);

        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        wp_send_json([
            'error'   => true,
            'message' => __('Email is invalid.', DOMAIN)
        ]);

        return;
    }

    $mailchimp_response = mailchimp_request($email);
    
    if (empty($mailchimp_response)) {
        wp_send_json([
            'error'   => true,
            'message' => 'No access.'
        ]);

        return;
    }

    if ($mailchimp_response['title'] == 'Member Exists') {
        wp_send_json([
            'error'   => true,
            'message' => __('You are already subscribed.', DOMAIN)
        ]);

        return;
    }

    if (!empty($mailchimp_response['status']) && $mailchimp_response['status'] == 'subscribed') {
        wp_send_json([
            'success' => true,
            'message' => __('Thank you for subscribing! Please, check your mailbox.', DOMAIN)
        ]);
    } else {
        wp_send_json([
            'error'   => true,
            'message' => 'Something went wrong.'
        ]);
    }
}


function add_product_to_cart()
{
    check_ajax_referer('wop-nonce', 'nonce');

    $data = sanitize_post($_POST);

    $product_id = $data['product_id'] ?? '';

    if (!$product_id) {
        wp_send_json([
            'error'   => true,
            'message' => __('Product ID not found.', DOMAIN)
        ]);

        return;
    }

    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json([
            'error'   => true,
            'message' => __('Product not found.', DOMAIN)
        ]);

        return;
    }

    $add_product_to_cart = WC()->cart->add_to_cart($product_id);
    WC()->cart->calculate_totals();

    if (!$add_product_to_cart) {
        wp_send_json([
            'error'   => true,
            'message' => 'Product not added to cart.'
        ]);

        return;
    }

    update_mini_cart_items_html();
}

function update_mini_cart_items_html()
{
    ob_start();
    header_cart();
    $cart_html = ob_get_clean();

    wp_send_json([
        'success' => true,
        'html'    => $cart_html
    ]);
}