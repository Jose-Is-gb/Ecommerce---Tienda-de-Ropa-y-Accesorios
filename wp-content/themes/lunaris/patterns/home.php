<?php
/**
 * Title: JONILUX home
 * Slug: lunaris/home
 * Categories: featured
 * Inserter: no
 */
if (!defined("ABSPATH")) { exit; }
$base = get_stylesheet_directory_uri() . "/assets/images/";
$shop = function_exists("wc_get_page_permalink") ? wc_get_page_permalink("shop") : home_url("/shop/");

// Helper seguro para renderizar tarjetas de producto
if (!function_exists('lunaris_render_product_card')) {
    function lunaris_render_product_card($product, $base) {
        $pid = $product->get_id();
        $permalink = esc_url($product->get_permalink());
        $title = esc_html($product->get_name());
        $price_html = $product->get_price_html();
        $image_id = $product->get_image_id();
        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : esc_url($base . 'accessories.webp');
        
        $terms = get_the_terms($pid, 'product_cat');
        $cat_name = 'JONILUX';
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $t) {
                if ($t->slug !== 'uncategorized' && $t->slug !== 'hombre' && $t->slug !== 'mujer') {
                    $cat_name = esc_html($t->name);
                    break;
                }
            }
        }
        
        $rating = $product->get_average_rating();
        $rating_count = $product->get_rating_count();
        ?>
        <article class="lunaris-product-card">
            <a href="<?php echo $permalink; ?>" class="lunaris-card-link">
                <div class="lunaris-product-thumb">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" width="1200" height="1600" loading="lazy">
                    <span class="lunaris-card-badge"><?php echo $cat_name; ?></span>
                </div>
                <div class="lunaris-card-details">
                    <div class="lunaris-card-stars">
                        <?php if ($rating_count > 0) : ?>
                            <?php echo wc_get_rating_html((float) $rating, $rating_count); ?>
                        <?php else : ?>
                            <small>Sin valoraciones</small>
                        <?php endif; ?>
                    </div>
                    <h3 class="lunaris-card-title"><?php echo $title; ?></h3>
                    <div class="lunaris-product-price">
                        <?php echo $price_html; ?>
                    </div>
                    <span class="lunaris-card-cta">Ver Prenda <span aria-hidden="true">↗</span></span>
                </div>
            </a>
        </article>
        <?php
    }
}

// Consultas segmentadas por categoría
$hombre_products = function_exists('wc_get_products') ? wc_get_products([
    'status' => 'publish',
    'limit' => 8,
    'category' => ['hombre'],
    'orderby' => 'date',
    'order' => 'DESC'
]) : [];

$mujer_products = function_exists('wc_get_products') ? wc_get_products([
    'status' => 'publish',
    'limit' => 8,
    'category' => ['mujer'],
    'orderby' => 'date',
    'order' => 'DESC'
]) : [];

