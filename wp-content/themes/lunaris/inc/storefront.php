<?php
/**
 * JONILUX storefront: funciones de apoyo para la cabecera y la portada.
 */
if (!defined('ABSPATH')) { exit; }

/**
 * URL de una categoria de producto por slug, con respaldo a la tienda.
 */
function jx_term_url($slug) {
    $shop = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
    $term = get_term_by('slug', $slug, 'product_cat');
    if (!$term) {
        return $shop;
    }
    $link = get_term_link($term);
    return is_wp_error($link) ? $shop : $link;
}

/**
 * Categorias del menu "Todas las categorias". Se omiten las que no existen o no tienen productos.
 */
function jx_mega_groups() {
    $cache_key = 'jx_mega_groups_v1_' . md5(home_url('/'));
    $cached = get_transient($cache_key);
    if (is_array($cached)) {
        return $cached;
    }

    $groups = [
        'hombre' => ['polos-y-camisetas', 'hoodies-y-sudaderas', 'camisas', 'pantalones', 'chaquetas-y-abrigos'],
        'mujer' => ['tops-y-camisetas', 'vestidos', 'faldas', 'sweaters-y-cardigans', 'shorts'],
        'accesorios' => ['gorras-y-gorros', 'bolsos-y-carteras', 'joyeria-y-pulseras', 'relojes', 'lentes-de-sol', 'medias-y-calcetines'],
        'calzado' => ['zapatillas', 'zapatos-y-sandalias'],
    ];

    $out = [];
    foreach ($groups as $parent_slug => $children) {
        $parent = get_term_by('slug', $parent_slug, 'product_cat');
        if (!$parent || (int) $parent->count === 0) {
            continue;
        }
        $items = [];
        foreach ($children as $slug) {
            $term = get_term_by('slug', $slug, 'product_cat');
            if ($term && (int) $term->count > 0) {
                $items[] = ['name' => $term->name, 'url' => jx_term_url($slug)];
            }
        }
        $out[] = ['name' => $parent->name, 'url' => jx_term_url($parent_slug), 'items' => $items];
    }
    set_transient($cache_key, $out, 15 * MINUTE_IN_SECONDS);
    return $out;
}

/**
 * Imagen representativa de una categoria: la miniatura propia o la del producto mas reciente.
 */
function jx_category_image($slug) {
    $key = 'jx_cat_img_' . md5($slug);
    $cached = get_transient($key);
    if (is_string($cached) && $cached !== '') {
        return $cached;
    }

    $term = get_term_by('slug', $slug, 'product_cat');
    $url = '';
    if ($term) {
        $thumb_id = (int) get_term_meta($term->term_id, 'thumbnail_id', true);
        if ($thumb_id) {
            $url = (string) wp_get_attachment_image_url($thumb_id, 'medium');
        }
    }
    if ($url === '' && function_exists('wc_get_products')) {
        $ids = wc_get_products([
            'status' => 'publish',
            'limit' => 1,
            'category' => [$slug],
            'orderby' => 'date',
            'order' => 'DESC',
            'return' => 'ids',
        ]);
        if (!empty($ids)) {
            $url = (string) get_the_post_thumbnail_url($ids[0], 'medium');
        }
    }

    if ($url !== '') {
        set_transient($key, $url, 10 * MINUTE_IN_SECONDS);
    }
    return $url;
}

/**
 * Cupon porcentual vigente (mismas condiciones que la barra superior). Devuelve null si no hay.
 */
function jx_active_coupon() {
    if (!function_exists('wc_get_coupon_id_by_code')) {
        return null;
    }
    $coupon_id = wc_get_coupon_id_by_code('joniluxvip');
    if (!$coupon_id) {
        return null;
    }
    $coupon = new WC_Coupon($coupon_id);
    $expires = $coupon->get_date_expires();
    if ($coupon->get_status() !== 'publish' || $coupon->get_discount_type() !== 'percent'
        || ($expires && $expires->getTimestamp() < time())
        || ($coupon->get_usage_limit() && $coupon->get_usage_count() >= $coupon->get_usage_limit())) {
        return null;
    }
    return $coupon;
}

/**
 * Nombre de la categoria mas especifica de un producto, para la etiqueta de la tarjeta.
 */
function jx_product_category_label($product) {
    $terms = get_the_terms($product->get_id(), 'product_cat');
    if (empty($terms) || is_wp_error($terms)) {
        return '';
    }
    $skip = ['uncategorized', 'hombre', 'mujer', 'ropa', 'accesorios', 'accesorios-mujer', 'ropa-mujer'];
    foreach ($terms as $term) {
        if (!in_array($term->slug, $skip, true)) {
            return $term->name;
        }
    }
    return $terms[0]->name;
}

