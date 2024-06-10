<?php

/**
 * @return array
 * @description get products to exclude
 */
function exclude_product_ids(): array
{
    $exclude_product_ids = get_free_gift_products();
    $gift_product_fields = [
        'free_gift_note',
        'gift_wrap'
    ];

    foreach ($gift_product_fields as $field) {
        $product_id = get_field($field, 'options');

        if (!$product_id) {
            continue;
        }

        $exclude_product_ids[] = $product_id;
    }

    $exclude_product_ids[] = get_gift_product();

    return $exclude_product_ids;
}

function _get_posts(array $args = []): array
{
    $args = array_merge([
        'post_type'    => 'product',
        'post_status'  => 'publish',
        'numberposts'  => 20,
        'post__not_in' => exclude_product_ids(),
    ], $args);

    return get_posts($args);
}

/**
 * @description generates alphabet with radio buttons
 */
function alphabet($alphabetClass = '', $letterClass = '')
{
    $alphabet = [
        'a' => 'A',
        'b' => 'B',
        'c' => 'C',
        'd' => 'D',
        'e' => 'E',
        'f' => 'F',
        'g' => 'G',
        'h' => 'H',
        'i' => 'I',
        'j' => 'J',
        'k' => 'K',
        'l' => 'L',
        'm' => 'M',
        'n' => 'N',
        'o' => 'O',
        'p' => 'P',
        'q' => 'Q',
        'r' => 'R',
        's' => 'S',
        't' => 'T',
        'u' => 'U',
        'v' => 'V',
        'w' => 'W',
        'x' => 'X',
        'y' => 'Y',
        'z' => 'Z',
    ];
    ?>

    <div class="<?php echo esc_attr($alphabetClass); ?>">
        <?php foreach ($alphabet as $letter_lover => $letter_upper):
            $id = 'letter-' . $letter_lover; ?>
            <div class="<?php echo esc_attr($letterClass); ?>">
                <input type="radio" id="<?php echo $id; ?>" data-value="<?php echo $letter_upper; ?>" name="alphabet-letter">
                <label for="<?php echo $id; ?>">
                    <?php echo $letter_upper; ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}


/**
 * @param int $term_id
 * @return string|null
 * @description gets the total sales count of a specific product category
 */
function counts_total_sales_by_product_category(int $term_id): ?string
{
    if (!$term_id) {
        return null;
    }

    global $wpdb;
    return $wpdb->get_var("
        SELECT sum(meta_value)
        FROM $wpdb->postmeta
        INNER JOIN {$wpdb->term_relationships} ON ({$wpdb->term_relationships}.object_id = {$wpdb->postmeta}.post_id)
        WHERE ({$wpdb->term_relationships}.term_taxonomy_id IN ($term_id))
        AND {$wpdb->postmeta}.meta_key = 'total_sales'"
    );
}


/**
 * @param int $limit
 * @return array
 * @description gets the product categories with the best sales
 */
function gets_best_selling_product_categories(int $limit = 20): array
{
    $total_sales = [];
    $product_categories = get_terms('pwb-brand');

    if (empty($product_categories)) {
        return $total_sales;
    }

    foreach ($product_categories as $product_cat) {
        $product_cat_id = $product_cat->term_id;
        $total_sales[$product_cat_id] = counts_total_sales_by_product_category($product_cat_id);
    }

    /* removes empty values from the array */
    $total_sales = array_filter($total_sales);

    /* sorts the array values in descending order */
    arsort($total_sales);

    return $total_sales;
}


/**
 * @param int $limit
 * @return array
 * @description sort best selling products by the current language
 */
function best_selling_products_sort_by_language(int $limit = 20): array
{
    $sorted_brands = [];
    $brands = get_option('_best_selling_brands');

    if (empty($brands)) {
        return $sorted_brands;
    }

    $i = 0;
    foreach ($brands as $brand_id => $total_sales) {
        if ($i > $limit) {
            break;
        }

        if (pll_get_term_language($brand_id) != CURRENT_LANG) {
            continue;
        }

        $sorted_brands[$brand_id] = $total_sales;
        $i++;
    }

    return $sorted_brands;
}


/**
 * @param bool $flag
 * @return array
 * @description gets the languages in array
 */
function get_languages(bool $flag = false): array
{
    $languages_array = [];

    if (!function_exists('pll_the_languages')) {
        return $languages_array;
    }

    $languages = pll_the_languages([
        'raw'              => 1,
        'show_names'       => 0,
        'show_flags'       => $flag,
        'display_names_as' => 'slug'
    ]);

    if (empty($languages)) {
        return $languages_array;
    }

    foreach ($languages as $slug => $data) {
        $name = $slug === 'en' ? 'eng' : $slug;

        $languages_array[$slug] = [
            'name' => strtoupper($name),
            'url'  => $data['url']
        ];

        if (!$flag) {
            continue;
        }

        $flag_path = '/dest/img/flag-' . $slug . '.svg';

        if (file_exists(get_template_directory() . $flag_path)) {
            $languages_array[$slug]['flag'] = get_template_directory_uri() . $flag_path;
        }
    }

    ksort($languages_array);

    return $languages_array;
}


