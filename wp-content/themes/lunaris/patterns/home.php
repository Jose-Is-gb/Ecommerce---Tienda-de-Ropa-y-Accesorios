<?php
/**
 * Title: LUNARIS home
 * Slug: lunaris/home
 * Categories: featured
 * Inserter: no
 */
if (!defined("ABSPATH")) { exit; }
$base = get_stylesheet_directory_uri() . "/assets/images/";
$shop = function_exists("wc_get_page_permalink") ? wc_get_page_permalink("shop") : home_url("/shop/");
?>
<!-- wp:html -->
<main id="contenido" class="lunaris-home">
<section class="lunaris-hero" aria-labelledby="lunaris-title">
<img src="<?php echo esc_url($base . 'hero.webp'); ?>" alt="Estilo urbano LUNARIS: conjunto negro en una calle de la ciudad" width="1920" height="700" fetchpriority="high">
<div class="lunaris-hero-copy"><p class="lunaris-eyebrow">LUNARIS / Urban essentials</p><h1 id="lunaris-title">Tu estilo.<br>Tu propia<br><span>órbita.</span></h1><p>Esenciales urbanos y detalles que hablan por ti. Descubre el universo LUNARIS.</p><a class="lunaris-button" href="#colecciones">Explorar colección <span aria-hidden="true">↗</span></a></div>
<div class="lunaris-hero-bottom"><span>Diseñado para tu día a día</span><span>Ropa / Accesorios / Actitud</span></div>
</section>
<div class="lunaris-strip"><span>Esenciales atemporales</span><span>Identidad urbana</span><span>Detalles con carácter</span></div>
<section class="lunaris-section" id="colecciones" aria-labelledby="colecciones-title"><div class="lunaris-section-head"><div><p class="lunaris-eyebrow">01 / Encuentra tu estilo</p><h2 id="colecciones-title">Una ciudad. Mil formas de vestir.</h2></div><a class="lunaris-text-link" href="<?php echo esc_url($shop); ?>">Ir a la tienda ↗</a></div>
<div class="lunaris-categories">
<a class="lunaris-category" href="#esenciales"><img src="<?php echo esc_url($base.'men.webp'); ?>" alt="Colección de hoodies para hombre en tonos oscuros" width="1920" height="500" loading="lazy"><div class="lunaris-category-label"><strong>Hombre</strong><span>Explorar esenciales ↗</span></div></a>
<a class="lunaris-category" href="#esenciales"><img src="<?php echo esc_url($base.'women.webp'); ?>" alt="Blusas para mujer en tonos vino, negro y gris" width="1920" height="500" loading="lazy"><div class="lunaris-category-label"><strong>Mujer</strong><span>Explorar esenciales ↗</span></div></a>
</div></section>
<section class="lunaris-section lunaris-products-section" id="esenciales" aria-labelledby="esenciales-title"><div class="lunaris-section-head"><div><p class="lunaris-eyebrow">02 / Selección LUNARIS</p><h2 id="esenciales-title">Menos ruido. Más personalidad.</h2></div><span class="lunaris-text-link">El poder de lo esencial</span></div><div class="lunaris-products">
<?php foreach ([['tshirt','Ropa','Camiseta negra','La base de cualquier look.'],['hoodie','Ropa','Hoodie negro','Comodidad con carácter.'],['bag','Accesorios','Bolso cruzado','Tu día, a tu ritmo.'],['bracelet','Accesorios','Pulsera de cuero','Un detalle que define.']] as $item) : ?>
<article class="lunaris-product"><div class="lunaris-product-image"><img src="<?php echo esc_url($base.$item[0].'.webp'); ?>" alt="<?php echo esc_attr($item[2]); ?>" width="1200" height="1600" loading="lazy"></div><small><?php echo esc_html($item[1]); ?></small><h3><?php echo esc_html($item[2]); ?></h3><p><?php echo esc_html($item[3]); ?></p></article>
<?php endforeach; ?>
</div></section>
<section class="lunaris-feature" id="accesorios" aria-labelledby="accesorios-title"><img class="lunaris-feature-image" src="<?php echo esc_url($base.'accessories.webp'); ?>" alt="Accesorios negros LUNARIS: cinturón, pulsera y detalles de cuero" width="1920" height="700" loading="lazy"><div class="lunaris-feature-copy"><p class="lunaris-eyebrow">03 / El toque final</p><h2 id="accesorios-title">Los detalles<br>cambian todo.</h2><p>Texturas, líneas limpias y tonos que combinan contigo. Completa tu estilo con accesorios que tienen su propia voz.</p><a class="lunaris-button" href="#esenciales">Descubrir accesorios <span aria-hidden="true">↗</span></a></div></section>
<section class="lunaris-section lunaris-story" id="lunaris" aria-labelledby="story-title"><div><p class="lunaris-eyebrow">El universo LUNARIS</p><h2 id="story-title">Vestir también es expresarte.</h2></div><p>Nos inspira la ciudad cuando baja el ruido: sus texturas, su energía y la libertad de ir a tu manera. LUNARIS reúne prendas versátiles, tonos profundos y accesorios con carácter para construir un estilo que se sienta tuyo.</p></section>
</main>
<!-- /wp:html -->