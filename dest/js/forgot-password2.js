(function(r){r(document).ready(function(){var n={init:function(){r(document.body).on("keyup change","form.checkout #account_password, form.lost_reset_password #password_1, form.lost_reset_password #password_2",this.strengthMeter),r("form.checkout #createaccount").trigger("change")},strengthMeter:function(){var s=r("form.lost_reset_password"),o=r("#password_1",s),e=r("#password_2",s);n.includeMeter(s,o),n.checkPasswordStrength(s,o),n.checkPasswordMatch(o,e)},includeMeter:function(s,o){var e=s.find(".woocommerce-password-strength"),t=s.find(".password-strength-bar"),d=s.find(".password-strength-indicator"),a=s.find(".woocommerce-form__notice");o.val()===""?(e.hide(),d.hide(),a.hide(),r(document.body).trigger("wc-password-strength-hide")):e.length===0?(t=s.find(".password-strength-bar"),d.show(),a.show(),r(document.body).trigger("wc-password-strength-added")):(e.show(),d.show(),a.show(),r(document.body).trigger("wc-password-strength-show")),t.css("visibility","visible"),a.css("display","flex")},checkPasswordStrength:function(s,o){var e=s.find(".woocommerce-password-strength"),t=s.find(".password-strength-bar"),d=wp.passwordStrength.meter(o.val(),wp.passwordStrength.userInputDisallowedList());if(e.is(":hidden"))return d;t.removeClass("password-strength-bar-weak password-strength-bar-medium password-strength-bar-strong");let a=s.closest("form").find('button[type="submit"]');return d<=1?(t.eq(0).addClass("password-strength-bar-weak"),e.text("Weak password - a puppy would break it"),a.prop("disabled",!0)):d<=3?(t.eq(0).addClass("password-strength-bar-medium"),t.eq(1).addClass("password-strength-bar-medium"),e.text("Good password - a puppy needs more time"),a.prop("disabled",!1)):(t.eq(0).addClass("password-strength-bar-strong"),t.eq(1).addClass("password-strength-bar-strong"),t.eq(2).addClass("password-strength-bar-strong"),e.text("Excellent password - a puppy is proud of you"),a.prop("disabled",!1)),d},checkPasswordMatch:function(s,o){let e=s.closest("form").find('button[type="submit"]');s.val()!==o.val()?e.prop("disabled",!0):e.prop("disabled",!1)}};n.init()})})(jQuery);
