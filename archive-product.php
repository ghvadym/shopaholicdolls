<?php
get_header();
$current_category = get_queried_object();
$category_count = $current_category->count;

get_template_part_var('archive/breadcrumb');

get_template_part_var('archive/head-section'); ?>
<?php if ($category_count > 0) : ?>
<section class="archive-content">
    <div class="container archive-wrapper">
        <?php
            get_template_part_var('archive/archive-products');
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