/**
 * @description generates social items
 */
function socials(): void
{
    $socials = get_field('socials', 'options');

    if (empty($socials)) {
        return;
    } ?>

    <div class="social_items__list">
        <?php foreach ($socials as $social_item) {
            if (empty($social_item['url']) || empty($social_item['icon'])) {
                continue;
            } ?>
            <a href="<?php echo $social_item['url']; ?>" class="social_item" target="_blank">
                <img src="<?php echo $social_item['icon']; ?>"
                     alt="<?php echo basename($social_item['url']); ?>"
                     title="<?php echo basename($social_item['url']); ?>">
            </a>
        <?php } ?>
    </div>
    <?php
}


/**
 * @param int $term_id
 * @return string|null
 * @description gets the term thumbnail url by term id
 */
function get_term_thumbnail_url(int $term_id = 0, string $size = 'large'): ?string
{
    if (!$term_id) {
        return null;
    }

    $term_image_id = get_term_meta($term_id, 'thumbnail_id', true);

    if (empty($term_image_id)) {
        return null;
    }

    return wp_get_attachment_image_url($term_image_id, 'large');
}


/**
 * @param string $table_name
 * @return bool
 * @description checks if table exists by name
 */
function is_table_exists(string $table_name = ''): bool
{
    if (!$table_name) {
        return false;
    }

    global $wpdb;
    return !!$wpdb->get_var("SHOW TABLES LIKE '$table_name'");
}


/**
 * @param string $taxonomy
 * @param array $terms
 * @param int $count
 * @return array
 * @description get the best selling products by taxonomy and terms
 */
function get_best_selling_products(string $taxonomy = 'product_cat', array $terms = [], int $count = 10): array
{
    $args = [
        'fields'              => 'ids',
        'ignore_sticky_posts' => true,
        'numberposts'         => $count,
        'suppress_filters'    => false,
        'meta_key'            => 'total_sales',
        'orderby'             => 'meta_value_num'
    ];

    if (!empty($terms)) {
        $args['tax_query'][] = [
            'taxonomy' => $taxonomy,
            'field'    => 'id',
            'terms'    => $terms
        ];
    }

    return _get_posts($args);
}


/**
 * @description cart icon output in header
 */
function header_cart()
{
    $cart_items_count = WC()->cart->get_cart_contents_count();

    $icon_name = $cart_items_count ? 'filled' : 'empty';
    $icon_url = get_template_directory_uri() . "/dest/img/cart-$icon_name.svg";
    ?>

    <div class="header_cart<?php echo $cart_items_count ? ' cart-filled' : ''; ?>">
        <a href="<?php echo CART_LINK; ?>">
            <div class="header_cart__img">
                <img src="<?php echo $icon_url; ?>"
                     alt="<?php _e('Cart', DOMAIN); ?>"
                     title="<?php _e('Cart', DOMAIN); ?>">
                <?php if ($cart_items_count): ?>
                    <div class="header_cart__count">
                        <?php echo $cart_items_count; ?>
                    </div>
                <?php endif; ?>
            </div>
        </a>
    </div>

    <?php
}


/**
 * @param $product
 * @return int
 * @description get the default variation id of a product
 */
function get_default_product_variation_id($product): int
{
    $variation_id = 0;

    if (empty($product)) {
        return $variation_id;
    }

    if (!$product->is_type('variable') && !$product->is_type('variation')) {
        return $variation_id;
    }

    $available_variations = $product->get_available_variations();

    if (empty($available_variations)) {
        return $variation_id;
    }

    $is_numeric_variation = false;
    foreach ($available_variations as $variation_values) {
        foreach ($variation_values['attributes'] as $key => $attribute_value) {
            if (preg_match('~[0-9]+~', $attribute_value)) {
                $is_numeric_variation = true;
                break 2;
            }
        }
    }

    $default_variation = get_post_meta($product->get_id(), '_default_attributes', true);

    if (!empty($default_variation)) {
        foreach ($available_variations as $variation_values) {
            $is_default_variation = true;

            foreach ($variation_values['attributes'] as $key => $attribute_value) {
                $attribute_name = str_replace('attribute_', '', $key);
                $default_value = $product->get_variation_default_attribute($attribute_name);

                if ($default_value != $attribute_value) {
                    $is_default_variation = false;
                    break;
                }
            }

            if ($is_default_variation) {
                $variation_id = $variation_values['variation_id'];
                break;
            }
        }
    } else {
        if ($is_numeric_variation) {
            $filtered_variations = [];
            $all_variations = [];

            foreach ($available_variations as $variation_values) {
                if (wc_get_product($variation_values['variation_id'])->is_in_stock()) {
                    foreach ($variation_values['attributes'] as $key => $attribute_value) {
                        $all_variations[$key][] = $attribute_value;
                    }
                }
            }

            if (!empty($all_variations)) {
                foreach ($all_variations as $variation_name => $variation_values) {
                    if (preg_match('~[0-9]+~', $variation_values[0] ?? '')) {
                        sort($variation_values, SORT_NUMERIC);
                        $filtered_variations[$variation_name] = end($variation_values);
                    } else {
                        if (!empty($variation_values)) {
                            $filtered_variations[$variation_name] = $variation_values[0];
                        }
                    }
                }
            }

            $variation_id = find_variation_id_by_attributes($product->get_id(), $filtered_variations);
        } else {
            $filtered_variations = [];

            foreach ($available_variations as $variation_values) {
                if (wc_get_product($variation_values['variation_id'])->is_in_stock()) {
                    $filtered_variations[] = $variation_values;
                }
            }

            if (!empty($filtered_variations)) {
                usort($filtered_variations, function ($a, $b) {
                    return $a['display_price'] - $b['display_price'];
                });

                $variation_id = $filtered_variations[0]['variation_id'];
            } else {
                $prices = array_column($available_variations, 'display_price');
                $min_price_index = array_search(min($prices), $prices);
                $variation_id = $available_variations[$min_price_index]['variation_id'];
            }
        }
    }

    return $variation_id;
}


