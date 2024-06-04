<?php

$points_total = get_user_meta(USER_ID, 'order_reward_points', true) ?: 0;
$user_orders = wc_get_orders([
    'customer' => USER_ID,
    'limit'    => -1
]);

if (empty($user_orders)) {
    get_template_part_var('myaccount/no-orders');
    return;
}

$points_count = 0;
?>

<div class="account__tab_title">
    <?php _e('Rewards', DOMAIN); ?>
</div>

<div class="content__block">
    <div class="content__head">
        <div class="content__head_title">
            <?php echo get_local_img_html('myaccount/info.svg', 'content__head_img', __('Reward points status', DOMAIN)); ?>
            <?php _e('Status', DOMAIN); ?>
        </div>
    </div>
    <div class="content__body">
        <div class="content__body_val">
            <div class="content__body_row">
                <strong>
                    <?php _e('Current balance', DOMAIN); ?>:
                </strong>
                <div class="content__coin">
                    <?php echo get_local_img_html('coin.svg', '', __('Reward points amount', DOMAIN)); ?>
                    <?php echo sprintf('%s pkt', $points_total); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content__block">
    <div class="content__head">
        <div class="content__head_title">
            <?php echo get_local_img_html('myaccount/list.svg', 'content__head_img', __('Reward points history', DOMAIN)); ?>
            <?php _e('Points history', DOMAIN); ?>
        </div>
    </div>
    <div class="content__body">
        <div class="content__body_val">
            <ul class="content__points_list points-list">
                <?php foreach ($user_orders as $order):
                    $earned_points = get_post_meta($order->get_id(), 'order_reward_points', true) ?: 0;
                    $used_points = get_post_meta($order->get_id(), 'order_used_points', true) ?: 0;

                    $points = [];

                    if ($earned_points) {
                        $points[] = sprintf('+ %s', $earned_points);
                    }

                    if ($used_points) {
                        $points[] = sprintf('- %s', $used_points);
                    }

                    foreach ($points as $point):
                        $points_count++; ?>
                        <li class="content__body_row point-item">
                            <span>
                                <?php echo wc_format_datetime($order->get_date_created(), 'd.m.Y'); ?>
                            </span>
                            <div class="span">
                                <?php echo $point; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
            <?php if ($points_count > 10): ?>
                <div class="simple_pagination points-pagination"></div>
            <?php endif; ?>
        </div>
    </div>
</div>
