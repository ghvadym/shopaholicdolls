(function ($) {
    $(document).ready(function () {
        var ajax = ajaxcart.ajaxurl;

        function cart_processing()
        {
            $('.cart_totals, .woocommerce-cart-form').block({
                message   : null,
                overlayCSS: {
                    background: '#fff',
                    opacity   : 0.6
                }
            }).addClass('processing');
        }

        function cart_finish_processing()
        {
            $('.cart_totals, .woocommerce-cart-form').unblock().removeClass('processing');
        }

        function update_cart()
        {
            $(document.body).trigger('wc_update_cart');
            update_mini_cart_items();
        }

        function update_mini_cart_items()
        {
            $.ajax({
                type      : 'POST',
                url       : ajax,
                data      : {
                    action: 'update_mini_cart_items_html'
                },
                success   : function (response) {
                    if (response.html) {
                        $('.header_cart').html(response.html);
                    }
                },
                error     : function (err) {
                    console.log('error', err);
                }
            });
        }

        $(document).on('change', '.product-quantity select.qty', function () {
            var data = {
                action       : 'update_cart',
                security     : ajaxcart.update_cart_nonce,
                cart_item_key: $(this).attr('name').match(/\[(.*?)\]/)[1],
                quantity     : $(this).val()
            };

            $.ajax({
                type      : 'POST',
                url       : ajax,
                data      : data,
                beforeSend: function () {
                    cart_processing();
                },
                success   : function (response) {
                    update_cart();
                },
                error     : function (err) {
                    console.log('error', err);
                }
            });
        });


        $(document).on('click', '.restore-button, .restore-product', function () {
            var restore_button = $(this);
            var cart_item_key = restore_button.data('cart_item_key');

            $.ajax({
                type      : 'POST',
                url       : ajaxcart.ajaxurl,
                data      : {
                    action         : 'update_cart',
                    security       : ajaxcart.update_cart_nonce,
                    cart_item_key  : cart_item_key,
                    restore_product: true
                },
                beforeSend: function () {
                    cart_processing();
                },
                success   : function (response) {
                    if (response.success) {
                        update_cart();
                    }
                },
                error     : function (err) {
                    console.log('error', err);
                }
            });
        });

        $(document).on('click', '.product-bottom .remove', function (e) {
            e.preventDefault();

            var remove_link = $(this);
            var cart_item_key = remove_link.data('cart_item_key');
            var cart_item_row_to_remove = remove_link.closest('.cart-table-row');
            var data = {
                action        : 'update_cart',
                security      : ajaxcart.update_cart_nonce,
                cart_item_key : cart_item_key,
                remove_product: true
            };

            $.ajax({
                type      : 'POST',
                url       : ajaxcart.ajaxurl,
                data      : data,
                beforeSend: function () {
                    cart_processing();
                },
                success   : function (response) {
                    if (response.success) {
                        $('.cart-sidebar__subtotal .woocommerce-Price-amount').html(response.subtotal);
                        $('.cart-sidebar__total--total .woocommerce-Price-amount').html(response.cart_total);

                        var restore_block = $('<div class="restore-product" data-cart_item_key="' + cart_item_key + '" ></div>');
                        restore_block.append('<svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none"><path d="M3.16406 5.6665H10.4974C12.3383 5.6665 13.8307 7.15889 13.8307 8.99984C13.8307 10.8408 12.3383 12.3332 10.4974 12.3332H5.83073M3.16406 5.6665L5.16406 3.6665M3.16406 5.6665L5.16406 7.6665" stroke="#1B1B1B" stroke-linecap="round" stroke-linejoin="round"/></svg>');
                        restore_block.append('<div class="restore-button" data-cart_item_key="' + cart_item_key + '" data-restored_product_id="' + response.restored_product_id + '" data-restored_variation_id="' + response.restored_variation_id + '">'+ ajaxcart.restore_item_message +'</div>');

                        cart_item_row_to_remove.before(restore_block).hide();
                        cart_finish_processing();

                        setTimeout(function () {
                            cart_processing();
                            update_cart();
                        }, 2000);

                        setTimeout(function () {
                            cart_update_rewards_html();
                        }, 3000);
                    } else {
                        $('.cart-sidebar__total, .woocommerce-cart-form').unblock();
                        console.log('Error: ' + response.message);
                        console.log('Additional error details:', response.debug);
                    }
                },
                error     : function (err) {
                    console.log('error', err);
                }
            });
        });


        $(document).on('click', '.edit-variation', function (e) {
            e.preventDefault();
            var wrap = $(this).closest('.product-info');
            $(wrap).find('.product-variations').hide();
            $(wrap).find('.variation-wrapper').show();
            $(this).addClass('active');
        });


        $(document).on('click', '.cancel-button', function (event) {
            event.preventDefault();
            var variations = $(this).closest('.product-info').find('.product-variations');

            $(this).closest('.product-info').find('.edit-variation').removeClass('active');
            $(this).closest('.product-info').find('.variation-wrapper').hide();

            $(variations).show();
        });

        $(document).on('click', '.confirm-button', function (event) {
            event.preventDefault();
            var cart_item_key = $(this).attr('data-cart_item_key');
            var selected_variations = {};

            var select_wrapper = $(this).closest('.variation-wrapper').find('select');
            $(select_wrapper).each(function (index, element) {
                var selected_value = $(element).val();
                if (selected_value !== "") {
                    var attribute_name = $(element).attr('data-attribute_name');
                    selected_variations[attribute_name] = selected_value;
                }
            });

            $('.edit-variation').removeClass('active');

            $.ajax({
                type      : 'POST',
                url       : ajax,
                data      : {
                    action       : 'update_cart_variation',
                    cart_item_key: cart_item_key,
                    variation    : selected_variations,
                    security     : ajaxcart.update_cart_nonce
                },
                dataType  : 'json',
                beforeSend: function () {
                    cart_processing();
                },
                success   : function (response) {
                    update_cart();
                },
                error     : function (xhr, status, error) {
                    console.error('AJAX error:', xhr, status, error);
                },
            });
        });

        /* Hide select with one option */
        check_select_attributes();
        function check_select_attributes()
        {
            var cart_items = $('.cart_item');
            if (cart_items.length > 0) {
                $(cart_items).each(function (index, element) {
                    var select = $(element).find('.variation-wrapper select');
                    if (select && select.length > 0) {
                        $(select).each(function (index, el) {
                            var options = $(el).find('option');
                            $(el).find('option:first').remove();
                            if (options && options.length === 2) {
                                $(el).find('option').prop('selected', true).trigger('change');
                                $(el).prop('disabled', true);
                            }
                        });
                    }
                });
            }
        }


        /* Cart sidebar tabs */
        $(document).on('click', '.cart-sidebar__top', function () {
            var parent = $(this).parent('.cart-sidebar__item');
            var content = parent.find('.cart-sidebar__content');
            var svg = parent.find('svg');

            content.toggleClass('open');
            svg.toggleClass('rotate');
        });


        /* Save message in session */
        var order_note = $('#order_note');

        if ($(order_note).length > 0) {
            var typing_timer;
            var done_typing_interval = 3000;

            $(document).on('keyup', '#order_note', function (e) {
                if (e.keyCode === 13) {
                    done_typing();
                } else {
                    clearTimeout(typing_timer);
                    typing_timer = setTimeout(done_typing, done_typing_interval);
                }
            });

            $(document).on('keydown', '#order_note', function () {
                clearTimeout(typing_timer);
            });

            function done_typing()
            {
                $(order_note).blur();

                update_order_session({
                    'order_note': $(order_note).val()
                }, $(order_note).closest('.cart-sidebar__content'));
            }
        }

        /* Save sample in session */
        var sampleProducts = $('.cart-sidebar__samples--item');
        if (sampleProducts.length) {
            $(document).on('click', '.cart-sidebar__samples--item', function () {
                var item = $('.cart-sidebar__samples--item');

                $(item).removeClass('active');
                $(item).find('input[name="sample"]').prop('checked', false);

                $(this).addClass('active');
                $(this).find('input[name="sample"]').prop('checked', true);

                update_order_session({
                    'product_sample': $(this).find('input[name="sample"]').attr('data-key')
                }, $(this).closest('.cart-sidebar__content'));
            });
        }


        $(document).on('submit', '#apply_coupon', function (e) {
            e.preventDefault();
            var wrap = $(this).closest('.cart-sidebar__promo');
            var form = $(this);
            var coupon_code = $('#coupon_code').val();
            var error_message = $(wrap).find('.form_error_message');

            $.ajax({
                type   : 'POST',
                url    : ajaxcart.ajaxurl,
                data   : {
                    action     : 'cart_apply_coupon',
                    coupon_code: coupon_code,
                    security   : ajaxcart.update_cart_nonce
                },
                beforeSend: function () {
                    $(form).addClass('_spinner');
                },
                success: function (response) {
                    if (response.error) {
                        if (response.message) {
                            $(error_message).html(response.message);
                        }
                    }

                    if (response.success) {
                        $(error_message).html('');
                        $(form).trigger('reset');

                        cart_processing();
                        update_cart();
                    }

                    $(form).removeClass('_spinner');
                },
                error  : function (error) {
                    console.log(error);
                }
            });
        });

        $(document).on('submit', '#apply_gift_card', function (e) {
            e.preventDefault();
            var wrap = $(this).closest('.cart-sidebar__gift');
            var form = $(this);
            var gift_code = $('#gift_card_code').val();
            var error_message = $(wrap).find('.form_error_message');

            $.ajax({
                type   : 'POST',
                url    : ajaxcart.ajaxurl,
                data   : {
                    action   : 'cart_apply_gift_card',
                    gift_code: gift_code,
                    security : ajaxcart.update_cart_nonce
                },
                beforeSend: function () {
                    $(form).addClass('_spinner');
                },
                success: function (response) {
                    if (response.error) {
                        if (response.message) {
                            $(error_message).html(response.message);
                        }
                    }

                    if (response.success) {
                        $(error_message).html('');
                        $(form).trigger('reset');

                        cart_processing();
                        update_cart();
                    }

                    $(form).removeClass('_spinner');
                },
                error  : function (error) {
                    console.log(error);
                }
            });
        });


        /* Reward items */
        if ($('.cart-sidebar__points--item .reward_product_item').length) {
            $(document).on('change', '.cart-sidebar__points--item .reward_product_item', function () {
                $('#reward_products').submit();
            });

            $(document).on('submit', '#reward_products', function (e) {
                e.preventDefault();

                var wrap = $(this).closest('.cart-sidebar__points');
                var form_data = new FormData($(this)[0]);

                form_data.append('action', 'cart_update_rewards');
                form_data.append('security', ajaxcart.update_cart_nonce);

                $.ajax({
                    type      : 'POST',
                    url       : ajax,
                    data      : form_data,
                    processData: false,
                    contentType: false,
                    dataType  : 'json',
                    beforeSend: function () {
                        $(wrap).addClass('_spinner');
                    },
                    success   : function (response) {
                        if (response.html) {
                            $(wrap).html(response.html);
                        }

                        $(wrap).removeClass('_spinner');

                        cart_processing();
                        update_cart();
                    },
                    error     : function (err) {
                        console.log('error', err);
                    }
                });
            });
        }

        function cart_update_rewards_html()
        {
            var wrap = $('.cart-sidebar__points');

            if (!wrap) {
                return;
            }

            $.ajax({
                type      : 'POST',
                url       : ajax,
                data      : {
                    action : 'cart_update_rewards_html'
                },
                beforeSend: function () {
                    $(wrap).addClass('_spinner');
                },
                success   : function (response) {
                    if (response.html) {
                        $(wrap).html(response.html);
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
        }

        function update_order_session(options = {}, wrap = '')
        {
            var data = $.extend({
                'action': 'update_order_session'
            }, options);

            $.ajax({
                type      : 'POST',
                url       : ajaxcart.ajaxurl,
                data      : data,
                beforeSend: function () {
                    if (wrap) {
                        $(wrap).addClass('_spinner');
                    }
                },
                success   : function (response) {
                    if (response) {
                        if (response.message) {
                            console.log(response.message);
                        }
                    }

                    if (wrap) {
                        $(wrap).removeClass('_spinner');
                    }
                },
                error     : function (err) {
                    console.log('error', err);
                }
            });
        }

        $(document.body).on('updated_cart_totals', function () {
            check_select_attributes();
        });

    });
})(jQuery);