<div class="header__main_mobile">
    <div class="container">
        <div class="header__main_mobile_row">
            <div class="header__account header__main_mobile_item">
                <a href="<?php echo MY_ACC_LINK; ?>">
                    <img class="account_icon"
                         src="<?php echo get_template_directory_uri() . '/dest/img/Account.svg'; ?>"
                         alt="<?php _e('My account', DOMAIN); ?>"
                         title="<?php _e('My account', DOMAIN); ?>">
                    <p>
                        <?php _e('Account', DOMAIN); ?>
                    </p>
                </a>
            </div>
            <?php if ($brands_link = get_field('menu_item_brands', 'options')): ?>
                <div class="header__brands header__main_mobile_item">
                    <a href="<?php echo $brands_link['url']; ?>">
                        <img class="brands_icon"
                             src="<?php echo get_template_directory_uri() . '/dest/img/AZ.svg'; ?>"
                             alt="<?php _e('Brands', DOMAIN); ?>"
                             title="<?php _e('Brands', DOMAIN); ?>">
                        <p>
                            <?php echo $brands_link['title']; ?>
                        </p>
                    </a>
                </div>
            <?php endif; ?>
            <?php if ($bestsellers_link = get_field('menu_item_bestsellers', 'options')): ?>
                <div class="header__bestsellers header__main_mobile_item">
                    <a href="<?php echo $bestsellers_link['url'] ?? ''; ?>">
                        <img class="bestsellers_icon"
                             src="<?php echo get_template_directory_uri() . '/dest/img/Star.svg'; ?>"
                             alt="<?php _e('Bestsellers', DOMAIN); ?>"
                             title="<?php _e('Bestsellers', DOMAIN); ?>">
                        <p>
                            <?php echo $bestsellers_link['title'] ?? ''; ?>
                        </p>
                    </a>
                </div>
            <?php endif; ?>
            <?php if (empty(get_field('hide_live_chat_trigger', 'options'))): ?>
                <div class="header__chat header__main_mobile_item live_chat_trigger">
                    <img class="chat_icon" src="<?php echo get_template_directory_uri() . '/dest/img/Chat.svg'; ?>"
                         alt="<?php _e('Live chat', DOMAIN); ?>"
                         title="<?php _e('Live chat', DOMAIN); ?>">

                    <p>
                        <?php _e('Chat', DOMAIN); ?>
                    </p>
                </div>
            <?php endif; ?>
            <div class="header__categories header__main_mobile_item">
                <img class="categories_icon"
                     src="<?php echo get_template_directory_uri() . '/dest/img/burger.svg'; ?>"
                     alt="<?php _e('Mobile menu', DOMAIN); ?>"
                     title="<?php _e('Mobile menu', DOMAIN); ?>">
                <p>
                    <?php _e('Categories', DOMAIN); ?>
                </p>
            </div>
        </div>
    </div>
</div>