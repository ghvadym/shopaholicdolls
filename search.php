<?php
get_header();
?>

<section class="search_results">
    <div class="container">
        <div class="archive-head">
            <div class="container archive-head__wrapper">
                <div class="archive-head__top">
                    <div class="archive-head__title">
                        <small>
                            <?php _e('Search Results for', DOMAIN); ?>:
                        </small>
                        <?php echo htmlspecialchars($_GET['s']); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if (have_posts()): ?>
            <div class="archive-products">
                <div class="wrapper large-col">
                    <?php while (have_posts()): the_post();
                        get_template_part_var('cards/product-card', [
                            'product_id' => get_the_ID(),
                        ]);
                    endwhile; ?>
                </div>
            </div>
        <?php else:
            get_template_part_var('general/products-not-found');
        endif; ?>
    </div>
</section>

<?php
get_template_part_var('archive/pagination');

get_footer();
?>
