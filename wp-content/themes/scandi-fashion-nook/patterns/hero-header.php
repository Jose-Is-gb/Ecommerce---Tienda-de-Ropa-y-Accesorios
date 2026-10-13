<?php
/**
 * Title: hero-header
 * Slug: scandi-fashion-nook/hero-header
 * Inserter: no
 * Text Domain: scandi-fashion-nook
 */
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/home_hero.jpg","dimRatio":20,"overlayColor":"theme-4","isUserOverlayColor":true,"minHeight":60,"minHeightUnit":"vw","contentPosition":"center center","isDark":false,"sizeSlug":"full","metadata":{"name":"Hero"},"className":"is-position-top-center","style":{"spacing":{"padding":{"top":"0","bottom":"0","right":"0","left":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch","verticalAlignment":"space-between"}} -->
<div class="wp-block-cover is-light is-position-top-center" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;min-height:60vw"><img class="wp-block-cover__image-background  size-full" alt="" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/home_hero.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-theme-4-background-color has-background-dim-20 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"metadata":{"name":"Hero Contents"},"align":"full","style":{"dimensions":{"minHeight":""},"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|theme-1"}}}},"textColor":"theme-1","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-theme-1-color has-text-color has-link-color" style="margin-top:0;margin-bottom:0"><!-- wp:template-part {"slug":"header","align":"wide"} /-->

<!-- wp:spacer {"height":"45vh"} -->
<div style="height:45vh" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"100%","layout":{"type":"constrained"}} -->
<div class="wp-block-column" style="flex-basis:100%"><!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:heading {"align":"full","className":"has-text-align-left","style":{"typography":{"textAlign":"left"}}} -->
<h2 class="wp-block-heading has-text-align-left alignfull"><?php esc_html_e('Luxurious lounging', 'scandi-fashion-nook');?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"has-text-align-left alignfull","style":{"typography":{"textAlign":"full"}}} -->
<p class="has-text-align-left alignfull"><?php esc_html_e('Get cozy and browse our new loungewear line.', 'scandi-fashion-nook');?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"align":"full","style":{"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"flex","justifyContent":"left"}} -->
<div class="wp-block-buttons alignfull" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20)"><!-- wp:button {"className":"is-style-outline","style":{"typography":{"textAlign":"left"}},"fontSize":"custom-1"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-align-left has-custom-1-font-size has-custom-font-size wp-element-button" href="#"><?php esc_html_e('Shop Now', 'scandi-fashion-nook');?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->