function is_top_header_available()
{
    $wp_template_name = get_page_name();

    if ($wp_template_name) {
        $show_top_header = get_field('top_header_show', 'options');

        if (empty($show_top_header)) {
            return false;
        }

        return in_array($wp_template_name, $show_top_header);
    }

    return get_field('top_header_show');
}

function get_page_name(): string
{
    if (is_tax('product_cat') || is_page_template('templates/template-archive.php')) {
        return 'product_cat';
    }

    if (is_tax('pwb-brand')) {
        return 'product_brand';
    }

    if (is_singular('product')) {
        return 'product';
    }

    if (is_singular('post')) {
        return 'post';
    }

    return '';
}


/**
 * @param string $add_gift_wrap
 */
function add_gift_wrapper(string $add_gift_wrap = 'false'): void
{
    $gift_wrap_product_id = get_field('gift_wrap', 'options');

    if (empty($gift_wrap_product_id)) {
        return;
    }

    $is_product_in_cart = is_product_in_cart($gift_wrap_product_id);

    if ($add_gift_wrap === 'true') {
        if (!$is_product_in_cart) {
            WC()->cart->add_to_cart($gift_wrap_product_id);
        }
    } else {
        if ($is_product_in_cart) {
            $free_gift_note_id = get_field('free_gift_note', 'options');
            $is_gift_note_in_cart = is_product_in_cart($free_gift_note_id);

            remove_cart_item($gift_wrap_product_id);

            if ($is_gift_note_in_cart) {
                remove_cart_item($free_gift_note_id);
            }
        }
    }
}


/**
 * @param string $message
 */
function add_free_message(string $message = ''): void
{
    $message = trim($message);

    if ($message) {
        $max_length = get_field('free_gift_note_max_length', 'options') ?: 300;
        $message = cut_str($message, $max_length, '');
    }

    WC()->session->set('gift_message', $message);

    $free_gift_note_id = get_field('free_gift_note', 'options');

    $is_product_in_cart = is_product_in_cart($free_gift_note_id);

    if ($message) {
        if (!$is_product_in_cart) {
            WC()->cart->add_to_cart($free_gift_note_id);
        }
    } else {
        if ($is_product_in_cart) {
            remove_cart_item($free_gift_note_id);
        }
    }
}


/**
 * @param int $product_id
 * @return bool
 */
function is_product_in_cart(int $product_id = 0): bool
{
    if (!$product_id) {
        return false;
    }

    return in_array($product_id, array_column(WC()->cart->get_cart(), 'product_id'));
}


/**
 * @param int $product_id
 */
function remove_cart_item(int $product_id = 0): void
{
    if (!$product_id) {
        return;
    }

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            WC()->cart->remove_cart_item($cart_item_key);
            break;
        }
    }
}


/**
 * @param string $chosen_shipping_method
 * @return string
 */
function get_shipping_rate(string $chosen_shipping_method = '') : string
{
    if (!$chosen_shipping_method) {
        $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');

        if (empty($chosen_shipping_methods[0])) {
            return '';
        }

        $chosen_shipping_method = $chosen_shipping_methods[0];
    }

    if (empty($chosen_shipping_method)) {
        return '';
    }

    $shipping_rate = new WC_Shipping_Rate($chosen_shipping_method);
    $shipping_rate = $shipping_rate->get_id();

    return str_replace(':', '_', $shipping_rate);
}

