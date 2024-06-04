<section class="archive-head">
    <div class="container archive-head__wrapper">
        <?php
        $category = get_queried_object();
        $term_id = $category->term_id;
        $category_title = single_term_title('', false);
        $product_count = 0;
        $is_bestsellers = is_tax('product_cat', 'bestsellers');
        if (isset($_GET['categories']) && !empty($_GET['categories'])) {
            $categories = explode(',', sanitize_text_field($_GET['categories']));
            $args = [
                'post_type'   => 'product',
                'post_status' => 'publish',
                'tax_query'   => [
                    'relation' => 'AND',
                ],
            ];
            foreach ($categories as $cat_slug) {
                $args['tax_query'][] = [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $cat_slug,
                ];
            }
            $from_price = isset($_GET['from']) ? intval($_GET['from']) : null;
            $to_price = isset($_GET['to']) ? intval($_GET['to']) : null;
            if ($from_price !== null || $to_price !== null) {
                $args['meta_query'] = [
                    'relation' => 'AND',
                ];
        
                if ($from_price !== null) {
                    $args['meta_query'][] = [
                        'key'     => '_price',
                        'value'   => $from_price,
                        'type'    => 'numeric',
                        'compare' => '>=',
                    ];
                }
        
                if ($to_price !== null) {
                    $args['meta_query'][] = [
                        'key'     => '_price',
                        'value'   => $to_price,
                        'type'    => 'numeric',
                        'compare' => '<=',
                    ];
                }
            }
            $product_count_query = new WP_Query($args);
            $product_count = $product_count_query->found_posts;
        } else {
            $category = get_queried_object();
            $term_id = $category->term_id;
            $from_price = isset($_GET['from']) ? intval($_GET['from']) : null;
            $to_price = isset($_GET['to']) ? intval($_GET['to']) : null;
            if ($from_price !== null || $to_price !== null) {
                $meta_query = [
                    'relation' => 'AND',
                ];
                if ($from_price !== null) {
                    $meta_query[] = [
                        'key'     => '_price',
                        'value'   => $from_price,
                        'type'    => 'numeric',
                        'compare' => '>=',
                    ];
                }
                if ($to_price !== null) {
                    $meta_query[] = [
                        'key'     => '_price',
                        'value'   => $to_price,
                        'type'    => 'numeric',
                        'compare' => '<=',
                    ];
                }
                $product_count_query = new WP_Query([
                    'post_type'   => 'product',
                    'post_status' => 'publish',
                    'tax_query'   => [
                        [
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $term_id,
                        ],
                    ],
                    'meta_query'  => $meta_query,
                ]);
                $product_count = $product_count_query->found_posts;
            } else {
                $product_count_query = new WP_Query([
                    'post_type'   => 'product',
                    'post_status' => 'publish',
                    'tax_query'   => [
                        [
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $term_id,
                        ],
                    ],
                ]);
                    $product_count = $product_count_query->found_posts;
            }
        }

        $user_favorites = get_user_meta(USER_ID, 'favorites_posts', true);
        $selected_categories = isset($_GET['categories']) ? explode(',', sanitize_text_field($_GET['categories'])) : null;
        $current_url = esc_url(home_url($_SERVER['REQUEST_URI']));
        $base_category = product_category_base();
        $url_segments = explode('/', trim(parse_url($current_url, PHP_URL_PATH), '/'));
        $base_category_index = array_search($base_category, $url_segments);

        if (!empty($user_favorites)) {
            $favorites_count = count($user_favorites);
        }
        ?>
        <div class="archive-head__top">
            <?php
            if (is_page_template('templates/template-archive.php') || (is_page_template('templates/whishlist.php') && USER_ID)) {
                ?>
                <h1 class="archive-head__title"><?php echo esc_html(get_the_title()); ?></h1>
                <?php
            } else {
                ?>
                <?php
                if (empty($selected_categories)) {
                    $current_category = get_queried_object();

                    if ($current_category->parent) {
                        $parent_category = get_term($current_category->parent, $current_category->taxonomy);

                        $parent_category_link = get_term_link($parent_category);

                        if (!is_wp_error($parent_category_link)) {
                            echo '<a class="back-link" href="' . esc_url($parent_category_link) . '">back to ' . esc_html($parent_category->name) . '</a>';
                        }
                    }
                }
                ?>
                <h1 class="archive-head__title">
                    <?php
                    if (!empty($selected_categories)) {
                        _e('ADVANCED FILTER', DOMAIN);
                    } else {
                        echo esc_html($category_title);
                    }
                    ?>
                </h1>
                <?php
                $child_categories = get_terms([
                    'taxonomy'   => $category->taxonomy,
                    'parent'     => $term_id,
                    'hide_empty' => false,
                ]);

                if (!empty($selected_categories)) { ?>
                    <a href="<?php if ($base_category_index !== false && isset($url_segments[$base_category_index + 1])) {
                        $final_url = home_url($base_category . '/' . $url_segments[$base_category_index + 1]);
                        echo esc_url($final_url);
                    } else {
                        echo esc_url($current_url);
                    }
                    ?>" class="clear-filter-button">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="Linear / Essentional, UI / Trash Bin Minimalistic">
                                <path id="Vector" d="M7.64844 3.33268C7.99163 2.36169 8.91767 1.66602 10.0062 1.66602C11.0947 1.66602 12.0207 2.36169 12.3639 3.33268" stroke="#FE0000"
                                      stroke-width="1.25" stroke-linecap="round"/>
                                <path id="Vector_2" d="M17.0886 5H2.92188" stroke="#FE0000" stroke-width="1.25" stroke-linecap="round"/>
                                <path id="Vector_3"
                                      d="M15.6936 7.08301L15.3103 12.8323C15.1628 15.0447 15.089 16.1509 14.3682 16.8253C13.6474 17.4997 12.5387 17.4997 10.3214 17.4997H9.6769C7.45956 17.4997 6.35089 17.4997 5.63005 16.8253C4.90921 16.1509 4.83547 15.0447 4.68797 12.8323L4.30469 7.08301"
                                      stroke="#FE0000" stroke-width="1.25" stroke-linecap="round"/>
                                <path id="Vector_4" d="M7.92188 9.16602L8.33854 13.3327" stroke="#FE0000" stroke-width="1.25" stroke-linecap="round"/>
                                <path id="Vector_5" d="M12.0885 9.16602L11.6719 13.3327" stroke="#FE0000" stroke-width="1.25" stroke-linecap="round"/>
                            </g>
                        </svg>
                        <?php _e('Clear Filter', DOMAIN); ?>
                    </a>
                <?php } else if (!empty($child_categories)) { ?>
                    <div class="archive-head--subcat">
                        <?php _e('Subcategories', DOMAIN); ?>
                    </div>
                    <div class="child-categories">
                        <?php foreach ($child_categories as $child_category) { ?>
                            <a href="<?php echo esc_url(get_term_link($child_category->term_id)); ?>">
                                <?php echo esc_html($child_category->name);

                                $sub_categories = get_terms([
                                    'taxonomy'   => $category->taxonomy,
                                    'parent'     => $child_category->term_id,
                                    'hide_empty' => false
                                ]);

                                if (!empty($sub_categories)) { ?>
                                    <span class="category-arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="7" height="11" viewBox="0 0 7 11" fill="none">
                                            <path d="M0.906298 1.48027C0.663585 1.2898 0.6633 0.922338 0.905719 0.731496C1.0783 0.595633 1.32141 0.595434 1.49421 0.731011L6.56979 4.71325C7.08009 5.11363 7.08009 5.88637 6.56979 6.28675L1.49276 10.2701C1.32072 10.4051 1.07875 10.4052 0.906594 10.2704C0.663936 10.0803 0.663696 9.71311 0.906105 9.52274L6.02865 5.5L0.906298 1.48027Z" fill="#585858"/>
                                        </svg>
                                    </span>
                                <?php } ?>
                            </a>
                        <?php } ?>
                    </div>
                <?php }
            } ?>
        </div>

        <div class="archive-head__bottom">
            <?php if (is_page_template('templates/whishlist.php')):
                if (!empty($user_favorites) && $favorites_count > 0)  : ?>
                    <div class="archive-head__count"><?php echo esc_html(sprintf(_n('%d item', '%d items', $favorites_count, DOMAIN), $favorites_count)); ?></div>
                <?php endif;
                
            elseif (($product_count > 0 || $category->count > 0) && ($is_bestsellers)): ?>
                <div class="archive-head__count">
                    <?php
                        $display_count = ($product_count > 0) ? $product_count : 0;
                        echo esc_html(sprintf('%s item%s', $display_count, ($display_count !== 1) ? 's' : ''));
                    ?>
                </div>
            <?php elseif ($product_count > 0 && (!empty($is_child_category) || (isset($parent_category_id) && $parent_category_id === 0 && !empty($has_child_categories))) && (!$is_bestsellers)) : ?>
                <div class="title mob-title">
                    <?php _e('FILTER', DOMAIN); ?><span>(<?php echo esc_html(sprintf(_n('%d', '%d', $product_count, DOMAIN), $product_count)); ?>)</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M20 17L4 17" stroke="#1B1B1B" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M15 12L4 12" stroke="#1B1B1B" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M9 7L4 7" stroke="#1B1B1B" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
            <?php elseif ($category->count > 0): ?>
                <div class="archive-head__count">
                    <?php
                    $display_count = ($product_count > 0) ? $product_count : (is_numeric($category->count) ? $category->count : 0);
                    echo esc_html(sprintf('%s item%s', $display_count, ($display_count !== 1) ? 's' : ''));
                    ?>
                </div>
            <?php endif; ?>
            <?php
            if (is_page_template('templates/template-archive.php')) {
                $cats = get_field('cats');
                $brands = get_field('brands');

                $total_count = 0;

                function get_posts_count($field_value, $taxonomy)
                {
                    $count = 0;

                    if ($field_value && is_array($field_value)) {
                        foreach ($field_value as $term_id) {
                            $posts_query = new WP_Query([
                                'post_type'      => 'product',
                                'posts_per_page' => -1,
                                'post_status'    => 'publish',
                                'post__not_in'   => exclude_product_ids(),
                                'tax_query'      => [
                                    [
                                        'taxonomy' => $taxonomy,
                                        'field'    => 'term_id',
                                        'terms'    => $term_id,
                                    ],
                                ],
                            ]);
                            $count += $posts_query->found_posts;

                            wp_reset_postdata();
                        }
                    }

                    return $count;
                }

                $cats_count = get_posts_count($cats, 'product_cat');
                $brands_count = get_posts_count($brands, 'pwb-brand');

                $total_count = $cats_count + $brands_count;

                if ($total_count > 0): ?>
                    <div class="archive-head__count">
                        <?php
                        echo esc_html(sprintf(_n('%d item', '%d items', $total_count, DOMAIN), $total_count));
                        ?>
                    </div>
                <?php endif;
            }
            ?>
            <?php if (($product_count > 0 || $category->count > 0) || (is_page_template('templates/template-archive.php') || (is_page_template('templates/whishlist.php') && !empty($user_favorites) && $favorites_count))) : ?>
                <div class="archive-head__order">
                    <div class="archive-head__select">
                        <span class="selected-option"><?php _e('Sort by', DOMAIN); ?></span>
                        <ul class="archive-head__options-list">
                            <?php
                            $orderby_options = [
                                'total_sales' => __('Bestselling (Default)', DOMAIN),
                                'name'        => __('Product Name A-Z', DOMAIN),
                                'name-desc'   => __('Product Name Z-A', DOMAIN),
                                'price'       => __('Price Low to High', DOMAIN),
                                'price-desc'  => __('Price High to Low', DOMAIN),
                                'date'        => __('Newest First', DOMAIN),
                                'date-asc'    => __('Oldest First', DOMAIN),

                            ];

                            foreach ($orderby_options as $value => $label) {
                                $active_class = (empty($_GET['orderby']) && $value === 'total_sales') || (isset($_GET['orderby']) && $_GET['orderby'] === $value) ? 'class="active"' : '';
                                $order = (isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC') ? 'DESC' : 'ASC';
                                if (in_array($value, ['price', 'price-desc', 'date-asc', 'date', 'total_sales', 'name', 'name-desc'])) {
                                    $current_order = isset($_GET['order']) ? strtoupper($_GET['order']) : '';
                                    if ($value === 'price') {
                                        $order = 'ASC';
                                    } else if ($value === 'price-desc') {
                                        $order = 'DESC';
                                    } else if ($value === 'date-asc') {
                                        $order = 'ASC';
                                    } else if ($value === 'date') {
                                        $order = 'DESC';
                                    }else if ($value === 'name-desc') {
                                        $order = 'DESC';
                                    }else if ($value === 'name') {
                                        $order = 'ASC';
                                    }else if ($value === 'total_sales') {
                                        $order = 'ASC';
                                    }
                                }
                                $url_params = ['orderby' => $value, 'order' => $order];
                                $url = home_url($_SERVER['REQUEST_URI']);
                                $url = remove_query_arg('page', $url);
                                $url = preg_replace('#/(.*?)/page/\d+/?$#', '/$1', $url);
                                $url = esc_url(add_query_arg($url_params, $url));
                                echo '<li ' . $active_class . '><a href="' . $url . '">' . esc_html($label) . '</a></li>';
                            }

                            ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>