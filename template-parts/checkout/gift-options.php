<?php
$gift_wrap_product_id = get_field('gift_wrap', 'options');
if ($gift_wrap_product_id) {
    $gift_product = wc_get_product($gift_wrap_product_id);
    $gift_wrap_price = $gift_product->get_price();
}

if (empty($gift_wrap_price)) {
    return;
}

$is_gift_wrap_in_cart = is_product_in_cart($gift_wrap_product_id);
$gift_message = WC()->session->get('gift_message') ?: '';
?>

<div class="gift_options__heading">
    <?php _e('Add gift options', DOMAIN); ?>:
</div>
<ul class="gift_options__list">
    <li class="gift_option__item">
        <input type="checkbox" name="gift_option_wrap" id="gift_option_wrap" class="gift_option" <?php checked(true, !empty($is_gift_wrap_in_cart)); ?>>
        <label for="gift_option_wrap">
            <?php echo wc_price($gift_wrap_price); ?>
            <?php _e('Wrap & send as a gift', DOMAIN); ?>
            <img src="<?php echo get_template_directory_uri() . '/dest/img/gift.svg'; ?>"
                 alt="<?php _e('Gift wrap image', DOMAIN); ?>"
                 title="<?php _e('Gift wrap image', DOMAIN); ?>"
                 class="gift_option__img">
        </label>
    </li>
    <li class="gift_option__item gift_option_message_wrap<?php echo empty($is_gift_wrap_in_cart) ? ' d-none' : ''; ?>">
        <input type="checkbox" name="gift_option_message" id="gift_option_message" class="gift_option" <?php checked(true, !empty($is_gift_wrap_in_cart) && !empty($gift_message)); ?>>
        <label for="gift_option_message">
            <span class="amount">
                <?php _e('Free', DOMAIN); ?>
            </span>
            <?php _e('Write a gift message', DOMAIN); ?>
            <img src="<?php echo get_template_directory_uri() . '/dest/img/messages.svg'; ?>"
                 alt="<?php _e('Gift message image', DOMAIN); ?>"
                 title="<?php _e('Gift message image', DOMAIN); ?>"
                 class="gift_option__img">
        </label>
        <p class="form-row">
            <label for="gift_message">
                <?php _e('Your message', DOMAIN); ?>
            </label>
            <?php $max_length = get_field('free_gift_note_max_length', 'options') ?: 300; ?>
            <textarea name="gift_message" id="gift_message" class="text_field_max_length" maxlength="<?php echo $max_length; ?>"><?php echo trim(esc_textarea($gift_message)); ?></textarea>
            <span class="characters_remain form-row-small">
                <span class="characters_remain__amount">
                    <?php echo $max_length; ?>
                </span>
                <?php esc_html_e('characters left', DOMAIN); ?>
            </span>
            <span class="btn-transparent gift_message_apply">
                <?php _e('Confirm', DOMAIN); ?>
            </span>
        </p>
    </li>
</ul>