$accesorios_products = function_exists('wc_get_products') ? wc_get_products([
    'status' => 'publish',
    'limit' => 4,
    'category' => ['accesorios', 'accesorios-mujer', 'calzado', 'zapatillas'],
    'orderby' => 'date',
    'order' => 'DESC'
]) : [];
?>
<!-- wp:html -->
<main id="contenido" class="lunaris-home">

    <!-- HERO SECTION -->
    <section class="lunaris-hero" aria-labelledby="lunaris-title">
        <img src="<?php echo esc_url($base . 'hero.webp'); ?>" alt="Estilo urbano JONILUX: conjunto negro en una calle de la ciudad" width="1920" height="700" fetchpriority="high">
        <div class="lunaris-hero-copy">
            <p class="lunaris-eyebrow">JONILUX / Urban Essentials 2026</p>
            <h1 id="lunaris-title">Tu estilo.<br>Tu propia<br><span>órbita.</span></h1>
            <p>Esenciales urbanos con cortes contemporáneos, precios transparentes y sin complicaciones.</p>
            <div class="lunaris-hero-buttons">
                <a class="lunaris-button" href="#coleccion-hombre">Colección Hombre <span aria-hidden="true">↓</span></a>
                <a class="lunaris-button secondary" href="#coleccion-mujer">Colección Mujer <span aria-hidden="true">↓</span></a>
            </div>
        </div>
        <div class="lunaris-hero-bottom">
            <span>Confección peruana de calidad</span>
            <span>Envíos a todo el Perú</span>
            <span>Prendas / Accesorios / Actitud</span>
        </div>
    </section>

    <!-- BANNER DE GARANTÍAS / BENEFICIOS -->
    <div class="lunaris-strip">
        <span>Envíos Lima 24-48h y Provincias</span>
        <span>Yape / Plin / Transferencia / Contra Entrega</span>
        <span>Garantía de cambio por 7 días</span>
        <span>Guía de tallas oficial JONILUX</span>
    </div>

    <!-- SECCIÓN 01: SEGMENTACIÓN DE CATEGORÍAS -->
    <section class="lunaris-section" id="colecciones" aria-labelledby="colecciones-title">
        <div class="lunaris-section-head">
            <div>
                <p class="lunaris-eyebrow">01 / Categorías Principales</p>
                <h2 id="colecciones-title">Elige tu categoría</h2>
            </div>
            <a class="lunaris-text-link" href="<?php echo esc_url($shop); ?>">Ver catálogo completo (<?php echo (count($hombre_products) + count($mujer_products)); ?>+) ↗</a>
        </div>
        <div class="lunaris-categories">
            <a class="lunaris-category" href="#coleccion-hombre">
                <img src="<?php echo esc_url($base.'men.webp'); ?>" alt="Colección de ropa urbana para hombre: hoodies y polos" width="1920" height="500" loading="lazy">
                <div class="lunaris-category-label">
                    <div>
                        <strong>Hombre</strong>
                        <small style="display:block;font-size:12px;opacity:.9;">Polos oversize, hoodies, joggers y camisas</small>
                    </div>
                    <span class="lunaris-cat-btn">Ver Colección ↗</span>
                </div>
            </a>
            <a class="lunaris-category" href="#coleccion-mujer">
                <img src="<?php echo esc_url($base.'women.webp'); ?>" alt="Colección de ropa urbana para mujer: tops, vestidos y faldas" width="1920" height="500" loading="lazy">
                <div class="lunaris-category-label">
                    <div>
                        <strong>Mujer</strong>
                        <small style="display:block;font-size:12px;opacity:.9;">Tops, blusas, vestidos, faldas y sweaters</small>
                    </div>
                    <span class="lunaris-cat-btn">Ver Colección ↗</span>
                </div>
            </a>
        </div>
    </section>

    <!-- BARRA DE ACCESO RÁPIDO ENTRE CATEGORÍAS -->
    <div class="lunaris-category-tabs-bar">
        <div class="lunaris-category-tabs-inner">
            <span class="tabs-label">Explorar por categoría:</span>
            <a href="#coleccion-hombre" class="cat-tab-btn active">Ropa de Hombre (41)</a>
            <a href="#coleccion-mujer" class="cat-tab-btn">Ropa de Mujer (42)</a>
            <a href="#accesorios" class="cat-tab-btn">Accesorios & Calzado</a>
            <a href="<?php echo esc_url($shop); ?>" class="cat-tab-btn all">Todo el Catálogo</a>
        </div>
    </div>

    <!-- SECCIÓN 02: COLECCIÓN HOMBRE -->
    <section class="lunaris-section lunaris-collection-section" id="coleccion-hombre" aria-labelledby="hombre-title">
        <div class="lunaris-section-head">
            <div>
                <p class="lunaris-eyebrow">02 / Colección Masculina</p>
                <h2 id="hombre-title">Ropa de Hombre</h2>
                <p class="lunaris-section-sub">Polos de alto gramaje, hoodies urbanos, sobrecamisas vintage y pantalones cargo.</p>
            </div>
            <a class="lunaris-text-link" href="<?php echo esc_url(get_term_link('hombre', 'product_cat')); ?>">Ver toda la ropa de hombre (41) ↗</a>
        </div>

        <div class="lunaris-products-grid">
            <?php
            if (!empty($hombre_products)) {
                foreach ($hombre_products as $p) {
                    lunaris_render_product_card($p, $base);
                }
            }
            ?>
        </div>
    </section>

    <!-- SECCIÓN 03: COLECCIÓN MUJER -->
    <section class="lunaris-section lunaris-collection-section" id="coleccion-mujer" aria-labelledby="mujer-title" style="background:#fafaf8; border-top:1px solid #ebece6; border-bottom:1px solid #ebece6;">
        <div class="lunaris-section-head">
            <div>
                <p class="lunaris-eyebrow">03 / Colección Femenina</p>
                <h2 id="mujer-title">Ropa de Mujer</h2>
                <p class="lunaris-section-sub">Tops contemporáneos, vestidos ligeros, faldas casuales y cardigans con estilo urbano.</p>
            </div>
            <a class="lunaris-text-link" href="<?php echo esc_url(get_term_link('mujer', 'product_cat')); ?>">Ver toda la ropa de mujer (42) ↗</a>
        </div>

        <div class="lunaris-products-grid">
            <?php
            if (!empty($mujer_products)) {
                foreach ($mujer_products as $p) {
                    lunaris_render_product_card($p, $base);
                }
            }
            ?>
        </div>
    </section>

    <!-- SECCIÓN 04: ACCESORIOS & CALZADO -->
    <section class="lunaris-section" id="accesorios" aria-labelledby="accesorios-title">
        <div class="lunaris-section-head">
            <div>
                <p class="lunaris-eyebrow">04 / El Toque Final</p>
                <h2 id="accesorios-title">Accesorios & Calzado</h2>
                <p class="lunaris-section-sub">Complementa tu outfit con gorras de diseño minimalista, pulseras de hematita y zapatillas urbanas.</p>
            </div>
            <a class="lunaris-text-link" href="<?php echo esc_url(get_term_link('accesorios', 'product_cat')); ?>">Ver todos los accesorios ↗</a>
        </div>

        <div class="lunaris-products-grid">
            <?php
            if (!empty($accesorios_products)) {
                foreach ($accesorios_products as $p) {
                    lunaris_render_product_card($p, $base);
                }
            }
            ?>
        </div>
    </section>

    <!-- MANIFIESTO DE MARCA -->
    <section class="lunaris-section lunaris-story" id="lunaris" aria-labelledby="story-title">
        <div>
            <p class="lunaris-eyebrow">El universo JONILUX</p>
            <h2 id="story-title">Vestir también es expresarte.</h2>
        </div>
        <div>
            <p style="margin-bottom:15px;">JONILUX nació en 2026 como una propuesta de estudiantes de UPN para transformar la compra de ropa urbana: frente al desorden y la falta de transparencia de precios y tallas en redes sociales, creamos un e-commerce formal con catálogo en tiempo real, guía de medidas exacta y despacho confiable.</p>
            <p style="color:#888;font-size:14px;">Propuesta de valor: moda moderna, precios claros y compra sin complicaciones para jóvenes de todo el Perú.</p>
        </div>
    </section>

</main>
<!-- /wp:html -->