function get_free_shipping_amount()
{
    $free_shipping_amount = '';

    if (!WC()->cart->show_shipping()) {
        return $free_shipping_amount;
    }

    $current_shipping_rate = get_shipping_rate();

    if (!$current_shipping_rate) {
        return $free_shipping_amount;
    }

    $free_shipping_rule = get_option('woocommerce_'.$current_shipping_rate.'_settings')['method_free_shipping_requires'] ?? '';

    if ($free_shipping_rule && $free_shipping_rule === 'coupon') {
        return $free_shipping_amount;
    }

    return get_option('woocommerce_'.$current_shipping_rate.'_settings')['method_free_shipping'] ?? '';
}


/**
 * @return int
 * @description how much left for get gift
 */
function left_for_gift_product(): array
{
    $gift_prices = get_free_gift_prices();
    $next_step_price = 0;

    if (empty($gift_prices)) {
        return [];
    }

    $total_price = 0;
    $gift_card_id = get_gift_product();

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if ($cart_item['product_id'] == $gift_card_id) {
            continue;
        }

        $total_price += $cart_item['data']->get_price() * $cart_item['quantity'];
    }

    foreach ($gift_prices as $gift_price) {
        if ($total_price < $gift_price) {
            $next_step_price = $gift_price;
            break;
        }
    }

    if ($next_step_price === 0) {
        return [];
    }

    $left_price = absint($next_step_price - $total_price);

    return [
        'next_step_price'    => $next_step_price,
        'left_to_reach_gift' => $left_price
    ];
}


/**
 * @return int
 */
function get_gift_product(): int
{
    $gift_product_id = get_posts([
        'post_type'   => 'product',
        'post_status' => 'publish',
        'numberposts' => 1,
        'fields'      => 'ids',
        'orderby'     => 'date',
        'order'       => 'DESC',
        'tax_query'   => [
            'relation' => 'OR',
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'gift-cards',
            ]
        ]
    ]);

    return $gift_product_id[0] ?? 0;
}


/**
 * @return array
 * @description get available social provider links
 */
function social_auth_providers(): array
{
    $available_provider_links = [];
    $apple = [
        'id'  => 'apple',
        'url' => apple_auth_url()
    ];

    if (!is_plugin_active('woocommerce-social-login/woocommerce-social-login.php')) {
        $available_provider_links[] = $apple;
        return $available_provider_links;
    }

    $return_url = wp_get_current_url();

    foreach (wc_social_login()->get_available_providers() as $provider) {
        if (get_user_meta(USER_ID, '_wc_social_login_' . $provider->get_id() . '_profile', true)) {
            continue;
        }

        $available_provider_links[] = [
            'id'  => $provider->get_id(),
            'url' => $provider->get_auth_url($return_url)
        ];
    }

    $available_provider_links[] = $apple;

    return $available_provider_links;
}


/**
 * @param $product_id
 * @param $attributes
 */
function find_variation_id_by_attributes($product_id, $attributes)
{
    $product = wc_get_product($product_id);

    $variations = $product->get_available_variations();

    foreach ($variations as $variation) {
        $match = true;

        foreach ($attributes as $attribute_name => $attribute_value) {
            if (strpos($attribute_name, 'attribute_') === false) {
                $attribute_name = 'attribute_' . $attribute_name;
            }

            if ($variation['attributes'][$attribute_name] !== $attribute_value) {
                $match = false;
                break;
            }
        }

        if ($match) {
            return $variation['variation_id'];
        }
    }

    return false;
}

/**
 * @param $variation_id
 * @return string
 */
function get_variation_price($variation_id): string
{
    $variation = wc_get_product($variation_id);
    return wc_price($variation->get_price());
}


/**
 * @param $product_id
 * @return array
 */
function get_all_possible_variations($product_id)
{
    $possible_variations = [];

    if (!$product_id) {
        return $possible_variations;
    }

    $product = wc_get_product($product_id);

    if (!$product || !$product->is_type('variable')) {
        return $possible_variations;
    }

    $available_variations = $product->get_available_variations();

    if (empty($available_variations)) {
        return $possible_variations;
    }

    foreach ($available_variations as $index => $available_variation) {
        if (empty($available_variation['attributes'])) {
            continue;
        }

        foreach ($available_variation['attributes'] as $variation_name => $available_variation_attribute) {
            $variation_name = str_replace('attribute_', '', $variation_name);
            
            if (!isset($possible_variations[$variation_name])) {
                $possible_variations[$variation_name] = [];
            }
            if (!in_array($available_variation_attribute, $possible_variations[$variation_name])) {
                $possible_variations[$variation_name][] = $available_variation_attribute;
            }
        }
    }

    return $possible_variations;
}


function get_product_images_of_order_item($order_items)
{
    $images = [];

    if (empty($order_items)) {
        return $images;
    }

    foreach ($order_items as $item_id => $item) {
        $thumbnail_url = '';

        if ($variation_id = $item->get_variation_id()) {
            $thumbnail_url = get_the_post_thumbnail_url($variation_id);
        }

        if (!$thumbnail_url) {
            if ($product_id = $item->get_product_id()) {
                $thumbnail_url = get_the_post_thumbnail_url($product_id);
            }
        }

        if (!$thumbnail_url) {
            $thumbnail_url = get_stylesheet_directory_uri() . '/dest/img/noimage.svg';
        }

        $images[$item_id] = $thumbnail_url;
    }

    return $images;
}


