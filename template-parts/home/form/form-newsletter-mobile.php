<?php
if (empty($options)) {
    return;
}
?>

<div class="section form_newsletter newsletter_mobile mobile_version footer_mobile">
    <?php get_template_part_var('home/form/form-newsletter-line', [
        'options' => $options,
    ]); ?>
    <div class="container">
        <div class="newsletter__content">
            <?php if (!empty($options['form_newsletter_title'])): ?>
                <div class="newsletter__title">
                    <?php echo $options['form_newsletter_title']; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($options['form_newsletter_text'])): ?>
                <div class="newsletter__text">
                    <?php echo $options['form_newsletter_text']; ?>
                </div>
            <?php endif; ?>
            <div class="newsletter__body">
                <form class="newsletter__form">
                    <div class="newsletter__form_body">
                        <div class="form-row">
                            <input class="newsletter__input" name="user-email" placeholder="<?php _e('Enter your email'); ?>" required>
                            <span class="form-row-small error"></span>
                        </div>
                        <button class="newsletter__btn">
                            <img src="<?php echo get_template_directory_uri() . '/dest/img/check.svg' ?>"
                                 alt="<?php _e('Subscribe to newsletter button', DOMAIN); ?>"
                                 title="<?php _e('Subscribe to newsletter button', DOMAIN); ?>">
                        </button>
                    </div>
                    <div class="form_success_message"></div>
                </form>
            </div>
        </div>
    </div>
</div>