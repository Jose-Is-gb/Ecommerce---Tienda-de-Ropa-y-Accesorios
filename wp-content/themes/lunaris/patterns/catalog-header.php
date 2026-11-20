<?php
/**
 * Title: JONILUX catalog header
 * Slug: lunaris/catalog-header
 * Categories: featured
 * Inserter: no
 */
if (!defined('ABSPATH')) { exit; }
if (!function_exists('wc_get_page_permalink')) { return; }
$shop = wc_get_page_permalink('shop');
$is_catalog = is_shop() && !is_search();
$current_cat = '';
if (is_product_category()) {
    $qo = get_queried_object();
    if ($qo && isset($qo->slug)) {
        $current_cat = (string) $qo->slug;
    }
} elseif (!empty($_GET['jx_cat'])) {
    $current_cat = sanitize_title(wp_unslash($_GET['jx_cat']));
}

$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (int) $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (int) $_GET['max_price'] : '';
$orderby   = isset($_GET['orderby']) ? sanitize_text_field(wp_unslash($_GET['orderby'])) : 'date';
$search_q  = isset($_GET['jx_q']) ? sanitize_text_field(wp_unslash($_GET['jx_q'])) : get_search_query();

$price_range = '';
if ($min_price === '' && $max_price === 60) {
    $price_range = '0-60';
} elseif ($min_price === 60 && $max_price === 120) {
    $price_range = '60-120';
} elseif ($min_price === 120 && $max_price === 180) {
    $price_range = '120-180';
} elseif ($min_price === 180 && $max_price === '') {
    $price_range = '180+';
}

$title = $is_catalog ? 'Catálogo JONILUX' : woocommerce_page_title(false);
if ($current_cat !== '' && $is_catalog) {
    $t_obj = get_term_by('slug', $current_cat, 'product_cat');
    if ($t_obj && !is_wp_error($t_obj)) {
        $title = $t_obj->name;
    }
}

$quick_cats = [
    ''                    => 'Todo el catálogo',
    'hombre'              => 'Hombre',
    'mujer'               => 'Mujer',
    'zapatillas'          => 'Zapatillas',
    'hoodies-y-sudaderas' => 'Hoodies y Polerones',
    'chaquetas-y-abrigos' => 'Casacas',
    'polos-y-camisetas'   => 'Polos',
    'pantalones'          => 'Pantalones',
    'vestidos'            => 'Vestidos',
    'accesorios'          => 'Accesorios',
];

$has_active_filters = ($current_cat !== '' || $min_price !== '' || $max_price !== '' || $search_q !== '' || ($orderby !== '' && $orderby !== 'date'));
?>
<!-- wp:html -->
<section class="lunaris-catalog-header jx-cat-header" aria-labelledby="catalog-title">
    <div class="lunaris-catalog-header-inner">
        <?php woocommerce_breadcrumb(); ?>
        <div class="jx-cat-header-top">
            <div>
                <p class="lunaris-eyebrow">JONILUX / MODA URBANA &amp; STREETWEAR</p>
                <h1 id="catalog-title"><?php echo esc_html($title); ?></h1>
            </div>
            <p class="jx-cat-header-sub">Filtra por tipo de prenda, rango de precio o colección para encontrar tu outfit ideal.</p>
        </div>

        <!-- Pildoras rapidas de categoria -->
        <nav class="lunaris-catalog-links jx-filter-pills" aria-label="Categorías rápidas del catálogo">
            <?php foreach ($quick_cats as $slug => $label) :
                if ($slug === '') {
                    $url = $shop;
                    $active = ($current_cat === '');
                } else {
                    $url = add_query_arg('jx_cat', $slug, $shop);
                    $active = ($current_cat === $slug);
                }
                ?>
                <a href="<?php echo esc_url($url); ?>" <?php if ($active) echo 'aria-current="page" class="is-active"'; ?>><?php echo esc_html($label); ?></a>
            <?php endforeach; ?>
        </nav>

        <!-- Barra avanzada de filtros combinados -->
        <form class="jx-filter-bar" method="get" action="<?php echo esc_url($shop); ?>" role="search" aria-label="Filtros de productos">
            <div class="jx-filter-field">
                <label for="jx-f-q">Buscar prenda</label>
                <input type="search" id="jx-f-q" name="jx_q" value="<?php echo esc_attr($search_q); ?>" placeholder="Ej. Hoodie, NASA, Zapatillas...">
            </div>

            <div class="jx-filter-field">
                <label for="jx-f-cat">Categoría</label>
                <select id="jx-f-cat" name="jx_cat">
                    <?php foreach ($quick_cats as $slug => $label) : ?>
                        <option value="<?php echo esc_attr($slug); ?>" <?php selected($current_cat, $slug); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="jx-filter-field">
                <label for="jx-f-price">Rango de precio</label>
                <select id="jx-f-price" name="jx_price">
                    <option value="" <?php selected($price_range, ''); ?>>Todos los precios</option>
                    <option value="0-60" <?php selected($price_range, '0-60'); ?>>Hasta S/ 60</option>
                    <option value="60-120" <?php selected($price_range, '60-120'); ?>>S/ 60 – S/ 120</option>
                    <option value="120-180" <?php selected($price_range, '120-180'); ?>>S/ 120 – S/ 180</option>
                    <option value="180+" <?php selected($price_range, '180+'); ?>>Más de S/ 180</option>
                </select>
            </div>

            <div class="jx-filter-field">
                <label for="jx-f-order">Ordenar por</label>
                <select id="jx-f-order" name="orderby">
                    <option value="date" <?php selected($orderby, 'date'); ?>>Más recientes primero</option>
                    <option value="popularity" <?php selected($orderby, 'popularity'); ?>>Más vendidos</option>
                    <option value="price" <?php selected($orderby, 'price'); ?>>Precio: menor a mayor</option>
                    <option value="price-desc" <?php selected($orderby, 'price-desc'); ?>>Precio: mayor a menor</option>
                </select>
            </div>

            <div class="jx-filter-actions">
                <button type="submit" class="jx-filter-submit">Filtrar</button>
                <?php if ($has_active_filters) : ?>
                    <a href="<?php echo esc_url($shop); ?>" class="jx-filter-reset">Limpiar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</section>
<!-- /wp:html -->