/**
 * @return array
 * Additional endpoints
 */
function my_account_additional_endpoints(): array
{
    $endpoints = [
        'reward-points' => __('Rewards', DOMAIN)
    ];

    if (is_plugin_active('pw-gift-cards/pw-gift-cards.php')) {
        $endpoints['gift-cards'] = __('Gift cards', DOMAIN);
    }

    return $endpoints;
}


/**
 * @return array
 * @description get user gift cards
 */
function get_users_gift_cards(int $user_id = 0)
{
    $user_id = $user_id ?: USER_ID;

    if (!$user_id) {
        return [];
    }

    $card_ids = get_user_meta($user_id, 'gift_cards', true);

    if (empty($card_ids)) {
        return [];
    }

    global $wpdb;

    $ids = implode(',', array_map('absint', $card_ids));

    return $wpdb->get_results("SELECT * FROM {$wpdb->pimwick_gift_card} WHERE pimwick_gift_card_id IN ({$ids})");
}


/**
 * @param int $gift_card_id
 * @return string|null
 */
function get_gift_card_amount(int $gift_card_id = 0): ?string
{
    if (!$gift_card_id) {
        return '';
    }

    global $wpdb;

    return $wpdb->get_var(
        $wpdb->prepare(
            "SELECT `amount` FROM {$wpdb->pimwick_gift_card_activity} 
                WHERE pimwick_gift_card_id = %d
                    AND `action` = 'transaction'
                    AND `amount` > 0 
                    LIMIT 1",
            $gift_card_id
        )
    );
}


/**
 * @param int $gift_card_id
 * @return string|null
 */
function gift_used_date(int $gift_card_id = 0): ?string
{
    if (!$gift_card_id) {
        return '';
    }

    global $wpdb;

    return $wpdb->get_var(
        $wpdb->prepare(
            "SELECT `activity_date` FROM {$wpdb->pimwick_gift_card_activity} 
                WHERE pimwick_gift_card_id = %d
                    AND `action` = 'transaction'
                    AND `amount` < 0 
                    LIMIT 1",
            $gift_card_id
        )
    );
}

function get_rewards(string $lang = ''): array
{
    if (!$lang) {
        $lang = CURRENT_LANG;
    }

    $rewards = get_field('rewards', 'options');
    $rewards_data = [];

    if (empty($rewards)) {
        return $rewards_data;
    }

    $currency_rate = get_currency_rate();

    foreach ($rewards as $reward) {
        $price = $reward['points'] ?? 0;
        $price *= $currency_rate;
        $products = $reward['products'] ?? [];

        if (empty($products)) {
            continue;
        }

        foreach ($products as $product_id) {
            $translations = pll_get_post_translations($product_id);

            if (empty($translations[$lang])) {
                continue;
            }

            $rewards_data[$translations[$lang]] = $price;
        }
    }

    return $rewards_data;
}


function get_used_reward_points()
{
    $used_reward_points = 0;
    $reward_items = get_rewards();

    if (empty($reward_items)) {
        return $used_reward_points;
    }

    $total_reward_points = get_user_meta(USER_ID, 'order_reward_points', true) ?: 0;

    if (!$total_reward_points) {
        return $used_reward_points;
    }

    $cart_items = WC()->cart->get_cart();

    if (empty($cart_items)) {
        return $used_reward_points;
    }

    foreach ($cart_items as $cart_item) {
        $product_id = $cart_item['product_id'] ?? '';

        if (!$product_id) {
            continue;
        }

        if (array_key_exists($product_id, $reward_items)) {
            $used_reward_points += $reward_items[$product_id];
        }
    }

    return $used_reward_points;
}

function gift_cards_total(): int
{
    if (!defined('PWGC_SESSION_KEY')) {
        return 0;
    }

    $session_data = (array)WC()->session->get(PWGC_SESSION_KEY);

    if (empty($session_data)) {
        return 0;
    }

    $gift_cards_total = 0;

    if (isset($session_data['gift_cards'])) {
        foreach ($session_data['gift_cards'] as $card_number => $discount_amount) {
            $gift_cards_total += $discount_amount;
        }
    }

    return $gift_cards_total;
}


function order_note_string(string $order_note): string
{
    if (!$order_note) {
        return '';
    }

    $reason_label = __('Reason', DOMAIN) . ': ';

    if ($strpos = strpos($order_note, ':')) {
        return $reason_label . trim(substr($order_note, 0, $strpos));
    }

    return $reason_label . $order_note;
}


