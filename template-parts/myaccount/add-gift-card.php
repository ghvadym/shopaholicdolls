<div class="content__block">
    <div class="content__head">
        <div class="content__head_title">
            <?php echo get_local_img_html('myaccount/gift.svg', 'content__head_img', __('Gift card', DOMAIN)); ?>
            <?php _e('NEW eGIFT CARD'); ?>
        </div>

        <div class="content__head_actions">
            <div class="content__head_action active" data-action="add" style="display:block;">
                <div class="content__head_action_row">
                    <?php echo get_local_img_html('myaccount/add.svg', 'content__head_img', __('Add gift card', DOMAIN)); ?>
                    <span>
                        <?php esc_html_e('Add', DOMAIN); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="content__body">
        <div class="content__body_edit">
            <p>
                <?php _e('Gift Card code should be at your email.', DOMAIN); ?>
            </p>
            <form class="add_gift_card">

                <div class="content__body_fields">
                    <div class="form-row">
                        <label for="gift_card_code">
                            <?php _e('Gift card number', DOMAIN); ?>
                        </label>
                        <input type="text" id="gift_card_code" name="gift_card_code" required>
                    </div>
                </div>
                <div class="form-row-small form_error_message"></div>

                <?php get_template_part_var('myaccount/buttons-group'); ?>
            </form>
        </div>
    </div>
</div>
