<?php

function shoppable_style_default_styles(){

	// Begin Style
	$shoppable_style_css = '<style>';

	# Responsive Memorable Moments
	if( !get_theme_mod( 'shoppable_style_mobile_memorable_moments', true ) ){
		$shoppable_style_css .= '
			@media screen and (max-width: 767px){
				.section-memorable-moment-area {
	    			display: none;
				}
			}
		';
	}

	# Responsive Comments
	if( !get_theme_mod( 'shoppable_style_mobile_comments', true ) ){
		$shoppable_style_css .= '
			@media screen and (max-width: 767px){
				.section-comment-area {
	    			display: none;
				}
			}
		';
	}

	# Responsive Clients
	if( !get_theme_mod( 'shoppable_style_mobile_clients', true ) ){
		$shoppable_style_css .= '
			@media screen and (max-width: 767px){
				.section-client-area {
	    			display: none;
				}
			}
		';
	}

	// End Style
	$shoppable_style_css .= '</style>';

	// return generated & compressed CSS
	echo str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $shoppable_style_css); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'shoppable_style_default_styles', 99 );