<div class="container header__top_mobile">
    <div class="header__top">
        <div class="header__col">
            <?php get_template_part_var('mega-menu/language-switcher'); ?>
            <div class="header__burger">
                <div class="header__burger_icon header__burger_open">
                    <img src="<?php echo get_template_directory_uri() . '/dest/img/burger.svg'; ?>"
                         alt="<?php _e('Burger menu', DOMAIN); ?>"
                         title="<?php _e('Burger menu', DOMAIN); ?>">
                </div>
                <div class="header__burger_icon header__burger_close">
                    <img src="<?php echo get_template_directory_uri() . '/dest/img/cross.svg'; ?>"
                         alt="<?php _e('Burger menu close', DOMAIN); ?>"
                         title="<?php _e('Burger menu close', DOMAIN); ?>">
                </div>
            </div>
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
        </div>
        <?php if (function_exists('the_custom_logo') && has_custom_logo()): ?>
            <div class="header__logo logo">
                <?php the_custom_logo(); ?>
            </div>
        <?php endif; ?>
        <div class="header__col">
            <?php if (empty(get_field('hide_live_chat_trigger', 'options'))): ?>
                <div class="header__menu_icon live_chat_trigger">
                    <img src="<?php echo get_template_directory_uri() . '/dest/img/Chat.svg'; ?>"
                         alt="<?php _e('Live chat', DOMAIN); ?>"
                         title="<?php _e('Live chat', DOMAIN); ?>">
                </div>
            <?php endif; ?>
            <div class="header_cart">
                <?php header_cart(); ?>
            </div>
        </div>
    </div>
