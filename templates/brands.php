<?php
/** Template Name: Brands
 * The template for displaying Brands page
 */
get_header();

$page_title = get_the_title();

$brands_taxonomy = get_taxonomy('pwb-brand');

if (empty($brands_taxonomy)) {
    return;
}

$args = array(
    'taxonomy'   => 'pwb-brand',
    'hide_empty' => false,
);

$categories = get_categories($args);

$alphabetical_categories = array();

foreach ($categories as $category) {
    $first_letter = strtoupper(substr($category->name, 0, 1));

    if (!isset($alphabetical_categories[$first_letter])) {
        $alphabetical_categories[$first_letter] = array();
    }

    $alphabetical_categories[$first_letter][] = $category;
}

ksort($alphabetical_categories);
?>

<section class="brands">
    <div class="container">
        <div class="brands__row">
            <div class="brands__title-col">
                <h1><?php echo $page_title; ?></h1>
            </div>
            <div class="brands__search-col">
                <div class="brands__search_input">
                    <img class="search_icon" src="<?php echo get_template_directory_uri() . '/dest/img/Search.svg'; ?>"
                         alt="<?php _e('Search', DOMAIN); ?>"
                         title="<?php _e('Search', DOMAIN); ?>">
                    <input class="search_brand_input" placeholder="<?php _e('find the brand', DOMAIN); ?>">
                    <div class="search_clear">
                        <img src="<?php echo get_template_directory_uri() . '/dest/img/cross.svg'; ?>"
                             alt="<?php _e('Cross', DOMAIN); ?>"
                             title="<?php _e('Cross', DOMAIN); ?>">
                    </div>
                </div>
            </div>
            <div class="brands__filter-col">
                <?php alphabet('brands__alphabet','brands__letter'); ?>
            </div>
        </div>

        <div class="brands__result">
            <?php foreach ($alphabetical_categories as $letter => $category_list) : ?>
                <div class="brands__result-row">
                    <h3><?php echo $letter; ?></h3>

                    <ul>
                        <?php foreach ($category_list as $category) :
                            $category_url = get_term_link($category);
                            ?>

                            <li>
                                <a href="<?php echo $category_url ?>"><?php echo esc_html($category->name); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
get_footer();
?>