/**
 * Tarjeta de producto de la portada.
 */
function jx_render_product_card($product) {
    $pid = $product->get_id();
    $url = $product->get_permalink();
    $title = $product->get_name();
    $price_html = $product->get_price_html();
    $price_text = html_entity_decode(wp_strip_all_tags($price_html), ENT_QUOTES, 'UTF-8');
    $image_id = $product->get_image_id();
    $thumb = $image_id ? (string) wp_get_attachment_image_url($image_id, 'medium') : '';
    $large = $image_id ? (string) wp_get_attachment_image_url($image_id, 'large') : '';
    if ($large === '') {
        $large = $thumb !== '' ? $thumb : (string) wc_placeholder_img_src();
    }
    $label = jx_product_category_label($product);
    if ($label === '') {
        $label = 'COLECCIÓN URBANA';
    }
    $rating = (float) $product->get_average_rating();
    $rating_count = (int) $product->get_rating_count();
    ?>
    <article class="jx-card">
        <div class="jx-card-media">
            <a class="jx-card-media-link" href="<?php echo esc_url($url); ?>" tabindex="-1" aria-hidden="true">
                <img class="jx-card-img" src="<?php echo esc_url($large); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
            </a>
            <button type="button" class="jonilux-card-wishlist-btn jx-wish"
                    data-id="<?php echo (int) $pid; ?>"
                    data-title="<?php echo esc_attr($title); ?>"
                    data-price="<?php echo esc_attr($price_text); ?>"
                    data-image="<?php echo esc_url($thumb); ?>"
                    data-url="<?php echo esc_url($url); ?>"
                    aria-label="<?php echo esc_attr('Guardar en favoritos: ' . $title); ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            </button>
        </div>
        <div class="jx-card-body">
            <span class="jx-card-cat"><?php echo esc_html($label); ?></span>
            <h3 class="jx-card-title"><a href="<?php echo esc_url($url); ?>"><?php echo esc_html($title); ?></a></h3>
            <div class="jx-card-rating">
                <?php if ($rating_count > 0) : ?>
                    <span class="jx-stars" style="--r: <?php echo esc_attr(number_format($rating, 1, '.', '')); ?>;" role="img" aria-label="<?php echo esc_attr('Valoración ' . number_format_i18n($rating, 1) . ' de 5'); ?>"></span>
                    <span>(<?php echo (int) $rating_count; ?>)</span>
                <?php else : ?>
                    <span class="jx-card-stock-note">Envío 24-48h · En stock</span>
                <?php endif; ?>
            </div>
            <div class="jx-card-price"><?php echo wp_kses_post($price_html); ?></div>
            <div class="jx-card-actions">
                <a class="jx-card-btn" href="<?php echo esc_url($url); ?>">Ver producto</a>
                <?php if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) : ?>
                    <button type="button" class="jx-card-quickadd" data-product-id="<?php echo (int) $pid; ?>" aria-label="<?php echo esc_attr('Añadir al carrito: ' . $title); ?>">+ Carrito</button>
                <?php endif; ?>
            </div>
        </div>
    </article>
    <?php
}

/**
 * Fila horizontal de productos con flechas. No imprime nada si no hay productos.
 */
function jx_render_row($id, $title, $products, $url, $subtitle = '') {
    if (empty($products)) {
        return;
    }
    ?>
    <section class="jx-row jx-panel" id="<?php echo esc_attr($id); ?>" aria-labelledby="<?php echo esc_attr($id); ?>-title">
        <div class="jx-row-head">
            <div class="jx-row-titles">
                <h2 id="<?php echo esc_attr($id); ?>-title"><?php echo esc_html($title); ?></h2>
                <?php if ($subtitle !== '') : ?><p><?php echo esc_html($subtitle); ?></p><?php endif; ?>
            </div>
            <a class="jx-row-all" href="<?php echo esc_url($url); ?>">Ver todo</a>
            <div class="jx-row-nav">
                <button type="button" class="jx-row-btn" data-dir="-1" aria-label="<?php echo esc_attr('Anterior: ' . $title); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button type="button" class="jx-row-btn" data-dir="1" aria-label="<?php echo esc_attr('Siguiente: ' . $title); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>
        <div class="jx-row-track" tabindex="0" role="group" aria-label="<?php echo esc_attr($title); ?>">
            <?php foreach ($products as $product) { jx_render_product_card($product); } ?>
        </div>
    </section>
    <?php
}
