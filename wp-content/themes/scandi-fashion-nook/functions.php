<?php
/**
 * Scandi Fashion Nook - Child Theme Functions
 *
 * @package Scandi_Fashion_Nook
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue parent and child theme stylesheets.
 */
function scandi_fashion_nook_enqueue_styles() {
	$parent_style = 'fashion-nook-style';

	wp_enqueue_style(
		$parent_style,
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( get_template() )->get( 'Version' )
	);

	wp_enqueue_style(
		'scandi-fashion-nook-style',
		get_stylesheet_uri(),
		array( $parent_style ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'scandi_fashion_nook_enqueue_styles' );

/**
 * Show the Fashion Nook admin notice for this child theme.
 */
add_filter( 'fashion_nook_show_admin_notice', '__return_true' );
