<?php

$user_fields = get_user_meta(USER_ID);
$get_countries = WC()->countries;
$addresses = [
    'shipping' => [
        'title' => __('Shipping address', DOMAIN),
        'icon'  => 'courier.svg',
    ],
    'billing'  => [
        'title' => __('Billing address', DOMAIN),
        'icon'  => 'bill.svg',
    ]
];
?>

<div class="account__tab_title">
    <?php _e('Addresses', DOMAIN); ?>
</div>

<?php foreach ($addresses as $key => $billing_data):
    $fields = [
        'country' => [
            'title'    => esc_html__('Country', DOMAIN),
            'meta_key' => $key . '_country',
            'type'     => 'select',
            'options'  => $get_countries->get_allowed_countries(),
            'default'  => 'PL'
        ],
        'name' => [
            'title'    => esc_html__('Name', DOMAIN),
            'meta_key' => $key . '_first_name'
        ],
        'address' => [
            'title'    => esc_html__('Street & Number', DOMAIN),
            'meta_key' => $key . '_address_1'
        ],
        'address_number' => [
            'title'    => esc_html__('Apartment number', DOMAIN),
            'meta_key' => $key . '_address_2'
        ],
        'city' => [
            'title'    => esc_html__('City / Region', DOMAIN),
            'meta_key' => $key . '_city'
        ],
        'post' => [
            'title'    => esc_html__('ZIP Code', DOMAIN),
            'meta_key' => $key . '_postcode'
        ]
    ];
    ?>
    <div class="content__block" data-val="<?php echo !empty($user_fields[$key . '_country'][0]) ? '1' : ''; ?>">
        <div class="content__head">
            <div class="content__head_title">
                <?php echo get_local_img_html('myaccount/' . $billing_data['icon'], 'content__head_img', __('Address info', DOMAIN)); ?>
                <?php echo $billing_data['title']; ?>
            </div>
            <?php get_template_part_var('myaccount/block-actions', [
                'value' => $user_fields[$fields['name']['meta_key']][0] ?? ''
            ]); ?>
        </div>
        <div class="content__body">
            <?php if (!empty($user_fields[$key . '_country'][0])): ?>
                <div class="content__body_val">
                    <?php foreach ($fields as $name => $data):
                        $meta_key = $data['meta_key'] ?? '';

                        if (!$meta_key):
                            continue;
                        endif;

                        $value = $user_fields[$meta_key][0] ?? '';

                        if (!$value):
                            continue;
                        endif;

                        if ($name === 'country'):
                            if ($countries = $get_countries->get_countries()): ?>
                                <p>
                                    <?php echo $countries[$value]; ?>
                                </p>
                            <?php endif;
                            continue;
                        endif; ?>

                        <p>
                            <?php echo esc_html($value); ?>
                        </p>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="content__body_edit">
                <form class="user_meta_form">
                    <div class="content__body_fields">
                        <?php foreach ($fields as $name => $data):
                            $meta_key = $data['meta_key'] ?? '';
                            $value = $user_fields[$meta_key][0] ?? '';

                            if (!$value && $name == 'country' && !empty($data['default'])) {
                                $value = $data['default'];
                            }

                            woocommerce_form_field($data['meta_key'], [
                                'type'    => !empty($data['type']) ? $data['type'] : 'text',
                                'label'   => $data['title'],
                                'options' => !empty($data['options']) ? $data['options'] : [],
                                'class'   => $value ? $data['meta_key'] . ' active-input' : $data['meta_key']
                            ], $value);

                        endforeach; ?>
                    </div>

                    <?php echo '<input type="hidden" name="template_name" value="address">'; ?>
                    <?php echo '<input type="hidden" name="rm_fields" value="">'; ?>

                    <div class="content__body_remove">
                        <p class="red">
                            <?php esc_html_e('This address will be removed.', DOMAIN); ?>
                        </p>
                    </div>

                    <?php get_template_part_var('myaccount/buttons-group'); ?>
                </form>
            </div>
        </div>
    </div>
<?php endforeach;