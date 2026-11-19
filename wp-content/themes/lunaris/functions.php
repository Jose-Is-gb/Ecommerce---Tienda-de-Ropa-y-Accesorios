<?php
if (!defined('ABSPATH')) { exit; }
require_once get_stylesheet_directory() . '/inc/storefront.php';
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('lunaris', get_stylesheet_uri(), ['fashion-nook-style'], filemtime(get_stylesheet_directory() . '/style.css'));
    wp_enqueue_style('lunaris-editorial', get_stylesheet_directory_uri() . '/assets/css/editorial.css', ['lunaris'], filemtime(get_stylesheet_directory() . '/assets/css/editorial.css'));
    wp_enqueue_style('lunaris-storefront', get_stylesheet_directory_uri() . '/assets/css/storefront.css', ['lunaris-editorial'], filemtime(get_stylesheet_directory() . '/assets/css/storefront.css'));
    wp_enqueue_script('lunaris-storefront', get_stylesheet_directory_uri() . '/assets/js/storefront.js', [], filemtime(get_stylesheet_directory() . '/assets/js/storefront.js'), ['in_footer' => true, 'strategy' => 'defer']);
});
add_action('after_setup_theme', function () { add_editor_style(['style.css', 'assets/css/editorial.css']); });
