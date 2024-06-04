<div class="modal_window">
    <div class="modal_window__bg"></div>
    <div class="modal_window__wrapper">
        <div class="modal_window__body">
            <div class="modal_window__content">
                <div class="modal_window__title">
                    <strong>
                        <?php _e('Change your password.', DOMAIN); ?>
                    </strong>
                    <p>
                        <?php _e('Old password is needed to confirm owner.', DOMAIN); ?>
                    </p>
                </div>
            </div>
            <div class="modal_window__form">
                <form class="change_user_pass woocommerce-form">
                    <div class="form-row">
                        <label for="old_password">
                            <?php _e('Old password', DOMAIN); ?>
                        </label>
                        <div class="password-input">
                            <input type="password" name="old_password" id="old_password" placeholder="<?php _e('Old password', DOMAIN); ?>" required>
                            <span class="show-password-input"></span>
                        </div>
                    </div>
                    <div class="content-divider"></div>
                    <div class="form-row">
                        <label for="new_password">
                            <?php _e('New password', DOMAIN); ?>
                        </label>
                        <div class="password-input">
                            <input type="password" class="password-requirements" name="new_password" id="new_password" placeholder="<?php _e('New password', DOMAIN); ?>" minlength="8" required>
                            <span class="show-password-input"></span>
                        </div>
                        <div class="form-row-small">
                            <span class="min_length_span"><?php _e('Min. 8 characters', DOMAIN); ?></span>,
                            <span class="has_upper_case_span"><?php _e('big letter', DOMAIN); ?></span>,
                            <span class="has_lower_case_span"><?php _e('small letter', DOMAIN); ?></span>,
                            <span class="has_digit_or_symbol_span"><?php _e('digit/symbol', DOMAIN); ?></span>
                        </div>
                    </div>
                    <?php wp_nonce_field('my-acc-change-pass', 'change-pass-nonce'); ?>
                    <div class="content__body_buttons">
                        <div class="content__body_btn modal_window__close btn-transparent">
                            <?php esc_html_e('Cancel', DOMAIN); ?>
                        </div>
                        <button class="content__body_btn btn">
                            <?php esc_html_e('Confirm', DOMAIN); ?>
                        </button>
                    </div>
                    <div class="form-row-small form_error_message"></div>
                </form>
            </div>
        </div>
    </div>
</div>