<?php

if (!defined('IS_MOB')) {
    define('IS_MOB', is_mobile() || wp_is_mobile());
}

if (!function_exists('dd')) {
    function dd()
    {
        echo '<pre>';
        array_map(function ($x) {
            var_dump($x);
        }, func_get_args());
        die;
    }
}

if (!function_exists('get_template_part_var')) {
    function get_template_part_var($template, $data = [])
    {
        extract($data);
        require locate_template("template-parts/$template.php");
    }
}

function register_ajax(array $names = [])
{
    if (empty($names)) {
        return;
    }

    foreach ($names as $name) {
        add_action("wp_ajax_$name", $name);
        add_action("wp_ajax_nopriv_$name", $name);
    }
}

function get_svg(string $file_name = '')
{
    if (!$file_name) {
        return;
    }

    get_template_part('template-parts/svg/' . $file_name);
}

function wp_get_current_url(): string
{
    return home_url(strtok($_SERVER["REQUEST_URI"], '?'));
}

function wp_get_full_url(): string
{
    return home_url($_SERVER["REQUEST_URI"]);
}

function current_url_attr(): string
{
    return sprintf('data-url="%s"', esc_url(wp_get_full_url()));
}

function get_widgets(array $widgets = [])
{
    if (empty($widgets)) {
        return;
    }

    foreach ($widgets as $widget) {
        if (is_active_sidebar($widget)) {
            dynamic_sidebar($widget);
        }
    }
}

function _get_field($field, string $class = '', string $tag = 'div')
{
    if (empty($field)) {
        return;
    }

    if ($class) {
        $class = sprintf(' class="%s"', esc_attr($class));
    }

    echo sprintf('<%1$s%2$s>%3$s</%1$s>', $tag, $class, esc_html($field));
}

function get_ip()
{
    $user_ip = $_SERVER['REMOTE_ADDR'];

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $user_ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    return $user_ip;
}

function cut_str(string $text, int $limit = 100, string $append = '...'): string
{
    $plain_text = trim(strip_tags($text));
    $clear_text = str_replace('&nbsp;', '', $plain_text);
    return strlen($clear_text) > $limit ? mb_substr($clear_text, 0, $limit, 'utf-8') . $append : $clear_text;
}

function get_thumbnail_url(int $post_id = 0, string $size = 'large'): string
{
    if (!$post_id) {
        return '';
    }

    $default_image = get_stylesheet_directory_uri() . '/dest/img/noimage.svg';

    if (!has_post_thumbnail($post_id)) {
        return $default_image;
    }

    return get_the_post_thumbnail_url($post_id, $size);
}

function get_thumbnail_html(int $post_id = 0, string $label = '', string $size = 'large'): string
{
    if (!$post_id) {
        return '';
    }

    $image_url = get_stylesheet_directory_uri() . '/dest/img/noimage.svg';

    if (has_post_thumbnail($post_id)) {
        $image_url = get_the_post_thumbnail_url($post_id, $size);
        $image_id = get_post_thumbnail_id($post_id);
        if ($image_id) {
            $label = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: $label;
        }
    }

    return sprintf('<img src="%1$s" alt="%2$s" title="%3$s">', $image_url, $label, $label);
}

function get_image_url(string $image_id = ''): string
{
    if (!$image_id) {
        return get_stylesheet_directory_uri() . '/dest/img/noimage.svg';
    }

    return wp_get_attachment_image_url($image_id, 'large');
}

function get_image(string $image_url = ''): string
{
    return $image_url ?: get_stylesheet_directory_uri() . '/dest/img/noimage.svg';
}

function get_local_img_url(string $image_name = ''): string
{
    $path = "/dest/img/$image_name";

    if (!file_exists(get_template_directory() . $path)) {
        return '';
    }

    return get_template_directory_uri() . $path;
}

function get_local_img_html(string $image_name = '', string $class = '', string $label = ''): string
{
    if (!$image_name) {
        return '';
    }

    $img_url = get_local_img_url($image_name);

    if (!$img_url) {
        return '';
    }

    if ($class) {
        $class = sprintf(' class="%s"', $class);
    }

    if ($label) {
        $label = sprintf(' alt="%s" title="%s"', $label , $label);
    }

    return sprintf('<img src="%1$s"%2$s%3$s>', $img_url, $class, $label);
}

function is_mobile(): bool
{
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    return (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',
            $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent, 0, 4)));
}

function check_category_exists_for_current_page($category_slug) {
    $current_page_id = get_the_ID();

    if (!$current_page_id) {
        return false;
    }

    $categories = get_the_terms($current_page_id, 'product_cat');

    if ($categories && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            if ($category->slug === $category_slug) {
                return true;
            }
        }
    }
    return false;
}

function generate_string($length = 10): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}