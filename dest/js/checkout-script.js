(function(e){e(document).ready(function(){var n=ajaxcheckout.ajaxurl,c=e(window).width()>1024;e(document).on("click",'button[type="submit"]',function(){e(".validate-required input, .validate-required select").trigger("validate")}),e(document.body).on("updated_checkout",function(){i()}),e(document).on("change","#gift_option_wrap",function(){var t=e(this).is(":checked"),a=e("#gift_message");t||(e(a).val(""),e("#gift_option_message").prop("checked",!1)),e(".gift_option_message_wrap").toggleClass("d-none"),i({gift_wrap:e(this).is(":checked"),gift_message:e(a).val()})}),e(document).on("click",".gift_message_apply",function(){var t=e("#gift_message").val();t||e("#gift_option_message").prop("checked",!1),i({gift_message:t})}),e(document).on("change","#gift_option_message",function(){var t="";e(this).is(":checked")?t=e("#gift_message").val():t="",i({gift_message:t})}),e(document).on("click",".checkout__auth_tab",function(){e(".checkout__auth_tab, .checkout__auth_form").removeClass("tab-active"),e(this).addClass("tab-active");var t=e(this).data("tab");e(`.checkout__auth_form[data-tab="${t}"]`).addClass("tab-active")}),e(document).on("click",".checkout__auth_guest",function(){e(".checkout__auth").addClass("d-none"),e(".checkout__fields").removeClass("d-none"),c||e(".checkout__summary").removeClass("d-none"),_({customer_hide_login:1})}),e(document).on("click",".show_login_form",function(){e(".checkout__auth").removeClass("d-none"),e(".checkout__fields").addClass("d-none"),c||e(".checkout__summary").addClass("d-none"),e("html, body").animate({scrollTop:e(".checkout__auth").offset().top},"slow"),_({customer_hide_login:0})}),c||e(document).on("click",".order_summary__head",function(){var t=0;e(".order_summary").hasClass("summary_opened")?(setTimeout(function(){e(".order_summary__note").stop().slideDown(500)},300),e(".order_summary__body").stop().slideUp(300),e(this).closest(".order_summary").toggleClass("summary_opened")):(setTimeout(function(){e(".order_summary__body").stop().slideDown(600)},300),e(".order_summary__note").stop().slideUp(300),e(this).closest(".order_summary").toggleClass("summary_opened"),t=1),_({summary_opened:t})});function _(t={}){var a=e.extend({action:"update_order_session"},t);jQuery.ajax({type:"POST",url:n,data:a,success:function(o){o&&o.message&&console.log(o.message)},error:function(o){console.log("error",o)}})}function i(t={}){var a=e(".order_summary"),o=e.extend({action:"checkout_update_totals"},t),r="gift_message"in t;jQuery.ajax({type:"POST",url:n,data:o,beforeSend:function(){e(a).addClass("_spinner"),r&&e(".gift_option_message_wrap").addClass("_spinner")},success:function(s){s&&(s.message&&console.log(s.message),s.html&&e(a).html(s.html),u()),e(a).removeClass("_spinner"),r&&e(".gift_option_message_wrap").removeClass("_spinner")},error:function(s){console.log("error",s)}})}function u(){e.ajax({type:"POST",url:n,data:{action:"update_mini_cart_items_html"},success:function(t){t.html&&e(".header_cart").html(t.html)},error:function(t){console.log("error",t)}})}})})(jQuery);