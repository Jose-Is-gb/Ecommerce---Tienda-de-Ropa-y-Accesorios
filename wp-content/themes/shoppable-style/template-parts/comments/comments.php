<?php
$shoppable_style_page_one 	= get_theme_mod( 'shoppable_style_comments_page_one', '' );
$shoppable_style_page_two 	= get_theme_mod( 'shoppable_style_comments_page_two', '' );
$shoppable_style_page_three = get_theme_mod( 'shoppable_style_comments_page_three', '' ); 
$shoppable_style_page_four  = get_theme_mod( 'shoppable_style_comments_page_three', '' ); 

$shoppable_style_page_array = array();
$shoppable_style_has_page = false;
if( !empty( $shoppable_style_page_one ) ){
	$shoppable_style_has_page = true;
	$shoppable_style_page_array['page_one'] = array(
		'ID' => $shoppable_style_page_one,
	);
}
if( !empty( $shoppable_style_page_two ) ){
	$shoppable_style_has_page = true;
	$shoppable_style_page_array['page_two'] = array(
		'ID' => $shoppable_style_page_two,
	);
}
if( !empty( $shoppable_style_page_three ) ){
	$shoppable_style_has_page = true;
	$shoppable_style_page_array['page_three'] = array(
		'ID' => $shoppable_style_page_three,
	);
}
if( !empty( $shoppable_style_page_four ) ){
	$shoppable_style_has_page = true;
	$shoppable_style_page_array['page_four'] = array(
		'ID' => $shoppable_style_page_four,
	);
}

if( get_theme_mod( 'shoppable_style_comment_section', false ) && $shoppable_style_has_page ){ ?>
	<section class="section-comment-area">
		<div class="row justify-content-center">
			<?php foreach( $shoppable_style_page_array as $shoppable_style_each_page ){ ?>
				<div class="col-sm-12 col-md-6">
					<article class="comment-item">
						<figure class= "featured-image">
							<?php echo get_the_post_thumbnail( $shoppable_style_each_page[ 'ID' ], 'hello-shoppable-420-300' ); ?>
						</figure>
						<div class="entry-content">
							<h3 class="entry-title">
								<a href="<?php echo esc_url( get_permalink( $shoppable_style_each_page[ 'ID' ] ) ); ?>">
									<?php echo esc_html( get_the_title( $shoppable_style_each_page[ 'ID' ] ) ); ?>
								</a>
							</h3>		
							<div class="entry-text">
								<p>
									<?php 
									$shoppable_style_excerpt = get_the_excerpt( $shoppable_style_each_page[ 'ID' ] );
									$shoppable_style_result  = wp_trim_words( $shoppable_style_excerpt, 20, '' );
									echo esc_html( $shoppable_style_result );
									?>
								</p>
								<span class="comment-quote-icon">
									<i class="fas fa-quote-right"></i>
								</span>
							</div>
						</div>
					</article>
				</div>
			<?php } ?>	
		</div>
	</section>
<?php } ?>