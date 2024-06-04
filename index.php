<?php

get_header();
?>

<?php if (have_posts()): ?>

    <section class="section simple-page">
        <div class="container">
            <div class="row">
                <?php while (have_posts()): the_post(); ?>
                    <div class="col-12">
                        <?php the_content(); ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

<?php
wp_reset_postdata();
endif;

get_footer();