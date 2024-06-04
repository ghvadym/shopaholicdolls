<?php

$orders = wc_get_orders([
    'customer_id' => USER_ID,
    'limit'       => -1
]);

if (empty($orders)) {
    get_template_part_var('myaccount/no-orders');
    return;
}

$tabs = [
    'all'   => esc_html__('All', DOMAIN),
    'week'  => esc_html__('This week', DOMAIN),
    'month' => esc_html__('This month', DOMAIN),
    'year'  => esc_html__('This year', DOMAIN)
];
?>

<div class="account__tab_title">
    <?php _e('Orders', DOMAIN); ?>
</div>

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

<?php foreach ($tabs as $key => $title): ?>
    <div class="content__tab_body<?php echo $key === 'all' ? ' tab-active' : ''; ?>"
         data-tab="<?php echo $key; ?>">
        <div class="content__tab_list orders-list-<?php echo $key; ?>">
            <?php $order_items = 0;
            foreach ($orders as $order):
                $date_created = $order->get_date_created();

                if ($key === 'week' && date('Y-m-d', strtotime($date_created)) < date('Y-m-d', strtotime('-7 days'))) {
                    break;
                }

                if ($key === 'month' && date('Y-m-d', strtotime($date_created)) < date('Y-m-d', strtotime('-1 month'))) {
                    break;
                }

                if ($key === 'year' && date('Y-m-d', strtotime($date_created)) < date('Y-m-d', strtotime('-1 year'))) {
                    break;
                }

                $order_items++;

                $items = $order->get_items();
                $order_id = $order->get_id();
                $images = get_product_images_of_order_item($items);
                $status = $order->get_status();

                $order_note = '';
                if ($status === 'cancelled' || $status === 'refunded' || $status === 'failed') {
                    $order_notes = wc_get_order_notes([
                        'order_id' => $order_id,
                        'limit'    => 1
                    ]);

                    $order_note = $order_notes[0]->content ?? '';
                }

                ?>
                <div class="content__block order_block">
                    <div class="content__head">
                        <div class="content__head_title">
                            <strong>
                                <?php esc_html_e('Order', DOMAIN); ?>
                                #<?php echo esc_html($order_id); ?>
                            </strong>
                            <p>
                                <?php echo wc_format_datetime($order->get_date_created(), 'd.m.Y'); ?>
                            </p>
                        </div>
                        <?php get_svg('arrow-select'); ?>
                    </div>
                    <div class="content__short_info">
                        <div class="info__status" data-status="<?php echo esc_attr($status); ?>">
                            <?php echo esc_html($status); ?>
                            <span class="info__status_reason">
                                <?php echo order_note_string($order_note); ?>
                            </span>
                        </div>
                        <div class="info__total">
                            <?php echo wc_price($order->get_total()); ?>
                        </div>
                    </div>
                    <?php if (!empty($images)):
                        $images_to_show = array_slice($images, 0, 5);
                        ?>
                        <div class="order__products_gallery">
                            <?php
                            foreach ($images_to_show as $image_url): ?>
                                <div class="order__product_img">
                                    <img src="<?php echo esc_url($image_url); ?>"
                                         alt="<?php _e('Product image', DOMAIN); ?>"
                                         title="<?php _e('Product image', DOMAIN); ?>">
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($images) > 5): ?>
                                <div class="order__product_img_left">
                                    <span>
                                        +<?php echo count($images) - 5; ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="content__body">
                        <div class="content__body_val">
                            <div class="content-divider"></div>
                            <?php get_template_part_var('myaccount/customer-info', [
                                'order' => $order
                            ]); ?>
                            <div class="content-divider"></div>
                            <?php get_template_part_var('confirmation/order-summary', [
                                'order' => $order
                            ]); ?>
                            <div class="reorder_order btn-transparent" data-id="<?php echo $order->get_id(); ?>">
                                <?php _e('Reorder', DOMAIN); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if ($order_items > 4): ?>
            <div class="simple_pagination"></div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