function get_order_shipping_data(int $order_id = 0): array
{
    if (!$order_id) {
        return [];
    }

    if (!function_exists('fs_get_order_shipments')) {
        return [];
    }

    $shippings = [];
    $shipments = fs_get_order_shipments($order_id);

    if (empty($shipments)) {
        return [];
    }

    foreach ($shipments as $shipment) {
        $shipping = [];
        $shipping['order_id'] = $order_id;
        $shipping['integration'] = $shipment->get_integration();
        $shipping['url'] = $shipment->get_order_metabox_url();
        $shipping['error'] = $shipment->get_error_message();
        $shipping['status'] = $shipment->get_status_for_shipping_column();
        $shipping['tracking_number'] = $shipment->get_tracking_number();
        $shipping['label_url'] = $shipment->get_label_url();
        $shipping['tracking_url'] = $shipment->get_tracking_url();
        $shippings[] = $shipping;
    }

    return is_array($shippings) ? $shippings : [];
}


function mailchimp_request(string $email = '')
{
    if (!$email) {
        return null;
    }

    $api_key = get_field('mailchimp_api_key', 'options');
    $list_id = get_field('mailchimp_list_id', 'options');

    if (!$api_key || !$list_id) {
        return null;
    }

    $mailchimp = new SD_Mail_Chimp($api_key);

    $mailchimp_response = $mailchimp->post("lists/$list_id/members", [
        'email_address' => $email,
        'status'        => 'subscribed'
    ]);

    file_put_contents(
        get_stylesheet_directory() . '/mailchimp_logs.log',
        date('Y-m-d H:i:s') . json_encode($mailchimp_response) . PHP_EOL,
        FILE_APPEND
    );

    return $mailchimp_response;
}


function custom_get_page_title() {
    $title = '';
    if (is_product_category() || is_tax('product_tag')) {
        $title = strtoupper(single_term_title('', false));
    } elseif (is_single()) {
        $title = get_the_title();
    } else {
        $title = get_the_title();
    }
    return $title . ' - ' . get_bloginfo('name');
}


function get_wc_page_link(string $name = ''): string
{
    if (!$name) {
        return '';
    }

    $page_id = wc_get_page_id($name);

    if (!function_exists('pll_current_language')) {
        return get_permalink($page_id);
    }

    $current_language = pll_current_language();
    $translations = pll_get_post_translations($page_id);

    $translation_page_id = $translations[$current_language] ?? $page_id;

    return get_permalink($translation_page_id);
}

function apple_redirect_url()
{
    /* must be /konto */
    return home_url('konto');
}

function apple_authorize()
{
    if (empty($_POST['state']) || $_POST['state'] !== 'apple_auth') {
        return;
    }

    $response = http('https://appleid.apple.com/auth/token', [
        'grant_type'    => 'authorization_code',
        'code'          => $_POST['code'],
        'redirect_uri'  => apple_redirect_url(),
        'client_id'     => APPLE_CLIENT_ID,
        'client_secret' => APPLE_SECRET_ID
    ]);

    if (empty($response->access_token) || empty($response->id_token)) {
        return;
    }

    $claims = explode('.', $response->id_token)[1];
    $claims = json_decode(base64_decode($claims));
    $user_data = !empty($_POST['user']) ? unserialize($_POST['user'] ?? '') : [];

    auth_user_apple($claims->email ?? '', $user_data);
}

function apple_auth_url(): string
{
    return 'https://appleid.apple.com/auth/authorize' . '?' . http_build_query([
        'response_type' => 'code',
        'response_mode' => 'form_post',
        'client_id'     => APPLE_CLIENT_ID,
        'redirect_uri'  => apple_redirect_url(),
        'state'         => 'apple_auth',
        'scope'         => 'name email',
        'use_popup'     => true
    ], '', '&', PHP_QUERY_RFC3986);
}

function auth_user_apple(string $email = '', $user_data = [])
{
    if (!$email) {
        return;
    }

    $user = get_user_by('email', $email);
    $remember_place = $_COOKIE['remember_place'] ?? '';

    if ($remember_place) {
        $redirect = $remember_place;
    } else {
        $redirect = WC()->cart->get_cart_contents_count() ? CHECKOUT_LINK : '';
    }

    if (!empty($user)) {
        user_log_in($user->ID, $redirect);
        return;
    }

    $name = $user_data['name'] ?? [];
    $password = wp_generate_password(10, false);
    $display_name = '';
    $user_args = [
        'user_login' => $email,
        'user_email' => $email,
        'user_pass'  => $password,
        'role'       => 'customer'
    ];

    if (!empty($name)) {
        if ($first_name = $name['firstName'] ?? '') {
            $user_args['first_name'] = $first_name;
            $display_name = $first_name;
        }

        if ($last_name = $name['lastName'] ?? '') {
            $user_args['last_name'] = $last_name;
            $display_name .= ' ' . $last_name;
        }

        if ($display_name) {
            $user_args['display_name'] = $display_name;
        }

        if ($email = $user_data['email'] ?? '') {
            $user_args['user_email'] = $email;
        }
    }

    $user_id = wp_insert_user($user_args);

    if (is_wp_error($user_id) || !$user_id) {
        return;
    }

    user_log_in($user_id, $redirect);
}

