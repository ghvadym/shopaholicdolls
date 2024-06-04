<?php

const DOMAIN = 'shopaholicdolls';
const APPLE_CLIENT_ID = 'com.shopaholicdolls.client';
const APPLE_SECRET_ID = 'eyJraWQiOiJZOTQ4NzJVOFBOIiwiYWxnIjoiRVMyNTYifQ.eyJpc3MiOiI5OTRVNjkyOE0yIiwiaWF0IjoxNzEwNzYzMjkyLCJleHAiOjE3MjYzMTUyOTIsImF1ZCI6Imh0dHBzOi8vYXBwbGVpZC5hcHBsZS5jb20iLCJzdWIiOiJjb20uc2hvcGFob2xpY2RvbGxzLmNsaWVudCJ9.wxlHOEGsE4m1GRldqK_Z847II512BdhCX8wcYHnJEylpvmLXTgJocqNi78C_q67qePv5d1AQ2kxkUZN13dmhLA';

$files = [
    'helper',
    'custom_functions',
    'webtec-theme-settings',
    'sidebar',
    'post_types',
    'setup',
    'ajax'
];

foreach ($files as $file) {
    if (file_exists(get_template_directory() . "/inc/$file.php")) {
        require_once("$file.php");
    }
}


require_once('classes/SD_Mail_Chimp.php');


if (!defined('WOP_THEME_URL')) {
    define('WOP_THEME_URL', get_stylesheet_directory_uri());
}

if (!defined('WOP_THEME_PATH')) {
    define('WOP_THEME_PATH', get_stylesheet_directory());
}

if (!defined('CURRENCY_SYMBOL')) {
    define('CURRENCY_SYMBOL', get_woocommerce_currency_symbol());
}

if (!defined('CURRENT_LANG')) {
    if (function_exists('pll_current_language')) {
        define('CURRENT_LANG', pll_current_language());
    } else {
        define('CURRENT_LANG', 'pl');
    }
}

if (!defined('MULTI_CURRENCY_ACTIVE')) {
    define('MULTI_CURRENCY_ACTIVE', is_plugin_active('woocommerce-multi-currency/woocommerce-multi-currency.php'));
}

if (!defined('USER_ID')) {
    define('USER_ID', get_current_user_id());
}

if (!defined('MY_ACC_LINK')) {
    define('MY_ACC_LINK', get_wc_page_link('myaccount'));
}

if (!defined('CART_LINK')) {
    define('CART_LINK', get_wc_page_link('cart'));
}

if (!defined('CHECKOUT_LINK')) {
    define('CHECKOUT_LINK', get_wc_page_link('checkout'));
}

apple_authorize();