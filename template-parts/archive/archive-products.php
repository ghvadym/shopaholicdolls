<?php
$posts_per_page = 24;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$category = get_queried_object();
$is_bestsellers = is_tax('product_cat', 'bestsellers');
$categories = get_terms($category->taxonomy, ['parent' => $category->term_id, 'hide_empty' => false]);
$currency_rate = get_currency_rate();
$price_from = $_GET['from'] ?? '';
$price_to = $_GET['to'] ?? '';
?>
<section class="archive-products">
    <div class="wrapper <?php if (is_page_template('templates/template-archive.php') || is_page_template('templates/whishlist.php') || (is_archive() && empty($is_filter))) {
        echo 'container large-col';
    } ?>">
        <?php
        if (is_page_template('templates/template-archive.php')) {
            $paged = isset($_GET['product-page']) ? max(1, intval($_GET['product-page'])) : 1;
            $product_count = 0;

            $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'menu_order';
            $order = isset($_GET['order']) ? strtoupper(sanitize_text_field($_GET['order'])) : 'ASC';

            wp_reset_query();

            $visible_relations_cats = [];
            $visible_relations_brands = [];

            $terms_cats = get_field('cats');
            if (!empty($terms_cats)) {
                foreach ($terms_cats as $term) {
                    $args = [
                        'post_type'      => 'product',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'post__not_in'   => exclude_product_ids(),
                        'tax_query'      => [
                            [
                                'taxonomy' => 'product_cat',
                                'field'    => 'term_id',
                                'terms'    => $term->term_id,
                            ],
                        ],
                    ];
                    $posts_query = new WP_Query($args);

                    if ($posts_query->have_posts()) {
                        while ($posts_query->have_posts()) {
                            $posts_query->the_post();
                            $visible_relations_cats[] = get_the_ID();
                        }
                    }
                    wp_reset_postdata();
                }
            }

            $terms_brands = get_field('brands');
            if (!empty($terms_brands)) {
                foreach ($terms_brands as $brand) {
                    $args = [
                        'post_type'      => 'product',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'post__not_in'   => exclude_product_ids(),
                        'tax_query'      => [
                            [
                                'taxonomy' => 'pwb-brand',
                                'field'    => 'term_id',
                                'terms'    => $brand->term_id,
                            ],
                        ],
                    ];
                    $posts_query = new WP_Query($args);

                    if ($posts_query->have_posts()) {
                        while ($posts_query->have_posts()) {
                            $posts_query->the_post();
                            $visible_relations_brands[] = get_the_ID();
                        }
                    }
                    wp_reset_postdata();
                }
            }

            $visible_relations = array_merge($visible_relations_cats, $visible_relations_brands);

            usort($visible_relations, function ($a, $b) use ($orderby, $order) {
                $product_a = wc_get_product($a);
                $product_b = wc_get_product($b);
                switch ($orderby) {
                    case 'total_sales':
                        $sales_a = $product_a->get_total_sales();
                        $sales_b = $product_b->get_total_sales();
                        return $order === 'ASC' ? $sales_a - $sales_b : $sales_b - $sales_a;
                    case 'name':
                        $title_a = $product_a->get_title();
                        $title_b = $product_b->get_title();
                        return strcasecmp($title_a, $title_b);
                    case 'name-desc':
                        $title_a = $product_a->get_title();
                        $title_b = $product_b->get_title();
                        return strcasecmp($title_b, $title_a);
                    case 'price':
                        $price_a = $product_a->get_price();
                        $price_b = $product_b->get_price();
                        return $order === 'ASC' ? $price_a - $price_b : $price_b - $price_a;
                    case 'price-desc':
                        $price_a = $product_a->get_price();
                        $price_b = $product_b->get_price();
                        return $order === 'DESC' ? $price_b - $price_a : $price_a - $price_b;
                    case 'date':
                        $date_a = strtotime($product_a->get_date_created()->date('Y-m-d H:i:s'));
                        $date_b = strtotime($product_b->get_date_created()->date('Y-m-d H:i:s'));
                        return $order === 'DESC' ? $date_b - $date_a : $date_a - $date_b;
                    case 'date-asc':
                        $date_a = strtotime($product_a->get_date_created()->date('Y-m-d H:i:s'));
                        $date_b = strtotime($product_b->get_date_created()->date('Y-m-d H:i:s'));
                        return $order === 'ASC' ? $date_a - $date_b : $date_b - $date_a;
                    default:
                        return strcasecmp($product_a->get_title(), $product_b->get_title());
                }
            });


            $total_relations = count($visible_relations);
            $posts_per_page = 24;
            $offset = ($paged - 1) * $posts_per_page;
            $visible_relations = array_slice($visible_relations, $offset, $posts_per_page);

            if (!empty($visible_relations)) {
                foreach ($visible_relations as $relation_id) {
                    get_template_part_var('cards/product-card', [
                        'product_id' => $relation_id,
                    ]);

                    $product_count++;
                }
            }
        } else {
            $args = [
                'post_type'      => 'product',
                'posts_per_page' => $posts_per_page,
                'paged'          => $paged,
                'post_status'    => 'publish',
                'post__not_in'   => exclude_product_ids(),
            ];

            if (!empty($_GET['categories'])) {
                $categories = explode(',', sanitize_text_field($_GET['categories']));

                $args['tax_query'] = [
                    'relation' => 'AND',
                ];


                foreach ($categories as $cat_slug) {
                    $args['tax_query'][] = [
                        'taxonomy'         => $category->taxonomy,
                        'field'            => 'slug',
                        'terms'            => $cat_slug,
                        'include_children' => true,
                    ];
                }
            } else {
                $args['tax_query'] = [
                    [
                        'taxonomy'         => $category->taxonomy,
                        'field'            => 'id',
                        'terms'            => [$category->term_id],
                        'include_children' => true,
                    ],
                ];
            }

            if ($price_from) {
                $price_from *= (1 / $currency_rate);
                $args['meta_query'][] = [
                    'key'     => '_price',
                    'value'   => sanitize_text_field($price_from),
                    'compare' => '>=',
                    'type'    => 'NUMERIC',
                ];
            }

            if ($price_to) {
                $price_to *= (1 / $currency_rate);
                $args['meta_query'][] = [
                    'key'     => '_price',
                    'value'   => sanitize_text_field($price_to),
                    'compare' => '<=',
                    'type'    => 'NUMERIC',
                ];
            }

            if (isset($_GET['orderby'])) {
                $orderby = sanitize_text_field($_GET['orderby']);
                $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'ASC';

                switch ($orderby) {
                    case 'total_sales':
                        $args['orderby'] = 'meta_value_num';
                        $args['meta_key'] = 'total_sales';
                        $order = 'DESC';
                        break;
                    case 'name':
                        $args['orderby'] = 'title';
                        break;
                    case 'name-desc':
                        $args['orderby'] = 'title';
                        $order = 'DESC';
                        break;
                    case 'price':
                        $args['meta_key'] = '_price';
                        $args['orderby'] = 'meta_value_num';
                        break;
                    case 'price-desc':
                        $args['meta_key'] = '_price';
                        $args['orderby'] = 'meta_value_num';
                        $order = 'DESC';
                        break;
                    case 'date':
                        $args['orderby'] = 'date';
                        $order = 'DESC';
                        break;
                    case 'date-asc':
                        $args['orderby'] = 'date';
                        $order = 'ASC';
                        break;
                    default:
                        $args['orderby'] = 'meta_value_num';
                        $args['meta_key'] = 'total_sales';
                        break;
                }

                $args['order'] = $order;
            } else {
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'total_sales';
            }

            global $posts_query;
            $posts_query = new WP_Query($args);
            if ($posts_query->have_posts()) :
                while ($posts_query->have_posts()) : $posts_query->the_post();
                    $product_id = get_the_ID();
                    get_template_part_var('cards/product-card', [
                        'product_id' => $product_id,
                    ]);
                endwhile;
            else :
                get_template_part_var('general/products-not-found');
            endif;

            wp_reset_postdata();

        }
        ?>
    </div>
</section>