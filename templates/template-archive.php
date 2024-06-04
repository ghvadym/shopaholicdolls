<?php
/*
* Template name: Archive
*/
get_header();

get_template_part_var('archive/breadcrumb');

get_template_part_var('archive/head-section');

get_template_part_var('archive/archive-products');

get_template_part_var('archive/pagination');

get_footer();
?>
