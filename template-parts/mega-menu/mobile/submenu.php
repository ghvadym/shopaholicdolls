<?php
if (empty($header_location) || empty($title) || empty($fields)) {
    return;
}

$link_type = $fields['link_type'] ?? '';
$image_id = $fields['image'] ?? '';
$image_url = '';
$link = '';

if ($image_id) {
    $image_url = wp_get_attachment_image_url($image_id, 'medium');
}

if ($link_type === 'category') {
    $category = $fields['category'] ?? '';

    if (!empty($category)) {
        $term_id = is_int($category) ? $category : $category->term_id;

        if (!$image_id) {
            $image_url = get_term_thumbnail_url($term_id, 'medium');
        }

        $link = get_term_link($term_id, 'product_cat');
    }
} elseif ($link_type === 'custom_link') {
    $link_array = $fields['link'] ?? '';

    if (!empty($link_array)) {
        $link = $link_array['url'] ?? '';
    }
}

wp_nav_menu([
    'theme_location' => $header_location,
    'container'      => false,
    'menu_class'     => 'sub-menu sub-menu-mobile',
]);
