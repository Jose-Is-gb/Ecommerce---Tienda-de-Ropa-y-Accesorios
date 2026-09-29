<?php
$shoppable_style_blogclientoneID = get_theme_mod( 'shoppable_style_blog_clients_one','' );
$shoppable_style_blogclienttwoID = get_theme_mod( 'shoppable_style_blog_clients_two','' );       
$shoppable_style_blogclientthreeID = get_theme_mod( 'shoppable_style_blog_clients_three','' );
$shoppable_style_blogclientfourID = get_theme_mod( 'shoppable_style_blog_clients_four','' );
$shoppable_style_blogclientfiveID = get_theme_mod( 'shoppable_style_blog_clients_five','' );
$shoppable_style_blogclientsixID = get_theme_mod( 'shoppable_style_blog_clients_six','' );
$shoppable_style_title = get_theme_mod( 'shoppable_style_clients_tagline','' );


$shoppable_style_client_array = array();
$shoppable_style_has_client = false;
if( !empty( $shoppable_style_title ) ){
 		$shoppable_style_has_client = true;
}
if( !empty( $shoppable_style_blogclientoneID ) ){
	$shoppable_style_blog_client_one  = wp_get_attachment_image_src( $shoppable_style_blogclientoneID,'hello-shoppable-420-300');
 	if ( is_array(  $shoppable_style_blog_client_one ) ){
 		$shoppable_style_has_client = true;
   	 	$shoppable_style_blog_client_one = $shoppable_style_blog_client_one[0];
   	 	$shoppable_style_client_array['image_one'] = array(
			'ID' => $shoppable_style_blog_client_one,
		);	
  	}
}
if( !empty( $shoppable_style_blogclienttwoID ) ){
	$shoppable_style_blog_client_two = wp_get_attachment_image_src( $shoppable_style_blogclienttwoID,'hello-shoppable-420-300');
	if ( is_array(  $shoppable_style_blog_client_two ) ){
		$shoppable_style_has_client = true;	
        $shoppable_style_blog_client_two = $shoppable_style_blog_client_two[0];
        $shoppable_style_client_array['image_two'] = array(
			'ID' => $shoppable_style_blog_client_two,
		);	
  	}
}
if( !empty( $shoppable_style_blogclientthreeID ) ){	
	$shoppable_style_blog_client_three = wp_get_attachment_image_src( $shoppable_style_blogclientthreeID,'hello-shoppable-420-300');
	if ( is_array(  $shoppable_style_blog_client_three ) ){
		$shoppable_style_has_client = true;
      	$shoppable_style_blog_client_three = $shoppable_style_blog_client_three[0];
      	$shoppable_style_client_array['image_three'] = array(
			'ID' => $shoppable_style_blog_client_three,
		);	
  	}
}
if( !empty( $shoppable_style_blogclientfourID ) ){	
	$shoppable_style_blog_client_four = wp_get_attachment_image_src( $shoppable_style_blogclientfourID,'hello-shoppable-420-300');
	if ( is_array(  $shoppable_style_blog_client_four ) ){
		$shoppable_style_has_client = true;
      	$shoppable_style_blog_client_four = $shoppable_style_blog_client_four[0];
      	$shoppable_style_client_array['image_four'] = array(
			'ID' => $shoppable_style_blog_client_four,
		);	
  	}
}
if( !empty( $shoppable_style_blogclientfiveID ) ){	
	$shoppable_style_blog_client_five = wp_get_attachment_image_src( $shoppable_style_blogclientfiveID,'hello-shoppable-420-300');
	if ( is_array(  $shoppable_style_blog_client_five ) ){
		$shoppable_style_has_client = true;
      	$shoppable_style_blog_client_five = $shoppable_style_blog_client_five[0];
      	$shoppable_style_client_array['image_five'] = array(
			'ID' => $shoppable_style_blog_client_five,
		);	
  	}
}
if( !empty( $shoppable_style_blogclientsixID ) ){	
	$shoppable_style_blog_client_six = wp_get_attachment_image_src( $shoppable_style_blogclientsixID,'hello-shoppable-420-300');
	if ( is_array(  $shoppable_style_blog_client_six ) ){
		$shoppable_style_has_client = true;
      	$shoppable_style_blog_client_six = $shoppable_style_blog_client_six[0];
      	$shoppable_style_client_array['image_six'] = array(
			'ID' => $shoppable_style_blog_client_six,
		);	
  	}
}


if( get_theme_mod( 'shoppable_style_clients_section', true ) && $shoppable_style_has_client ){ ?>
	<section class="section-client-area">
		<div class="client-content-wrap">
			<div class="section-title-wrap text-center">
				<h2 class="section-title">
					<?php echo esc_html( $shoppable_style_title ); ?>
				</h2>
			</div>
			<article class="client-item">
				<?php foreach( $shoppable_style_client_array as $shoppable_style_each_client ){ ?>
					<figure class= "featured-image">
						<img src="<?php echo esc_url( $shoppable_style_each_client['ID'] ); ?>">
					</figure>
				<?php } ?>
			</article>
		</div>
	</section>
<?php } ?>
