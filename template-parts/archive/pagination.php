<?php
// if ($total_pages > 1) {
$big = 999999999;
?>
<section class="pagination">
    <div class="container">
        <?php
        $current_page = max(1, get_query_var('paged'));

        if (is_page_template('templates/template-archive.php')) {
            $paged = isset($_GET['product-page']) ? max(1, intval($_GET['product-page'])) : 1;
            $product_count = 0;

            $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'menu_order title';

            wp_reset_query();
            $cats = get_field('cats');
            $brands = get_field('brands');

            $visible_relations = [];

            if (!empty($cats)) {
                foreach ($cats as $cat) {
                    $posts_query = new WP_Query([
                        'post_type'      => 'product',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'post__not_in'   => exclude_product_ids(),
                        'tax_query'      => [
                            [
                                'taxonomy' => 'product_cat',
                                'field'    => 'term_id',
                                'terms'    => $cat->term_id,
                            ],
                        ],
                    ]);

                    if ($posts_query->have_posts()) {
                        while ($posts_query->have_posts()) {
                            $posts_query->the_post();
                            $relation_id = get_the_ID();
                            $visible_relations[] = $relation_id;
                        }
                    }
                    wp_reset_postdata();
                }
            }

            if (!empty($brands)) {
                foreach ($brands as $brand) {
                    $posts_query = new WP_Query([
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
                    ]);

                    if ($posts_query->have_posts()) {
                        while ($posts_query->have_posts()) {
                            $posts_query->the_post();
                            $relation_id = get_the_ID();
                            $visible_relations[] = $relation_id;
                        }
                    }
                    wp_reset_postdata();
                }
            }
            if (!empty($visible_relations) && count($visible_relations) > 24) {
                $total_relations = count($visible_relations);
                $posts_per_page = 24;
                $offset = ($paged - 1) * $posts_per_page;
                $visible_relations = array_slice($visible_relations, $offset, $posts_per_page);
                $mid_size = ($paged < 3) ? 2 : (($paged >= 3 && $paged <= $totals - 3) ? 0 : (($paged <= $totals - 2) ? 1 : 2));
                if (!empty($visible_relations)) {
                    $totals = ceil($total_relations / $posts_per_page);
                    $paginate_links = paginate_links([
                        'base'      => add_query_arg('product-page', '%#%'),
                        'format'    => '',
                        'current'   => $paged,
                        'total'     => $totals,
                        'prev_text' => '<span class="page-link arrow-prev"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15.8102 5.21172C16.1642 4.92828 16.1646 4.38995 15.8111 4.10596C15.5522 3.89796 15.1835 3.89766 14.9243 4.10522L6.97507 10.4694C6.47504 10.8697 6.47504 11.6303 6.97507 12.0306L14.9265 18.3965C15.1846 18.6032 15.5515 18.6033 15.8098 18.3969C16.1637 18.1141 16.164 17.5761 15.8105 17.2928L8.26953 11.25L15.8102 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg></span>',
                        'next_text' => '<span class="page-link arrow-next"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M8.18978 5.21172C7.83582 4.92828 7.8354 4.38995 8.18892 4.10596C8.44784 3.89796 8.81647 3.89766 9.07573 4.10522L17.0249 10.4694C17.525 10.8697 17.525 11.6303 17.0249 12.0306L9.07354 18.3965C8.81541 18.6032 8.44853 18.6033 8.19022 18.3969C7.83632 18.1141 7.83597 17.5761 8.18949 17.2928L15.7305 11.25L8.18978 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg></span>',
                        'type'      => 'array',
                        'mid_size'  => $mid_size,
                    ]);

                    ?>
                    <ul class="pagination__wrapper">
                        <?php
                        if ($paged == 1) {
                            ?>
                            <li class="pagination__item">
                                <span class="page-link arrow-prev disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15.8102 5.21172C16.1642 4.92828 16.1646 4.38995 15.8111 4.10596C15.5522 3.89796 15.1835 3.89766 14.9243 4.10522L6.97507 10.4694C6.47504 10.8697 6.47504 11.6303 6.97507 12.0306L14.9265 18.3965C15.1846 18.6032 15.5515 18.6033 15.8098 18.3969C16.1637 18.1141 16.164 17.5761 15.8105 17.2928L8.26953 11.25L15.8102 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg>
                                </span>
                            </li>
                            <?php
                        }
                        foreach ($paginate_links as $link) {
                            if (strpos($link, 'dots') !== false) {
                                echo '<li class="pagination__item dots">....</li>';
                            } else {
                                echo '<li class="pagination__item">' . $link . '</li>';
                            }
                        }
                        if ($paged == $totals) {
                            ?>
                            <li class="pagination__item">
                                <span class="page-link arrow-next disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M8.18978 5.21172C7.83582 4.92828 7.8354 4.38995 8.18892 4.10596C8.44784 3.89796 8.81647 3.89766 9.07573 4.10522L17.0249 10.4694C17.525 10.8697 17.525 11.6303 17.0249 12.0306L9.07354 18.3965C8.81541 18.6032 8.44853 18.6033 8.19022 18.3969C7.83632 18.1141 7.83597 17.5761 8.18949 17.2928L15.7305 11.25L8.18978 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg>
                                </span>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                }
            }
        } else if (is_page_template('templates/whishlist.php')) {
            $user_favorites = get_user_meta(USER_ID, 'favorites_posts', true);
            $posts_per_page = 12;
            if (!empty($user_favorites)) {
                $total_favorites = count($user_favorites);
                $total_pages = ceil($total_favorites / $posts_per_page);

                $current_page = get_query_var('paged') ? get_query_var('paged') : 1;
                $mid_size = ($current_page < 3) ? 2 : (($current_page >= 3 && $current_page <= $total_pages - 3) ? 0 : (($current_page <= $total_pages - 2) ? 1 : 2));

                $args = [
                    'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format'    => '?paged=%#%',
                    'current'   => $current_page,
                    'total'     => $total_pages,
                    'prev_text' => '<span class="page-link arrow-prev"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15.8102 5.21172C16.1642 4.92828 16.1646 4.38995 15.8111 4.10596C15.5522 3.89796 15.1835 3.89766 14.9243 4.10522L6.97507 10.4694C6.47504 10.8697 6.47504 11.6303 6.97507 12.0306L14.9265 18.3965C15.1846 18.6032 15.5515 18.6033 15.8098 18.3969C16.1637 18.1141 16.164 17.5761 15.8105 17.2928L8.26953 11.25L15.8102 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg></span>',
                    'next_text' => '<span class="page-link arrow-next"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M8.18978 5.21172C7.83582 4.92828 7.8354 4.38995 8.18892 4.10596C8.44784 3.89796 8.81647 3.89766 9.07573 4.10522L17.0249 10.4694C17.525 10.8697 17.525 11.6303 17.0249 12.0306L9.07354 18.3965C8.81541 18.6032 8.44853 18.6033 8.19022 18.3969C7.83632 18.1141 7.83597 17.5761 8.18949 17.2928L15.7305 11.25L8.18978 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg></span>',
                    'type'      => 'array',
                    'mid_size'  => $mid_size,
                ];
                $paginate_links = paginate_links($args);

                if ($paginate_links) {
                    ?>
                    <ul class="pagination__wrapper">
                        <?php
                        if ($current_page == 1) {
                            ?>
                            <li class="pagination__item">
                                <span class="page-link arrow-prev disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15.8102 5.21172C16.1642 4.92828 16.1646 4.38995 15.8111 4.10596C15.5522 3.89796 15.1835 3.89766 14.9243 4.10522L6.97507 10.4694C6.47504 10.8697 6.47504 11.6303 6.97507 12.0306L14.9265 18.3965C15.1846 18.6032 15.5515 18.6033 15.8098 18.3969C16.1637 18.1141 16.164 17.5761 15.8105 17.2928L8.26953 11.25L15.8102 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg>
                                </span>
                            </li>
                            <?php
                        }

                        foreach ($paginate_links as $link) {
                            if (strpos($link, 'dots') !== false) {
                                echo '<li class="pagination__item dots">....</li>';
                            } else {
                                echo '<li class="pagination__item">' . $link . '</li>';
                            }
                        }
                        if ($current_page == $total_pages) {
                            ?>
                            <li class="pagination__item">
                                <span class="page-link arrow-next disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M8.18978 5.21172C7.83582 4.92828 7.8354 4.38995 8.18892 4.10596C8.44784 3.89796 8.81647 3.89766 9.07573 4.10522L17.0249 10.4694C17.525 10.8697 17.525 11.6303 17.0249 12.0306L9.07354 18.3965C8.81541 18.6032 8.44853 18.6033 8.19022 18.3969C7.83632 18.1141 7.83597 17.5761 8.18949 17.2928L15.7305 11.25L8.18978 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg>
                                </span>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                }
            }
        } else if (is_search()) {
            global $wp_query;
            $total_pages = $wp_query->max_num_pages;
            $current_page = max(1, get_query_var('paged'));
            $mid_size = ($current_page < 3) ? 2 : (($current_page >= 3 && $current_page <= $total_pages - 3) ? 0 : (($current_page <= $total_pages - 2) ? 1 : 2));

            $paginate_links = paginate_links([
                'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format'    => '?paged=%#%',
                'current'   => $current_page,
                'total'     => $total_pages,
                'prev_text' => '<span class="page-link arrow-prev"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15.8102 5.21172C16.1642 4.92828 16.1646 4.38995 15.8111 4.10596C15.5522 3.89796 15.1835 3.89766 14.9243 4.10522L6.97507 10.4694C6.47504 10.8697 6.47504 11.6303 6.97507 12.0306L14.9265 18.3965C15.1846 18.6032 15.5515 18.6033 15.8098 18.3969C16.1637 18.1141 16.164 17.5761 15.8105 17.2928L8.26953 11.25L15.8102 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg></span>',
                'next_text' => '<span class="page-link arrow-next"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M8.18978 5.21172C7.83582 4.92828 7.8354 4.38995 8.18892 4.10596C8.44784 3.89796 8.81647 3.89766 9.07573 4.10522L17.0249 10.4694C17.525 10.8697 17.525 11.6303 17.0249 12.0306L9.07354 18.3965C8.81541 18.6032 8.44853 18.6033 8.19022 18.3969C7.83632 18.1141 7.83597 17.5761 8.18949 17.2928L15.7305 11.25L8.18978 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg></span>',
                'type'      => 'array',
                'mid_size'  => $mid_size,
            ]);

            if ($paginate_links) { ?>
                <ul class="pagination__wrapper">
                    <?php
                    if ($current_page == 1) {
                        ?>
                        <li class="pagination__item">
                            <span class="page-link arrow-prev disabled">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15.8102 5.21172C16.1642 4.92828 16.1646 4.38995 15.8111 4.10596C15.5522 3.89796 15.1835 3.89766 14.9243 4.10522L6.97507 10.4694C6.47504 10.8697 6.47504 11.6303 6.97507 12.0306L14.9265 18.3965C15.1846 18.6032 15.5515 18.6033 15.8098 18.3969C16.1637 18.1141 16.164 17.5761 15.8105 17.2928L8.26953 11.25L15.8102 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg>
                            </span>
                        </li>
                        <?php
                    }

                    foreach ($paginate_links as $link) {
                        if (strpos($link, 'dots') !== false) {
                            echo '<li class="pagination__item dots">....</li>';
                        } else {
                            echo '<li class="pagination__item">' . $link . '</li>';
                        }
                    }

                    if ($current_page == $total_pages) { ?>
                        <li class="pagination__item">
                            <span class="page-link arrow-next disabled">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M8.18978 5.21172C7.83582 4.92828 7.8354 4.38995 8.18892 4.10596C8.44784 3.89796 8.81647 3.89766 9.07573 4.10522L17.0249 10.4694C17.525 10.8697 17.525 11.6303 17.0249 12.0306L9.07354 18.3965C8.81541 18.6032 8.44853 18.6033 8.19022 18.3969C7.83632 18.1141 7.83597 17.5761 8.18949 17.2928L15.7305 11.25L8.18978 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg>
                            </span>
                        </li>
                        <?php
                    } ?>
                </ul>
                <?php
            }
        } else {
            global $posts_query;
            if ($posts_query && $posts_query->have_posts()) {
                $total_pages = $posts_query->max_num_pages;
                $mid_size = ($current_page < 3) ? 2 : (($current_page >= 3 && $current_page <= $total_pages - 3) ? 0 : (($current_page <= $total_pages - 2) ? 1 : 2));
                $paginate_links = paginate_links([
                    'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format'    => '?paged=%#%',
                    'current'   => $current_page,
                    'total'     => $total_pages,
                    'prev_text' => '<span class="page-link arrow-prev"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15.8102 5.21172C16.1642 4.92828 16.1646 4.38995 15.8111 4.10596C15.5522 3.89796 15.1835 3.89766 14.9243 4.10522L6.97507 10.4694C6.47504 10.8697 6.47504 11.6303 6.97507 12.0306L14.9265 18.3965C15.1846 18.6032 15.5515 18.6033 15.8098 18.3969C16.1637 18.1141 16.164 17.5761 15.8105 17.2928L8.26953 11.25L15.8102 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg></span>',
                    'next_text' => '<span class="page-link arrow-next"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M8.18978 5.21172C7.83582 4.92828 7.8354 4.38995 8.18892 4.10596C8.44784 3.89796 8.81647 3.89766 9.07573 4.10522L17.0249 10.4694C17.525 10.8697 17.525 11.6303 17.0249 12.0306L9.07354 18.3965C8.81541 18.6032 8.44853 18.6033 8.19022 18.3969C7.83632 18.1141 7.83597 17.5761 8.18949 17.2928L15.7305 11.25L8.18978 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg></span>',
                    'type'      => 'array',
                    'mid_size'  => $mid_size,
                ]);

                if ($paginate_links) {
                    ?>
                    <ul class="pagination__wrapper">
                        <?php
                        if ($current_page == 1) {
                            ?>
                            <li class="pagination__item">
                                <span class="page-link arrow-prev disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15.8102 5.21172C16.1642 4.92828 16.1646 4.38995 15.8111 4.10596C15.5522 3.89796 15.1835 3.89766 14.9243 4.10522L6.97507 10.4694C6.47504 10.8697 6.47504 11.6303 6.97507 12.0306L14.9265 18.3965C15.1846 18.6032 15.5515 18.6033 15.8098 18.3969C16.1637 18.1141 16.164 17.5761 15.8105 17.2928L8.26953 11.25L15.8102 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg>
                                </span>
                            </li>
                            <?php
                        }

                        foreach ($paginate_links as $link) {
                            if (strpos($link, 'dots') !== false) {
                                echo '<li class="pagination__item dots">....</li>';
                            } else {
                                echo '<li class="pagination__item">' . $link . '</li>';
                            }
                        }


                        if ($current_page == $total_pages) {
                            ?>
                            <li class="pagination__item"><span class="page-link arrow-next disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M8.18978 5.21172C7.83582 4.92828 7.8354 4.38995 8.18892 4.10596C8.44784 3.89796 8.81647 3.89766 9.07573 4.10522L17.0249 10.4694C17.525 10.8697 17.525 11.6303 17.0249 12.0306L9.07354 18.3965C8.81541 18.6032 8.44853 18.6033 8.19022 18.3969C7.83632 18.1141 7.83597 17.5761 8.18949 17.2928L15.7305 11.25L8.18978 5.21172Z" fill="#1B1B1B" stroke="#1B1B1B" stroke-width="0.5" stroke-linejoin="round"></path></svg>
                                </span>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                }
            }
        }
        ?>
    </div>
</section>
<?php
// }
?>
