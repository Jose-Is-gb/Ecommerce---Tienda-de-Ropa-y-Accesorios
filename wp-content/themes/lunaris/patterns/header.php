<?php
/**
 * Title: JONILUX header
 * Slug: lunaris/header
 * Categories: featured
 * Inserter: no
 */
if (!defined("ABSPATH")) { exit; }
$shop = function_exists("wc_get_page_permalink") ? wc_get_page_permalink("shop") : home_url("/shop/");
$hombre = home_url("/#coleccion-hombre");
$mujer = home_url("/#coleccion-mujer");
$acc = home_url("/#accesorios");
if (function_exists('wc_get_page_permalink')) {
    foreach (['hombre', 'mujer', 'accesorios'] as $slug) {
        $term = get_term_by('slug', $slug, 'product_cat');
        $url = $term ? get_term_link($term) : $shop;
        if (is_wp_error($url)) { $url = $shop; }
        if ($slug === 'hombre') { $hombre = $url; }
        elseif ($slug === 'mujer') { $mujer = $url; }
        else { $acc = $url; }
    }
}
$myaccount = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : wp_login_url();
?>
<!-- wp:html -->
<div class="lunaris-note">JONILUX • Moda urbana para Hombre y Mujer • Envíos a todo el Perú</div>
<!-- /wp:html -->
<!-- wp:group {"className":"lunaris-header","layout":{"type":"default"}} -->
<div class="wp-block-group lunaris-header"><!-- wp:group {"className":"lunaris-header-inner","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"}} -->
<div class="wp-block-group lunaris-header-inner">
<!-- wp:html --><h1 class="wp-block-site-title lunaris-logo"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home">JONILUX</a></h1><!-- /wp:html -->
<!-- wp:search {"label":"Buscar productos","showLabel":false,"placeholder":"Buscar productos…","buttonText":"Buscar","buttonPosition":"button-inside","buttonUseIcon":true,"query":{"post_type":"product"},"className":"lunaris-header-search"} /-->
<!-- wp:navigation {"overlayMenu":"mobile","className":"lunaris-nav","layout":{"type":"flex"}} -->
<!-- wp:navigation-link {"label":"Inicio","url":"<?php echo esc_url(home_url('/')); ?>"} /-->
<!-- wp:navigation-link {"label":"Hombre","url":"<?php echo esc_url($hombre); ?>"} /-->
<!-- wp:navigation-link {"label":"Mujer","url":"<?php echo esc_url($mujer); ?>"} /-->
<!-- wp:navigation-link {"label":"Accesorios","url":"<?php echo esc_url($acc); ?>"} /-->
<!-- wp:navigation-link {"label":"Tienda","url":"<?php echo esc_url($shop); ?>"} /-->
<!-- wp:navigation-link {"label":"Mi cuenta","url":"<?php echo esc_url($myaccount); ?>"} /-->
<!-- /wp:navigation -->
<!-- wp:html -->
<button type="button" id="jonilux-wishlist-open-btn" class="jonilux-header-icon-btn" aria-label="Ver mis favoritos" title="Mis Favoritos">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
    <span class="jonilux-wishlist-badge" id="jonilux-wishlist-counter">0</span>
</button>
<!-- /wp:html -->
<!-- wp:woocommerce/mini-cart /-->
</div><!-- /wp:group --></div><!-- /wp:group -->
