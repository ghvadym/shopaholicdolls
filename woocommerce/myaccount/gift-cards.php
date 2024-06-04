<?php

if (!is_plugin_active('pw-gift-cards/pw-gift-cards.php')) {
    return;
}

$tabs = [
    'all'    => esc_html__('All', DOMAIN),
    'used'   => esc_html__('Used', DOMAIN),
    'active' => esc_html__('Active', DOMAIN)
];
?>

<div class="account__tab_title">
    <?php _e('Gift cards', DOMAIN); ?>
</div>

<?php get_template_part_var('myaccount/add-gift-card');

$users_gift_cards = get_users_gift_cards();

if (empty($users_gift_cards)) {
    return;
}

$session_data = WC()->session->get(PWGC_SESSION_KEY);
$gift_cards_in_cart = $session_data['gift_cards'] ?? [];

if (get_option('pwgc_allow_gift_card_purchasing') !== 'yes') {
    $gift_card_id = get_gift_product();
    $is_gift_cards_unavailable = is_product_in_cart($gift_card_id);
    $gift_cards_unavailable_message = __('eGift card is on the cart', DOMAIN);
}

if (!WC()->cart->get_cart_contents_count()) {
    $is_gift_cards_unavailable = true;
    $gift_cards_unavailable_message = __('Cart is empty', DOMAIN);
}
?>

<div class="content__tabs">
    <div class="content__tabs_body">
        <div class="content__tabs_list">
            <?php foreach ($tabs as $key => $title): ?>
                <div class="content__tab<?php echo $key === 'all' ? ' tab-active' : ''; ?>"
                     data-tab="<?php echo $key; ?>">
                    <div class="content__tab_title">
                        <?php echo $title; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php foreach ($tabs as $key => $title):
    $gift_items = 0; ?>
    <div class="content__tab_body<?php echo $key === 'all' ? ' tab-active' : ''; ?>"
         data-tab="<?php echo $key; ?>">
        <div class="content__tab_list">
            <?php foreach ($users_gift_cards as $users_gift_card):
                $gift_card = new PW_Gift_Card($users_gift_card->number);

                if (empty($gift_card) || !$gift_card->get_active()) {
                    continue;
                }

                $gift_id = $gift_card->get_id();
                $balance = $gift_card->get_balance();
                $is_active = $balance != 0;

                if ($key === 'active' && !$is_active) {
                    continue;
                }

                if ($key === 'used' && $is_active) {
                    continue;
                }

                $status = __('Active', DOMAIN);
                $status_title = $is_active ? __('Active', DOMAIN) : __('Used', DOMAIN);

                if ($status_title === 'Used') {
                    $used_date = gift_used_date($gift_id);
                    $used_date = $used_date ? date('d.m.Y', strtotime($used_date)) : '';
                    $status = sprintf(__('Used on %s', DOMAIN), $used_date);
                }

                $amount = get_gift_card_amount($gift_card->get_id());

                $is_gift_in_cart = array_key_exists($users_gift_card->number, $gift_cards_in_cart);

                $for = $gift_card->get_recipient_name() ?: $gift_card->get_recipient_email();

                $gift_items++;

                ?>
                <div class="content__block">
                    <div class="content__block_label">
                        <?php echo $status_title; ?>
                    </div>
                    <div class="content__head">
                        <div class="content__head_title">
                            <?php echo get_local_img_html('myaccount/gift.svg', 'content__head_img', __('Gift card', DOMAIN)); ?>
                            <?php esc_html_e('eGIFT CARD', DOMAIN); ?>
                        </div>
                        <div class="content__head_total">
                            <?php echo str_replace(',00', '', wc_price($amount)); ?>
                        </div>
                    </div>
                    <div class="content__block_body">
                        <?php
                        if ($is_active):
                            if ($is_gift_in_cart): ?>
                                <div class="content__status_message">
                                    <img src="<?php echo get_local_img_url('confirmed.svg'); ?>"
                                         alt="<?php _e('Confirmed', DOMAIN); ?>"
                                         title="<?php _e('Confirmed', DOMAIN); ?>">
                                    <p>
                                        <?php _e('Discount has been applied! Please check your cart.', DOMAIN); ?>
                                    </p>
                                </div>
                                <a href="<?php echo CART_LINK; ?>" class="content__body_btn btn-transparent">
                                    <?php _e('To cart', DOMAIN); ?>
                                </a>
                            <?php else: ?>
                                <?php if (!empty($is_gift_cards_unavailable) && !empty($gift_cards_unavailable_message)): ?>
                                    <div class="content__status_message">
                                        <img src="<?php echo get_local_img_url('failed.svg'); ?>"
                                             alt="<?php _e('Failed', DOMAIN); ?>"
                                             title="<?php _e('Failed', DOMAIN); ?>">
                                        <p>
                                            <?php echo esc_html($gift_cards_unavailable_message); ?>
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <div class="content__body_btn apply_gift_card button" data-number="<?php echo $gift_card->get_number() ?>">
                                        <?php _e('Apply', DOMAIN); ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif;
                        endif; ?>
                        <div class="content__body_val">
                            <div class="content-divider"></div>
                            <div class="content__body_row<?php echo $is_active ? ' toggle-slide' : ''; ?>" <?php echo $is_active ? 'style="display:none;"' : ''; ?>>
                                <span>
                                    <?php _e('Status', DOMAIN); ?>:
                                </span>
                                <span>
                                    <?php echo esc_html($status); ?>
                                </span>
                            </div>
                            <?php if ($for): ?>
                                <div class="content__body_row toggle-slide" style="display:none;">
                                    <span>
                                        <?php _e('For', DOMAIN); ?>:
                                    </span>
                                    <span>
                                        <?php echo esc_html($for); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <?php if ($from = $gift_card->get_from()): ?>
                                <div class="content__body_row toggle-slide" style="display:none;">
                                    <span>
                                        <?php _e('From', DOMAIN); ?>:
                                    </span>
                                    <span>
                                        <?php echo esc_html($from); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <?php if ($is_active): ?>
                                <div class="content__body_row">
                                    <span>
                                        <?php _e('Code', DOMAIN); ?>:
                                    </span>
                                    <span>
                                        <?php echo esc_html($users_gift_card->number); ?>
                                        <?php get_template_part_var('general/copy-to-clipboard', [
                                            'text_to_copy' => $users_gift_card->number
                                        ]); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div class="content-divider"></div>
                            <?php if ($message = $gift_card->get_message()): ?>
                                <div class="content__body_message toggle-slide" style="display:none;">
                                    <strong>
                                        <?php _e('Message from sender', DOMAIN); ?>:
                                    </strong>
                                    <p>
                                        <?php echo esc_html($message); ?>
                                    </p>
                                    <div class="content-divider"></div>
                                </div>
                            <?php endif; ?>
                            <div class="content__body_btn">
                                <div class="gift_body_visibility btn-plain" data-toggle-text="<?php _e('View less', DOMAIN); ?>" data-visibility="hidden">
                                    <?php _e('View more', DOMAIN); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if ($gift_items > 4): ?>
            <div class="simple_pagination"></div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>