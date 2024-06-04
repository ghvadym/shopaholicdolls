<div class="container header__top_desktop">
    <div class="header__top">
        <div class="header__search">
            <div class="header__search_icon">
                <img class="search_icon" src="<?php echo get_template_directory_uri() . '/dest/img/Search.svg'; ?>"
                     alt="<?php _e('Search', DOMAIN); ?>"
                     title="<?php _e('Search', DOMAIN); ?>">
            </div>
        </div>
        <div class="header__search_wrapper">
            <div class="header__search_input">
                <img class="search_icon" src="<?php echo get_template_directory_uri() . '/dest/img/Search.svg'; ?>"
                     alt="<?php _e('Search', DOMAIN); ?>"
                     title="<?php _e('Search', DOMAIN); ?>">
                <input class="search_input" placeholder="<?php _e('search', DOMAIN); ?>">
                <div class="search_submit">
                    <img src="<?php echo get_template_directory_uri() . '/dest/img/arrow-right.svg'; ?>"
                         alt="<?php _e('Arrow', DOMAIN); ?>"
                         title="<?php _e('Arrow', DOMAIN); ?>">
                </div>
                <div class="search_clear">
                    <img src="<?php echo get_template_directory_uri() . '/dest/img/cross.svg'; ?>"
                         alt="<?php _e('Cross', DOMAIN); ?>"
                         title="<?php _e('Cross', DOMAIN); ?>">
                </div>
            </div>
            <div class="header__search_results d-none"></div>
        </div>
        <?php if (function_exists('the_custom_logo') && has_custom_logo()): ?>
            <div class="header__logo logo">
                <?php the_custom_logo(); ?>
            </div>
        <?php endif; ?>
        <div class="header__top_menu">
            <?php get_template_part_var('mega-menu/currency-switcher'); ?>
            <?php get_template_part_var('mega-menu/language-switcher'); ?>
            <?php if (empty(get_field('hide_live_chat_trigger', 'options'))): ?>
                <div class="header__menu_icon live_chat_trigger">
                    <img src="<?php echo get_template_directory_uri() . '/dest/img/Chat.svg'; ?>"
                         alt="<?php _e('Live chat', DOMAIN); ?>"
                         title="<?php _e('Live chat', DOMAIN); ?>">
                </div>
            <?php endif; ?>
            <?php if($wishlist_link = get_field('whishlist_link', 'options')) : ?>
                <div class="header__menu_icon">
                    <?php if (USER_ID): ?>
                        <a href="<?php echo esc_url($wishlist_link); ?>">
                            <img src="<?php echo get_template_directory_uri() . '/dest/img/Heart.svg'; ?>"
                                 alt="<?php _e('Wishlist', DOMAIN); ?>"
                                 title="<?php _e('Wishlist', DOMAIN); ?>">
                        </a>
                    <?php else: ?>
                        <a href="<?php echo MY_ACC_LINK; ?>" class="remember_place" data-url="<?php echo esc_url($wishlist_link); ?>">
                            <img src="<?php echo get_template_directory_uri() . '/dest/img/Heart.svg'; ?>"
                                 alt="<?php _e('Wishlist', DOMAIN); ?>"
                                 title="<?php _e('Wishlist', DOMAIN); ?>">
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="header__menu_icon">
                <a href="<?php echo MY_ACC_LINK; ?>">
                    <img src="<?php echo get_template_directory_uri() . '/dest/img/Account.svg'; ?>"
                         alt="<?php _e('My account', DOMAIN); ?>"
                         title="<?php _e('My account', DOMAIN); ?>">
                </a>
            </div>
            <div class="header_cart">
                <?php header_cart(); ?>
            </div>
        </div>
    </div>
    <div class="header__main header__main_desktop">
        <?php wp_nav_menu([
            'theme_location' => 'main_header'
        ]); ?>
    </div>
</div>