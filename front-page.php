<?php
/*
* Template name: Home
*/
get_header();

$fields = get_fields();
$options = get_fields('options');

echo '<h1 style="display:none;">'.get_option('blogname').'</h1>';

/* Hero section */
get_template_part_var('home/hero/hero', [
    'fields' => $fields
]);

/* Info section */
get_template_part_var("home/info/info", [
    'fields' => $fields
]);

/* Main categories section */
get_template_part_var('home/categories/categories-desktop', [
    'fields'              => $fields,
    'category_field_name' => 'main'
]);

/* Main categories section */
get_template_part_var("home/skin-types/skin-types", [
    'fields' => $fields
]);

/* Categories section */
get_template_part_var('home/categories/categories-desktop', [
    'fields'              => $fields,
    'category_field_name' => 'skin',
    'desktop'             => true
]);

/* Skincare section with slider - mobile */
get_template_part_var('home/categories/categories-mobile', [
    'fields'              => $fields,
    'category_field_name' => 'skin',
    'mobile'              => true
]);

/* Wellness categories section */
get_template_part_var('home/categories/categories-wellness', [
    'fields' => $fields,
]);

/* Sale section */
get_template_part_var('home/sale/sale', [
    'fields' => $fields
]);

get_footer();