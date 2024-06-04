<?php
/*
* Template name: Success Reset Password
*/
get_header();
?>
<section class="lost-confirmed lost-confirmed_success">
    <div class="container lost-confirmed__wrapper">
    <?php $last_icon = get_field('last_icon', 'options'); 
        if(!empty($last_icon)) :?>
            <div class="icon">
                <img src="<?php echo esc_url($last_icon['url']); ?>"
                     alt="<?php echo esc_attr($last_icon['alt']); ?>"
                     title="<?php echo esc_attr($last_icon['alt']); ?>">
            </div>
        <?php endif; ?>
        <div class="content">
            <h4><?php echo get_field('last_title', 'options') ?: __('SUCCESS!', DOMAIN); ?></h4>
            <p><?php echo get_field('last_text', 'options') ?: __('Password changed.', DOMAIN); ?></p>
        </div>
    </div>
</section>
<section class="login-link">
    <div class="container">
        <?php $last_button_text = get_field('last_button_text', 'options') ?: __('Log In', DOMAIN); ?>

        <a class="button" href="<?php echo esc_url(MY_ACC_LINK); ?>">
            <?php echo esc_html($last_button_text); ?>
        </a>
    </div>
</section>

<?php
get_footer();
?>