</div>
<div class="header_modal">
    <div class="header_modal__top">
        <?php
        $my_account_title = __('Account', DOMAIN);
        if (USER_ID): ?>
            <a class="header_modal__top_title" href="<?php echo MY_ACC_LINK; ?>">
                <img src="<?php echo get_template_directory_uri() . '/dest/img/Account.svg'; ?>"
                     alt="<?php _e('My account', DOMAIN); ?>"
                     title="<?php _e('My account', DOMAIN); ?>">
                <?php echo $my_account_title; ?>
            </a>
        <?php else: ?>
            <div class="header_modal__top_title">
                <img src="<?php echo get_template_directory_uri() . '/dest/img/Account.svg'; ?>"
                     alt="<?php _e('My account', DOMAIN); ?>"
                     title="<?php _e('My account', DOMAIN); ?>">
                <?php echo $my_account_title; ?>
            </div>
        <?php endif; ?>
        <div class="header_modal__top_links">
            <?php if (USER_ID && $wishlist_link = get_field('whishlist_link', 'options')): ?>
                <a href="<?php echo esc_url($wishlist_link); ?>" class="wishlist-link">
                    <img src="<?php echo get_template_directory_uri() . '/dest/img/Heart.svg'; ?>"
                         alt="<?php _e('Wishlist', DOMAIN); ?>"
                         title="<?php _e('Wishlist', DOMAIN); ?>">
                    <?php _e('Wishlist', DOMAIN); ?>
                </a>
            <?php else:
                $auth_page_url = get_field('auth_page', 'options');
                $login_page = $auth_page_url ?: wp_login_url();
                $registration_page = $auth_page_url ?: wp_registration_url(); ?>

                <a href="<?php echo $login_page; ?>" class="remember_place" <?php echo current_url_attr(); ?>>
                    <?php _e('Log in', DOMAIN); ?>
                </a>
                /
                <a href="<?php echo $registration_page; ?>" class="remember_place" <?php echo current_url_attr(); ?>>
                    <?php _e('Sign up', DOMAIN); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="header_modal__content">
        <?php

        $menus = [
            'main_header',
            'header_mobile_bottom'
        ];

        foreach ($menus as $menu):
            wp_nav_menu([
                'theme_location' => $menu
            ]);
        endforeach;

        $languages = get_languages(true);?>
        <div class="mobile_switcher">
            <?php if (!empty($languages) && empty(get_field('hide_language_switcher', 'options'))): ?>
                <div class="mobile_switcher--wrapper">
                    <div class="mobile_switcher__icon lang">
                        <svg width="20" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.362 10.0001C18.362 11.0944 18.1464 12.1781 17.7276 13.1891C17.3089 14.2002 16.695 15.1188 15.9212 15.8926C15.1474 16.6665 14.2287 17.2803 13.2177 17.6991C12.2066 18.1179 11.123 18.3334 10.0286 18.3334C8.9343 18.3334 7.85066 18.1179 6.83962 17.6991C5.82857 17.2803 4.90991 16.6665 4.13609 15.8926C3.36227 15.1188 2.74844 14.2002 2.32965 13.1891C1.91086 12.1781 1.69531 11.0944 1.69531 10.0001C1.69531 8.90573 1.91086 7.8221 2.32965 6.81105C2.74844 5.80001 3.36227 4.88135 4.13609 4.10752C4.90991 3.3337 5.82857 2.71987 6.83962 2.30108C7.85066 1.8823 8.9343 1.66675 10.0286 1.66675C11.123 1.66675 12.2066 1.8823 13.2177 2.30109C14.2287 2.71988 15.1474 3.3337 15.9212 4.10753C16.695 4.88135 17.3089 5.80001 17.7276 6.81105C18.1464 7.8221 18.362 8.90573 18.362 10.0001L18.362 10.0001Z" stroke="#585858" stroke-width="1.25"/>
                            <path d="M13.362 10.0001C13.362 11.0944 13.2758 12.1781 13.1082 13.1891C12.9407 14.2002 12.6952 15.1188 12.3857 15.8926C12.0761 16.6665 11.7087 17.2803 11.3043 17.6991C10.8998 18.1179 10.4664 18.3334 10.0286 18.3334C9.59091 18.3334 9.15745 18.1179 8.75303 17.6991C8.34862 17.2803 7.98115 16.6665 7.67162 15.8926C7.36209 15.1188 7.11656 14.2002 6.94905 13.1891C6.78153 12.1781 6.69531 11.0944 6.69531 10.0001C6.69531 8.90573 6.78153 7.8221 6.94905 6.81105C7.11656 5.80001 7.36209 4.88135 7.67162 4.10752C7.98115 3.3337 8.34862 2.71987 8.75303 2.30108C9.15745 1.8823 9.59091 1.66675 10.0286 1.66675C10.4664 1.66675 10.8998 1.8823 11.3043 2.30109C11.7087 2.71988 12.0761 3.3337 12.3857 4.10753C12.6952 4.88135 12.9407 5.80001 13.1082 6.81105C13.2758 7.8221 13.362 8.90573 13.362 10.0001L13.362 10.0001Z" stroke="#585858" stroke-width="1.25"/>
                            <path d="M1.69531 10H18.362" stroke="#585858" stroke-width="1.25" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="mobile_switcher__item switcher-item-active">
                        <span>
                            <?php
                            if ($languages[CURRENT_LANG]['name'] === 'PL') {
                                echo 'POLSKI';
                            } elseif ($languages[CURRENT_LANG]['name'] === 'ENG') {
                                echo 'ENGLISH';
                            } else {
                                echo $languages[CURRENT_LANG]['name'];
                            }
                            ?>
                        </span>
                    </div>
                    <div class="mobile_switcher__icon">
                        <?php echo get_local_img_html('Arrows.svg', '', __('Language switcher', DOMAIN)); ?>
                    </div>
                    <?php foreach ($languages as $slug => $data): ?>
                        <?php if ($slug != CURRENT_LANG): ?>
                            <a href="<?php echo $data['url']; ?>" class="mobile_switcher__item">
                                <?php
                                if ($data['name'] === 'PL') {
                                    echo 'POLSKI';
                                } elseif ($data['name'] === 'ENG') {
                                    echo 'ENGLISH';
                                } else {
                                    echo $data['name'];
                                }
                                ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php
            $woo_multi_currency_params = get_option('woo_multi_currency_params');
            if (MULTI_CURRENCY_ACTIVE && !empty($woo_multi_currency_params)) :
                $multi_currency_settings = WOOMULTI_CURRENCY_Data::get_ins();
                $current_currency = $multi_currency_settings->get_current_currency();
                ?>

                <div class="mobile_switcher--wrapper currency--wrapper">
                    <div class="mobile_switcher__icon currency">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                            <path d="M0.0546875 6.25C0.0546875 9.28554 2.21874 11.8156 5.08823 12.3817C5.17135 11.4653 5.40664 10.5928 5.76863 9.78967C4.67296 9.42048 3.96981 8.58028 3.80339 7.38857H3.17969V6.85562H3.76181V6.31298C3.76181 6.25484 3.76181 6.19671 3.76701 6.14341H3.17969V5.61047H3.81898C4.09965 4.05039 5.32106 3.125 7.15578 3.125C7.55079 3.125 7.89382 3.16376 8.17969 3.23159V4.14729C7.90422 4.07946 7.56638 4.04554 7.16098 4.04554C6.01233 4.04554 5.2379 4.62694 4.99362 5.61047H7.38967V6.14341H4.91565C4.91046 6.20155 4.91046 6.26453 4.91046 6.32752V6.85562H7.38967V7.38857H4.96763C5.11509 8.14115 5.55279 8.6768 6.22324 8.92464C7.52575 6.77211 9.79914 5.27273 12.4364 5.03355C11.8703 2.16405 9.34023 0 6.30469 0C2.85291 0 0.0546875 2.79822 0.0546875 6.25Z" fill="#585858"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.0537 13.1743C20.0537 16.944 16.9977 20 13.228 20C9.45831 20 6.40234 16.944 6.40234 13.1743C6.40234 9.4046 9.45831 6.34863 13.228 6.34863C16.9977 6.34863 20.0537 9.4046 20.0537 13.1743ZM16.9767 11.5141V12.2191C16.9767 12.3558 16.9201 12.4458 16.8067 12.4891L16.0867 12.7941V16.6741H14.8517V13.2241L14.0117 13.5691V12.8391C14.0117 12.7258 14.0617 12.6458 14.1617 12.5991L14.8517 12.3041V9.24409H16.0867V11.8691L16.9767 11.5141ZM13.4666 12.3094C13.4966 12.226 13.5116 12.141 13.5116 12.0544V11.5444H9.62156V12.4894H12.0966L9.63656 15.7344C9.58323 15.8044 9.54323 15.8794 9.51656 15.9594C9.4899 16.036 9.47656 16.1027 9.47656 16.1594V16.6744H13.4366V15.7244H10.9116L13.3466 12.5144C13.3966 12.461 13.4366 12.3927 13.4666 12.3094Z" fill="#585858"/>
                        </svg>
                    </div>
                    <?php foreach ($woo_multi_currency_params['currency'] as $currency) : ?>
                        <?php if ($currency === $current_currency) : ?>
                            <div class="mobile_switcher__item">
                                <span>
                                    <?php
                                    $currency_name = '';
                                    $currency_symbol = '';
                                    switch ($currency) {
                                        case 'EUR':
                                            $currency_name = 'EURO';
                                            $currency_symbol = '€';
                                            break;
                                        case 'PLN':
                                            $currency_name = 'ZŁOTY';
                                            $currency_symbol = 'PLN';
                                            break;
                                        default:
                                            $currency_name = $currency;
                                            $currency_symbol = $currency;
                                            break;
                                    }
                                    echo esc_html($currency_name) . " ($currency_symbol)";
                                    ?>
                                </span>
                            </div>
                            <div class="mobile_switcher__icon">
                                <?php echo get_local_img_html('Arrows.svg', '', __('Language switcher', DOMAIN)); ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php foreach ($woo_multi_currency_params['currency'] as $currency) : ?>
                        <?php if ($currency !== $current_currency) : ?>
                            <a href="#" class="wmc-currency-redirect" data-currency="<?php echo esc_attr($currency); ?>">
                                <?php
                                $currency_name = '';
                                $currency_symbol = '';
                                switch ($currency) {
                                    case 'EUR':
                                        $currency_name = 'EURO';
                                        $currency_symbol = '€';
                                        break;
                                    case 'PLN':
                                        $currency_name = 'ZŁOTY';
                                        $currency_symbol = 'PLN';
                                        break;
                                    default:
                                        $currency_name = $currency;
                                        $currency_symbol = $currency;
                                        break;
                                }
                                echo esc_html($currency_name) . " ($currency_symbol)";
                                ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (USER_ID) { ?>
                <a href="<?php echo wp_logout_url(MY_ACC_LINK); ?>" class="header-modal-logout">
                    <?php _e('Log out', DOMAIN); ?>
                </a>
            <?php } ?>
        </div>
        <div class="header_modal__social">
            <?php socials(); ?>
        </div>
    </div>
</div>
<div class="header_modal__bg"></div>
<div class="header_modal__close">
    <img src="<?php echo get_template_directory_uri() . '/dest/img/cross.svg'; ?>"
         alt="<?php _e('Burger menu icon close', DOMAIN); ?>"
         title="<?php _e('Burger menu icon close', DOMAIN); ?>">
</div>