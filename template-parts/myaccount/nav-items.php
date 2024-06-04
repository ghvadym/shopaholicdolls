<div class="nav__items">
    <div class="nav__item my_acc_change_pass">
        <img src="<?php echo get_local_img_url('myaccount/lock.svg'); ?>"
             alt="<?php _e('Change password', DOMAIN); ?>"
             title="<?php _e('Change password', DOMAIN); ?>">
        <div class="btn-plain">
            <?php _e('Change password', DOMAIN); ?>
        </div>
    </div>
    <div class="nav__item">
        <img src="<?php echo get_local_img_url('myaccount/logout.svg'); ?>"
             alt="<?php _e('Log out', DOMAIN); ?>"
             title="<?php _e('Log out', DOMAIN); ?>">
        <a href="<?php echo wp_logout_url(MY_ACC_LINK); ?>" class="btn-plain">
            <?php _e('Log out', DOMAIN); ?>
        </a>
    </div>
</div>