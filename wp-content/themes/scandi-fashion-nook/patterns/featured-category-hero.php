<?php
/**
 * Title: Featured Category Hero
 * Slug: scandi-fashion-nook/featured-category-hero
 * Categories: Featured
 * Text Domain: scandi-fashion-nook
 */
?>
<!-- wp:group {"metadata":{"patternName":"scandi-fashion-nook/featured-category-cover","name":"Featured Category Cover","categories":["Featured"]},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:cover {"url":"<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/hero_banner.jpg","dimRatio":20,"overlayColor":"theme-5","isUserOverlayColor":true,"minHeight":35,"minHeightUnit":"rem","contentPosition":"bottom left","isDark":false,"sizeSlug":"full","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|20"},"elements":{"link":{"color":{"text":"#ffffff"}}},"color":{"text":"#ffffff"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull is-light has-custom-content-position is-position-bottom-left has-text-color has-link-color" style="color:#ffffff;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);min-height:35rem"><img class="wp-block-cover__image-background size-full" alt="" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/hero_banner.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-theme-5-background-color has-background-dim-20 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"align":"wide"} -->
<h2 class="wp-block-heading alignwide"><?php esc_html_e('Sleep in Silk', 'scandi-fashion-nook');?></h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button"><?php esc_html_e('View Lookbook', 'scandi-fashion-nook');?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->