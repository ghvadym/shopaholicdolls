(function ($) {
    $(document).ready(function () {
        var wc_password_strength_meter = {
            init: function () {
                $(document.body)
                    .on(
                        'keyup change',
                        'form.checkout #account_password, ' +
                        'form.lost_reset_password #password_1, form.lost_reset_password #password_2',
                        this.strengthMeter
                    );
                $('form.checkout #createaccount').trigger('change');
            },

            strengthMeter: function () {
                var wrapper = $('form.lost_reset_password'),
                    field1 = $('#password_1', wrapper),
                    field2 = $('#password_2', wrapper),
                    strength = 1;

                wc_password_strength_meter.includeMeter(wrapper, field1);
                strength = wc_password_strength_meter.checkPasswordStrength(wrapper, field1);

                wc_password_strength_meter.checkPasswordMatch(field1, field2);
            },

            includeMeter: function (wrapper, field) {
                var meter = wrapper.find('.woocommerce-password-strength'),
                    bars = wrapper.find('.password-strength-bar'),
                    indicator = wrapper.find('.password-strength-indicator'),
                    notice = wrapper.find('.woocommerce-form__notice');

                if ('' === field.val()) {
                    meter.hide();
                    indicator.hide();
                    notice.hide();
                    $(document.body).trigger('wc-password-strength-hide');
                } else if (0 === meter.length) {
                    bars = wrapper.find('.password-strength-bar');
                    indicator.show();
                    notice.show();
                    $(document.body).trigger('wc-password-strength-added');
                } else {
                    meter.show();
                    indicator.show();
                    notice.show();
                    $(document.body).trigger('wc-password-strength-show');
                }

                bars.css('visibility', 'visible');
                notice.css('display', 'flex');
            },

            checkPasswordStrength: function (wrapper, field) {
                var meter = wrapper.find('.woocommerce-password-strength'),
                    bars = wrapper.find('.password-strength-bar'),
                    strength = wp.passwordStrength.meter(field.val(), wp.passwordStrength.userInputDisallowedList());

                if (meter.is(':hidden')) {
                    return strength;
                }

                bars.removeClass('password-strength-bar-weak password-strength-bar-medium password-strength-bar-strong');
                let submitButton = wrapper.closest('form').find('button[type="submit"]');

                if (strength <= 1) {
                    bars.eq(0).addClass('password-strength-bar-weak');
                    meter.text('Weak password - a puppy would break it');
                    submitButton.prop('disabled', true);
                } else if (strength <= 3) {
                    bars.eq(0).addClass('password-strength-bar-medium');
                    bars.eq(1).addClass('password-strength-bar-medium');
                    meter.text('Good password - a puppy needs more time');
                    submitButton.prop('disabled', false);
                } else {
                    bars.eq(0).addClass('password-strength-bar-strong');
                    bars.eq(1).addClass('password-strength-bar-strong');
                    bars.eq(2).addClass('password-strength-bar-strong');
                    meter.text('Excellent password - a puppy is proud of you');
                    submitButton.prop('disabled', false);
                }

                return strength;
            },

            checkPasswordMatch: function (field1, field2) {
                let submitButton = field1.closest('form').find('button[type="submit"]');

                if (field1.val() !== field2.val()) {
                    submitButton.prop('disabled', true);
                } else {
                    submitButton.prop('disabled', false);
                }
            }
        };

        wc_password_strength_meter.init();
    });
})(jQuery);
