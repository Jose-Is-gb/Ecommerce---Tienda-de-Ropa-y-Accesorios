<?php
if (!defined('ABSPATH')) { exit; }
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('lunaris', get_stylesheet_uri(), ['fashion-nook-style'], filemtime(get_stylesheet_directory() . '/style.css'));
});
add_action('after_setup_theme', function () { add_editor_style('style.css'); });
