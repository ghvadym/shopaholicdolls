(function(e){e(document).ready(function(){var l=wopajax.ajaxurl,c=e(window).width()>1024;if(c?e("body").addClass("desktop-version"):e("body").addClass("mobile-version"),e(".currency-switcher .lang_switcher__item, .wmc-currency-redirect").click(function(){e(this).hasClass("wmc-currency-redirect")?e(".currency--wrapper").addClass("_spinner"):e(".currency-switcher").addClass("_spinner")}),!c){var v=e("body.home #header");if(v.length){var i=v.outerHeight();e("body.home .main").css("margin-top",i+"px"),e(".header_modal").css({top:i+4+"px",height:`calc(100vh - ${i+4}px)`}),e(".header_modal__bg").css("top",i+"px"),e(".header_modal__close").css("top",i+14+"px")}var g=e("body.single-product #header");if(g.length){var u=g.outerHeight()+8;e("body.single-product .main").css("margin-top",u+"px"),e(".header_modal").css({top:i+4+"px",height:`calc(100vh - ${i+4}px)`}),e(".header_modal__bg").css("top",u+"px"),e(".header_modal__close").css("top",u+14+"px")}}e(".add_product_to_cart").length&&e(document).on("click",".add_product_to_cart",function(){var t=e(this).attr("data-id"),a=e(this);e.ajax({type:"POST",url:l,data:{action:"add_product_to_cart",product_id:t,nonce:wopajax.nonce},beforeSend:function(){e(a).addClass("_spinner")},success:function(n){n.html&&(e(".header_cart").html(n.html),e(a).hasClass("cart_page")&&e(document.body).trigger("wc_update_cart")),e(a).removeClass("_spinner")},error:function(n){console.log("error",n)}})}),c&&(e(".nav__tab:first-child").addClass("tab-active"),e(".nav__contents .nav__content:first-child").addClass("tab-active")),e(document).on("click",".nav__tab",function(){var t=e(this);if(c){e(".nav__tab").removeClass("tab-active"),e(t).addClass("tab-active"),e(".nav__contents > .nav__content").removeClass("tab-active");var a=e(t).data("tab");e(`.nav__contents > .nav__content[data-tab=${a}]`).addClass("tab-active")}else e(t).toggleClass("tab-active"),e(t).find(".nav__content").slideToggle()});var b=e("#header");e(window).scroll(function(){e(this).scrollTop()>400?b.addClass("_scrolled"):b.removeClass("_scrolled")}),e(document).on("change",'.mega_menu__letter input[name="alphabet-letter"]',function(){var t=e(".mega_menu__most_wanted_brands"),a=e(".mega_menu__brand_filter_results");jQuery.ajax({type:"POST",url:l,data:{letter:e(this).attr("data-value"),action:"header_menu_brands_filter"},beforeSend:function(){e(t).hasClass("d-none")?e(a).addClass("_spinner"):e(t).addClass("_spinner")},success:function(n){n&&(n.message&&console.log(n.message),n.html&&e(t).html(n.html)),e(a).removeClass("_spinner d-none"),e(t).removeClass("_spinner")},error:function(n){console.log("error",n)}})}),e(document).on("click",".header__main_mobile_row .header__categories, .header_modal__close, .header__burger_icon",function(){var t=e(".header_modal");t.length&&(e(t).toggleClass("active-menu"),e(t).hasClass("active-menu")?e("body").addClass("active-menu"):e("body").removeClass("active-menu"))}),e(document).on("click",".header_modal__bg",function(){e(".header_modal, body").removeClass("active-menu")});var y=0,C=0;e(document).on("touchstart",function(t){y=t.touches[0].clientX}),e(document).on("touchend",function(t){C=t.changedTouches[0].clientX,R()});function R(){var t=e(".header_modal");t.length&&y-C>50&&(t.removeClass("active-menu"),e("body").removeClass("active-menu"))}e(document).on("click",".header_modal__content .has-mega-menu > a",function(t){e(t.target).hasClass("header_modal__view-more")||(t.preventDefault(),e(this).closest(".has-mega-menu").children(".sub-menu").slideToggle(),e(this).closest(".has-mega-menu").toggleClass("menu-item-active"))}),e(document).on("click",".header__top_mobile .header__lang_switcher",function(t){e(this).toggleClass("drop-down-active")}),e(document).on("click",function(t){var a=e(t.target),n=e(".header__top_mobile .header__lang_switcher");!a.closest(n).length&&e(n).is(":visible")&&e(n).removeClass("drop-down-active")});var m=c?".header__top_desktop .search_input":".header__top_mobile .search_input",d=e(m),h=e(c?".header__top_desktop .header__top":".header__top_mobile .header__top"),A=c?".header__top_desktop .search_submit":".header__top_mobile .search_submit";if(h.length&&d.length){let t=function(){e(d).blur(),a(e(d))},a=function(n){if(e(n).val()){var o=e(n).closest(".header__search_wrapper"),r=e(o).find(".header__search_results");e(r).empty().addClass("d-none"),jQuery.ajax({type:"POST",url:l,data:{search:e(n).val(),action:"header_search_results"},beforeSend:function(){e(o).addClass("_spinner")},success:function(s){s&&(s.message&&console.log(s.message),s.error&&s.message?(e(r).removeClass("d-none"),e(r).html('<div class="search_results__error">'+s.message+"</div>")):s.html?e(r).html(s.html).removeClass("d-none"):e(r).addClass("d-none")),e(o).removeClass("_spinner")},error:function(s){console.log("error",s)}})}};var Z=t,G=a;e(document).on("click",".header__search_icon, .search_clear",function(){e(h).addClass("search-active")}),e(document).on("click",".search_clear",function(){e(h).removeClass("search-active"),e(d).val(""),e(".header__search_results").empty().addClass("d-none")}),e(document).on("click",A,function(){e(d).val()&&a(e(d))}),e(document).on("paste",m,function(){a(e(this))}),e(document).on("keypress",m,function(n){n.which===13&&a(e(this).val())});var f,I=1e3;e(document).on("keyup",m,function(n){n.keyCode===13?t():(clearTimeout(f),f=setTimeout(t,I))}),e(document).on("keydown",m,function(){clearTimeout(f)})}if(wopajax.my_account){var w=document.querySelectorAll(".billing_country select, .shipping_country select");w.length&&w.forEach(t=>{const a={fields:["address_components","geometry","icon","name"],types:["establishment"],componentRestrictions:{country:t.value}};var n=t.parentElement.parentElement.parentElement.querySelector('input[id*="_address_1"]');if(n){var o=new google.maps.places.Autocomplete(n,a);t.addEventListener("change",function(){o.setOptions({componentRestrictions:{country:this.value}})})}})}if(wopajax.checkout){var x=e("#billing_country"),Q=e("#billing_address_1");if(e(x).length&&e(Q).length){const t={fields:["address_components","geometry","icon","name"],types:["establishment"],componentRestrictions:{country:e(x).val()}};var B=document.getElementById("billing_address_1"),F=new google.maps.places.Autocomplete(B,t);e(document.body).on("change","select[name=billing_country]",function(){F.setOptions({componentRestrictions:{country:e(this).val()}})})}var k=e("#shipping_country"),H=e("#shipping_address_1");if(e(k).length&&e(H).length){const t={fields:["address_components","geometry","icon","name"],types:["establishment"],componentRestrictions:{country:e(k).val()}};var X=document.getElementById("shipping_address_1"),z=new google.maps.places.Autocomplete(X,t);e(document.body).on("change","select[name=shipping_country]",function(){z.setOptions({componentRestrictions:{country:e(this).val()}})})}}var _=e(".custom_select");if(_.length){var L=_.find(".custom_select__head");L.on("click",function(t){_.toggleClass("select-active")}),e(document).on("click",function(t){var a=e(t.target);!a.closest(_).length&&_.is(":visible")&&_.removeClass("select-active")})}e("textarea.text_field_max_length").each(function(){T(e(this))}),e(".form-row input, .form-row textarea").each(function(){e(this).val()&&e(this).closest(".form-row").addClass("active-input")}),e(document).on("click",function(t){var a=e(t.target);e(".form-row input, .form-row textarea").each(function(n,o){!a.closest(o).length&&!e(o).val()&&e(o).closest(".form-row").removeClass("active-input")})}),e(document).on("change click focus",".form-row input, .form-row textarea",function(t){e(this).attr("type")==="checkbox"||e(this).attr("type")==="radio"||(e(this).val()||e(this).closest(".form-row").removeClass("active-input"),e(this).closest(".form-row").addClass("active-input"))}),e(document).on("keyup","textarea.text_field_max_length",function(t){T(e(this))});function T(t){if(!(!t||!e(t).length)){var a=e(t).attr("maxlength"),n=e(t).val().length,o=a-n;e(t).closest(".form-row").find(".characters_remain__amount").html(o)}}function U(t,a){var n={min_length:t.val().length>=8,has_upper_case:/[A-Z]/.test(t.val()),has_lower_case:/[a-z]/.test(t.val()),has_digit_or_symbol:/[0-9!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(t.val())};e.each(n,function(o,r){r?a.find("."+o+"_span").addClass("active"):a.find("."+o+"_span").removeClass("active")})}var p=e(".password-requirements"),j=e("form.woocommerce-form");e(p).length&&e(j).length&&e(p).on("keyup change",function(){U(p,j)}),e(".add_item_to_wishlist").on("click",function(){var t=e(this).attr("data-id"),a=e(this),n=a.hasClass("favorite")?"remove":"add";e.ajax({type:"POST",url:l,data:{action:"manage_wishlist",post_ID:t,wishlist_action:n},beforeSend:function(){a.addClass("_spinner")},success:function(o){o.success&&(o.data.in_favorites?a.addClass("favorite"):a.removeClass("favorite"),a.removeClass("_spinner"),!o.data.in_favorites&&e("body").hasClass("page-template-whishlist")&&location.reload())},error:function(){a.removeClass("_spinner")}})});var S=e(".copy_to_clipboard");S.length&&e(S).on("click",function(){D(e(this));var t=e(this).find(".copy_to_clipboard__icon_copy"),a=e(this).find(".copy_to_clipboard__icon_check");t.length&&a.length&&(t.addClass("d-none"),a.removeClass("d-none"),setTimeout(function(){t.removeClass("d-none"),a.addClass("d-none")},2e3))});let O=e(".product-action__copy-item-url");const q=e(".product-action__copy-url-info");O.length&&e(O).on("click",function(){q.removeClass("copy-url-info-hidden"),D(e(this)),setTimeout(function(){q.addClass("copy-url-info-hidden")},2e3)});function D(t){var a=e("<input>");e("body").append(a),a.val(e(t).attr("data-copy")).select(),document.execCommand("copy"),a.remove()}e(document).on("submit",".newsletter__form",function(t){t.preventDefault();var a=e(this),n=e(this).find("input[name=user-email]"),o=e(this).find(".form-row-small.error"),r=e(this).find(".form_success_message");e(n).val()||e(o).html(wopajax.required_field),e(o).html(""),e.ajax({type:"POST",url:l,data:{action:"newsletter_subscribe",nonce:wopajax.nonce,email:e(n).val()},beforeSend:function(){e(a).addClass("_spinner")},success:function(s){s.success&&(e(o).html(""),e(n).val(""),s.message&&e(r).html(s.message),setTimeout(function(){e(r).html("")},3e3)),s.error&&s.message&&(e(o).html(s.message),e(r).html("")),e(a).removeClass("_spinner")},error:function(s){console.log(s)}})});var E=e(".remember_place");E.length&&e(E).on("click",function(){var t=e(this).attr("data-url"),a=e(this).attr("data-id");t&&P("remember_place",t),a&&P("add_product_id",a)});function P(t,a,n=1){if(!t||!a)return;const o=new Date;o.setTime(o.getTime()+n*60*60*1e3);let r="expires="+o.toUTCString();document.cookie=t+"="+a+";"+r+";path=/"}e("form.woocommerce-form-register").length&&e(document).on("submit","form.woocommerce-form-register",function(t){t.preventDefault();var a=e(this),n=new FormData(e(this)[0]);n.append("action","customer_register"),n.append("nonce",wopajax.nonce),e.ajax({type:"POST",url:l,data:n,processData:!1,contentType:!1,dataType:"json",beforeSend:function(){e(a).addClass("_spinner")},success:function(o){o.error?o.show&&e(a).find(".form_error_message").text(o.error):e(a).find(".form_error_message").empty(),o.redirect&&(window.location.href=o.redirect),e(a).removeClass("_spinner")},error:function(o){console.log("error",o)}})})})})(jQuery);
