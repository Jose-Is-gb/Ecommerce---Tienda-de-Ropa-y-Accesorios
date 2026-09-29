<?php
/**
 * Enqueue customizer css.
 */

function shoppable_style_customize_enqueue_style() {

	wp_enqueue_style( 'shoppable-style-customize-controls', get_stylesheet_directory_uri() . '/inc/customizer/customizer.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'shoppable_style_customize_enqueue_style', 99 );

/**
 * Kirki Customizer
 *
 * @return void
 */
// add_action( 'init' , 'shoppable_style_kirki_fields', 999, 1 );


/**
 * Sanitize checkboxes
 */

function shoppable_style_sanitize_checkbox( $input ){
	if ( $input === true ) {
        return true;
    } else {
        return false;
    }
}

/**
 * Sanitize Selects
 */
function shoppable_style_sanitize_select( $input, $setting ){
          
    $input = sanitize_key($input);

    $choices = $setting->manager->get_control( $setting->id )->choices;
                      
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );                
      
}


/**
 * Memorabla Moments section callback
 */
function shoppable_style_is_enable_shoppable_style_blog_memorable_moments() {
	$value = get_theme_mod( 'shoppable_style_memorable_moments_section', false );

	if ( $value ) {
		return true;
	} else {
		return false;
	} 
}

/**
 * comments section callback
 */
function shoppable_style_is_enable_blog_comments() {
	$value = get_theme_mod( 'shoppable_style_comment_section', false );

	if ( $value ) {
		return true;
	} else {
		return false;
	} 
}

/**
 * Special Occasion Sections callback
 */
function shoppable_style_is_enable_blog_clients() {
	$value = get_theme_mod( 'shoppable_style_clients_section', false );

	if ( $value ) {
		return true;
	} else {
		return false;
	} 
}

function shoppable_style_customizer_structure( $wp_customize ) {

	// Blog Homepage Options
	$wp_customize->add_panel( 'blog_homepage_options', array(
	    'title' 	 => __( 'Blog', 'shoppable-style' ),
	    'priority'	 => 80,
	    'capability' => 'edit_theme_options',
	) );

	//Memorable Moments
	$wp_customize->add_section( 'shoppable_style_blog_memorable_moments', array(
	    'title'          => esc_html__( 'Memorable Moments', 'shoppable-style' ),
	    'panel'          => 'blog_homepage_options',
	    'capability'     => 'edit_theme_options',
	    'priority'       => 26,
	) );

	$wp_customize->add_setting( 'shoppable_style_memorable_moments_section', array(
	    'default' 			=> false,
	    'sanitize_callback' => 'shoppable_style_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'shoppable_style_memorable_moments_section', array(
	    'label'        => esc_html__( 'Memorable Moments Section', 'shoppable-style' ),
		'type'         => 'checkbox',
		'priority'	   => 10,
		'section'      => 'shoppable_style_blog_memorable_moments',
	) );

	$wp_customize->add_setting( 'shoppable_style_memorable_moments_title', array(
		'default' 			=> '',
	    'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'shoppable_style_memorable_moments_title', array(
	    'label'       => esc_html__( 'title', 'shoppable-style' ),
	    'type' 		  => 'text',
	    'priority'    => 20,
	    'section' 	  => 'shoppable_style_blog_memorable_moments',
	) );
	
	$wp_customize->add_setting( 'shoppable_style_blog_memorable_moments_page_one', array(
		'sanitize_callback' => 'shoppable_style_sanitize_select',
		'default' 			=> '',
	) );

	$wp_customize->add_control( 'shoppable_style_blog_memorable_moments_page_one', array(
		'type' 			=> 'select',
		'section' 		=> 'shoppable_style_blog_memorable_moments',
		'label'       	=> esc_html__( 'Page 1', 'shoppable-style' ),
		'choices'    	=> shoppable_style_get_pages(),
		'priority'		=> 30,
	) );

	$wp_customize->add_setting( 'shoppable_style_blog_memorable_moments_page_two', array(
		'sanitize_callback' => 'shoppable_style_sanitize_select',
		'default' 			=> '',
	) );

	$wp_customize->add_control( 'shoppable_style_blog_memorable_moments_page_two', array(
		'type' 			=> 'select',
		'section' 		=> 'shoppable_style_blog_memorable_moments',
		'label'       	=> esc_html__( 'Page 2', 'shoppable-style' ),
		'choices'    	=> shoppable_style_get_pages(),
		'priority'		=> 40,
	) );

	$wp_customize->add_setting( 'shoppable_style_blog_memorable_moments_page_three', array(
		'sanitize_callback' => 'shoppable_style_sanitize_select',
		'default' 			=> '',
	) );

	$wp_customize->add_control( 'shoppable_style_blog_memorable_moments_page_three', array(
		'type' 			=> 'select',
		'section' 		=> 'shoppable_style_blog_memorable_moments',
		'label'       	=> esc_html__( 'Page 3', 'shoppable-style' ),
		'choices'    	=> shoppable_style_get_pages(),
		'priority'		=> 50,
	) );

	$wp_customize->add_setting( 'shoppable_style_blog_memorable_moments_page_four', array(
		'sanitize_callback' => 'shoppable_style_sanitize_select',
		'default' 			=> '',
	) );

	$wp_customize->add_control( 'shoppable_style_blog_memorable_moments_page_four', array(
		'type' 			=> 'select',
		'section' 		=> 'shoppable_style_blog_memorable_moments',
		'label'       	=> esc_html__( 'Page 4', 'shoppable-style' ),
		'choices'    	=> shoppable_style_get_pages(),
		'priority'		=> 60,
	) );

	$wp_customize->add_setting( 'shoppable_style_blog_memorable_moments_page_five', array(
		'sanitize_callback' => 'shoppable_style_sanitize_select',
		'default' 			=> '',
	) );

	$wp_customize->add_control( 'shoppable_style_blog_memorable_moments_page_five', array(
		'type' 			=> 'select',
		'section' 		=> 'shoppable_style_blog_memorable_moments',
		'label'       	=> esc_html__( 'Page 5', 'shoppable-style' ),
		'choices'    	=> shoppable_style_get_pages(),
		'priority'		=> 70,
	) );

	// Responsive for Memorable Moments
	$wp_customize->add_setting( 'shoppable_style_mobile_memorable_moments', array(
	    'default' 			=> true,
	    'sanitize_callback' => 'shoppable_style_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'shoppable_style_mobile_memorable_moments', array(
	    'label'        => esc_html__( 'Memorable Moments Section', 'shoppable-style' ),
		'description'  => esc_html__( 'Responsive ', 'shoppable-style' ),
		'type'         => 'checkbox',
		'priority'	   => 80,
		'section'      => 'shoppable_style_blog_memorable_moments',
		'active_callback' => 'shoppable_style_is_enable_shoppable_style_blog_memorable_moments',
	) );

	//Comments
	$wp_customize->add_section( 'shoppable_style_blog_comments', array(
	    'title'          => esc_html__( 'Comments', 'shoppable-style' ),
	    'panel'          => 'blog_homepage_options',
	    'capability'     => 'edit_theme_options',
	    'priority'       => 27,
	) );

	$wp_customize->add_setting( 'shoppable_style_comment_section', array(
	    'default' 			=> false,
	    'sanitize_callback' => 'shoppable_style_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'shoppable_style_comment_section', array(
	    'label'        => esc_html__( 'Comments Section', 'shoppable-style' ),
		'type'         => 'checkbox',
		'priority'	   => 10,
		'section'      => 'shoppable_style_blog_comments',
	) );
	
	$wp_customize->add_setting( 'shoppable_style_comments_page_one', array(
		'sanitize_callback' => 'shoppable_style_sanitize_select',
		'default' 			=> '',
	) );

	$wp_customize->add_control( 'shoppable_style_comments_page_one', array(
		'type' 			=> 'select',
		'section' 		=> 'shoppable_style_blog_comments',
		'label'       	=> esc_html__( 'Page 1', 'shoppable-style' ),
		'choices'    	=> shoppable_style_get_pages(),
		'priority'		=> 20,
	) );

	$wp_customize->add_setting( 'shoppable_style_comments_page_two', array(
		'sanitize_callback' => 'shoppable_style_sanitize_select',
		'default' 			=> '',
	) );

	$wp_customize->add_control( 'shoppable_style_comments_page_two', array(
		'type' 			=> 'select',
		'section' 		=> 'shoppable_style_blog_comments',
		'label'       	=> esc_html__( 'Page 2', 'shoppable-style' ),
		'choices'    	=> shoppable_style_get_pages(),
		'priority'		=> 30,
	) );

	$wp_customize->add_setting( 'shoppable_style_comments_page_three', array(
		'sanitize_callback' => 'shoppable_style_sanitize_select',
		'default' 			=> '',
	) );

	$wp_customize->add_control( 'shoppable_style_comments_page_three', array(
		'type' 			=> 'select',
		'section' 		=> 'shoppable_style_blog_comments',
		'label'       	=> esc_html__( 'Page 3', 'shoppable-style' ),
		'choices'    	=> shoppable_style_get_pages(),
		'priority'		=> 40,
	) );

	$wp_customize->add_setting( 'shoppable_style_comments_page_four', array(
		'sanitize_callback' => 'shoppable_style_sanitize_select',
		'default' 			=> '',
	) );

	$wp_customize->add_control( 'shoppable_style_comments_page_four', array(
		'type' 			=> 'select',
		'section' 		=> 'shoppable_style_blog_comments',
		'label'       	=> esc_html__( 'Page 4', 'shoppable-style' ),
		'choices'    	=> shoppable_style_get_pages(),
		'priority'		=> 50,
	) );

	// Responsive for Comments
	$wp_customize->add_setting( 'shoppable_style_mobile_comments', array(
	    'default' 			=> true,
	    'sanitize_callback' => 'shoppable_style_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'shoppable_style_mobile_comments', array(
	    'label'        => esc_html__( 'Comments Section', 'shoppable-style' ),
		'description'  => esc_html__( 'Responsive ', 'shoppable-style' ),
		'type'         => 'checkbox',
		'priority'	   => 60,
		'section'      => 'shoppable_style_blog_comments',
		'active_callback' => 'shoppable_style_is_enable_blog_comments',
	) );

	//Clients
	$wp_customize->add_section( 'shoppable_style_blog_clients', array(
	    'title'          => esc_html__( 'Clients', 'shoppable-style' ),
	    'panel'          => 'blog_homepage_options',
	    'capability'     => 'edit_theme_options',
	    'priority'       => 28,
	) );

	$wp_customize->add_setting( 'shoppable_style_clients_section', array(
	    'default' 			=> false,
	    'sanitize_callback' => 'shoppable_style_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'shoppable_style_clients_section', array(
	    'label'        => esc_html__( 'Clients Section', 'shoppable-style' ),
		'type'         => 'checkbox',
		'priority'	   => 10,
		'section'      => 'shoppable_style_blog_clients',
	) );

	$wp_customize->add_setting( 'shoppable_style_clients_tagline', array(
		'default' 			=> '',
	    'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'shoppable_style_clients_tagline', array(
	    'label'       => esc_html__( 'Tagline', 'shoppable-style' ),
	    'type' 		  => 'text',
	    'priority'    => 20,
	    'section' 	  => 'shoppable_style_blog_clients',
	) );

	$wp_customize->add_setting( 'shoppable_style_blog_clients_one', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Media_Control( 
			$wp_customize, 
			'shoppable_style_blog_clients_one',
			array(
				'label'        => esc_html__( 'Client 1', 'shoppable-style' ),
				'section'       => 'shoppable_style_blog_clients',
				'mime_type'     => 'image',
				'priority'	    => 30
			)
		)
	);

	$wp_customize->add_setting( 'shoppable_style_blog_clients_two', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Media_Control( 
			$wp_customize, 
			'shoppable_style_blog_clients_two',
			array(
				'label'        => esc_html__( 'Client 2', 'shoppable-style' ),
				'section'       => 'shoppable_style_blog_clients',
				'mime_type'     => 'image',
				'priority'	    => 40
			)
		)
	);

	$wp_customize->add_setting( 'shoppable_style_blog_clients_three', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Media_Control( 
			$wp_customize, 
			'shoppable_style_blog_clients_three',
			array(
				'label'        => esc_html__( 'Client 3', 'shoppable-style' ),
				'section'       => 'shoppable_style_blog_clients',
				'mime_type'     => 'image',
				'priority'	    => 50
			)
		)
	);

	$wp_customize->add_setting( 'shoppable_style_blog_clients_four', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Media_Control( 
			$wp_customize, 
			'shoppable_style_blog_clients_four',
			array(
				'label'        => esc_html__( 'Client 4', 'shoppable-style' ),
				'section'       => 'shoppable_style_blog_clients',
				'mime_type'     => 'image',
				'priority'	    => 50
			)
		)
	);

	$wp_customize->add_setting( 'shoppable_style_blog_clients_five', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Media_Control( 
			$wp_customize, 
			'shoppable_style_blog_clients_five',
			array(
				'label'        => esc_html__( 'Client 5', 'shoppable-style' ),
				'section'       => 'shoppable_style_blog_clients',
				'mime_type'     => 'image',
				'priority'	    => 60
			)
		)
	);

	$wp_customize->add_setting( 'shoppable_style_blog_clients_six', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Media_Control( 
			$wp_customize, 
			'shoppable_style_blog_clients_six',
			array(
				'label'        => esc_html__( 'Client 6', 'shoppable-style' ),
				'section'       => 'shoppable_style_blog_clients',
				'mime_type'     => 'image',
				'priority'	    => 70
			)
		)
	);

	// Responsive for Clients
	$wp_customize->add_setting( 'shoppable_style_mobile_clients', array(
	    'default' 			=> true,
	    'sanitize_callback' => 'shoppable_style_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'shoppable_style_mobile_clients', array(
	    'label'        => esc_html__( 'Clients Section', 'shoppable-style' ),
		'description'  => esc_html__( 'Responsive ', 'shoppable-style' ),
		'type'         => 'checkbox',
		'priority'	   => 80,
		'section'      => 'shoppable_style_blog_clients',
		'active_callback' => 'shoppable_style_is_enable_blog_clients',
	) );
}
add_action( 'customize_register', 'shoppable_style_customizer_structure', 15 );
