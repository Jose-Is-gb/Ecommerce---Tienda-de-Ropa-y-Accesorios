<?php
/**
 * Title: LUNARIS footer
 * Slug: lunaris/footer
 * Categories: featured
 * Inserter: no
 */
if (!defined("ABSPATH")) { exit; }
$base = get_stylesheet_directory_uri() . "/assets/images/";
$shop = function_exists("wc_get_page_permalink") ? wc_get_page_permalink("shop") : home_url("/shop/");
?>
<!-- wp:html -->
<div class="lunaris-footer"><div class="lunaris-footer-top"><div><a class="lunaris-footer-logo" href="<?php echo esc_url(home_url('/')); ?>">LUNARIS</a><p>Esenciales urbanos. Identidad propia.</p></div><nav aria-label="Navegación del pie"><a href="<?php echo esc_url(home_url('/#colecciones')); ?>">Colecciones</a><a href="<?php echo esc_url(home_url('/#accesorios')); ?>">Accesorios</a><a href="<?php echo esc_url($shop); ?>">Tienda</a><a href="<?php echo esc_url(home_url('/#lunaris')); ?>">Sobre LUNARIS</a></nav></div><div class="lunaris-footer-bottom"><span>© <?php echo esc_html(wp_date('Y')); ?> LUNARIS</span><span>TU ESTILO. TU PROPIA ÓRBITA.</span></div></div>
<!-- /wp:html -->