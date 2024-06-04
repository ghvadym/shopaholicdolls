document.addEventListener('DOMContentLoaded', function() {
    var is_desktop = window.innerWidth > 1024;

    if (!is_desktop) {
        var archive_select = document.querySelector('.archive-head__select');
        if (archive_select) {
            archive_select.addEventListener('click', function () {
                this.classList.toggle('select-active');
            });
        }
    }

    if (!is_desktop) {
        /* Registration hero slider for mobile version in home page */
        var hero_slider = new Swiper('.hero-slider', {
            pagination: {
                el: '.hero-slider-pagination',
                clickable: true,
            },
        });
    }

    /* Registration categories slider for mobile version in home page */
    var cat_slider = new Swiper('.cat_slider__list', {
        slidesPerView: 'auto',
        spaceBetween: 16,
    });

    /* Registration sale products slider for mobile version in home page */
    var sale_products_slider = new Swiper('.sale_slider__products', {
        slidesPerView: 'auto'
    });

    /* Registration products slider for mobile version in home page */
    var products_slider = new Swiper('.product_list .post_cards__list', {
        slidesPerView: 'auto'
    });

    /* Registration products slider for mobile version in home page */
    var recent_viewed_products_slider = new Swiper('.recently_viewed__list', {
        slidesPerView: 'auto',
        navigation: {
            nextEl: '.recently_viewed__btn_next',
            prevEl: '.recently_viewed__btn_prev'
        }
    });

    /* Products upsells list slider */
    var products_up_cross_slider = new Swiper('.products-up-sell__list', {
        slidesPerView: 'auto',
        navigation: {
            nextEl: '.products-up-sell__btn_next',
            prevEl: '.products-up-sell__btn_prev'
        }
    });

    var radioButtons = document.querySelectorAll('.cart-sidebar__samples--radio input[type="radio"]');
    
    
    radioButtons.forEach(function (radioButton) {
        radioButton.addEventListener('change', function () {
        var item = this.closest('.cart-sidebar__samples--item');

        radioButtons.forEach(function (otherRadioButton) {
            var otherItem = otherRadioButton.closest('.cart-sidebar__samples--item');
            if (otherItem !== item) {
            otherItem.classList.remove('active');
            otherRadioButton.checked = false;
            }
        });
        
        if (this.checked) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
        });
    });
      
});