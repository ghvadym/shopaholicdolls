(function ($) {
    $(document).ready(function () {
        const image_size_object = {};
        const is_desktop = $(window).width() >= 1025;


        /* Cross sell products slider for product page */
        const products_cross_slider = new Swiper('.products-cross__list', {
            slidesPerView: 'auto',
            navigation: {
                nextEl: '.products-cross__btn_next',
                prevEl: '.products-cross__btn_prev'
            }
        });

        /* Cross up sell products slider for product page */
        const products_up_cross_slider = new Swiper('.products-up-sell__list', {
            slidesPerView: 'auto',
            navigation: {
                nextEl: '.products-up-sell__btn_next',
                prevEl: '.products-up-sell__btn_prev'
            }
        });

        const product_slider = new Swiper('.products-up-sell__list', {
            slidesPerView: 'auto',
        });

        let single_slider = {};

        if (is_desktop) {
            const single_slider_nav = new Swiper('.product-thumb__nav', {
                slidesPerView: 4,
                freeMode: true,
                spaceBetween: 10
            });


            single_slider = new Swiper('.product-thumb__img', {
                zoom: true,
                toggle: false,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                    dynamicMainBullets: 5
                },
                thumbs: {
                    swiper: single_slider_nav,
                },
                breakpoints: {
                    "0": {
                        spaceBetween: 16,
                    },
                    "576": {
                        spaceBetween: 0,
                    },
                },
            });

            if (document.querySelector('.product-options__select')) {
                changeSlideVariation();
            }

            single_slider.on("slideChange", () => {
                single_slider_nav.slideTo(single_slider.activeIndex);
            });
        } else {
            single_slider = new Swiper('.product-thumb__img', {
                zoom: true,
                toggle: false,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                    dynamicMainBullets: 5
                },
                breakpoints: {
                    "0": {
                        spaceBetween: 16,
                    },
                    "576": {
                        spaceBetween: 0,
                    },
                },
            });

            if (document.querySelector('.product-options__select')) {
                changeSlideVariation();
            }
        }


        function changeSlideVariation () {
            const selectWrapper = document.querySelector('.product-options__select');
            const variationsSelects = selectWrapper.querySelectorAll('select');

            const variationForm = document.querySelector('.variations_form');
            const variationSlider = document.querySelector('.product-thumb__slider');

            variationsSelects.forEach(select => {
                select.addEventListener('change', getCurrentImage);
            });


            function getCurrentImage() {
                setTimeout(function() {
                    const currentImageAttribute = variationForm.getAttribute('current-image');
                    const variationSlides = variationSlider.querySelectorAll('.product-thumb__variation-slide');

                    let targetSlide = null;
                    for (const slide of variationSlides) {
                        const dataVariation = slide.dataset.variation;
                        if (dataVariation === currentImageAttribute) {
                            targetSlide = slide;
                            break;
                        }
                    }

                    if (targetSlide) {
                        const index = Array.from(variationSlider.children).indexOf(targetSlide);
                        single_slider.slideTo(index);
                    } else {
                        console.log('Slide with data-variation not found:', currentImageAttribute);
                    }
                }, 100);
            }
        }


        if (document.querySelector("#pwgc-custom-amount")) {
            function getGiftCardPrice() {
                const inputElement = document.querySelector("#pwgc-custom-amount");
                const outputDiv = document.querySelector(".gift-card__price-value");
                const errorMessage = document.querySelector("#pwgc-custom-amount-error");

                inputElement.addEventListener("input", function() {
                    let price = inputElement.value;

                    if  (price !== 0 && price !== '') {
                        outputDiv.textContent = price;
                    }
                });

                inputElement.addEventListener("blur", function() {
                    let error = errorMessage.innerText
                    let price = inputElement.value;

                    if  (price !== 0 && price !== '' && error === '') {
                        outputDiv.textContent = price;
                        inputElement.classList.remove('gift-cards__error');
                    } else if (error !== '') {
                        inputElement.classList.add('gift-cards__error');
                    }
                });
            }

            getGiftCardPrice();
        }


        /* Select default attributes */
        var default_attributes = $('.product-default-variation');
        if (default_attributes.length) {
            default_attributes.each(function () {
                var value = $(this).attr('data-value');
                var name = $(this).attr('data-name');

                if (!value || !name) {
                    return;
                }

                var select = $('select[name="' + name + '"]');

                if (!select.length) {
                    return;
                }

                $(select).val(value).trigger('change');

                setTimeout(function () {
                    const currentImageAttribute = $('.variations_form').attr('current-image');
                    const variationSlides = $('.product-thumb__variation-slide');

                    if (!currentImageAttribute || !variationSlides.length) {
                        return;
                    }

                    let targetSlide = null;

                    variationSlides.each(function ( index, element ) {
                        const dataVariation = $(this).attr('data-variation');
                        if (dataVariation === currentImageAttribute) {
                            targetSlide = element;
                            return false;
                        }
                    });

                    if (!targetSlide) {
                        return;
                    }

                    var slider_index = $(variationSlides).index(targetSlide) + 1;

                    single_slider.slideTo(slider_index);
                }, 1000 );
            });
        }

        $('.product-options__select select').each(function (index, element) {
            var options = $(element).find('option');
            if (options) {
                if (options.length === 2) {
                    $(element).find('option:last-child').prop('selected', true).trigger('change');
                    $(element).addClass('single-select');
                    $(element).prop('disabled', true);
                } else if (options.length === 1) {
                    $(element).closest('.product-options').hide();
                }
            }
        });
    });
})(jQuery);