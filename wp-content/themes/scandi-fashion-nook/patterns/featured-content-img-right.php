<?php
/**
 * Title: Featured Content with Image Right
 * Slug: scandi-fashion-nook/featured-content-img-right
 * Categories: Text, Banners, Featured
 * Text Domain: scandi-fashion-nook
 */
?>
<!-- wp:group {"metadata":{"categories":["Featured"],"patternName":"scandi-fashion-nook/content-with-image-on-the-left","name":"Content with Image on the left"},"align":"full","className":"alignfull is-style-section-3","layout":{"type":"constrained","justifyContent":"center"}} -->
<div class="wp-block-group alignfull is-style-section-3"><!-- wp:columns {"verticalAlignment":"top","align":"full","style":{"spacing":{"blockGap":{"left":"0"},"padding":{"right":"0","left":"0"}}}} -->
<div class="wp-block-columns alignfull are-vertically-aligned-top" style="padding-right:0;padding-left:0"><!-- wp:column {"verticalAlignment":"top","width":"50%","style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--50);flex-basis:50%"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase"}}} -->
<p style="text-transform:uppercase"><?php esc_html_e('Dressed for real life', 'scandi-fashion-nook');?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e('Rooted in Scandinavian simplicity. Built on the belief that good clothes don\'t ask for attention — they just feel right, wash well, and last.', 'scandi-fashion-nook');?></h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline","fontSize":"xsmall"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-xsmall-font-size has-custom-font-size wp-element-button"><?php esc_html_e('Shop now', 'scandi-fashion-nook');?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top","width":"50%","style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:50%"><!-- wp:cover {"url":"<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/knit_sweater.jpg","dimRatio":0,"isDark":false,"style":{"dimensions":{"aspectRatio":"1"}}} -->
<div class="wp-block-cover is-light"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/knit_sweater.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size"></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->