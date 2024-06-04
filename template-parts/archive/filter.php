<?php
$is_categories_selected = isset($_GET['categories']);
$is_price_selected = isset($_GET['from']) || isset($_GET['to']);
?>
<section class="filter">
    <div class="filter-wrapper">
        <div class="title">
            <?php _e('ADVANCED FILTER', DOMAIN); ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M20 17L4 17" stroke="#1B1B1B" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M15 12L4 12" stroke="#1B1B1B" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M9 7L4 7" stroke="#1B1B1B" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </div>

        <div class="range-title"><?php _e('Price range', DOMAIN); ?></div>
        <div class="range-inputs">
            <p class="form-row">
                <label for="from"><?php _e('From', DOMAIN); ?><span class="required">*</span></label>
                <input type="text" class="input-text" name="from" id="from" value="<?php echo !empty($_GET['from']) ? $_GET['from'] : ''; ?>">
            </p>
            <p class="form-row">
                <label for="to"><?php _e('To', DOMAIN); ?><span class="required">*</span></label>
                <input type="text" class="input-text" name="to" id="to" value="<?php echo !empty($_GET['to']) ? $_GET['to'] : ''; ?>">
            </p>
        </div>
        <div class="divider"></div>
        <div class="filter-categories">
            <?php
            $current_category = get_queried_object();
            $current_category_id = $current_category->term_id;
            $current_category_slug = $current_category->slug;

            function get_top_level_category($category)
            {
                if ($category->parent != 0) {
                    $parent_category = get_term($category->parent, $category->taxonomy);
                    return get_top_level_category($parent_category);
                } else {
                    return $category;
                }
            }

            $top_level_category = get_top_level_category($current_category);
            $top_level_category_id = $top_level_category->term_id;

            $categories = get_terms($current_category->taxonomy, ['parent' => $top_level_category_id, 'hide_empty' => false]);

            function get_ancestor_categories($category)
            {
                $ancestors = get_ancestors($category->term_id, $category->taxonomy, 'taxonomy');
                return array_merge([$category->term_id], $ancestors);
            }

            $ancestor_categories = get_ancestor_categories($current_category);

            function is_category_checked($category, $ancestor_categories)
            {
                return in_array($category->term_id, $ancestor_categories);
            }

            $selected_categories = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];

            function is_category_selected($category, $selected_categories)
            {
                return !empty($selected_categories) && in_array($category->slug, $selected_categories);
            }

            function render_category($category, $ancestor_categories, $current_category_id, $current_category_slug, $selected_categories, $depth = 1)
            {
                $parent_id = sanitize_title($category->slug);
                $parent_name = sanitize_title($category->name);
                global $wp;
                $current_url = esc_url(home_url(add_query_arg([], $wp->request)));
                $category_link = get_term_link($category);
                $is_current_category = ($current_url === $category_link || is_category_checked($category, $ancestor_categories) || is_category_selected($category,
                        $selected_categories));
                $from_price = isset($_GET['from']) ? intval($_GET['from']) : 0;
                $to_price = isset($_GET['to']) ? intval($_GET['to']) : PHP_INT_MAX;

                $meta_query = [
                    'relation' => 'AND',
                    [
                        'key'     => '_price',
                        'value'   => [$from_price, $to_price],
                        'type'    => 'numeric',
                        'compare' => 'BETWEEN',
                    ],
                ];
                $product_count_query = new WP_Query([
                    'post_type'      => 'product',
                    'post_status'    => 'publish',
                    'tax_query'      => [
                        [
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $category->term_id,
                        ],
                    ],
                    'meta_query'     => $meta_query,
                    'posts_per_page' => -1,
                ]);
                $product_count = $product_count_query->found_posts;
                $parent_class = ($product_count == 0 || $category->count == 0) ? 'disabled ' : '';
                ?>
                <div class="checkbox_item <?php echo esc_attr($parent_class . ($is_current_category ? 'active' : '')); ?>">
                    <div class="checkbox">
                        <input id="<?php echo esc_attr($parent_id); ?>" class="input-checkbox" type="checkbox" name="<?php echo esc_attr($parent_name); ?>"
                               value="1" <?php echo esc_attr($parent_class); ?> <?php echo $is_current_category ? 'checked' : ''; ?> />
                        <label for="<?php echo esc_attr($parent_id); ?>">
                            <?php echo esc_html($category->name); ?> <span class="count">(<?php echo esc_html($product_count); ?>)</span>
                        </label>
                    </div>
                    <?php
                    if ($is_current_category) {
                        $subcategories = get_terms($category->taxonomy, ['parent' => $category->term_id, 'hide_empty' => false]);
                        if (!empty($subcategories)) {
                            ?>
                            <div class="subcategories">
                                <?php foreach ($subcategories as $subcategory) { ?>
                                    <?php render_category($subcategory, $ancestor_categories, $current_category_id, $current_category_slug, $selected_categories, $depth + 1); ?>
                                <?php } ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <?php
            }

            foreach ($categories as $category) {
                render_category($category, $ancestor_categories, $current_category_id, $current_category_slug, $selected_categories);
            }
            ?>
        </div>
        <div class="range-buttons filter-buttons desktop_version">
            <button class="btn-transparent apply_filters"><?php _e('show', DOMAIN); ?></button>
            <a href="<?php echo wp_get_current_url(); ?>" class="btn-plain clear_filters"><?php _e('clear all', DOMAIN); ?></a>
        </div>
        <?php if ($is_categories_selected || $is_price_selected) : ?>
            <div class="range-buttons filter-buttons mobile_version">
                <a href="<?php echo wp_get_current_url(); ?>" class="btn-transparent clear_filters"><?php _e('clear all', DOMAIN); ?></a>
                <button href="#" class="button apply_filters"><?php _e('show', DOMAIN); ?></button>
            </div>
        <?php elseif (!$is_categories_selected || !$is_price_selected) : ?>
            <div class="range-buttons filter-buttons mobile_version">
                <button class="btn-transparent close_filters"><?php _e('close', DOMAIN); ?></button>
                <a href="<?php echo wp_get_current_url(); ?>" class="btn-transparent clear_filters"><?php _e('clear all', DOMAIN); ?></a>
                <button href="#" class="button apply_filters"><?php _e('show', DOMAIN); ?></button>
            </div>
        <?php endif; ?>
    </div>
</section>
<div class="mob-filter__bg"></div>
<div class="mob-filter__close">
    <img src="<?php echo get_template_directory_uri() . '/dest/img/cross.svg'; ?>"
         alt="<?php _e('Filter icon close', DOMAIN); ?>"
         title="<?php _e('Filter icon close', DOMAIN); ?>">
</div>

