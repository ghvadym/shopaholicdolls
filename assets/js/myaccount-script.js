(function ($) {
    $(document).ready(function () {
        var ajax = ajaxmyaccount.ajaxurl;
        var is_desktop = $(window).width() > 1024;

        $(document).on('click', '.content__head_action', function () {
            var action = $(this).attr('data-action');

            if (!action) {
                return;
            }

            var wrap = $(this).closest('.content__block');
            var input = $(wrap).find('input');
            var remove = $(wrap).find('.content__head_action[data-action="remove"]');
            var add = $(wrap).find('.content__head_action[data-action="add"]');
            var edit = $(wrap).find('.content__head_action[data-action="edit"]');
            var action_default = $(wrap).find('.content__head_action[data-action="default"]');

            $(wrap).addClass('active');
            $(wrap).find('.content__body').slideDown();

            if (action === 'edit' || action === 'add') {
                $(wrap).find('.content__body_val').hide();
                $(wrap).find('.content__body_edit').show();
                $(edit).hide();
                $(add).hide();

                if ($(input).val()) {
                    $(remove).show();
                }
            } else if (action === 'remove') {
                $(wrap).find('.content__body_val').hide();
                $(wrap).find('.content__body_edit').show();
                $(wrap).find('.content__body_fields').hide();
                $(wrap).find('.content__body_remove').show();
                $(wrap).find('.content__body_default').hide();
                $(wrap).find('input[name="rm_fields"]').val('1');
                $(action_default).hide();
                $(remove).hide();
            } else if (action === 'default') {
                $(wrap).find('.content__body_val').hide();
                $(wrap).find('.content__body_edit').show();
                $(wrap).find('.content__body_remove').hide();
                $(wrap).find('.content__body_default').show();
                $(action_default).hide();
                $(remove).hide();
            }
        });

        $(document).on('click', '.content__body_cancel', function () {
            var wrap = $(this).closest('.content__block');
            var remove = $(wrap).find('.content__head_action[data-action="remove"]');
            var add = $(wrap).find('.content__head_action[data-action="add"]');
            var edit = $(wrap).find('.content__head_action[data-action="edit"]');
            var is_filled = $(wrap).attr('data-val') === '1';

            $(wrap).find('.content__body_val').show();
            $(wrap).find('.content__body_edit').hide();
            $(wrap).find('.content__body_fields').show();
            $(wrap).find('.content__body_remove').hide();
            $(wrap).find('.content__body_default').hide();
            $(wrap).find('input[name="rm_fields"]').val('');
            $(wrap).removeClass('active');


            if (!is_filled) {
                $(edit).hide();
                $(add).show();
            } else {
                $(edit).show();
                $(add).hide();
            }

            $(remove).hide();
        });

        $(document).on('click', '.content__tab', function () {
            $('.content__tab, .content__tab_body').removeClass('tab-active');
            $(this).addClass('tab-active');
            $(`.content__tab_body[data-tab='${$(this).attr('data-tab')}']`).addClass('tab-active');
        });

        $(document).on('click', '.content__head', function () {
            var wrap = $(this).closest('.order_block');

            $(wrap).toggleClass('active');
            $(wrap).find('.content__body').slideToggle();
        })

        $(document).on('submit', '.user_meta_form', function (e) {
            e.preventDefault();
            update_user_data($(this));
        });

        function update_user_data(form)
        {
            if (!form) {
                return;
            }

            var wrap = $(form).closest('.content__block');
            var form_data = new FormData($(form)[0]);

            form_data.append('action', 'update_user_data');
            form_data.append('nonce', ajaxmyaccount.nonce);

            jQuery.ajax({
                type       : 'POST',
                url        : ajax,
                data       : form_data,
                dataType   : 'json',
                processData: false,
                contentType: false,
                beforeSend : function () {
                    $(wrap).addClass('_spinner');
                },
                success    : function (response) {
                    $(wrap).removeClass('_spinner');

                    if (response) {
                        if (response.message) {
                            console.log(response.message);
                        }

                        if (response.html) {
                            $(wrap).closest('.account__tab_content').html(response.html);
                        }
                    }
                },
                error      : function (err) {
                    console.log('error', err);
                }
            });
        }

        /* Change pass modal window */
        $(document).on('click', '.my_acc_change_pass', function () {
            $('.modal_window').addClass('modal_window__active');
        });

        $(document).on('click', '.modal_window__bg, .modal_window__close', function () {
            $('.modal_window').removeClass('modal_window__active');
        });

        $(document).on('submit', 'form.change_user_pass', function (e) {
            e.preventDefault();
            change_user_pass($(this));
        });

        function change_user_pass(form)
        {
            if (!form) {
                return;
            }

            var wrap = $(form).closest('.modal_window__body');
            var form_data = new FormData($(form)[0]);
            form_data.append('action', 'change_user_pass');
            form_data.append('nonce', ajaxmyaccount.nonce);

            jQuery.ajax({
                type       : 'POST',
                url        : ajax,
                data       : form_data,
                dataType   : 'json',
                processData: false,
                contentType: false,
                beforeSend : function () {
                    $(wrap).addClass('_spinner');
                },
                success    : function (response) {
                    if (response.error) {
                        if (response.message) {
                            $(wrap).find('.form_error_message').html(response.message);
                        }
                    } else {
                        $(wrap).find('.form_error_message').empty();
                    }

                    if (response.success) {
                        $(wrap).find('.form_error_message').empty();
                        $(form).trigger('reset');
                        $('.modal_window').removeClass('modal_window__active');
                        location.reload();
                    }

                    $(wrap).removeClass('_spinner');
                },
                error      : function (err) {
                    console.log('error', err);
                }
            });
        }


        /* Reorder Order */
        $(document).on('click', '.reorder_order', function () {
            var btn = $(this);

            jQuery.ajax({
                type      : 'POST',
                url       : ajax,
                data      : {
                    action  : 'reorder_order',
                    nonce   : ajaxmyaccount.nonce,
                    order_id: $(btn).attr('data-id')
                },
                beforeSend: function () {
                    $(btn).addClass('_spinner');
                },
                success   : function (response) {
                    if (response.redirect) {
                        location.href = response.redirect;
                    }

                    $(btn).removeClass('_spinner');
                },
                error     : function (err) {
                    console.log('error', err);
                }
            });
        });

        $('.reg__form--wrapper .btn-plain').on('click', function (e) {
            e.preventDefault();
            let button = $(this);
            button.fadeOut('fast', function () {
                $('.woocommerce-form-register').fadeIn('fast');
            });
        });

        $('.form-switcher .btn-plain').on('click', function (e) {
            e.preventDefault();
            $('.login-mobile').addClass('hidden');
            $('.reg_form__col').addClass('hidden');

            if ($(this).closest('.login-mobile').length) {
                $('.reg_form__col').removeClass('hidden');
            } else {
                $('.login-mobile').removeClass('hidden');
            }
        });


        /* Tabs pagination */
        $(function() {
            var order_tabs = $('.account__tab_content .content__tab_body');
            if ($(order_tabs).length) {
                $(order_tabs).each(function (key, item) {
                    var items_pagination = $(item).find('.simple_pagination');
                    var items = $(item).find('.content__tab_list > .content__block');

                    if (!$(items_pagination).length || !$(items).length) {
                        return;
                    }

                    pagination(items, items_pagination, 4);
                });
            }
        });


        /* Points pagination */
        $(function() {
            var points_list = $('.content__points_list');
            if (points_list.length) {
                $(points_list).each(function (key, point_list) {
                    var points_pagination = $(point_list).closest('.content__body_val').find('.points-pagination');
                    var items = $(point_list).find('.point-item');

                    if ($(points_pagination).length && $(items).length) {
                        pagination(items, points_pagination, 10);
                    }
                });
            }
        });

        function pagination(items, pagination, per_page = 4, options = {})
        {
            items.slice(per_page).hide();

            var data = $.extend({
                items         : $(items).length,
                itemsOnPage   : per_page,
                prevText      : "<",
                nextText      : ">",
                useAnchors    : false,
                displayedPages: 2,
                edges         : 1,
                onPageClick: function (page_number) {
                    var show_from = per_page * (page_number - 1);
                    var show_to = show_from + per_page;
                    items.hide().slice(show_from, show_to).show();
                }
            }, options);

            $(pagination).pagination(data);
        }

        var gift_button_visibility = $('.gift_body_visibility');
        if ($(gift_button_visibility).length) {
            $(document).on('click', '.gift_body_visibility', function () {
                var toggle_text = $(this).attr('data-toggle-text');
                var current_text = $(this).text();
                var visibility = $(this).attr('data-visibility');

                $(this).text(toggle_text);
                $(this).attr('data-toggle-text', current_text);
                $(this).closest('.content__block').toggleClass('active');

                if (visibility === 'hidden') {
                    $(this).closest('.content__block_body').find('.toggle-slide').slideDown();
                } else if (visibility === 'visible') {
                    $(this).closest('.content__block_body').find('.toggle-slide').slideUp();
                }

                $(this).attr('data-visibility', visibility === 'hidden' ? 'visible' : 'hidden');
            });
        }

        var add_gift_card_form = $('.add_gift_card');
        if ($(add_gift_card_form).length) {
            $(document).on('submit', '.add_gift_card', function (e) {
                e.preventDefault();
                var gift_code = $(this).find('input#gift_card_code').val();

                if (!gift_code) {
                    return;
                }

                var form = $(this);
                var wrap = $(this).closest('.content__block');
                var error_message = $(wrap).find('.form_error_message');

                jQuery.ajax({
                    type      : 'POST',
                    url       : ajax,
                    data      : {
                        action   : 'add_gift_card',
                        nonce    : ajaxmyaccount.nonce,
                        gift_code: gift_code
                    },
                    beforeSend: function () {
                        $(wrap).addClass('_spinner');
                    },
                    success   : function (response) {
                        if (response.success) {
                            $(form).trigger('reset');
                            $(error_message).html('');
                        }

                        if (response.html) {
                            $(wrap).closest('.account__tab_content').html(response.html);
                            $(error_message).html('');
                        }

                        if (response.error) {
                            if (response.message) {
                                $(error_message).html(response.message);
                            }
                        }

                        $(wrap).removeClass('_spinner');
                    }
                });
            });
        }

        var apply_gift_card = $('.apply_gift_card');
        if ($(apply_gift_card).length) {
            $(document).on('click', '.apply_gift_card', function (e) {
                var gift_code = $(this).attr('data-number');

                if (!gift_code) {
                    return;
                }

                var wrap = $(this).closest('.content__block');

                jQuery.ajax({
                    type      : 'POST',
                    url       : ajax,
                    data      : {
                        action   : 'apply_gift_card',
                        nonce    : ajaxmyaccount.nonce,
                        gift_code: gift_code
                    },
                    beforeSend: function () {
                        $(wrap).addClass('_spinner');
                    },
                    success   : function (response) {
                        if (response.html) {
                            $(wrap).closest('.account__tab_content').html(response.html);
                        }

                        $(wrap).removeClass('_spinner');
                    }
                })
            });
        }

    });
})(jQuery);