<?php
/**
 * Theme functions and definitions
 *
 * @package Shoppable Style 1.0.0
 */

require get_stylesheet_directory() . '/inc/customizer/customizer.php';
require get_stylesheet_directory() . '/inc/customizer/loader.php';

if ( ! function_exists( 'shoppable_style_enqueue_styles' ) ) :
	/**
	 * @since Shoppable Style 1.0.0
	 */
	function shoppable_style_enqueue_styles() {
        require_once get_theme_file_path ( 'inc/wptt-webfont-loader.php');

		wp_enqueue_style( 'shoppable-style-style-parent', get_template_directory_uri() . '/style.css',
			array(
				'bootstrap',
				'slick',
				'slicknav',
				'slick-theme',
				'fontawesome',
				'hello-shoppable-blocks',
				'hello-shoppable-google-font'
				)
		);
//review check
	    wp_enqueue_style(
            'shoppable-style-google-fonts',
            wptt_get_webfont_url( "https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" ),
            false
        );


	}

endif;
add_action( 'wp_enqueue_scripts', 'shoppable_style_enqueue_styles', 10 );


//Stop WooCommerce redirect on activation
add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );

/**
* Get pages by post id.
* 
* @since Shoppable Style 1.0.0
* @return array.
*/
function shoppable_style_get_pages(){
    $shoppable_style_page_array = get_pages();
    $shoppable_style_pages_list = array();
    foreach ( $shoppable_style_page_array as $shoppable_style_key => $shoppable_style_value ){
        $shoppable_style_page_id = absint( $shoppable_style_value->ID );
        $shoppable_style_pages_list[ $shoppable_style_page_id ] = $shoppable_style_value->post_title;
    }
    return $shoppable_style_pages_list;
}

/**
* Get woocommerce product categories.
* 
* @since Shoppable Style 1.0.0
* @uses get_categories()
* @return array
*/
function shoppable_style_get_product_categories(){

    $shoppable_style_categories = get_categories( 'taxonomy=product_cat' );

    if( empty($shoppable_style_categories) || !is_array( $shoppable_style_categories ) ){
        return array();
    }

    $shoppable_style_data = array();
    foreach ( $shoppable_style_categories as $shoppable_style_key => $shoppable_style_value) {
        $shoppable_style_cat_ID = absint( $shoppable_style_value->cat_ID );
        $shoppable_style_data[$shoppable_style_cat_ID] =  esc_html( $shoppable_style_value->name );
    }
    return $shoppable_style_data;
}

add_theme_support( "title-tag" );
add_theme_support( 'automatic-feed-links' );