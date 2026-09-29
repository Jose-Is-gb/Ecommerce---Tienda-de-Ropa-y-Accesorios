<?php
$shoppable_style_page_one 	= get_theme_mod( 'shoppable_style_blog_memorable_moments_page_one', '' );
$shoppable_style_page_two 	= get_theme_mod( 'shoppable_style_blog_memorable_moments_page_two', '' );
$shoppable_style_page_three = get_theme_mod( 'shoppable_style_blog_memorable_moments_page_three', '' );
$shoppable_style_page_four  = get_theme_mod( 'shoppable_style_blog_memorable_moments_page_four', '' );
$shoppable_style_page_five  = get_theme_mod( 'shoppable_style_blog_memorable_moments_page_five', '');
$shoppable_style_title  = get_theme_mod( 'shoppable_style_memorable_moments_title', '');


$shoppable_style_page_array = array();
$shoppable_style_has_page = false;
$shoppable_style_has_array = false;
if( !empty( $shoppable_style_title ) ){
	$shoppable_style_has_page = true;
}
if( !empty( $shoppable_style_page_one ) ){
	$shoppable_style_has_page = true;
	$shoppable_style_has_array = true;
	$shoppable_style_page_array['page_one'] = array(
		'ID' => $shoppable_style_page_one,
	);
}
if( !empty( $shoppable_style_page_two ) ){
	$shoppable_style_has_page = true;
	$shoppable_style_has_array = true;
	$shoppable_style_page_array['page_two'] = array(
		'ID' => $shoppable_style_page_two,
	);
}
if( !empty( $shoppable_style_page_three ) ){
	$shoppable_style_has_page = true;
	$shoppable_style_has_array = true;
	$shoppable_style_page_array['page_three'] = array(
		'ID' => $shoppable_style_page_three,
	);
}
if( !empty( $shoppable_style_page_four ) ){
	$shoppable_style_has_page = true;
	$shoppable_style_has_array = true;
	$shoppable_style_page_array['page_four'] = array(
		'ID' => $shoppable_style_page_four,
	);
}
if( !empty( $shoppable_style_page_five ) ){
	$shoppable_style_has_page = true;
	$shoppable_style_has_array = true;
	$shoppable_style_page_array['page_five'] = array(
		'ID' => $shoppable_style_page_five,
	);
}

if( get_theme_mod( 'shoppable_style_memorable_moments_section', true ) && $shoppable_style_has_page ){ ?>
	<section class="section-memorable-moment-area">
		<div class="section-title-wrap text-center col-lg-6 offset-lg-3 col-md-8 offset-md-2">
			<h2 class="moments-moment-title">
				<?php echo esc_html( $shoppable_style_title ); ?>
			</h2>
			<p>
				<?php 
				$shoppable_style_excerpt = get_the_excerpt( $shoppable_style_page_one );
				$shoppable_style_result  = wp_trim_words( $shoppable_style_excerpt, 20, '' );
				echo esc_html( $shoppable_style_result );?>	
			</p>
		</div>
		<?php 
		if ( $shoppable_style_has_array ){ ?>			
			<?php foreach( $shoppable_style_page_array as $shoppable_style_each_page ){ ?>			
				<div class="moment-wrapper">
					<figure class="featured-image">
						<?php echo get_the_post_thumbnail( $shoppable_style_each_page[ 'ID' ], 'hello-shoppable-1370-550' ); ?>
					</figure>
					<div class="moment-iconbox">
							<h3 class="entry-title">
								<a href="<?php echo esc_url( get_permalink( $shoppable_style_each_page[ 'ID' ] ) ); ?>">
									<?php echo esc_html( get_the_title( $shoppable_style_each_page[ 'ID' ] ) ); ?>
								</a>
							</h3>
							<div class="entry-text">
								<?php 
								$shoppable_style_excerpt = get_the_excerpt( $shoppable_style_each_page[ 'ID' ] );
								$shoppable_style_result  = wp_trim_words( $shoppable_style_excerpt, 15, '' );
								echo esc_html( $shoppable_style_result );
								?>
							</div>
							<a href="<?php echo esc_url( get_permalink( $shoppable_style_each_page[ 'ID' ] ) ); ?>" class="moment-page-link">
								<?php echo esc_html__( 'Learn More ....', 'shoppable-style' ); ?>	
							</a>
					</div>
				</div>			
			<?php } ?>		
		<?php } ?>
	</section>	
<?php } ?>