<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo custom_get_page_title(); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header id="header" class="header">
    <?php get_template_part('template-parts/mega-menu/line'); ?>
    <?php get_template_part('template-parts/mega-menu/mobile/menu'); ?>
    <?php get_template_part('template-parts/mega-menu/desktop/menu'); ?>
</header>
<main class="main">