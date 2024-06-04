(function($){
    $(document).ready(function() {
        var product_cat_slug = archivesetting.cat_slug;
        var screen_width = $(window).width();

        if (screen_width <= 1024) {
            var isCheckboxChecked = $('.input-checkbox:checked').length > 0;
            var isPriceRangeFilled = $('#from').val().trim() !== '' || $('#to').val().trim() !== '';
            var hasParamsInUrl = window.location.search !== '';
        
            toggleButtons();
        
            $('.input-checkbox').change(function() {
                isCheckboxChecked = $('.input-checkbox:checked').length > 0;
                toggleButtons();
            });
        
            $('#from, #to').keyup(function() {
                isPriceRangeFilled = $('#from').val().trim() !== '' || $('#to').val().trim() !== '';
                toggleButtons();
            });
        
            function toggleButtons() {
                if (isCheckboxChecked || isPriceRangeFilled || hasParamsInUrl) {
                    $('.close_filters').hide();
                    $('.clear_filters, .apply_filters').show();
                } else {
                    $('.clear_filters, .apply_filters').hide();
                    $('.close_filters').show();
                }
            }
        }
        $('.checkbox_item.active').each(function() {       
            if ($(this).is(':last-child') && $(this).hasClass('active')) {
                var subcategories = $(this).find('.subcategories');
                var last_subcategory = subcategories.last();
                var subcategory_height = last_subcategory.outerHeight(true); 
                subcategories.parents('.subcategories').css('--before-height', 'calc(100% - ' + subcategory_height + 'px)');
            }
        });

        if (screen_width <= 1024) {
            $(document).ready(function() {
                function setElementHeight() {
                    var element = $('.filter');
                    element.css('height', window.innerHeight + 'px');
                }
                setElementHeight();
                window.onresize = function() {
                    setElementHeight();
                };
            });

            $(document).on('click', '.mob-title, .mob-filter__close', function() {
                var filter_menu = $('.filter');
                if (filter_menu.length) {
                    $(filter_menu).toggleClass('active-filter');

                    if ($(filter_menu).hasClass('active-filter')) {
                        $('body').addClass('active-filter');
                    } else {
                        $('body').removeClass('active-filter');
                    }
                }
            });

            $(document).on('click', '.mob-filter__bg , .close_filters', function() {
                $('.filter, body').removeClass('active-filter');
            });

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
                var filter_menu = $('.filter');
                if (filter_menu.length) {
                    if (touch_start - touch_end > 50) {
                        filter_menu.removeClass('active-filter');
                        $('body').removeClass('active-filter');
                    }
                }
            }
        }

        $(document).on('click', '#clear_all', function () {
            window.location.href = window.location.origin + window.location.pathname;
        });

        var filter_apply = $('.apply_filters');

        filter_apply.on('click', function () {
            console.log(123);
            var from = $('#from').val();
            var to = $('#to').val();
            var selected_categories = [];
            $('.input-checkbox:checked').each(function () {
                selected_categories.push($(this).attr('id'));
            });
            var current_url = window.location.href;
            var product_categories_regex = new RegExp(`\/${product_cat_slug}\/([^\/?]*)`);
            var categories_match = current_url.match(product_categories_regex);
            var top_level_category = categories_match ? categories_match[1] : '';
            var url_parts = current_url.split('?');
            var query_params = url_parts.length > 1 ? url_parts[1] : '';
            var params = new URLSearchParams(query_params);
            params.set('categories', selected_categories.join(','));
            if (from) {
                params.set('from', encodeURIComponent(from));
            }
            if (to) {
                params.set('to', encodeURIComponent(to));
            }
            window.location.href = `/${product_cat_slug}/` + top_level_category + '?' + params.toString();
        });

        $('.range-inputs input').keyup(function (e) {
            if (/\D/g.test(this.value)) {
                this.value = this.value.replace(/\D/g, '');
            }
        });
    });
})(jQuery);