function user_log_in($user_id, $redirect = MY_ACC_LINK)
{
    if (!$user_id) {
        return;
    }

    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    wp_safe_redirect($redirect);
}

function http($url, $params = false)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if ($params) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'User-Agent: curl'
        ]);
    }
    $response = curl_exec($ch);
    return json_decode($response);
}

function add_product_to_wishlist(int $product_id = 0, int $user_id = 0)
{
    if (!$product_id || !$user_id) {
        return;
    }

    $product_id = intval($product_id);

    $user_favorites = get_user_meta($user_id, 'favorites_posts', true) ?: [];

    if (in_array($product_id, $user_favorites)) {
        return;
    }

    if (empty($user_favorites) && !is_array($user_favorites)) {
        $user_favorites = [];
    }

    $user_favorites[] = $product_id;

    update_user_meta($user_id, 'favorites_posts', $user_favorites);
}


function gifts_data(): array
{
    $gifts_data = [];
    $free_gifts = get_field('free_gifts', 'options');
    $free_gifts_ids = [];
    $currency_rate = get_currency_rate();
    $current_currency = get_current_currency();

    if (empty($free_gifts)) {
        return $gifts_data;
    }

    foreach ($free_gifts as $item) {
        $step_price = $item['step_price'] ?? 0;
        $products = $item['products'] ?? [];

        /* Use currency exchange */
        if ($step_price && $current_currency !== 'PLN') {
            $step_price *= $currency_rate;
        }

        if (empty($products)) {
            continue;
        }

        $gifts_data[ceil($step_price)] = $products;
        $free_gifts_ids = array_merge($free_gifts_ids, $products);
    }

    return [
        'steps' => $gifts_data,
        'ids'   => $free_gifts_ids
    ];
}


/**
 * @return array
 * @description get available the free product gifts
 */
function get_free_gift_products(): array
{
    $gifts_data = gifts_data();
    return $gifts_data['ids'] ?? [];
}


/**
 * @return array
 * @description get available the free product gift prices
 */
function get_free_gift_prices(): array
{
    $gifts_data = gifts_data();

    if (empty($gifts_data['steps'])) {
        return [];
    }

    return array_keys($gifts_data['steps']);
}


function get_currency_rate()
{
    $rate = 1;

    if (!MULTI_CURRENCY_ACTIVE) {
        return $rate;
    }

    $options = get_option('woo_multi_currency_params');

    if (empty($options)) {
        return $rate;
    }

    $current_currency = get_current_currency();

    if (!$current_currency) {
        return $rate;
    }

    $currency = $options['currency'] ?? [];
    $currency_rate = $options['currency_rate'] ?? '';
    $currency_default = $options['currency_default'] ?? '';

    if (empty($currency) || empty($currency_rate) || empty($currency_default)) {
        return $rate;
    }

    if ($current_currency === $currency_default) {
        return $rate;
    }

    $currency_rate_index = array_search($current_currency, $currency);

    return $currency_rate[$currency_rate_index] ?? $rate;
}


function get_current_currency(): string
{
    if (!MULTI_CURRENCY_ACTIVE) {
        return 'PLN';
    }

    $multi_currency_data = WOOMULTI_CURRENCY_Data::get_ins();

    return $multi_currency_data->get_current_currency();
}


function points_amount($points_amount = 0): int
{
    if (!$points_amount) {
        $points_amount = WC()->cart->subtotal;
    }

    $points_multiplier = get_field('points_multiplier', 'options') ?: 1;

    return (int)($points_amount * $points_multiplier);
}


function get_currency_exchange_rates()
{
    $price_format = get_currency_price_format();

    if ($price_format != 'mid') {
        $table = 'c';
    } else {
        $table = 'a';
    }

    $response = http("https://api.nbp.pl/api/exchangerates/tables/$table/last/?format=json");

    if (empty($response[0]) || empty($response[0]->rates)) {
        return null;
    }

    return $response[0]->rates;
}

function get_currency_price_format(): string
{
    return get_field('multicurrency_price_format', 'options') ?: 'mid';
}

function new_products_time_interval(): int
{
    return get_field('new_product_time_interval_days', 'options') ?: 30;
}

function product_category_base(): string
{
    $wc_permalinks = get_option('woocommerce_permalinks', 'options') ?: [];

    return $wc_permalinks['category_base'] ?? 'kategoria-produktu';
}

function check_spell_string(string $string = ''): string
{
    /*if (!get_field('spell_check', 'options')) {
        return $string;
    }*/

    $string = trim($string);
    $string_array = explode(' ', $string);
    return implode(' ', array_map('check_spell_word', $string_array));
}

