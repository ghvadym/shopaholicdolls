(function($){
    $(document).ready(function() {
        var ajax = wopajax.ajaxurl;
        var is_desktop = $(window).width() > 1024;

        if (!is_desktop) {
            $('body').addClass('mobile-version');
        } else {
            $('body').addClass('desktop-version');
        }

        $('.currency-switcher .lang_switcher__item, .wmc-currency-redirect').click(function() {
            if ($(this).hasClass('wmc-currency-redirect')) {
                $('.currency--wrapper').addClass('_spinner');
            } else {
                $('.currency-switcher').addClass('_spinner');
            }
        });

        /* Change space under header depends on header height */
        if (!is_desktop) {
            var header_home = $('body.home #header');
            if (header_home.length) {
                var header_home_height = header_home.outerHeight();
                $('body.home .main').css('margin-top', header_home_height + 'px');
                $('.header_modal').css({
                    'top': (header_home_height + 4) + 'px',
                    'height': `calc(100vh - ${header_home_height + 4}px)`
                });
                $('.header_modal__bg').css('top', header_home_height + 'px');
                $('.header_modal__close').css('top', (header_home_height + 14) + 'px');
            }

            var header_product = $('body.single-product #header');
            if (header_product.length) {
                var header_product_height = header_product.outerHeight() + 8;
                $('body.single-product .main').css('margin-top', header_product_height + 'px');
                $('.header_modal').css({
                    'top': (header_home_height + 4) + 'px',
                    'height': `calc(100vh - ${header_home_height + 4}px)`
                });
                $('.header_modal__bg').css('top', header_product_height + 'px');
                $('.header_modal__close').css('top', (header_product_height + 14) + 'px');
            }
        }

        /* Add to cart */
        if ($('.add_product_to_cart').length) {
            $(document).on('click', '.add_product_to_cart', function () {
                var id = $(this).attr('data-id');
                var btn = $(this);

                $.ajax({
                    type: 'POST',
                    url : ajax,
                    data: {
                        action    : 'add_product_to_cart',
                        product_id: id,
                        nonce     : wopajax.nonce
                    },
                    beforeSend: function () {
                        $(btn).addClass('_spinner');
                    },
                    success: function (response) {
                        if (response.html) {
                            $('.header_cart').html(response.html);

                            if ($(btn).hasClass('cart_page')) {
                                $(document.body).trigger('wc_update_cart');
                            }
                        }

                        $(btn).removeClass('_spinner');
                    },
                    error: function (err) {
                        console.log('error', err);
                    }
                });
            });
        }

        /* Switching tabs */
        if (is_desktop) {
            $('.nav__tab').first().addClass('tab-active');
            $('.nav__contents .nav__content:first-child').addClass('tab-active');
        }

        $(document).on('click', '.nav__tab', function () {
            var wrap = $(this);
            if (is_desktop) {
                $('.nav__tab').removeClass('tab-active');
                $(wrap).addClass('tab-active');
                $('.nav__contents > .nav__content').removeClass('tab-active');
                var index = $(wrap).data('tab');
                $(`.nav__contents > .nav__content[data-tab=${index}]`).addClass('tab-active');
            } else {
                $(wrap).toggleClass('tab-active');
                $(wrap).find('.nav__content').slideToggle();
            }
        });


        /* Detect header menu position in mobile */
        var header_menu = $('#header');
        $(window).scroll(function () {
            if ($(this).scrollTop() > 400) {
                header_menu.addClass('_scrolled');
            } else {
                header_menu.removeClass('_scrolled');
            }
        });


        /* Header submenu Brands Alphabet Filter */
        $(document).on('change', '.mega_menu__letter input[name="alphabet-letter"]', function() {
            var most_wanted_brands = $('.mega_menu__most_wanted_brands');
            var filter_results = $('.mega_menu__brand_filter_results');

            jQuery.ajax({
                type   : 'POST',
                url    : ajax,
                data   : {
                    letter: $(this).attr('data-value'),
                    action: 'header_menu_brands_filter'
                },
                beforeSend: function () {
                    if (!$(most_wanted_brands).hasClass('d-none')) {
                        $(most_wanted_brands).addClass('_spinner');
                    } else {
                        $(filter_results).addClass('_spinner');
                    }
                },
                success: function (response) {
                    if (response) {
                        if (response.message) {
                            console.log(response.message);
                        }

                        if (response.html) {
                            $(most_wanted_brands).html(response.html);
                        }
                    }

                    $(filter_results).removeClass('_spinner d-none');
                    $(most_wanted_brands).removeClass('_spinner');
                },
                error  : function (err) {
                    console.log('error', err);
                }
            });
        });


        /* Toggling burger menu in mobile */
        $(document).on('click', '.header__main_mobile_row .header__categories, .header_modal__close, .header__burger_icon', function() {
            var header_menu = $('.header_modal');
            if (header_menu.length) {
                $(header_menu).toggleClass('active-menu');

                if ($(header_menu).hasClass('active-menu')) {
                    $('body').addClass('active-menu');
                } else {
                    $('body').removeClass('active-menu');
                }
            }
        });


        /* Close modal menu on click outside the menu */
        $(document).on('click', '.header_modal__bg', function() {
            $('.header_modal, body').removeClass('active-menu');
        });

        /* Close modal menu on swipe from right to left */
        var touch_start = 0;
        var touch_end = 0;
        
        $(document).on('touchstart', function(event) {
            touch_start = event.touches[0].clientX;
        });
        
        $(document).on('touchend', function(event) {
            touch_end = event.changedTouches[0].clientX;
            handleSwipe();
        });
        
        function handleSwipe() {
            var header_menu = $('.header_modal');
            if (header_menu.length) {
                if (touch_start - touch_end > 50) { 
                    header_menu.removeClass('active-menu');
                    $('body').removeClass('active-menu');
                }
            }
        }


        /* Mobile menu sub items toggle */
        $(document).on('click', '.header_modal__content .has-mega-menu > a', function(e) {
            if ($(e.target).hasClass('header_modal__view-more')) {
                return;
            }

            e.preventDefault();
            $(this).closest('.has-mega-menu').children('.sub-menu').slideToggle();
            $(this).closest('.has-mega-menu').toggleClass('menu-item-active');
        });


        /* Language switcher dropdown */
        $(document).on('click', '.header__top_mobile .header__lang_switcher', function(e) {
            $(this).toggleClass('drop-down-active');
        });


        /* Language switcher dropdown close on click outside */
        $(document).on('click', function(event) {
            var target = $(event.target);
            var switcher = $('.header__top_mobile .header__lang_switcher');

            if (!target.closest(switcher).length && $(switcher).is(":visible")) {
                $(switcher).removeClass('drop-down-active');
            }
        });

        var search_input_selector = is_desktop ? '.header__top_desktop .search_input' : '.header__top_mobile .search_input';
        var search_input = $(search_input_selector);
        var header_search = is_desktop ? $('.header__top_desktop .header__top') : $('.header__top_mobile .header__top');
        var header_search_submit_selector = is_desktop ? '.header__top_desktop .search_submit' : '.header__top_mobile .search_submit';

        if (header_search.length && search_input.length) {
            /* Open/close search bar */
            $(document).on('click', '.header__search_icon, .search_clear', function() {
                $(header_search).addClass('search-active');
            });

            /* Close searchbar */
            $(document).on('click', '.search_clear', function() {
                $(header_search).removeClass('search-active');
                $(search_input).val('');
                $('.header__search_results').empty().addClass('d-none');
            });


            /* Searching product by click on search button */
            $(document).on('click', header_search_submit_selector, function() {
                let search_val = $(search_input).val();
                if (!search_val) {
                    return;
                }

                window.location.href = window.location.origin + '?s=' + search_val;
            });


            /* Searching product by click on paste */
            $(document).on('paste', search_input_selector, function() {
                search_product($(this));
            });


            /* Searching product by click on enter */
            $(document).on('keypress', search_input_selector, function(e) {
                if (e.which === 13) {
                    search_product($(this).val());
                }
            });

            /* Searching product by ending of keyup */
            var typing_timer;
            var done_typing_interval = 1000;

            $(document).on('keyup', search_input_selector, function(e) {
                if (e.keyCode === 13) {
                    done_typing();
                } else {
                    clearTimeout(typing_timer);
                    typing_timer = setTimeout(done_typing, done_typing_interval);
                }
            });

            $(document).on('keydown', search_input_selector, function () {
                clearTimeout(typing_timer);
            });

            function done_typing()
            {
                $(search_input).blur();
                search_product($(search_input));
            }

            /* Ajax request to get product by search */
            function search_product(input)
            {
                if (!$(input).val()) {
                    return;
                }

                var wrapper = $(input).closest('.header__search_wrapper');
                var search_results = $(wrapper).find('.header__search_results');

                $(search_results).empty().addClass('d-none');


                jQuery.ajax({
                    type   : 'POST',
                    url    : ajax,
                    data   : {
                        search: $(input).val(),
                        action: 'header_search_results'
                    },
                    beforeSend: function () {
                        $(wrapper).addClass('_spinner');
                    },
                    success: function (response) {
                        if (response) {
                            if (response.message) {
                                console.log(response.message);
                            }

                            if (response.error && response.message) {
                                $(search_results).removeClass('d-none');
                                $(search_results).html('<div class="search_results__error">' + response.message + '</div>');
                            } else {
                                if (response.html) {
                                    $(search_results).html(response.html).removeClass('d-none');
                                } else {
                                    $(search_results).addClass('d-none');
                                }
                            }
                        }

                        $(wrapper).removeClass('_spinner');
                    },
                    error  : function (err) {
                        console.log('error', err);
                    }
                });
            }
        }


        /* Google places suggestions for address fields */
        if (wopajax.my_account) {
            var country_fields = document.querySelectorAll('.billing_country select, .shipping_country select');
            if (country_fields.length) {
                country_fields.forEach((country_field) => {
                    const options = {
                        fields: ['address_components', 'geometry', 'icon', 'name'],
                        types: ['establishment'],
                        componentRestrictions: {
                            country: country_field.value
                        }
                    };

                    var billing_input = country_field.parentElement.parentElement.parentElement.querySelector('input[id*="_address_1"]');

                    if (!billing_input) {
                        return;
                    }

                    var billing_autocomplete = new google.maps.places.Autocomplete(billing_input, options);

                    country_field.addEventListener('change', function() {
                        billing_autocomplete.setOptions({
                            componentRestrictions: {country: this.value}
                        });
                    });
                });
            }
        }

        if (wopajax.checkout) {
            var billing_country = $('#billing_country');
            var billing_address = $('#billing_address_1');
            if ($(billing_country).length && $(billing_address).length) {
                const options = {
                    fields               : ['address_components', 'geometry', 'icon', 'name'],
                    types                : ['establishment'],
                    componentRestrictions: {
                        country: $(billing_country).val()
                    }
                };

                var billing_input = document.getElementById('billing_address_1');
                var billing_autocomplete = new google.maps.places.Autocomplete(billing_input, options);

                $(document.body).on('change', 'select[name=billing_country]', function () {
                    billing_autocomplete.setOptions({
                        componentRestrictions: {country: $(this).val()}
                    });
                });
            }

            var shipping_country = $('#shipping_country');
            var shipping_address = $('#shipping_address_1');
            if ($(shipping_country).length && $(shipping_address).length) {
                const options = {
                    fields: ['address_components', 'geometry', 'icon', 'name'],
                    types: ['establishment'],
                    componentRestrictions: {
                        country: $(shipping_country).val()
                    }
                };

                var shipping_input = document.getElementById('shipping_address_1');
                var shipping_autocomplete = new google.maps.places.Autocomplete(shipping_input, options);

                $(document.body).on('change', 'select[name=shipping_country]', function() {
                    shipping_autocomplete.setOptions({
                        componentRestrictions: {country: $(this).val()}
                    });
                });
            }
        }

        /* Custom select */
        var custom_select = $('.custom_select');
        if (custom_select.length) {
            var select_head = custom_select.find('.custom_select__head');

            select_head.on('click', function (e) {
                custom_select.toggleClass('select-active');
            });

            $(document).on('click', function(event) {
                var target = $(event.target);

                if (!target.closest(custom_select).length && custom_select.is(":visible")) {
                    custom_select.removeClass('select-active');
                }
            });
        }

        $('textarea.text_field_max_length').each(function() {
            update_characters_remain($(this));
        });

        /* Label animation for fields */
        $('.form-row input, .form-row textarea').each(function() {
            if ($(this).val()) {
                $(this).closest('.form-row').addClass('active-input');
            }
        });

        $(document).on('click', function(event) {
            var target = $(event.target);
            $('.form-row input, .form-row textarea').each(function(i, input) {
                if (!target.closest(input).length && !$(input).val()) {
                    $(input).closest('.form-row').removeClass('active-input');
                }
            });
        });

        $(document).on('change click focus', '.form-row input, .form-row textarea', function(e) {
            if ($(this).attr('type') === 'checkbox' || $(this).attr('type') === 'radio') {
                return;
            }

            if (!$(this).val()) {
                $(this).closest('.form-row').removeClass('active-input');
            }

            $(this).closest('.form-row').addClass('active-input');
        });

        $(document).on('keyup', 'textarea.text_field_max_length', function(e) {
            update_characters_remain($(this));
        });

        function update_characters_remain(item)
        {
            if (!item || !$(item).length) {
                return;
            }

            var max_length = $(item).attr('maxlength');
            var length = $(item).val().length;
            var remain = max_length - length;

            $(item).closest('.form-row').find('.characters_remain__amount').html(remain);
        }


        function check_password_requirements(field, wrapper) {
            var password_requirements = {
                min_length         : field.val().length >= 8,
                has_upper_case     : /[A-Z]/.test(field.val()),
                has_lower_case     : /[a-z]/.test(field.val()),
                has_digit_or_symbol: /[0-9!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(field.val())
            };
        
            $.each(password_requirements, function (key, value) {
                if (value) {
                    wrapper.find('.' + key + '_span').addClass('active');
                } else {
                    wrapper.find('.' + key + '_span').removeClass('active');
                }
            });
        }
        
        var passwordField = $('.password-requirements');
        var requirementsWrapper = $('form.woocommerce-form');

        if ($(passwordField).length && $(requirementsWrapper).length) {
            $(passwordField).on('keyup change', function () {
                check_password_requirements(passwordField, requirementsWrapper);
            });
        }

        $('.add_item_to_wishlist').on('click', function() {
            var postId = $(this).attr('data-id');
            var $button = $(this);


            var action = $button.hasClass('favorite') ? 'remove' : 'add';

            $.ajax({
                type: 'POST',
                url: ajax,
                data: {
                    action: 'manage_wishlist',
                    post_ID: postId,
                    wishlist_action: action
                },
                beforeSend: function () {
                    $button.addClass('_spinner');
                },
                success: function(response) {
                    if (response.success) {
                        if (response.data.in_favorites) {
                            $button.addClass('favorite');
                        } else {
                            $button.removeClass('favorite');
                        }
                        $button.removeClass('_spinner');
                        if (!response.data.in_favorites && $('body').hasClass('page-template-whishlist')) {
                            location.reload();
                        }

                    } else {
                    }
                },
                error: function() {
                    $button.removeClass('_spinner');
                }
            });
        });

        var copy_to_clipboard_btn = $('.copy_to_clipboard');
        if (copy_to_clipboard_btn.length) {
            $(copy_to_clipboard_btn).on('click', function () {
                copy_to_clipboard($(this));

                var icon_copy = $(this).find('.copy_to_clipboard__icon_copy');
                var icon_check = $(this).find('.copy_to_clipboard__icon_check');

                if (icon_copy.length && icon_check.length) {
                    icon_copy.addClass('d-none');
                    icon_check.removeClass('d-none');

                    setTimeout(function() {
                        icon_copy.removeClass('d-none');
                        icon_check.addClass('d-none');
                    }, 2000);
                }
            });
        }

        let copy_shop_item_url_btn = $('.product-action__copy-item-url');
        const copy_shop_item_url_info = $('.product-action__copy-url-info');
        if (copy_shop_item_url_btn.length) {
            $(copy_shop_item_url_btn).on('click', function () {
                copy_shop_item_url_info.removeClass('copy-url-info-hidden');

                copy_to_clipboard($(this));

                setTimeout(function () {
                    copy_shop_item_url_info.addClass('copy-url-info-hidden');
                }, 2000);
            });
        }

        function copy_to_clipboard(element)
        {
            var temp = $('<input>');
            $('body').append(temp);
            temp.val($(element).attr('data-copy')).select();
            document.execCommand('copy');
            temp.remove();
        }

        $(document).on('submit', '.newsletter__form', function(e) {
            e.preventDefault();

            var form = $(this);
            var email = $(this).find('input[name=user-email]');
            var error = $(this).find('.form-row-small.error');
            var success = $(this).find('.form_success_message');

            if (!$(email).val()) {
                $(error).html(wopajax.required_field);
            }

            $(error).html('');

            $.ajax({
                type: 'POST',
                url: ajax,
                data: {
                    action: 'newsletter_subscribe',
                    nonce : wopajax.nonce,
                    email : $(email).val()
                },
                beforeSend: function () {
                    $(form).addClass('_spinner');
                },
                success: function(response) {
                    if (response.success) {
                        $(error).html('');
                        $(email).val('');

                        if (response.message) {
                            $(success).html(response.message);
                        }

                        setTimeout(function () {
                            $(success).html('');
                        }, 3000);
                    }

                    if (response.error) {
                        if (response.message) {
                            $(error).html(response.message);
                            $(success).html('');
                        }
                    }

                    $(form).removeClass('_spinner');
                },
                error  : function (error) {
                    console.log(error);
                }
            });
        });

        /* Remember place you went from */
        var remember_place = $('.remember_place');
        if (remember_place.length) {
            $(remember_place).on('click', function() {
                var url = $(this).attr('data-url');
                var product_id = $(this).attr('data-id');

                if (url) {
                    set_cookie('remember_place', url);
                }

                if (product_id) {
                    set_cookie('add_product_id', product_id);
                }
            });
        }

        function set_cookie(name, value, hours = 1)
        {
            if (!name || !value) {
                return;
            }

            const d = new Date();
            d.setTime(d.getTime() + (hours * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = name + "=" + value + ";" + expires + ";path=/";
        }

        const form_register = $('form.woocommerce-form-register');
        if (form_register.length) {
            $(document).on('submit', 'form.woocommerce-form-register', function(e) {
                e.preventDefault();

                var wrap = $(this);
                var form_data = new FormData($(this)[0]);

                form_data.append('action', 'customer_register');
                form_data.append('nonce', wopajax.nonce);

                $.ajax({
                    type       : 'POST',
                    url        : ajax,
                    data       : form_data,
                    processData: false,
                    contentType: false,
                    dataType   : 'json',
                    beforeSend: function () {
                        $(wrap).addClass('_spinner');
                    },
                    success   : function (response) {
                        if (response.error) {
                            if (response.show) {
                                $(wrap).find('.form_error_message').text(response.error);
                            }
                        } else {
                            $(wrap).find('.form_error_message').empty();
                        }

                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }

                        $(wrap).removeClass('_spinner');
                    },
                    error     : function (err) {
                        console.log('error', err);
                    }
                });
            });
        }

    });
})(jQuery);