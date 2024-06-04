<?php

$fields = [
    'billing_email' => [
        'title' => esc_html__('Email', DOMAIN),
        'value' => get_user_meta(USER_ID, 'billing_email', true),
        'type' => 'email'
    ],
    'billing_phone' => [
        'title' => esc_html__('Phone', DOMAIN),
        'value' => get_user_meta(USER_ID, 'billing_phone', true),
        'type' => 'tel'
    ]
];

?>

<div class="account__tab_title">
    <?php _e('Contacts', DOMAIN); ?>
</div>

<?php foreach ($fields as $key => $data): ?>
    <div class="content__block" data-val="<?php echo $data['value'] ? '1' : ''; ?>">
        <div class="content__head">
            <div class="content__head_title">
                <?php echo get_local_img_html('myaccount/letter.svg', 'content__head_img', __('Contact info', DOMAIN)); ?>
                <?php echo $data['title']; ?>
            </div>

            <?php get_template_part_var('myaccount/block-actions', [
                'value' => $data['value']
            ]); ?>
        </div>
        <div class="content__body">
            <?php if ($data['value']): ?>
                <div class="content__body_val">
                    <?php echo $data['value']; ?>
                </div>
            <?php endif; ?>
            <div class="content__body_edit">
                <form class="user_meta_form">
                    <div class="content__body_fields">
                        <?php woocommerce_form_field($key, [
                            'type'  => $data['type'],
                            'label' => $data['title'],
                            'class' => $data['value'] ? 'active-input' : ''
                        ], $data['value']); ?>
                    </div>

                    <?php echo '<input type="hidden" name="template_name" value="contact">'; ?>
                    <?php echo '<input type="hidden" name="rm_fields" value="">'; ?>

                    <div class="content__body_remove">
                        <p>
                            <b>
                                <?php echo esc_html($data['title']); ?>:
                                <?php echo esc_html($data['value']); ?>
                            </b>
                        </p>
                        <p class="red">
                            <?php esc_html_e('This contact method will be removed.', DOMAIN); ?>
                        </p>
                    </div>

                    <?php get_template_part_var('myaccount/buttons-group'); ?>
                </form>
            </div>
        </div>
    </div>
<?php endforeach;