function check_spell_word(string $word = ''): string
{
    if (!$word) {
        return $word;
    }

    if (!function_exists('pspell_new')) {
        return $word;
    }

    $pspell = pspell_new(CURRENT_LANG, '', '', '', PSPELL_FAST);

    if (!$pspell) {
        return $word;
    }

    if (pspell_check($pspell, $word)) {
        return $word;
    }

    $suggestions = pspell_suggest($pspell, $word);

    return $suggestions[0] ?? $word;
}

function backorder_message(bool $is_image = true)
{
    if ($is_image) { ?>
        <img src="<?php echo get_template_directory_uri() . '/dest/img/backorder-img.svg'; ?>"
             alt="<?php _e('Backorder Notification Image', DOMAIN); ?>"
             title="<?php _e('Backorder Notification Image', DOMAIN); ?>"
             class="backorder-notification-image">
    <?php }

    echo wp_kses_post(
        apply_filters('woocommerce_cart_item_backorder_notification',
        '<p class="backorder_notification">' . esc_html__('AVAILABILITY: 5-15 DAYS', DOMAIN) . '</p>')
    );
}


/**
 * @param int $product_id
 * @param int $default_variation_id
 * @description update product metadata regarding default product variation
 */
function update_product_default_variation(int $product_id = 0, int $default_variation_id = 0)
{
    if (!$product_id || !$default_variation_id) {
        return;
    }

    $interval = time() + DAY_IN_SECONDS;

    update_post_meta($product_id, 'default_variation_update_time', $interval);
    update_post_meta($product_id, 'default_product_variation', $default_variation_id);
}


/**
 * @param int $product_id
 * @return int
 * @description getting product default variation id depends on time
 */
function get_saved_product_default_variation_id(int $product_id = 0)
{
    $default_variation_id = 0;

    if (!$product_id) {
        return $default_variation_id;
    }

    $interval = get_post_meta($product_id, 'default_variation_update_time', true);

    if (!$interval || $interval < time()) {
        return $default_variation_id;
    }

    return get_post_meta($product_id, 'default_product_variation', true) ?: $default_variation_id;
}

function custom_wc_dropdown_variation_attribute_options($args = [])
{
    $args = wp_parse_args(
        $args,
        [
            'options'          => false,
            'attribute'        => false,
            'product'          => false,
            'selected'         => false,
            'required'         => false,
            'name'             => '',
            'id'               => '',
            'class'            => '',
            'show_option_none' => __('Choose an option', 'woocommerce')
        ]
    );

    // Get selected value.
    if (false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product) {
        $selected_key = 'attribute_' . sanitize_title($args['attribute']);
        // phpcs:disable WordPress.Security.NonceVerification.Recommended
        $args['selected'] = isset($_REQUEST[$selected_key]) ? wc_clean(wp_unslash($_REQUEST[$selected_key])) : $args['product']->get_variation_default_attribute($args['attribute']);
        // phpcs:enable WordPress.Security.NonceVerification.Recommended
    }

    $options = $args['options'];
    $product = $args['product'];
    $attribute = $args['attribute'];
    $name = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title($attribute);
    $id = $args['id'] ? $args['id'] : sanitize_title($attribute);
    $class = $args['class'];
    $required = (bool)$args['required'];
    $show_option_none = (bool)$args['show_option_none'];
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __('Choose an option',
        'woocommerce'); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

    if (empty($options) && !empty($product) && !empty($attribute)) {
        $attributes = $product->get_variation_attributes();
        $options = $attributes[$attribute];
    }

    $html = '<select id="' . esc_attr($id) . '" class="' . esc_attr($class) . '" name="' . esc_attr($name) . '" data-attribute_name="attribute_' . esc_attr(sanitize_title($attribute)) . '" data-show_option_none="' . ($show_option_none ? 'yes' : 'no') . '"' . ($required ? ' required' : '') . '>';
    $html .= '<option value="">' . esc_html($show_option_none_text) . '</option>';

    if (!empty($options)) {
        if ($product && taxonomy_exists($attribute)) {
            // Get terms if this is a taxonomy - ordered. We need the names too.
            $terms = wc_get_product_terms(
                $product->get_id(),
                $attribute,
                [
                    'fields' => 'all'
                ]
            );

            foreach ($options as $option) {
                $option_name = '';
                foreach ($terms as $term) {
                    if ($term->slug == $option) {
                        $option_name = $term->name;
                    }
                }

                $selected = sanitize_title($args['selected']) === $args['selected'] ? selected($args['selected'], sanitize_title($option), false) : selected($args['selected'], $option, false);
                $html .= '<option value="' . esc_attr($option) . '" ' . $selected . '>' . esc_html($option_name) . '</option>';
            }
        } else {
            foreach ($options as $option) {
                // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                $selected = sanitize_title($args['selected']) === $args['selected'] ? selected($args['selected'], sanitize_title($option), false) : selected($args['selected'], $option, false);
                $html .= '<option value="' . esc_attr($option) . '" ' . $selected . '>' . esc_html($option) . '</option>';
            }
        }
    }

    $html .= '</select>';

    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo $html;
}