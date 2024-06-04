<?php
$selected_categories = isset($_GET['categories']) ? explode(',', $_GET['categories']) : array();
$is_bestsellers = is_tax('product_cat', 'bestsellers');
if (count($selected_categories) > 1) {
    $last_selected_category = end($selected_categories);
    $last_category = get_term_by('slug', $last_selected_category, 'product_cat');
}

get_header();
$current_category = get_queried_object();
$category_count = $current_category->count;
$parent_category_id = $current_category->parent;
$has_child_categories = false;
if ($parent_category_id === 0) {
    $child_categories = get_terms($current_category->taxonomy, array('parent' => $current_category->term_id, 'hide_empty' => false));
    $has_child_categories = !empty($child_categories);
} elseif($parent_category_id) {
    $child_categories = get_terms($current_category->taxonomy, array('parent' => $current_category->term_id, 'hide_empty' => false));
    $has_child_categories = !empty($child_categories);
}
$is_child_category = $parent_category_id !== 0;

//get_template_part_var('archive/breadcrumb');

get_template_part_var('archive/head-section', [
    'is_child_category'    => $is_child_category,
    'has_child_categories' => $has_child_categories,
    'parent_category_id'   => $parent_category_id
]);
?>
<?php if ($category_count > 0) : ?>
<section class="archive-content">
    <div class="container archive-wrapper">
        <?php
        $is_filter = false;
        if ($is_child_category || ($parent_category_id === 0 && $has_child_categories)) {
            get_template_part_var('archive/filter');
            $is_filter = true;
        }
        get_template_part_var('archive/archive-products', [
            'is_filter' => $is_filter
        ]);
        ?>
    </div>
</section>
<?php else :?>
    <?php get_template_part_var('general/products-not-found'); ?>
<?php endif;?>

<?php 
get_template_part_var('archive/pagination');
get_footer();
?>