<?php

add_action('init', 'create_post_types');

function create_post_types()
{
    $menu_icon = file_get_contents(get_template_directory() . '/dest/img/plant-icon.svg');
    create_post_type('product_advantages', [
        'public'        => true,
        'show_ui'       => true,
        'has_archive'   => false,
        'hierarchical'  => false,
        'menu_icon'     => 'data:image/svg+xml;base64,' . base64_encode($menu_icon),
        'supports'      => ['title', 'thumbnail'],
        'menu_position' => 56,
        'labels'    => [
            'name'          => __('Product Advantages', DOMAIN),
            'singular_name' => __('Product Advantages', DOMAIN),
            'add_new'       => __('Add New Item', DOMAIN),
            'add_new_item'  => __('Add New Item', DOMAIN),
            'view_item'     => __('View Item', DOMAIN),
            'search_items'  => __('Find Advantage', DOMAIN),
            'not_found'     => __('Advantage isn\'t found', DOMAIN),
            'menu_name'     => __('Products Advantages', DOMAIN)
        ],
    ]);
}


function create_post_type($postType, $args = [])
{
    $args = array_merge([
        'public'        => true,
        'show_ui'       => true,
        'has_archive'   => true,
        'menu_position' => 20,
        'hierarchical'  => true,
        'supports'      => ['title', 'excerpt', 'thumbnail', 'editor'],
    ], $args);

    register_post_type($postType, $args);
}

function create_taxonomy($taxonomy, $postType, $args = [])
{
    $args = array_merge([
        'description'  => '',
        'public'       => true,
        'hierarchical' => true,
        'has_archive'  => true,
    ], $args);

    register_taxonomy($taxonomy, $postType, $args);
}