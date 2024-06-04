(function($){
    $(document).ready(function() {
        var ajax = ajaxcheckout.ajaxurl;
        var is_desktop = $(window).width() > 1024;

        $(document).on('click', 'button[type="submit"]', function() {
            $('.validate-required input, .validate-required select').trigger('validate');
        });

        $(document.body).on('updated_checkout', function() {
            update_totals();
        });

        $(document).on('change', '#gift_option_wrap', function() {
            var is_checked = $(this).is(':checked');
            var gift_message = $('#gift_message');

            if (!is_checked) {
                $(gift_message).val('');
                $('#gift_option_message').prop('checked', false);
            }

            $('.gift_option_message_wrap').toggleClass('d-none');

            update_totals({
                'gift_wrap'    : $(this).is(':checked'),
                'gift_message' : $(gift_message).val()
            });
        });

        $(document).on('click', '.gift_message_apply', function() {
            var val = $('#gift_message').val();

            if (!val) {
                $('#gift_option_message').prop('checked', false);
            }

            update_totals({
                'gift_message' : val
            });
        });

        $(document).on('change', '#gift_option_message', function() {
            var gift_message = '';

            if ($(this).is(':checked')) {
                gift_message = $('#gift_message').val();
            } else {
                gift_message = '';
            }

            update_totals({
                'gift_message' : gift_message
            });
        });

        $(document).on('click', '.checkout__auth_tab', function () {
            $('.checkout__auth_tab, .checkout__auth_form').removeClass('tab-active');
            $(this).addClass('tab-active');

            var index = $(this).data('tab');
            $(`.checkout__auth_form[data-tab="${index}"]`).addClass('tab-active');
        });

        $(document).on('click', '.checkout__auth_guest', function () {
            $('.checkout__auth').addClass('d-none');
            $('.checkout__fields').removeClass('d-none');

            if (!is_desktop) {
                $('.checkout__summary').removeClass('d-none');
            }

            update_order_session({
                'customer_hide_login' : 1
            });
        });

        $(document).on('click', '.show_login_form', function () {
            $('.checkout__auth').removeClass('d-none');
            $('.checkout__fields').addClass('d-none');
            if (!is_desktop) {
                $('.checkout__summary').addClass('d-none');
            }
            $('html, body').animate({
                scrollTop: $('.checkout__auth').offset().top
            }, 'slow');

            update_order_session({
                'customer_hide_login' : 0
            });
        });

        if (!is_desktop) {
            $(document).on('click', '.order_summary__head', function () {
                var opened = 0;

                if ($('.order_summary').hasClass('summary_opened')) {
                    setTimeout(function () {
                        $('.order_summary__note').stop().slideDown(500);
                    }, 300 );

                    $('.order_summary__body').stop().slideUp(300);
                    $(this).closest('.order_summary').toggleClass('summary_opened');
                } else {
                    setTimeout(function () {
                        $('.order_summary__body').stop().slideDown(600);
                    }, 300 );

                    $('.order_summary__note').stop().slideUp(300);
                    $(this).closest('.order_summary').toggleClass('summary_opened');

                    opened = 1;
                }

                update_order_session({
                    'summary_opened' : opened
                });
            });
        }

        function update_order_session(options = {})
        {
            var data = $.extend({
                'action' : 'update_order_session'
            }, options);

            jQuery.ajax({
                type : 'POST',
                url  : ajax,
                data : data,
                success: function (response) {
                    if (response) {
                        if (response.message) {
                            console.log(response.message);
                        }
                    }
                },
                error  : function (err) {
                    console.log('error', err);
                }
            });
        }

        function update_totals(options = {})
        {
            var wrapper = $('.order_summary');
            var data = $.extend({
                'action' : 'checkout_update_totals'
            }, options);

            var is_gift_message = 'gift_message' in options;

            jQuery.ajax({
                type : 'POST',
                url  : ajax,
                data : data,
                beforeSend: function () {
                    $(wrapper).addClass('_spinner');

                    if (is_gift_message) {
                        $('.gift_option_message_wrap').addClass('_spinner');
                    }
                },
                success: function (response) {
                    if (response) {
                        if (response.message) {
                            console.log(response.message);
                        }

                        if (response.html) {
                            $(wrapper).html(response.html);
                        }

                        update_mini_cart_items();
                    }

                    $(wrapper).removeClass('_spinner');

                    if (is_gift_message) {
                        $('.gift_option_message_wrap').removeClass('_spinner');
                    }
                },
                error  : function (err) {
                    console.log('error', err);
                }
            });
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
    });
})(jQuery);