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
$brand = get_bloginfo('name');
$has_wc = function_exists('wc_get_products');

$hombre_url = jx_term_url('hombre');
$mujer_url = jx_term_url('mujer');
$acc_url = jx_term_url('accesorios');

$slides = [
    [
        'bg' => '#172a22', 'img' => 'hero.webp', 'pos' => '60% center', 'w' => 1920, 'h' => 700,
        'eyebrow' => $brand . ' / Esenciales urbanos',
        'title' => 'Tu estilo. Tu propia órbita.',
        'text' => 'Cortes contemporáneos, precios claros y compra sin complicaciones.',
        'cta' => 'Explorar la colección', 'url' => $shop,
        'alt' => 'Conjunto urbano negro en una calle de la ciudad',
    ],
    [
        'bg' => '#233629', 'img' => 'men.webp', 'pos' => '50% 25%', 'w' => 1920, 'h' => 500,
        'eyebrow' => 'Hombre',
        'title' => 'Hoodies, polos y pantalones',
        'text' => 'Básicos con corte moderno para usar todos los días.',
        'cta' => 'Ver ropa de hombre', 'url' => $hombre_url,
        'alt' => 'Hombres con hoodies en tonos oscuros',
    ],
    [
        'bg' => '#2a2f2c', 'img' => 'women.webp', 'pos' => '50% 20%', 'w' => 1920, 'h' => 500,
        'eyebrow' => 'Mujer',
        'title' => 'Tops, vestidos y faldas',
        'text' => 'Prendas versátiles para armar tu look de la semana.',
        'cta' => 'Ver ropa de mujer', 'url' => $mujer_url,
        'alt' => 'Mujeres con blusas en tonos oscuros',
    ],
    [
        'bg' => '#1d2420', 'img' => 'accessories.webp', 'pos' => '50% center', 'w' => 1920, 'h' => 700,
        'eyebrow' => 'Accesorios',
        'title' => 'El detalle que completa tu look',
        'text' => 'Gorras, bolsos, joyería y relojes para cerrar el outfit.',
        'cta' => 'Ver accesorios', 'url' => $acc_url,
        'alt' => 'Reloj, pulsera y bolso sobre telas oscuras',
    ],
];

$circle_slugs = [
    'polos-y-camisetas', 'hoodies-y-sudaderas', 'pantalones', 'vestidos',
    'zapatillas', 'gorras-y-gorros', 'bolsos-y-carteras', 'joyeria-y-pulseras',
];
$circles = [];
foreach ($circle_slugs as $slug) {
    $term = get_term_by('slug', $slug, 'product_cat');
    if (!$term || (int) $term->count === 0) {
        continue;
    }
    $img = jx_category_image($slug);
    if ($img === '') {
        continue;
    }
    $circles[] = ['name' => $term->name, 'url' => jx_term_url($slug), 'img' => $img];
}

$latest = $has_wc ? wc_get_products(['status' => 'publish', 'limit' => 10, 'orderby' => 'date', 'order' => 'DESC']) : [];

$bestsellers = [];
if ($has_wc) {
    $candidates = wc_get_products([
        'status' => 'publish', 'limit' => 10,
        'meta_key' => 'total_sales', 'orderby' => 'meta_value_num', 'order' => 'DESC',
    ]);
    foreach ($candidates as $candidate) {
        if ((int) $candidate->get_total_sales() > 0) {
            $bestsellers[] = $candidate;
        }
    }
    if (count($bestsellers) < 4) {
        $bestsellers = [];
    }
}

$hombre_products = $has_wc ? wc_get_products(['status' => 'publish', 'limit' => 10, 'category' => ['hombre'], 'orderby' => 'date', 'order' => 'DESC']) : [];
$mujer_products = $has_wc ? wc_get_products(['status' => 'publish', 'limit' => 10, 'category' => ['mujer'], 'orderby' => 'date', 'order' => 'DESC']) : [];
$acc_products = $has_wc ? wc_get_products(['status' => 'publish', 'limit' => 10, 'category' => ['accesorios', 'accesorios-mujer', 'calzado'], 'orderby' => 'date', 'order' => 'DESC']) : [];

$coupon = jx_active_coupon();

