(function(t){t(document).ready(function(){var c=ajaxmyaccount.ajaxurl;t(window).width()>1024,t(document).on("click",".content__head_action",function(){var e=t(this).attr("data-action");if(e){var n=t(this).closest(".content__block"),o=t(n).find("input"),a=t(n).find('.content__head_action[data-action="remove"]'),i=t(n).find('.content__head_action[data-action="add"]'),d=t(n).find('.content__head_action[data-action="edit"]'),_=t(n).find('.content__head_action[data-action="default"]');t(n).addClass("active"),t(n).find(".content__body").slideDown(),e==="edit"||e==="add"?(t(n).find(".content__body_val").hide(),t(n).find(".content__body_edit").show(),t(d).hide(),t(i).hide(),t(o).val()&&t(a).show()):e==="remove"?(t(n).find(".content__body_val").hide(),t(n).find(".content__body_edit").show(),t(n).find(".content__body_fields").hide(),t(n).find(".content__body_remove").show(),t(n).find(".content__body_default").hide(),t(n).find('input[name="rm_fields"]').val("1"),t(_).hide(),t(a).hide()):e==="default"&&(t(n).find(".content__body_val").hide(),t(n).find(".content__body_edit").show(),t(n).find(".content__body_remove").hide(),t(n).find(".content__body_default").show(),t(_).hide(),t(a).hide())}}),t(document).on("click",".content__body_cancel",function(){var e=t(this).closest(".content__block"),n=t(e).find('.content__head_action[data-action="remove"]'),o=t(e).find('.content__head_action[data-action="add"]'),a=t(e).find('.content__head_action[data-action="edit"]'),i=t(e).attr("data-val")==="1";t(e).find(".content__body_val").show(),t(e).find(".content__body_edit").hide(),t(e).find(".content__body_fields").show(),t(e).find(".content__body_remove").hide(),t(e).find(".content__body_default").hide(),t(e).find('input[name="rm_fields"]').val(""),t(e).removeClass("active"),i?(t(a).show(),t(o).hide()):(t(a).hide(),t(o).show()),t(n).hide()}),t(document).on("click",".content__tab",function(){t(".content__tab, .content__tab_body").removeClass("tab-active"),t(this).addClass("tab-active"),t(`.content__tab_body[data-tab='${t(this).attr("data-tab")}']`).addClass("tab-active")}),t(document).on("click",".content__head",function(){var e=t(this).closest(".order_block");t(e).toggleClass("active"),t(e).find(".content__body").slideToggle()}),t(document).on("submit",".user_meta_form",function(e){e.preventDefault(),r(t(this))});function r(e){if(e){var n=t(e).closest(".content__block"),o=new FormData(t(e)[0]);o.append("action","update_user_data"),o.append("nonce",ajaxmyaccount.nonce),jQuery.ajax({type:"POST",url:c,data:o,dataType:"json",processData:!1,contentType:!1,beforeSend:function(){t(n).addClass("_spinner")},success:function(a){t(n).removeClass("_spinner"),a&&(a.message&&console.log(a.message),a.html&&t(n).closest(".account__tab_content").html(a.html))},error:function(a){console.log("error",a)}})}}t(document).on("click",".my_acc_change_pass",function(){t(".modal_window").addClass("modal_window__active")}),t(document).on("click",".modal_window__bg, .modal_window__close",function(){t(".modal_window").removeClass("modal_window__active")}),t(document).on("submit","form.change_user_pass",function(e){e.preventDefault(),l(t(this))});function l(e){if(e){var n=t(e).closest(".modal_window__body"),o=new FormData(t(e)[0]);o.append("action","change_user_pass"),o.append("nonce",ajaxmyaccount.nonce),jQuery.ajax({type:"POST",url:c,data:o,dataType:"json",processData:!1,contentType:!1,beforeSend:function(){t(n).addClass("_spinner")},success:function(a){a.error?a.message&&t(n).find(".form_error_message").html(a.message):t(n).find(".form_error_message").empty(),a.success&&(t(n).find(".form_error_message").empty(),t(e).trigger("reset"),t(".modal_window").removeClass("modal_window__active"),location.reload()),t(n).removeClass("_spinner")},error:function(a){console.log("error",a)}})}}t(document).on("click",".reorder_order",function(){var e=t(this);jQuery.ajax({type:"POST",url:c,data:{action:"reorder_order",nonce:ajaxmyaccount.nonce,order_id:t(e).attr("data-id")},beforeSend:function(){t(e).addClass("_spinner")},success:function(n){n.redirect&&(location.href=n.redirect),t(e).removeClass("_spinner")},error:function(n){console.log("error",n)}})}),t(".reg__form--wrapper .btn-plain").on("click",function(e){e.preventDefault(),t(this).fadeOut("fast",function(){t(".woocommerce-form-register").fadeIn("fast")})}),t(".form-switcher .btn-plain").on("click",function(e){e.preventDefault(),t(".login-mobile").addClass("hidden"),t(".reg_form__col").addClass("hidden"),t(this).closest(".login-mobile").length?t(".reg_form__col").removeClass("hidden"):t(".login-mobile").removeClass("hidden")}),t(function(){var e=t(".account__tab_content .content__tab_body");t(e).length&&t(e).each(function(n,o){var a=t(o).find(".simple_pagination"),i=t(o).find(".content__tab_list > .content__block");!t(a).length||!t(i).length||s(i,a,4)})}),t(function(){var e=t(".content__points_list");e.length&&t(e).each(function(n,o){var a=t(o).closest(".content__body_val").find(".points-pagination"),i=t(o).find(".point-item");t(a).length&&t(i).length&&s(i,a,10)})});function s(e,n,o=4,a={}){e.slice(o).hide();var i=t.extend({items:t(e).length,itemsOnPage:o,prevText:"<",nextText:">",useAnchors:!1,displayedPages:2,edges:1,onPageClick:function(d){var _=o*(d-1),m=_+o;e.hide().slice(_,m).show()}},a);t(n).pagination(i)}var f=t(".gift_body_visibility");t(f).length&&t(document).on("click",".gift_body_visibility",function(){var e=t(this).attr("data-toggle-text"),n=t(this).text(),o=t(this).attr("data-visibility");t(this).text(e),t(this).attr("data-toggle-text",n),t(this).closest(".content__block").toggleClass("active"),o==="hidden"?t(this).closest(".content__block_body").find(".toggle-slide").slideDown():o==="visible"&&t(this).closest(".content__block_body").find(".toggle-slide").slideUp(),t(this).attr("data-visibility",o==="hidden"?"visible":"hidden")});var u=t(".add_gift_card");t(u).length&&t(document).on("submit",".add_gift_card",function(e){e.preventDefault();var n=t(this).find("input#gift_card_code").val();if(n){var o=t(this),a=t(this).closest(".content__block"),i=t(a).find(".form_error_message");jQuery.ajax({type:"POST",url:c,data:{action:"add_gift_card",nonce:ajaxmyaccount.nonce,gift_code:n},beforeSend:function(){t(a).addClass("_spinner")},success:function(d){d.success&&(t(o).trigger("reset"),t(i).html("")),d.html&&(t(a).closest(".account__tab_content").html(d.html),t(i).html("")),d.error&&d.message&&t(i).html(d.message),t(a).removeClass("_spinner")}})}});var h=t(".apply_gift_card");t(h).length&&t(document).on("click",".apply_gift_card",function(e){var n=t(this).attr("data-number");if(n){var o=t(this).closest(".content__block");jQuery.ajax({type:"POST",url:c,data:{action:"apply_gift_card",nonce:ajaxmyaccount.nonce,gift_code:n},beforeSend:function(){t(o).addClass("_spinner")},success:function(a){a.html&&t(o).closest(".account__tab_content").html(a.html),t(o).removeClass("_spinner")}})}})})})(jQuery);
