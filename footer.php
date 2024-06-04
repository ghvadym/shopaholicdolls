<?php
wp_footer();

$options = get_fields('options');

if (is_account_page()):
    get_template_part_var('myaccount/modal-change-password');
endif;

?>

</main>

<footer id="footer" class="footer">
    <?php get_template_part_var('home/form/form-newsletter-desktop', [
        'options' => $options,
    ]); ?>

    <?php get_template_part_var('home/form/form-newsletter-mobile', [
        'options' => $options,
    ]); ?>
    <div class="footer__top mobile_version">
        <div class="container">
            <div class="footer__menu">
                <?php socials(); ?>
            </div>
        </div>
    </div>
    <div class="footer__top desktop_version">
        <div class="container">
            <div class="footer__menu">
                <?php get_widgets([
                    'Footer nav 1',
                    'Footer nav 2',
                    'Footer nav 3',
                ]); ?>
                <div class="footer__column">
                    <h2 class="footer__title">
                        <?php _e('Contact', DOMAIN); ?>
                    </h2>
                    <?php if ($contact_info = get_field('contact_info_items', 'options')): ?>
                        <div class="footer__contact_info">
                            <?php foreach ($contact_info as $contact_item):
                                if (empty($contact_item['image']) || empty($contact_item['title']) || empty($contact_item['link'])):
                                    continue;
                                endif; ?>
                                <a href="<?php echo $contact_item['link']; ?>"
                                   class="contact_info__item">
                                    <img src="<?php echo $contact_item['image']; ?>"
                                         alt="<?php echo $contact_item['title']; ?>"
                                         title="<?php echo $contact_item['title']; ?>">
                                    <div class="contact_info__title" href="<?php echo $contact_item['link']; ?>">
                                        <?php echo $contact_item['title']; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php socials(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__bottom">
        <p>
            © <?php echo get_bloginfo('name') . ' ' . date('Y'); ?>
        </p>
    </div>
</footer>

</body>
</html>