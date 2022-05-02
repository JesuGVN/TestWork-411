<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if (!function_exists('chld_thm_cfg_locale_css')) :
    function chld_thm_cfg_locale_css($uri)
    {
        if (empty($uri) && is_rtl() && file_exists(get_template_directory() . '/rtl.css'))
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;

include ('inc/woocommerce-custom-fields-group.php');


add_action('wp_enqueue_scripts', 'abelo_theme_scripts', PHP_INT_MAX);
function abelo_theme_scripts()
{
    wp_enqueue_style('scss-styles', get_stylesheet_directory_uri() . '/css/index.css?'.rand());
    wp_enqueue_script('upload-product', get_stylesheet_directory_uri() . '/js/upload-product.js?'.rand());
}

?>