$benefits = [
    ['Envíos a todo el Perú', 'Lima 24 a 48 horas. Provincias 2 a 4 días hábiles.', '<rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle>'],
    ['Paga como prefieras', 'Yape, Plin, transferencia y contra entrega en Lima.', '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line>'],
    ['Cambios por 7 días', 'Cambio de talla o prenda en 7 días calendario.', '<polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>'],
    ['Guía de tallas', 'Medidas en centímetros en cada prenda.', '<path d="M21.3 8.7 8.7 21.3a1 1 0 0 1-1.4 0L2.7 16.7a1 1 0 0 1 0-1.4L15.3 2.7a1 1 0 0 1 1.4 0l4.6 4.6a1 1 0 0 1 0 1.4z"></path><line x1="7" y1="14" x2="9" y2="16"></line><line x1="10" y1="11" x2="12" y2="13"></line><line x1="13" y1="8" x2="15" y2="10"></line>'],
];

$banners = [
    ['Hombre', 'Ropa de hombre', 'men.webp', '50% 25%', $hombre_url],
    ['Mujer', 'Ropa de mujer', 'women.webp', '50% 20%', $mujer_url],
    ['Accesorios', 'Accesorios y calzado', 'accessories.webp', '50% center', $acc_url],
];
?>
<!-- wp:html -->
<main id="contenido" class="jx-home">

    <section class="jx-hero" aria-label="Destacados de la tienda">
        <div class="jx-carousel" id="jx-carousel" role="region" aria-roledescription="carrusel" aria-label="Destacados">
            <div class="jx-slides">
                <?php foreach ($slides as $i => $slide) : $tag = $i === 0 ? 'h1' : 'h2'; ?>
                    <div class="jx-slide" style="--slide-bg: <?php echo esc_attr($slide['bg']); ?>;" role="group" aria-roledescription="diapositiva" aria-label="<?php echo esc_attr(($i + 1) . ' de ' . count($slides)); ?>">
                        <div class="jx-slide-copy">
                            <p class="jx-eyebrow"><?php echo esc_html($slide['eyebrow']); ?></p>
                            <<?php echo $tag; ?> class="jx-slide-title"><?php echo esc_html($slide['title']); ?></<?php echo $tag; ?>>
                            <p class="jx-slide-text"><?php echo esc_html($slide['text']); ?></p>
                            <a class="jx-slide-cta" href="<?php echo esc_url($slide['url']); ?>"><?php echo esc_html($slide['cta']); ?></a>
                        </div>
                        <div class="jx-slide-media">
                            <img src="<?php echo esc_url($base . $slide['img']); ?>" alt="<?php echo esc_attr($slide['alt']); ?>" width="<?php echo (int) $slide['w']; ?>" height="<?php echo (int) $slide['h']; ?>" style="object-position: <?php echo esc_attr($slide['pos']); ?>;" <?php echo $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="jx-car-btn jx-car-prev" aria-label="Diapositiva anterior">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button type="button" class="jx-car-btn jx-car-next" aria-label="Diapositiva siguiente">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
            <div class="jx-dots">
                <?php foreach ($slides as $i => $slide) : ?>
                    <button type="button" class="jx-dot" aria-label="<?php echo esc_attr('Ir a la diapositiva ' . ($i + 1)); ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>

        <aside class="jx-side" aria-label="Beneficios destacados">
            <a class="jx-promo jx-promo-light" href="<?php echo esc_url($shop); ?>">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                <strong>Envío gratis</strong>
                <span>En Lima y Callao en compras desde S/ 150.</span>
                <em>Ver tienda</em>
            </a>
            <?php if ($coupon) : ?>
                <a class="jx-promo jx-promo-dark" href="<?php echo esc_url($shop); ?>">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                    <strong><?php echo esc_html(wc_format_decimal($coupon->get_amount())); ?>% de descuento</strong>
                    <span>Con el código <?php echo esc_html(strtoupper($coupon->get_code())); ?><?php if ($coupon->get_minimum_amount()) : ?> en pedidos desde <?php echo esc_html(html_entity_decode(wp_strip_all_tags(wc_price($coupon->get_minimum_amount())), ENT_QUOTES, 'UTF-8')); ?><?php endif; ?>.</span>
                    <em>Aprovechar</em>
                </a>
            <?php else : ?>
                <a class="jx-promo jx-promo-dark" href="<?php echo esc_url($shop); ?>">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                    <strong>Paga al recibir</strong>
                    <span>Pago contra entrega disponible en Lima Metropolitana.</span>
                    <em>Ver tienda</em>
                </a>
            <?php endif; ?>
        </aside>
    </section>

    <section class="jx-benefits" aria-label="Beneficios de comprar en <?php echo esc_attr($brand); ?>">
        <?php foreach ($benefits as $benefit) : ?>
            <div class="jx-benefit">
                <span class="jx-benefit-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?php echo $benefit[2]; ?></svg>
                </span>
                <div>
                    <strong><?php echo esc_html($benefit[0]); ?></strong>
                    <span><?php echo esc_html($benefit[1]); ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <?php if (!empty($circles)) : ?>
    <section class="jx-cats jx-panel" id="colecciones" aria-labelledby="colecciones-title">
        <div class="jx-row-head">
            <div class="jx-row-titles"><h2 id="colecciones-title">Compra por categoría</h2></div>
            <a class="jx-row-all" href="<?php echo esc_url($shop); ?>">Ver todo el catálogo</a>
        </div>
        <ul class="jx-cats-list">
            <?php foreach ($circles as $circle) : ?>
                <li>
                    <a class="jx-cat" href="<?php echo esc_url($circle['url']); ?>">
                        <span class="jx-cat-img"><img src="<?php echo esc_url($circle['img']); ?>" alt="" width="120" height="120" loading="lazy"></span>
                        <span class="jx-cat-name"><?php echo esc_html($circle['name']); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

    <?php jx_render_row('novedades', 'Novedades', $latest, add_query_arg('orderby', 'date', $shop), 'Lo último que llegó a la tienda.'); ?>

    <section class="jx-banners" aria-label="Elige tu estilo">
        <?php foreach ($banners as $banner) : ?>
            <a class="jx-banner" href="<?php echo esc_url($banner[4]); ?>">
                <img src="<?php echo esc_url($base . $banner[2]); ?>" alt="" width="1920" height="500" loading="lazy" style="object-position: <?php echo esc_attr($banner[3]); ?>;">
                <span class="jx-banner-text">
                    <small><?php echo esc_html($banner[0]); ?></small>
                    <strong><?php echo esc_html($banner[1]); ?></strong>
                    <em>Ver colección</em>
                </span>
            </a>
        <?php endforeach; ?>
    </section>

    <?php jx_render_row('mas-vendidos', 'Más vendidos', $bestsellers, add_query_arg('orderby', 'popularity', $shop), 'Las prendas que más eligen nuestros clientes.'); ?>
    <?php jx_render_row('coleccion-hombre', 'Ropa de hombre', $hombre_products, $hombre_url, 'Polos, hoodies, camisas y pantalones.'); ?>
    <?php jx_render_row('coleccion-mujer', 'Ropa de mujer', $mujer_products, $mujer_url, 'Tops, vestidos, faldas y cardigans.'); ?>
    <?php jx_render_row('accesorios', 'Accesorios y calzado', $acc_products, $acc_url, 'Gorras, bolsos, joyería, relojes y zapatillas.'); ?>

    <section class="jx-story jx-panel" id="lunaris" aria-labelledby="story-title">
        <div>
            <p class="jx-eyebrow jx-eyebrow-dark">El universo <?php echo esc_html($brand); ?></p>
            <h2 id="story-title">Vestir también es expresarte.</h2>
        </div>
        <div class="jx-story-text">
            <p><?php echo esc_html($brand); ?> nació en 2026 como una propuesta de estudiantes de UPN para transformar la compra de ropa urbana: frente al desorden y la falta de transparencia de precios y tallas en redes sociales, creamos un e-commerce formal con catálogo en tiempo real, guía de medidas exacta y despacho confiable.</p>
            <p>Propuesta de valor: moda moderna, precios claros y compra sin complicaciones para jóvenes de todo el Perú.</p>
        </div>
    </section>

</main>
<!-- /wp:html -->
