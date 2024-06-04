<section class="breadcrumbs">
    <div class="container breadcrumbs__wrapper">
        <?php
        $output = '<a href="' . home_url('/') . '">' . __('Home', DOMAIN) . '</a>';
        $delimiter = ' / ';

        // Separator
        $output .= '<span class="breadcrumbs-separator">' . esc_html($delimiter) . '</span>';

        // Single post
        if (is_single()) {
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->cat_name) . '</a>';
                $output .= '<span class="breadcrumbs-separator">' . esc_html($delimiter) . '</span>';
            }
            $output .= '<span class="breadcrumbs-current">' . esc_html(get_the_title($post->ID)) . '</span>';
        }

        // Category archive
        elseif (is_category()) {
            $category = get_queried_object();
            if ($category->parent != 0) {
                $output .= get_category_parents($category->term_id, true, '<span class="breadcrumbs-separator">' . esc_html($delimiter) . '</span>');
            }
            if (get_query_var('paged')) {
                $output .= '<span class="breadcrumbs-separator">' . esc_html($delimiter) . '</span>';
                $output .= sprintf(__('Page %s', DOMAIN), get_query_var('paged'));
            } else {
                $output .= '<span class="breadcrumbs-current">' . esc_html(single_cat_title('', false)) . '</span>';
            }
        }

        // Taxonomy archive
        elseif (is_tax()) {
            $term = get_queried_object();
            $term_link = get_term_link($term);
            if (is_wp_error($term_link)) {
                $term_link = '';
            }
        
            if ($term->taxonomy === 'pwb-brand') {
                $brands_archive_link = home_url('/brands');
                if (!is_wp_error($brands_archive_link)) {
                    $output .= '<a href="' . esc_url($brands_archive_link) . '">' . esc_html__('Brands', DOMAIN) . '</a>';
                    $output .= '<span class="breadcrumbs-separator">' . esc_html($delimiter) . '</span>';
                }
            }

        
            if (get_query_var('paged')) {
                $output .= '<a href="' . esc_url($term_link) . '">' . esc_html(single_term_title('', false)) . '</a>';
                $output .= '<span class="breadcrumbs-separator">' . esc_html($delimiter) . '</span>';
                $output .= sprintf(__('Page %s', DOMAIN), get_query_var('paged'));
            } else {
                $output .= '<span class="breadcrumbs-current">' . esc_html(single_term_title('', false)) . '</span>';
            }
        }
        
        

        // Page
        elseif (is_page()) {
            $post = get_queried_object();
            if ($post->post_parent) {
                $parents = get_post_ancestors($post->ID);
                $parents = array_reverse($parents);
                foreach ($parents as $parent) {
                    $output .= '<a href="' . esc_url(get_permalink($parent)) . '">' . esc_html(get_the_title($parent)) . '</a>';
                    $output .= '<span class="breadcrumbs-separator">' . esc_html($delimiter) . '</span>';
                }
            }
            $output .= '<span class="breadcrumbs-current">' . esc_html(get_the_title($post->ID)) . '</span>';
        }

        // Other cases (custom post type archive, search results, 404 page)
        else {
            if (get_query_var('paged')) {
                $output .= '<span class="breadcrumbs-separator">' . esc_html($delimiter) . '</span>';
                $output .= sprintf(__('Page %s', DOMAIN), get_query_var('paged'));
            } else {
                $output .= '<span class="breadcrumbs-current">' . esc_html(get_the_title($post->ID)) . '</span>';
            }
        }

        echo $output;
        ?>
    </div>
</section>
