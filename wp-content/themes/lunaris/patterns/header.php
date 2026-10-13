<?php
/**
 * Title: LUNARIS header
 * Slug: lunaris/header
 * Categories: featured
 * Inserter: no
 */
if (!defined("ABSPATH")) { exit; }
$base = get_stylesheet_directory_uri() . "/assets/images/";
$shop = function_exists("wc_get_page_permalink") ? wc_get_page_permalink("shop") : home_url("/shop/");
?>
<!-- wp:html -->
<div class="lunaris-note">LUNARIS · Moda urbana, identidad propia</div>
<!-- /wp:html -->
<!-- wp:group {"className":"lunaris-header","layout":{"type":"default"}} -->
<div class="wp-block-group lunaris-header"><!-- wp:group {"className":"lunaris-header-inner","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"}} -->
<div class="wp-block-group lunaris-header-inner">
<!-- wp:site-title {"level":0,"className":"lunaris-logo"} /-->
<!-- wp:navigation {"overlayMenu":"mobile","className":"lunaris-nav","layout":{"type":"flex"}} -->
<!-- wp:navigation-link {"label":"Inicio","url":"<?php echo esc_url(home_url('/')); ?>"} /-->
<!-- wp:navigation-link {"label":"Colecciones","url":"<?php echo esc_url(home_url('/#colecciones')); ?>"} /-->
<!-- wp:navigation-link {"label":"Esenciales","url":"<?php echo esc_url(home_url('/#esenciales')); ?>"} /-->
<!-- wp:navigation-link {"label":"Tienda","url":"<?php echo esc_url($shop); ?>"} /-->
<!-- wp:navigation-link {"label":"Mi cuenta","url":"<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : wp_login_url()); ?>"} /-->
<!-- /wp:navigation -->
<!-- wp:woocommerce/mini-cart /-->
</div><!-- /wp:group --></div><!-- /wp:group -->